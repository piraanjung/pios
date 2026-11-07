<?php

namespace App\Features\Kiosk\SubmitKioskDeposit;

use App\Models\KeptKaya\KpPurchaseTransaction;
use App\Models\KeptKaya\KpPurchaseTransactionDetail;
use App\Models\KPBankAccount;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SubmitKioskDepositAction
{
    public function execute(
        int $userId,
        int $orgId,
        string $machineId,
        array $items,
        float $totalAmount = 0.0,
        int $totalPoints = 0,
        ?int $kioskId = null
    ): array {
        return DB::transaction(function () use ($userId, $orgId, $machineId, $items, $totalAmount, $totalPoints, $kioskId) {
            $user = User::find($userId);
            if (!$user) {
                throw new \InvalidArgumentException('User not found.');
            }

            $transNo = 'KIOSK-' . now()->format('ymdHis') . '-' . strtoupper(Str::random(4));

            $transaction = KpPurchaseTransaction::create([
                'kp_u_trans_no'    => $transNo,
                'org_id_fk'        => $orgId,
                'kiosk_id_fk'      => $kioskId,
                'user_id'          => $userId,
                'transaction_date' => now(),
                'total_weight'     => 0.00,
                'total_amount'     => $totalAmount,
                'total_points'     => $totalPoints,
                'status'           => 'completed',
                'recorder_id'      => null,
                'cash_back'        => 0,
            ]);

            foreach ($items as $item) {
                KpPurchaseTransactionDetail::create([
                    'kp_purchase_trans_id'         => $transaction->id,
                    'org_id_fk'                    => $orgId,
                    'kp_recycle_item_id'           => $item['rateId'] ?? $item['itemId'] ?? 1,
                    'kp_units_idfk'                => $item['unitId'] ?? 2,
                    'kp_tbank_items_pricepoint_id' => $item['priceConfigId'] ?? null,
                    'amount_in_units'              => $item['qty'] ?? 1,
                    'price_per_unit'               => $item['price'] ?? 0,
                    'amount'                       => ($item['price'] ?? 0) * ($item['qty'] ?? 1),
                    'points'                       => ($item['point'] ?? 0) * ($item['qty'] ?? 1),
                ]);
            }

            // Atomically update master bank account
            $account = KPBankAccount::where('user_id', $userId)
                ->where('org_id_fk', $orgId)
                ->first();

            if ($account) {
                $account->increment('balance', $totalAmount);
                $account->increment('points', $totalPoints);
            }

            return [
                'transaction_no' => $transNo,
                'total_points'   => $totalPoints,
                'total_amount'   => $totalAmount,
                'balance_after'  => $account ? (float) $account->balance : $totalAmount,
                'points_after'   => $account ? (int) $account->points : $totalPoints,
                'success'        => true,
            ];
        });
    }
}
