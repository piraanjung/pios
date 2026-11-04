<?php

namespace App\Http\Controllers\AnnualTrash;

use App\Http\Controllers\Controller;
use App\Models\Admin\Zone;
use App\Models\AnnualTrash\AnnualTrashSubscription;
use App\Models\AnnualTrash\AnnualTrashPayment;
use App\Models\AnnualTrash\AnnualTrash;
use App\Exports\AnnualTrashDailyCollectionExport;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;


class AnnualReportController extends Controller
{
    public function dailyCollection(Request $request)
    {
        $today = now()->toDateString();
        $validated = $request->validate([
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
            'zone_id' => 'nullable|string|max:20',
            'export' => 'nullable|in:excel',
        ]);
        $fromDate = $validated['from_date'] ?? $today;
        $toDate = $validated['to_date'] ?? $fromDate;
        $orgId = (int) getAuthOrgId();
        $zones = Zone::where('org_id_fk', $orgId)->orWhereNull('org_id_fk')->orderBy('zone_name')->get();
        $zoneId = $validated['zone_id'] ?? 'all';

        if ($zoneId !== 'all' && !$zones->contains('id', (int) $zoneId)) {
            abort(404);
        }

        $payments = $this->dailyCollectionQuery($orgId, $fromDate, $toDate, $zoneId)->get();

        if (($validated['export'] ?? null) === 'excel') {
            $filename = 'annual-trash-daily-collection-' . $fromDate . '-to-' . $toDate . '.xlsx';
            return Excel::download(new AnnualTrashDailyCollectionExport($payments), $filename);
        }

        $summary = [
            'transactions' => $payments->count(),
            'amount' => (float) $payments->sum('amount_paid'),
            'bins' => $payments->pluck('subscription.waste_bin_id')->filter()->unique()->count(),
            'members' => $payments->pluck('subscription.user_id')->filter()->unique()->count(),
        ];

        return view('annual_trash.reports.daily_collection', compact(
            'payments',
            'summary',
            'fromDate',
            'toDate',
            'zones',
            'zoneId'
        ));
    }

    private function dailyCollectionQuery(int $orgId, string $fromDate, string $toDate, string $zoneId)
    {
        $query = AnnualTrashPayment::where('org_id_fk', $orgId)
            ->whereDate('pay_date', '>=', $fromDate)
            ->whereDate('pay_date', '<=', $toDate)
            ->with([
                'subscription.AnnualTrash.user.annualTrashMember',
                'subscription.AnnualTrash.user.user_zone',
                'subscription.AnnualTrash.kpUserGroup',
                'staff',
            ]);

        if ($zoneId !== 'all') {
            $query->whereHas('subscription.AnnualTrash.user', function ($userQuery) use ($orgId, $zoneId) {
                $userQuery->where('org_id_fk', $orgId)->where('zone_id', (int) $zoneId);
            });
        }

        return $query->orderBy('pay_date')->orderBy('id');
    }

    // หน้าเลือกรายงาน
    public function index()
    {
        $orgId = (int) getAuthOrgId();
        $zones = Zone::where('org_id_fk', $orgId)->orWhereNull('org_id_fk')->get();
        $fiscalYear = AnnualTrashSubscription::calculateFiscalYear();
        return view('annual_trash.reports.index', compact('zones', 'fiscalYear'));
    }

