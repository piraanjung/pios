<?php

namespace App\Http\Controllers\AnnualTrash;

use App\Http\Controllers\Controller;
use App\Models\Admin\Organization;
use App\Models\AnnualTrash\AnnualTrash;
use App\Models\AnnualTrash\AnnualTrashSubscription;
use App\Models\AnnualTrash\AnnualTrashPayment;
use App\Models\AnnualTrash\AnnualTrashMember;
use App\Models\User;
use App\Support\PromptPayQr;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Carbon\Carbon;

class AnnualTrashSubscriptionController extends Controller
{
    /**
     * 1. หน้ารายการสมัครรับบริการและสถานะการชำระเงินประจำปี
     */
    public function index(Request $request)
    {
        $orgId = getAuthOrgId();
        $currentFiscalYear = AnnualTrashSubscription::calculateFiscalYear();
        $fiscalYear = $request->input('fy', $currentFiscalYear);
        $search = $request->input('search');
        $statusFilter = $request->input('status', 'all');

        // สถิติตัวเลขสรุปประจำปีงบประมาณ
        $statsQuery = AnnualTrashSubscription::where('org_id_fk', $orgId)
            ->with('payments')
            ->where('fiscal_year', $fiscalYear)
            ->whereHas('user', fn($query) => $query->where('org_id_fk', $orgId));

        $totalBins = (clone $statsQuery)->count();
        $paidBins = (clone $statsQuery)->where('status', 'paid')->count();
        $pendingBins = (clone $statsQuery)->whereIn('status', ['pending', 'partially_paid'])->count();

        // ดึงเฉพาะ Master User ที่ลงทะเบียนในตาราง annual_trash_members ของ อปท. นี้
        $query = User::query()
            ->where('org_id_fk', $orgId)
            ->whereHas('annualTrashMember', function ($q) use ($orgId) {
                $q->where('org_id_fk', $orgId)->where('status', 'active');
            })
            ->with([
                'annualTrashMember.userGroup',
                'AnnualTrashs' => function ($q) use ($fiscalYear, $orgId) {
                    $q->where('org_id_fk', $orgId)
                        ->with(['user.annualTrashMember', 'subscriptions' => function ($subQ) use ($fiscalYear, $orgId) {
                            $subQ->where('org_id_fk', $orgId)->where('fiscal_year', $fiscalYear);
                        }]);
                }
            ]);

        $query->whereHas('AnnualTrashs', fn($binQuery) => $binQuery->where('org_id_fk', $orgId));

        if ($search) {
            $query->where(function ($q) use ($search, $orgId) {
                                $q->where('firstname', 'like', "%{$search}%")
                  ->orWhere('lastname', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                                    ->orWhereHas('AnnualTrashs', function ($b) use ($search, $orgId) {
                                            $b->where('org_id_fk', $orgId)->where('bin_code', 'like', "%{$search}%");
                  });
            });
        }

        if ($statusFilter !== 'all') {
            $query->whereHas('AnnualTrashs.subscriptions', function ($q) use ($orgId, $fiscalYear, $statusFilter) {
                $q->where('org_id_fk', $orgId)->where('fiscal_year', $fiscalYear);
                if ($statusFilter == 'paid') {
                    $q->where('status', 'paid');
                } else {
                    $q->where('status', '!=', 'paid');
                }
            });
        }

        $members = $query->paginate(15);
        foreach ($members as $member) {
            foreach ($member->AnnualTrashs as $bin) {
                $subscription = $bin->subscriptions->first();
                if ($subscription) {
                    $subscription->setAttribute('effective_annual_fee', $this->annualFeeTarget($subscription));
                }
            }
        }
        $availableFiscalYears = collect(range($currentFiscalYear - 2, $currentFiscalYear + 1));

        return view('annual_trash.payments.index', compact(
            'members',
            'fiscalYear',
            'availableFiscalYears',
            'totalBins',
            'paidBins',
            'pendingBins',
            'search',
            'statusFilter'
        ));
    }

    /**
     * 2. หน้ารายการใบแจ้งหนี้ (Invoices) สำหรับผู้ที่ยังไม่ได้ชำระเงินหรือชำระบางส่วน
     */
    public function invoice(Request $request)
    {
        $orgId = getAuthOrgId();
        $currentFiscalYear = AnnualTrashSubscription::calculateFiscalYear();
        $fiscalYear = $request->input('fy', $currentFiscalYear);

        // ดึงใบแจ้งหนี้ที่มียอดค้างชำระ (pending หรือ partially_paid)
        $invoices = AnnualTrashSubscription::where('org_id_fk', $orgId)
        ->whereHas('user', fn($query) => $query->where('org_id_fk', $orgId))
        ->where('fiscal_year', $fiscalYear)
        ->whereIn('status', ['pending', 'partially_paid'])
        ->with(['AnnualTrash.user.annualTrashMember.userGroup', 'AnnualTrash.user.user_zone'])
        ->get();

        // จัดกลุ่มใบแจ้งหนี้ตาม User
        $invoicesByUser = $invoices->groupBy(function ($item) {
            return $item->AnnualTrash->user_id ?? 0;
        });

        return view('annual_trash.payments.invoice', compact('invoicesByUser', 'fiscalYear'));
    }

    /**
     * 3. พิมพ์ใบแจ้งหนี้ที่เลือก (Print Selected Invoices)
     */
    public function printSelectedInvoices(Request $request)
    {
        $request->validate([
            'invoice_ids'   => 'required|array|min:1',
            'invoice_ids.*' => 'required|exists:annual_trash_subscriptions,id',
        ], [
            'invoice_ids.required' => 'กรุณาเลือกใบแจ้งหนี้ที่ต้องการพิมพ์อย่างน้อย 1 รายการ',
        ]);

        $orgId = getAuthOrgId();
        $orgInfos = Organization::getOrgName($orgId);

        $invoices = AnnualTrashSubscription::whereIn('id', $request->invoice_ids)
            ->where('org_id_fk', $orgId)
            ->whereHas('user', fn($query) => $query->where('org_id_fk', $orgId))
            ->with(['AnnualTrash.user.annualTrashMember.userGroup', 'AnnualTrash.user.user_zone'])
            ->get();

        if ($invoices->isEmpty()) {
            return back()->with('error', 'ไม่พบรายการใบแจ้งหนี้ที่เลือก');
        }

        $invoicesByUser = $invoices->groupBy(function ($item) {
            return $item->AnnualTrash->user_id;
        });

        return view('annual_trash.payments.print_invoices', compact('invoicesByUser', 'orgInfos'));
    }

    public function show(AnnualTrashSubscription $wasteBinSubscription)
    {
        $orgId = (int) getAuthOrgId();
        abort_unless((int) $wasteBinSubscription->org_id_fk === $orgId, 404);

        $wasteBinSubscription->load(['AnnualTrash.user.annualTrashMember', 'payments']);
        $fiscalYear = (int) $wasteBinSubscription->fiscal_year;
        $startOfFiscalYear = $this->fiscalYearStart($fiscalYear);
        $memberSince = $this->memberStartDate($wasteBinSubscription);
        $proratedAnnualFee = $this->annualFeeTarget($wasteBinSubscription);
        $fullYearAnnualFee = (float) $wasteBinSubscription->month_fee * 12;
        $hasHistoricalMonths = $memberSince->gt($startOfFiscalYear);
        $paymentsByMonth = $wasteBinSubscription->payments
            ->groupBy(fn($payment) => $payment->pay_mon . '|' . $payment->pay_yr);
        $paymentSchedule = [];

        for ($monthOffset = 0; $monthOffset < 12; $monthOffset++) {
            $monthDate = $startOfFiscalYear->copy()->addMonths($monthOffset);
            $monthKey = $monthDate->month . '|' . $monthDate->year;
            $paidAmount = (float) $paymentsByMonth->get($monthKey, collect())->sum('amount_paid');
            $paymentSchedule[] = [
                'month_num' => $monthDate->month,
                'year' => $monthDate->year,
                'due_amount' => max((float) $wasteBinSubscription->month_fee - $paidAmount, 0),
                'paid_amount' => $paidAmount,
                'is_paid' => $paidAmount >= (float) $wasteBinSubscription->month_fee,
                'before_member' => $monthDate->lt($memberSince),
                'active' => $monthDate->gte($memberSince) ? 1 : 0,
            ];
        }

        $isBinActiveForAnnualCollection = (bool) optional($wasteBinSubscription->AnnualTrash)
            ->is_active_for_annual_collection;

        return view('annual_trash.payments.show', compact(
            'wasteBinSubscription',
            'paymentSchedule',
            'isBinActiveForAnnualCollection',
            'proratedAnnualFee',
            'fullYearAnnualFee',
            'hasHistoricalMonths'
        ));
    }

    public function createSubscription(Request $request)
    {
        $request->validate([
            'waste_bin_id' => 'required|integer|exists:annual_trashs,id',
            'annual_fee' => 'required|numeric|min:0',
            'fiscal_year' => 'required|integer|between:2400,2700',
        ]);

        $orgId = (int) getAuthOrgId();
        $bin = \App\Models\AnnualTrash\AnnualTrash::where('org_id_fk', $orgId)
            ->findOrFail($request->integer('waste_bin_id'));
        $annualFee = (float) $request->input('annual_fee');

        AnnualTrashSubscription::firstOrCreate(
            [
                'org_id_fk' => $orgId,
                'waste_bin_id' => $bin->id,
                'fiscal_year' => $request->integer('fiscal_year'),
            ],
            [
                'user_id' => $bin->user_id,
                'annual_fee' => $annualFee,
                'month_fee' => round($annualFee / 12, 2),
                'total_paid_amt' => 0,
                'status' => 'pending',
            ]
        );

        return redirect()->route('keptkayas.annual_payments.index')
            ->with('success', 'สร้างรายการค่าธรรมเนียมเรียบร้อยแล้ว');
    }

    public function createSubscriptionForBin(AnnualTrash $bin)
    {
        $orgId = (int) getAuthOrgId();
        abort_unless((int) $bin->org_id_fk === $orgId, 404);

        $member = AnnualTrashMember::where('org_id_fk', $orgId)
            ->where('user_id', $bin->user_id)
            ->where('status', 'active')
            ->firstOrFail();
        $fiscalYear = AnnualTrashSubscription::calculateFiscalYear();

        $subscription = AnnualTrashSubscription::where('org_id_fk', $orgId)
            ->where('waste_bin_id', $bin->id)
            ->where('fiscal_year', $fiscalYear)
            ->first();

        if ($subscription) {
            return redirect()->route('keptkayas.annual_payments.index', ['fy' => $fiscalYear])
                ->with('success', 'ถังนี้มีรายการชำระของปีงบประมาณนี้แล้ว');
        }

        $payrate = \App\Models\AnnualTrash\AnnualTrashPayratePerMonth::where('org_id_fk', $orgId)
            ->where('kp_usergroup_idfk', $member->user_group_id_fk)
            ->where('status', 'active')
            ->whereHas('budgetyear', fn($query) => $query->where('budgetyear_name', $fiscalYear))
            ->first();

        if (!$payrate) {
            return redirect()->route('keptkayas.annual_payments.index', ['fy' => $fiscalYear])
                ->with('error', 'ยังไม่มีอัตราค่าบริการของประเภทสมาชิกในปีงบประมาณ ' . $fiscalYear);
        }

        DB::transaction(function () use ($bin, $member, $payrate, $orgId, $fiscalYear) {
            $bin->update(['is_active_for_annual_collection' => true]);

            AnnualTrashSubscription::firstOrCreate(
                [
                    'org_id_fk' => $orgId,
                    'waste_bin_id' => $bin->id,
                    'fiscal_year' => $fiscalYear,
                ],
                [
                    'user_id' => $member->user_id,
                    'payrate_permonth_id_fk' => $payrate->id,
                    'month_fee' => $payrate->payrate_permonth,
                    'annual_fee' => (float) $payrate->payrate_permonth * 12,
                    'total_paid_amt' => 0,
                    'status' => 'pending',
                ]
            );
        });

        return redirect()->route('keptkayas.annual_payments.index', ['fy' => $fiscalYear])
            ->with('success', 'สร้างรายการชำระสำหรับถัง ' . $bin->bin_code . ' แล้ว');
    }

    public function printReceipt(Request $request, AnnualTrashSubscription $wasteBinSubscription)
    {
        $orgId = (int) getAuthOrgId();
        abort_unless((int) $wasteBinSubscription->org_id_fk === $orgId, 404);

        $paymentDate = $request->input('payment_date', session('payment_date'));
        if (!$paymentDate) {
            $paymentDate = $wasteBinSubscription->payments()->max('pay_date');
        }
        abort_if(!$paymentDate, 404, 'ไม่พบรายการชำระเงิน');

        return $this->renderAnnualTrashReceipt([$wasteBinSubscription->id], $paymentDate, $orgId);
    }

    public function printBulkReceipt(Request $request)
    {
        $validated = $request->validate([
            'subscription_ids' => 'required|array|min:1|max:100',
            'subscription_ids.*' => 'required|integer|distinct',
            'payment_date' => 'required|date',
        ]);

        return $this->renderAnnualTrashReceipt(
            array_map('intval', $validated['subscription_ids']),
            $validated['payment_date'],
            (int) getAuthOrgId()
        );
    }

    private function renderAnnualTrashReceipt(array $subscriptionIds, string $paymentDate, int $orgId)
    {
        $subscriptions = AnnualTrashSubscription::where('org_id_fk', $orgId)
            ->whereIn('id', $subscriptionIds)
            ->with([
                'AnnualTrash.user.annualTrashMember.userGroup',
                'payments' => fn($query) => $query->with('staff')->whereDate('pay_date', $paymentDate),
            ])
            ->get();

        abort_unless($subscriptions->count() === count(array_unique($subscriptionIds)), 404);
        abort_unless($subscriptions->pluck('user_id')->unique()->count() === 1, 404);

        $receipts = $subscriptions->map(function ($subscription) {
            $payments = $subscription->payments->sortBy('pay_mon')->values();
            return [
                'subscription' => $subscription,
                'payments' => $payments,
                'amount' => (float) $payments->sum('amount_paid'),
            ];
        })->filter(fn($receipt) => $receipt['payments']->isNotEmpty())->values();

        abort_if($receipts->isEmpty(), 404, 'ไม่พบรายการรับชำระในวันที่เลือก');

        $allPayments = $receipts->flatMap(fn($receipt) => $receipt['payments'])->sortBy('id')->values();
        $totalPaidAmount = (float) $receipts->sum('amount');
        $fiscalYear = (int) $receipts->first()['subscription']->fiscal_year;
        $receiptCode = 'AT-' . $fiscalYear . '-' . Carbon::parse($paymentDate)->format('Ymd') . '-'
            . str_pad((string) $allPayments->first()->id, 6, '0', STR_PAD_LEFT);
        $staff = $allPayments->first()->staff;
        $orgInfos = Organization::getOrgName($orgId);
        $receiptSheets = $receipts->values()->map(function ($receipt, $index) use ($receiptCode) {
            return [
                'receipt_code' => $receiptCode . '-' . str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT),
                'subscription' => $receipt['subscription'],
                'payments' => $receipt['payments'],
                'amount' => $receipt['amount'],
            ];
        });

        return view('annual_trash.payments.receipt_a5', compact(
            'receiptSheets',
            'paymentDate',
            'staff',
            'orgInfos',
            'fiscalYear'
        ));
    }

