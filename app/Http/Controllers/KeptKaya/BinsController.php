<?php

namespace App\Http\Controllers\KeptKaya;

use App\Http\Controllers\Controller;
use App\Models\Admin\Organization;
use App\Models\FoodWaste\FoodAnnualTrashStocks;
use App\Models\FoodWaste\FoodwastIotbox; // ต้องใช้ Model ของ IoT Box เพื่อดึงข้อมูลมาแสดงในฟอร์ม
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BinsController extends Controller
{
    public function index(Request $request)
    {
        $orgId = (int) Auth::user()->org_id_fk;
        $orgInfos = Organization::getOrgName($orgId);
        $binPrefix = trim((string) $request->input('prefix', ($orgInfos['org_code'] ?? 'ORG') . '-B'));

        $query = FoodAnnualTrashStocks::with('foodwaste_bin', 'foodwaste_bin.fw_user_preference', 'foodwaste_bin.fw_user_preference.user')
            ->where('bin_type', 'annual');

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('bin_code', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $summaryQuery = FoodAnnualTrashStocks::where('bin_type', 'annual');
        $summary = [
            'total' => (clone $summaryQuery)->count(),
            'available' => (clone $summaryQuery)->where('status', 'pending')->count(),
            'active' => (clone $summaryQuery)->where('status', 'active')->count(),
            'issues' => (clone $summaryQuery)->whereIn('status', ['damaged', 'removed'])->count(),
        ];

        $bins = $query->orderByDesc('id')->paginate(20)->withQueryString();
        $nextCode = $binPrefix . str_pad($this->nextRunNumber($binPrefix), 4, '0', STR_PAD_LEFT);

        return view('keptkayas.bins.index', compact('bins', 'summary', 'binPrefix', 'nextCode'));
    }

    public function create()
    {
        return redirect()->route('keptkayas.bins.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'prefix' => ['required', 'string', 'max:30', 'regex:/^[A-Za-z0-9-]+$/'],
            'quantity' => 'required|integer|min:1|max:500',
            'description' => 'nullable|string|max:191',
            'status' => 'required|in:active,pending,damaged,removed',
        ]);

        $prefix = $validated['prefix'];
        $quantity = (int) $validated['quantity'];

        $created = DB::transaction(function () use ($validated, $prefix, $quantity) {
            $startNumber = $this->nextRunNumber($prefix);
            $codes = collect(range($startNumber, $startNumber + $quantity - 1))
                ->map(fn($number) => $prefix . str_pad((string) $number, 4, '0', STR_PAD_LEFT));

            if (FoodAnnualTrashStocks::whereIn('bin_code', $codes)->exists()) {
                throw ValidationException::withMessages([
                    'prefix' => 'พบรหัสถังซ้ำในชุดที่จะสร้าง กรุณาลองใหม่อีกครั้ง',
                ]);
            }

            foreach ($codes as $code) {
                FoodAnnualTrashStocks::create([
                    'bin_code' => $code,
                    'bin_type' => 'annual',
                    'org_id_fk' => Auth::user()->org_id_fk,
                    'description' => $validated['description'] ?? null,
                    'status' => $validated['status'],
                ]);
            }

            return [$codes->first(), $codes->last()];
        });

        return redirect()->route('keptkayas.bins.index')
            ->with('success', 'สร้างรหัสถัง ' . $quantity . ' ใบ ตั้งแต่ ' . $created[0] . ' ถึง ' . $created[1]);
    }

    public function previewCodes(Request $request)
    {
        $validated = $request->validate([
            'prefix' => ['required', 'string', 'max:30', 'regex:/^[A-Za-z0-9-]+$/'],
            'quantity' => 'required|integer|min:1|max:500',
        ]);

        $startNumber = $this->nextRunNumber($validated['prefix']);
        $endNumber = $startNumber + (int) $validated['quantity'] - 1;

        return response()->json([
            'start_code' => $validated['prefix'] . str_pad((string) $startNumber, 4, '0', STR_PAD_LEFT),
            'end_code' => $validated['prefix'] . str_pad((string) $endNumber, 4, '0', STR_PAD_LEFT),
        ]);
    }

    private function nextRunNumber(string $prefix): int
    {
        $escapedPrefix = addcslashes($prefix, '%_\\');

        return (int) FoodAnnualTrashStocks::where('bin_type', 'annual')
            ->where('bin_code', 'like', $escapedPrefix . '%')
            ->pluck('bin_code')
            ->map(function ($code) use ($prefix) {
                if (preg_match('/^' . preg_quote($prefix, '/') . '(\d+)$/', $code, $matches)) {
                    return (int) $matches[1];
                }

                return 0;
            })
            ->max() + 1;
    }

    public function show(FoodAnnualTrashStocks $bin)
    {
        // ยังไม่มีหน้ารายละเอียดถัง ให้กลับไปหน้ารายการ
        return redirect()->route('keptkayas.bins.index');
    }

    public function edit(FoodAnnualTrashStocks $bin)
    {
        $iotboxes = FoodwastIotbox::all();
        return view('foodwaste.bins.edit', compact('bin', 'iotboxes'));
    }

    public function update(Request $request, FoodAnnualTrashStocks $bin)
    {
        // 1. Validation
        $request->validate([
            'bin_code' => 'required|string|unique:foodwaste_bins,bin_code,' . $bin->id . '|max:191', // ยกเว้นตัวเอง
            // ... (ตรวจสอบคอลัมน์อื่น ๆ เช่นเดียวกับ store)
        ]);

        // 2. อัปเดตข้อมูล
        $bin->update($request->all());

        return redirect()->route('foodwaste.bins.index')->with('success', 'อัปเดตข้อมูลสำเร็จ');
    }

    public function destroy(FoodAnnualTrashStocks $bin)
    {
        $bin->delete();
        return redirect()->route('foodwaste.bins.index')->with('success', 'ลบข้อมูลสำเร็จ');
    }
}