    // ฟังก์ชันประมวลผลรายงาน
    public function generate(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:daily_collection,arrears,zone_summary,receipt_control,remittance,service_points,damaged_bins',
            'date' => 'nullable|date',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'fiscal_year' => 'nullable|integer|between:2400,2700',
            'zone_id' => 'nullable|integer',
        ]);
        $orgId = (int) getAuthOrgId();
        if ($request->filled('zone_id') && !Zone::where(function ($query) use ($orgId) {
            $query->where('org_id_fk', $orgId)->orWhereNull('org_id_fk');
        })->whereKey($request->input('zone_id'))->exists()) {
            abort(404);
        }
        $type = $request->report_type;
        $data = [];
        $title = "";
        $summary = []; // ประกาศตัวแปรเปล่าไว้ก่อน กัน Error case อื่น
        $view = 'annual_trash.reports.preview_table';

        switch ($type) {
            // ... (Case 1-4 เดิมของคุณถูกต้องแล้ว ใส่ไว้เหมือนเดิม) ...
            case 'daily_collection':
                $date = $request->input('date', date('Y-m-d'));
                $title = "รายงานการรับเงินประจำวันที่ " . \Carbon\Carbon::parse($date)->locale('th')->isoFormat('D MMMM YYYY');
                $data = \App\Models\AnnualTrash\AnnualTrashPayment::with(['subscription.AnnualTrash.user', 'subscription.AnnualTrash.kpUserGroup', 'staff'])
                    ->where('org_id_fk', $orgId)
                    ->whereHas('subscription.AnnualTrash', fn($query) => $query->where('org_id_fk', $orgId))
                    ->whereHas('subscription.user', fn($query) => $query->where('org_id_fk', $orgId))
                    ->whereDate('pay_date', $date)
                    ->get();
                break;

            case 'arrears':
                $fy = $request->input('fiscal_year');
                $zone = $request->input('zone_id');
                $title = "รายชื่อลูกหนี้ค้างชำระ ประจำปีงบประมาณ $fy";
                $query = \App\Models\AnnualTrash\AnnualTrashSubscription::with(['AnnualTrash.user', 'AnnualTrash.user.user_zone'])
                    ->where('org_id_fk', $orgId)
                    ->whereHas('AnnualTrash', fn($binQuery) => $binQuery->where('org_id_fk', $orgId))
                    ->whereHas('user', fn($userQuery) => $userQuery->where('org_id_fk', $orgId))
                    ->where('fiscal_year', $fy)
                    ->whereColumn('total_paid_amt', '<', 'annual_fee');
                if ($zone) {
                    $query->whereHas('AnnualTrash.user', function ($q) use ($zone) {
                        $q->where('zone_id', $zone);
                    });
                }
                $data = $query->get()->map(function ($subscription) use ($fy) {
                    $fiscalYearAd = (int) $fy > 2400 ? (int) $fy - 543 : (int) $fy;
                    $dueDate = Carbon::create($fiscalYearAd, 9, 30)->endOfDay();
                    $subscription->aging_days = $dueDate->isPast() ? $dueDate->diffInDays(now()) : 0;
                    $subscription->aging_bucket = match (true) {
                        $subscription->aging_days <= 30 => '0-30 วัน',
                        $subscription->aging_days <= 60 => '31-60 วัน',
                        $subscription->aging_days <= 90 => '61-90 วัน',
                        default => 'มากกว่า 90 วัน',
                    };
                    return $subscription;
                });
                break;

            case 'zone_summary':
                $fy = $request->input('fiscal_year');
                $title = "สรุปผลการจัดเก็บรายโซน ปีงบประมาณ $fy";
                $subscriptions = AnnualTrashSubscription::where('org_id_fk', $orgId)
                    ->where('fiscal_year', $fy)
                    ->whereHas('AnnualTrash', fn($binQuery) => $binQuery->where('org_id_fk', $orgId))
                    ->whereHas('user', fn($userQuery) => $userQuery->where('org_id_fk', $orgId))
                    ->with('AnnualTrash.user.user_zone')
                    ->get();
                $data = $subscriptions->groupBy(fn($subscription) => $subscription->AnnualTrash->user->user_zone->zone_name ?? 'ไม่ระบุโซน')
                    ->map(fn($group, $zoneName) => (object) [
                        'zone_name' => $zoneName,
                        'total_bins' => $group->count(),
                        'total_revenue' => $group->sum('annual_fee'),
                        'total_collected' => $group->sum('total_paid_amt'),
                        'total_outstanding' => $group->sum(fn($item) => max($item->annual_fee - $item->total_paid_amt, 0)),
                    ])->values();
                break;

            case 'receipt_control':
                $start = $request->input('start_date');
                $end = $request->input('end_date');
                $title = "ทะเบียนคุมใบเสร็จรับเงิน (" . \Carbon\Carbon::parse($start)->format('d/m/Y') . " - " . \Carbon\Carbon::parse($end)->format('d/m/Y') . ")";
                $data = \App\Models\AnnualTrash\AnnualTrashPayment::with(['subscription.AnnualTrash.user', 'staff'])
                    ->where('org_id_fk', $orgId)
                    ->whereHas('subscription.AnnualTrash', fn($query) => $query->where('org_id_fk', $orgId))
                    ->whereHas('subscription.user', fn($query) => $query->where('org_id_fk', $orgId))
                    ->whereDate('pay_date', '>=', $start)
                    ->whereDate('pay_date', '<=', $end)
                    ->orderBy('id', 'asc')
                    ->get();
                break;

            case 'remittance':
                // 5. รายงานนำส่งเงิน
                $date = $request->input('date', date('Y-m-d'));
                $title = "ใบนำส่งเงินประจำวันที่ " . \Carbon\Carbon::parse($date)->locale('th')->isoFormat('D MMMM YYYY');
                $data = \App\Models\AnnualTrash\AnnualTrashPayment::with(['subscription.AnnualTrash.user', 'staff'])
                    ->where('org_id_fk', $orgId)
                    ->whereHas('subscription.AnnualTrash', fn($query) => $query->where('org_id_fk', $orgId))
                    ->whereHas('subscription.user', fn($query) => $query->where('org_id_fk', $orgId))
                    ->whereDate('pay_date', $date)
                    ->get();

                $summary = [
                    'total' => $data->sum('amount_paid'),
                    'count' => $data->count(),
                    'first_no' => $data->min('id'),
                    'last_no' => $data->max('id'),
                ];
                break;

            // --- เพิ่มใหม่ 2 รายงาน ---

            case 'service_points':
                // 6. รายชื่อจุดเก็บขยะ (Service Point List)
                $zone = $request->input('zone_id');
                $title = "รายชื่อจุดเก็บขยะ (Service Point List)";

                // ดึงข้อมูลถังขยะ Active
                $query = AnnualTrash::with(['user.user_zone', 'kpUserGroup'])
                    ->where('org_id_fk', $orgId)
                    ->whereHas('user', fn($userQuery) => $userQuery->where('org_id_fk', $orgId))
                    ->where('status', 'active');

                if ($zone) {
                    $query->whereHas('user', function ($q) use ($zone) {
                        $q->where('zone_id', $zone);
                    });
                }
                // เรียงตามโซน เพื่อให้รถเก็บวิ่งง่าย
                $data = $query->get()->sortBy(function ($bin) {
                    return $bin->user->user_zone->id ?? 0;
                });
                break;

            case 'damaged_bins':
                // 7. รายงานถังขยะชำรุด
                $title = "รายงานถังขยะชำรุด/แจ้งซ่อม (Damaged Bins)";

                $data = AnnualTrash::with(['user.user_zone'])
                    ->where('org_id_fk', $orgId)
                    ->whereHas('user', fn($userQuery) => $userQuery->where('org_id_fk', $orgId))
                    ->where('status', 'damaged') // สถานะ damaged
                    ->orderBy('updated_at', 'desc') // เรียงตามวันที่แจ้งล่าสุด
                    ->get();
                break;
        }

        // *** สำคัญ: ต้องส่ง $summary ไปด้วย ***
        return view($view, compact('data', 'type', 'title', 'request', 'summary'));
    }
}