    public function history(Request $request)
    {
        $orgId = (int) getAuthOrgId();
        $query = AnnualTrashSubscription::where('org_id_fk', $orgId)
            ->where('total_paid_amt', '>', 0)
            ->with(['AnnualTrash.user.user_zone', 'payments']);

        $searchOptions = (clone $query)->get()->unique('waste_bin_id')->values();
        $selectedSubscriptions = collect();

        if ($request->filled('bin_code')) {
            $selectedSubscriptions = $query
                ->whereHas('AnnualTrash', fn($binQuery) => $binQuery->where('bin_code', $request->input('bin_code')))
                ->orderByDesc('fiscal_year')
                ->get();
        }

        $orgInfos = Organization::getOrgName($orgId);

        return view('annual_trash.payments.history', compact(
            'searchOptions',
            'selectedSubscriptions',
            'orgInfos'
        ));
    }

    public function paymentMonthOptions(Request $request)
    {
        $validated = $request->validate([
            'subscription_ids' => 'required|array|min:1|max:100',
            'subscription_ids.*' => 'required|integer|distinct',
        ]);

        $orgId = (int) getAuthOrgId();
        $subscriptionIds = array_map('intval', $validated['subscription_ids']);
        $subscriptions = AnnualTrashSubscription::where('org_id_fk', $orgId)
            ->whereIn('id', $subscriptionIds)
            ->with(['AnnualTrash.user.annualTrashMember', 'payments'])
            ->get();

        abort_unless($subscriptions->count() === count($subscriptionIds), 404);
        abort_unless($subscriptions->pluck('user_id')->unique()->count() === 1, 422);

        return response()->json([
            'groups' => $subscriptions->map(function ($subscription) {
                return [
                    'subscription_id' => $subscription->id,
                    'bin_code' => $subscription->AnnualTrash->bin_code ?? '-',
                    'annual_fee' => $this->annualFeeTarget($subscription),
                    'months' => $this->monthSchedule($subscription),
                ];
            })->values(),
        ]);
    }

