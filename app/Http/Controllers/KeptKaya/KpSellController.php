<?php

namespace App\Http\Controllers\KeptKaya;

use App\Http\Controllers\Controller;
use App\Models\Admin\Staff;
use App\Models\KeptKaya\KpPurchaseShop;
use Illuminate\Http\Request;
use App\Models\KeptKaya\KpSellTransaction;
use App\Models\KeptKaya\KpSellDetail;
use App\Models\KeptKaya\KpTbankItems;
use App\Models\KPBankAccount;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class KpSellController extends Controller
{
    /**
     * Show the sell form to create a new transaction.
     *
     * @return \Illuminate\View\View
     */
    public function showSellForm()
    {
        $user = Auth::user();
        $orgId = $user->org_id_fk ?? 1;

        // ตรวจสอบบัญชีกลางของ อปท. (org_recycle_bank)
        $orgRecycleBankAccount = KPBankAccount::where('org_id_fk', $orgId)
            ->whereIn('entity_type', ['org_recycle_bank', 'recycle_bank'])
            ->first();

        $hasOrgBankAccount = !is_null($orgRecycleBankAccount);

        // Fetch necessary data for the form
        $recycleItems = KpTbankItems::with('items_price_and_point_infos.kp_units_info')
            ->where('org_id_fk', $orgId)
            ->whereHas('items_price_and_point_infos')
            ->get();

        // กรองเฉพาะ Staff ที่มีข้อมูล user เชื่อมโยงอยู่จริง (ป้องกัน null pointer)
        $staffs = Staff::with('user')
            ->where('org_id_fk', $orgId)
            ->whereHas('user')
            ->get();

        $shops = KpPurchaseShop::where('org_id_fk', $orgId)
            ->where('status', 'active')
            ->get();

        return view('keptkayas.sell.sell_form', compact(
            'recycleItems',
            'staffs',
            'shops',
            'hasOrgBankAccount',
            'orgRecycleBankAccount'
        ));
    }

    /**
     * Store the sell transaction and its details from the form.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeSellTransaction(Request $request)
    {
        $validated = $request->validate([
            'shop_name' => 'required',
            'sell_date' => 'required|date',
            'details' => 'required|array|min:1',
            'details.*.kp_recycle_item_id' => 'required|exists:kp_tbank_items,id',
            'details.*.weight' => 'required|numeric|min:0.01',
            'details.*.amount' => 'required|numeric|min:0.01',
            'details.*.price_per_unit' => 'required|numeric|min:0',
            'details.*.comment' => 'nullable|string',
            'recorder_id' => 'required|exists:staffs,user_id',
        ]);
        $totalWeight = array_sum(array_column($validated['details'], 'weight'));
        $totalAmount = array_sum(array_column($validated['details'], 'amount'));

        
        DB::beginTransaction();
        try {
            // 1. Create the main sell transaction
            $orgId = Auth::user()->org_id_fk ?? 1;
            $orgRecycleBankAccount = KPBankAccount::where('org_id_fk', $orgId)
                ->whereIn('entity_type', ['org_recycle_bank', 'recycle_bank'])
                ->lockForUpdate()
                ->first();

            if (!$orgRecycleBankAccount) {
                DB::rollBack();
                return back()->with('error', 'ไม่พบบัญชีกลางธนาคารขยะขององค์กร กรุณาตั้งค่าบัญชีกลางก่อนบันทึกรายการขาย')->withInput();
            }

            $transaction = KpSellTransaction::create([
                'org_id_fk'         => $orgId,
                'user_id_fk'        => $orgRecycleBankAccount->user_id,
                'kp_u_trans_no'     => 'S-' . Carbon::now()->format('YmdHis') . Str::random(4),
                'shop_id_fk'        => $validated['shop_name'],
                'transaction_date'  => $validated['sell_date'],
                'total_weight'      => $totalWeight,
                'total_amount'      => $totalAmount,
                'recorder_id'       => $validated['recorder_id'],
            ]);

            // 2. Create the sell details for each item in the transaction
            foreach ($validated['details'] as $detail) {
                KpSellDetail::create([
                    'amount'                => $detail['amount'],
                    'weight'                => $detail['weight'],
                    'comment'               => $detail['comment'],
                    'org_id_fk'             => $orgId,
                    'price_per_unit'        => $detail['price_per_unit'],
                    'kp_sell_trans_id'      => $transaction->id,
                    'kp_recycle_item_id'    => $detail['kp_recycle_item_id'],
                    
                ]);
            }

               $orgRecycleBankAccount->balance += $totalAmount;
               $orgRecycleBankAccount->save();



            DB::commit();
            return redirect()->route('keptkayas.sell.history')->with('success', 'บันทึกการขายขยะเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error saving sell transaction: " . $e->getMessage());
            return back()->with('error', 'เกิดข้อผิดพลาดในการบันทึกการขาย: ' . $e->getMessage());
        }
    }


    public function showSellHistory()
    {
        $transactions = KpSellTransaction::with(['recorder'])->orderByDesc('transaction_date')->paginate(20);
        return view('keptkayas.sell.history', compact('transactions'));
    }
    public function showReceipt(KpSellTransaction $transaction)
    {
        $transaction->load(['details.item', 'recorder']);
        return view('keptkayas.sell.receipt', compact('transaction'));
    }

    public function destroy(Request $request, $transaction) {}
}
