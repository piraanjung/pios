<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Tests\TestCase;

class KeptKayaCaseSensitivityTest extends TestCase
{
    /**
     * Test that all critical KeptKaya routes resolve and exist in route collection.
     */
    public function test_keptkaya_core_routes_are_defined_with_proper_prefix(): void
    {
        $criticalRoutes = [
            'keptkayas.recycle-bank.members.index',
            'keptkayas.recycle-bank.members.create',
            'keptkayas.recycle-bank.members.bulkStore',
            'keptkayas.recycle-bank.history',
            'keptkayas.purchase.select_route',
            'keptkayas.purchase.select_user',
            'keptkayas.purchase.form',
            'keptkayas.purchase.cart',
            'keptkayas.purchase.save_transaction',
            'keptkayas.purchase.receipt',
            'keptkayas.sell.form',
            'keptkayas.sell.store',
            'keptkayas.sell.history',
            'keptkayas.withdraw.create',
            'keptkayas.withdraw.store',
            'keptkayas.wbin_payrate_per_months.index',
            'keptkayas.wbin_payrate_per_months.update',
            'keptkayas.kp_usergroup.update',
            'keptkayas.waste_bins.update',
            'keptkayas.users.update',
            'keptkayas.users.enroll.store',
            'keptkayas.tbank.items.buy_items',
            'keptkayas.tbank.cart.cart_lists',
        ];

        foreach ($criticalRoutes as $routeName) {
            $this->assertTrue(
                Route::has($routeName),
                "Route [{$routeName}] is not defined or has incorrect naming/casing."
            );
        }
    }

    /**
     * Test case-sensitivity of critical models matching filesystem on Linux.
     */
    public function test_models_exist_with_exact_case(): void
    {
        $models = [
            'App\Models\KPBankAccount' => 'app/Models/KPBankAccount.php',
            'App\Models\KeptKaya\KpRecycleBankMember' => 'app/Models/KeptKaya/KpRecycleBankMember.php',
            'App\Models\KeptKaya\KpPurchaseTransaction' => 'app/Models/KeptKaya/KpPurchaseTransaction.php',
            'App\Models\KeptKaya\KpPurchaseTransactionDetail' => 'app/Models/KeptKaya/KpPurchaseTransactionDetail.php',
            'App\Models\KeptKaya\KpSellTransaction' => 'app/Models/KeptKaya/KpSellTransaction.php',
            'App\Models\KeptKaya\KpSellDetail' => 'app/Models/KeptKaya/KpSellDetail.php',
            'App\Models\KeptKaya\KpMoneyRequest' => 'app/Models/KeptKaya/KpMoneyRequest.php',
            'App\Models\KeptKaya\KpSetting' => 'app/Models/KeptKaya/KpSetting.php',
            'App\Models\Admin\Staff' => 'app/Models/Admin/Staff.php',
        ];

        foreach ($models as $class => $relativePath) {
            $this->assertTrue(class_exists($class), "Class [{$class}] cannot be autoloaded.");
            $this->assertFileExists(base_path($relativePath), "File [{$relativePath}] does not exist on disk with exact casing.");
        }
    }
}