    public function paymentQr(Request $request)
    {
        $validated = $request->validate([
            'subscription_ids' => 'required|array|min:1|max:100',
            'subscription_ids.*' => 'required|integer|distinct',
            'months' => 'required|array|min:1',
            'months.*' => 'required|array|min:1',
            'months.*.*' => ['required', 'regex:/^(?:[1-9]|1[0-2])\|\d{4}$/'],
            'include_previous_months' => 'nullable|boolean',
        ]);

        $promptPayId = trim((string) config('services.promptpay.id'));
        if ($promptPayId === '') {
            return response()->json([
                'message' => 'ยังไม่ได้ตั้งค่า PROMPTPAY_ID สำหรับบัญชีรับเงินขององค์กร',
            ], 422);
        }

        $orgId = (int) getAuthOrgId();
        $subscriptionIds = array_values(array_unique(array_map('intval', $validated['subscription_ids'])));
        $subscriptions = AnnualTrashSubscription::where('org_id_fk', $orgId)
            ->whereIn('id', $subscriptionIds)
            ->with(['AnnualTrash.user.annualTrashMember', 'payments'])
            ->get();

        abort_unless($subscriptions->count() === count($subscriptionIds), 404);
        abort_unless($subscriptions->pluck('user_id')->unique()->count() === 1, 422);

        [$plans, $amount] = $this->selectedMonthPlans(
            $subscriptions,
            $validated['months'],
            $request->boolean('include_previous_months')
        );

        if ($amount <= 0) {
            return response()->json(['message' => 'เดือนที่เลือกไม่มียอดค้างชำระ'], 422);
        }

        try {
            $payload = PromptPayQr::payload($promptPayId, $amount);
        } catch (\InvalidArgumentException $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }

        $svg = (string) QrCode::format('svg')->size(240)->margin(2)->generate($payload);

        return response()->json([
            'amount' => number_format($amount, 2, '.', ''),
            'qr_data_uri' => 'data:image/svg+xml;base64,' . base64_encode($svg),
        ]);
    }

