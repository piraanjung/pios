<?php

namespace Tests\Integration\Features\Kiosk;

use App\Features\Kiosk\SubmitKioskDeposit\SubmitKioskDepositAction;
use App\Models\KPBankAccount;
use App\Models\KeptKaya\KpPurchaseTransaction;
use App\Models\KeptKaya\KpPurchaseTransactionDetail;
use Tests\Support\Concerns\BuildsTestSchema;
use Tests\Support\Concerns\InteractsWithTenancy;
use Tests\TestCase;

class SubmitKioskDepositActionTest extends TestCase
{
    use BuildsTestSchema, InteractsWithTenancy;

    protected SubmitKioskDepositAction $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->buildStandardTestSchema();
        $this->action = new SubmitKioskDepositAction();
        $this->resetTenancy();
    }

    protected function tearDown(): void
    {
        $this->resetTenancy();
        parent::tearDown();
    }

    public function test_successfully_processes_kiosk_deposit_and_increments_ledger(): void
    {
        $orgId = 1;
        $citizenData = $this->createCitizenWithRecycleAccount($orgId, initialBalance: 50.00, initialPoints: 100);
        $user = $citizenData['user'];
        $account = $citizenData['account'];

        $items = [
            ['rateId' => 1, 'unitId' => 2, 'qty' => 5, 'price' => 0.50, 'point' => 1], // 2.50 THB, 5 pts
            ['rateId' => 2, 'unitId' => 2, 'qty' => 2, 'price' => 1.25, 'point' => 3], // 2.50 THB, 6 pts
        ];

        $result = $this->action->execute(
            userId: $user->id,
            orgId: $orgId,
            machineId: 'KIOSK-NORTH-01',
            items: $items,
            totalAmount: 5.00,
            totalPoints: 11
        );

        $this->assertTrue($result['success']);
        $this->assertSame(5.00, $result['total_amount']);
        $this->assertSame(11, $result['total_points']);
        $this->assertSame(55.00, $result['balance_after']);
        $this->assertSame(111, $result['points_after']);

        // ตรวจสอบ Transaction Header
        $transaction = KpPurchaseTransaction::where('kp_u_trans_no', $result['transaction_no'])->first();
        $this->assertNotNull($transaction);
        $this->assertSame($user->id, (int) $transaction->user_id);
        $this->assertSame(1, (int) $transaction->org_id_fk);
        $this->assertEquals(5.00, $transaction->total_amount);
        $this->assertSame(11, (int) $transaction->total_points);

        // ตรวจสอบ Transaction Details
        $details = KpPurchaseTransactionDetail::where('kp_purchase_trans_id', $transaction->id)->get();
        $this->assertCount(2, $details);

        // ตรวจสอบ Master Account Balance อัปเดตจริงในฐานข้อมูล
        $account->refresh();
        $this->assertEquals(55.00, $account->balance);
        $this->assertSame(111, (int) $account->points);
    }
}