    private function selectedMonthPlans(Collection $subscriptions, array $selectedMonths, bool $includePreviousMonths): array
    {
        $plans = [];
        $total = 0.0;
        $subscriptionIds = $subscriptions->pluck('id')->map(fn($id) => (string) $id)->all();

        if (array_diff(array_map('strval', array_keys($selectedMonths)), $subscriptionIds)) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'months' => 'พบเดือนที่ไม่ได้เป็นของถังที่เลือก',
            ]);
        }

        foreach ($subscriptions as $subscription) {
            $subscriptionKey = (string) $subscription->id;
            $requestedMonths = array_values(array_unique($selectedMonths[$subscriptionKey] ?? []));
            if (!$requestedMonths) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'months' => 'เลือกเดือนที่ต้องการชำระให้ครบทุกถัง',
                ]);
            }

            $schedule = $this->monthSchedule($subscription);
            $scheduleByMonth = collect($schedule)->keyBy('value');
            $eligibleMonths = collect($schedule)
                ->filter(fn($month) => !$month['paid'] && ($includePreviousMonths || !$month['before_member']))
                ->pluck('value')
                ->all();
            $requestedSet = array_fill_keys($requestedMonths, true);
            $orderedRequested = array_values(array_filter(
                $eligibleMonths,
                fn($monthValue) => isset($requestedSet[$monthValue])
            ));
            $expectedPrefix = array_slice($eligibleMonths, 0, count($requestedMonths));

            if (count($orderedRequested) !== count($requestedMonths) || $orderedRequested !== $expectedPrefix) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'months' => 'ต้องเลือกเดือนค้างชำระเรียงต่อเนื่องจากเดือนแรก ห้ามข้ามเดือน',
                ]);
            }

            foreach ($orderedRequested as $monthValue) {
                $month = $scheduleByMonth->get($monthValue);
                $plans[$subscription->id][] = $month;
                $total += $month['amount'];
            }
        }

        return [$plans, $total];
    }

    public function storeBulkPayment(Request $request)
    {
        $validated = $request->validate([
            'subscription_ids' => 'required|array|min:1|max:100',
            'subscription_ids.*' => 'required|integer|distinct',
            'months' => 'required|array|min:1',
            'months.*' => 'required|array|min:1',
            'months.*.*' => ['required', 'regex:/^(?:[1-9]|1[0-2])\|\d{4}$/'],
            'include_previous_months' => 'nullable|boolean',
            'payment_date' => 'required|date',
            'notes' => 'nullable|string|max:255',
        ]);

        $orgId = (int) getAuthOrgId();
        $subscriptionIds = array_values(array_unique(array_map('intval', $validated['subscription_ids'])));
        $includePreviousMonths = $request->boolean('include_previous_months');
        $selectedMonths = $validated['months'];

        $paymentTotal = DB::transaction(function () use ($validated, $orgId, $subscriptionIds, $selectedMonths, $includePreviousMonths) {
            $subscriptions = AnnualTrashSubscription::where('org_id_fk', $orgId)
                ->whereIn('id', $subscriptionIds)
                ->with(['AnnualTrash.user.annualTrashMember', 'payments'])
                ->lockForUpdate()
                ->get();

            if ($subscriptions->count() !== count($subscriptionIds)) {
                abort(404);
            }

            if ($subscriptions->pluck('user_id')->unique()->count() !== 1) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'subscription_ids' => 'เลือกชำระได้เฉพาะถังของสมาชิกคนเดียวกัน',
                ]);
            }

            [$plans, $total] = $this->selectedMonthPlans($subscriptions, $selectedMonths, $includePreviousMonths);

            if ($total <= 0) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'months' => 'เดือนที่เลือกไม่มียอดค้างชำระ',
                ]);
            }

            foreach ($subscriptions as $subscription) {
                $subscription->annual_fee = $this->annualFeeTarget($subscription, $includePreviousMonths);
                foreach ($plans[$subscription->id] ?? [] as $monthDue) {
                    AnnualTrashPayment::create([
                        'org_id_fk' => $orgId,
                        'wbs_id' => $subscription->id,
                        'pay_mon' => $monthDue['month'],
                        'pay_yr' => $monthDue['year'],
                        'amount_paid' => $monthDue['amount'],
                        'pay_date' => $validated['payment_date'],
                        'notes' => $validated['notes'] ?? null,
                        'staff_id' => Auth::id(),
                    ]);
                }

                $totalPaid = $subscription->payments()->sum('amount_paid');
                $subscription->total_paid_amt = $totalPaid;
                $subscription->status = $totalPaid >= $subscription->annual_fee
                    ? 'paid'
                    : ($totalPaid > 0 ? 'partially_paid' : 'pending');
                $subscription->save();
            }

            return $total;
        });

        return redirect()->route('keptkayas.annual_payments.bulk_receipt', [
            'subscription_ids' => $subscriptionIds,
            'payment_date' => $validated['payment_date'],
        ])->with('success', 'รับชำระรวม ' . number_format($paymentTotal, 2) . ' บาท เรียบร้อยแล้ว');
    }

    private function fiscalYearStart(int $fiscalYear): Carbon
    {
        $fiscalYearAd = $fiscalYear > 2400 ? $fiscalYear - 543 : $fiscalYear;
        return Carbon::create($fiscalYearAd - 1, 10, 1)->startOfMonth();
    }

    private function memberStartDate(AnnualTrashSubscription $subscription): Carbon
    {
        $subscription->loadMissing('AnnualTrash.user.annualTrashMember');
        $memberDate = $subscription->AnnualTrash?->user?->annualTrashMember?->created_at;

        if (!$memberDate) {
            $memberDate = AnnualTrashMember::where('org_id_fk', $subscription->org_id_fk)
                ->where('user_id', $subscription->user_id)
                ->value('created_at');
        }

        return $memberDate
            ? Carbon::parse($memberDate)->startOfMonth()
            : ($subscription->created_at
                ? Carbon::parse($subscription->created_at)->startOfMonth()
                : $this->fiscalYearStart((int) $subscription->fiscal_year));
    }

    private function annualFeeTarget(AnnualTrashSubscription $subscription, bool $includePreviousMonths = false): float
    {
        if ($includePreviousMonths) {
            return (float) $subscription->month_fee * 12;
        }

        $startOfFiscalYear = $this->fiscalYearStart((int) $subscription->fiscal_year);
        $memberSince = $this->memberStartDate($subscription);
        $serviceMonths = 0;

        for ($monthOffset = 0; $monthOffset < 12; $monthOffset++) {
            if ($startOfFiscalYear->copy()->addMonths($monthOffset)->gte($memberSince)) {
                $serviceMonths++;
            }
        }

        return (float) $subscription->month_fee * $serviceMonths;
    }

    private function outstandingMonthPlan(AnnualTrashSubscription $subscription): array
    {
        $startOfFiscalYear = $this->fiscalYearStart((int) $subscription->fiscal_year);
        $memberSince = $this->memberStartDate($subscription);
        $paymentsByMonth = $subscription->payments->groupBy(
            fn($payment) => $payment->pay_mon . '|' . $payment->pay_yr
        );
        $remainingAnnualFee = max(
            $this->annualFeeTarget($subscription) - (float) $subscription->payments->sum('amount_paid'),
            0
        );
        $plan = [];

        for ($monthOffset = 0; $monthOffset < 12 && $remainingAnnualFee > 0; $monthOffset++) {
            $monthDate = $startOfFiscalYear->copy()->addMonths($monthOffset);
            if ($monthDate->lt($memberSince)) {
                continue;
            }

            $monthPayments = $paymentsByMonth->get($monthDate->month . '|' . $monthDate->year, collect());
            $monthBalance = max(
                (float) $subscription->month_fee - (float) $monthPayments->sum('amount_paid'),
                0
            );
            $amount = min($monthBalance, $remainingAnnualFee);

            if ($amount > 0) {
                $plan[] = [
                    'month' => $monthDate->month,
                    'year' => $monthDate->year,
                    'amount' => $amount,
                ];
                $remainingAnnualFee -= $amount;
            }
        }

        return $plan;
    }

    private function monthSchedule(AnnualTrashSubscription $subscription): array
    {
        $startOfFiscalYear = $this->fiscalYearStart((int) $subscription->fiscal_year);
        $memberSince = $this->memberStartDate($subscription);
        $paymentsByMonth = $subscription->payments->groupBy(
            fn($payment) => $payment->pay_mon . '|' . $payment->pay_yr
        );
        $schedule = [];

        for ($monthOffset = 0; $monthOffset < 12; $monthOffset++) {
            $monthDate = $startOfFiscalYear->copy()->addMonths($monthOffset);
            $value = $monthDate->month . '|' . $monthDate->year;
            $paidAmount = (float) $paymentsByMonth->get($value, collect())->sum('amount_paid');
            $amountDue = max((float) $subscription->month_fee - $paidAmount, 0);

            $schedule[] = [
                'value' => $value,
                'month' => $monthDate->month,
                'year' => $monthDate->year,
                'month_name' => $monthDate->locale('th')->isoFormat('MMM'),
                'thai_year' => $monthDate->year + 543,
                'amount' => $amountDue,
                'paid' => $amountDue <= 0,
                'before_member' => $monthDate->lt($memberSince),
            ];
        }

        return $schedule;
    }

    /**
     * 4. บันทึกการรับชำระเงิน (Store Payment)
     */
    public function storePayment(Request $request, AnnualTrashSubscription $wasteBinSubscription)
    {
        $orgId = (int) getAuthOrgId();
        abort_unless((int) $wasteBinSubscription->org_id_fk === $orgId, 404);

        $validated = $request->validate([
            'selected_months'   => 'required|array|min:1',
            'selected_months.*' => ['required', 'regex:/^(?:[1-9]|1[0-2])\|\d{4}$/'],
            'amount_paid'       => 'required|numeric|min:0.01',
            'payment_date'      => 'required|date',
            'notes'             => 'nullable|string|max:255',
            'include_previous_months' => 'nullable|boolean',
        ]);

        $processedMonths = array_values(array_unique($validated['selected_months']));
        $fiscalYear = (int) $wasteBinSubscription->fiscal_year;
        $includePreviousMonths = $request->boolean('include_previous_months');
        $schedule = collect($this->monthSchedule($wasteBinSubscription));
        $eligibleMonthValues = $schedule
            ->filter(fn($month) => !$month['paid'] && ($includePreviousMonths || !$month['before_member']))
            ->pluck('value')
            ->all();
        $selectedSet = array_fill_keys($processedMonths, true);
        $orderedSelectedMonths = array_values(array_filter(
            $eligibleMonthValues,
            fn($monthValue) => isset($selectedSet[$monthValue])
        ));
        $expectedMonthPrefix = array_slice($eligibleMonthValues, 0, count($processedMonths));

        if (count($orderedSelectedMonths) !== count($processedMonths) || $orderedSelectedMonths !== $expectedMonthPrefix) {
            return back()->withErrors(['selected_months' => 'ต้องเลือกเดือนค้างชำระเรียงต่อเนื่องจากเดือนแรก ห้ามข้ามเดือน'])->withInput();
        }

        $dueTotal = 0.0;

        foreach ($orderedSelectedMonths as $monthYearString) {
            [$month, $year] = array_map('intval', explode('|', $monthYearString));
            $paidForMonth = $wasteBinSubscription->payments()
                ->where('pay_mon', $month)
                ->where('pay_yr', $year)
                ->sum('amount_paid');
            $dueTotal += max((float) $wasteBinSubscription->month_fee - (float) $paidForMonth, 0);
        }

        if (abs((float) $validated['amount_paid'] - $dueTotal) > 0.01) {
            return back()->withErrors(['amount_paid' => 'ยอดชำระไม่ตรงกับยอดคงเหลือของเดือนที่เลือก'])->withInput();
        }

        $annualFeeTarget = $this->annualFeeTarget($wasteBinSubscription, $includePreviousMonths);

        DB::transaction(function () use ($validated, $orderedSelectedMonths, $wasteBinSubscription, $orgId, $annualFeeTarget) {
            foreach ($orderedSelectedMonths as $monthYearString) {
                [$month, $year] = array_map('intval', explode('|', $monthYearString));
                $paidForMonth = $wasteBinSubscription->payments()
                    ->where('pay_mon', $month)
                    ->where('pay_yr', $year)
                    ->sum('amount_paid');
                $amountForMonth = max((float) $wasteBinSubscription->month_fee - (float) $paidForMonth, 0);

                if ($amountForMonth > 0) {
                    AnnualTrashPayment::create([
                        'org_id_fk' => $orgId,
                        'wbs_id' => $wasteBinSubscription->id,
                        'pay_mon' => $month,
                        'pay_yr' => $year,
                        'amount_paid' => $amountForMonth,
                        'pay_date' => $validated['payment_date'],
                        'notes' => $validated['notes'] ?? null,
                        'staff_id' => Auth::id(),
                    ]);
                }
            }

            $totalPaid = $wasteBinSubscription->payments()->sum('amount_paid');
            $wasteBinSubscription->annual_fee = $annualFeeTarget;
            $wasteBinSubscription->total_paid_amt = $totalPaid;
            $wasteBinSubscription->status = $totalPaid >= $wasteBinSubscription->annual_fee
                ? 'paid'
                : ($totalPaid > 0 ? 'partially_paid' : 'pending');
            $wasteBinSubscription->save();
        });

        return redirect()->route('keptkayas.annual_payments.printReceipt', $wasteBinSubscription->id)
            ->with('success', 'บันทึกการชำระเงินเรียบร้อยแล้ว!')
            ->with('payment_date', $validated['payment_date']);
    }
}