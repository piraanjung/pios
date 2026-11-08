<?php

use App\Exports\TabWaterMembersExport;
use App\Http\Controllers\AccessMenusController;
use App\Http\Controllers\Admin\ExcelController;
use App\Http\Controllers\Admin\IndexController;
use App\Http\Controllers\Admin\MetertypeController;
use App\Http\Controllers\Admin\OrgAdminController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\AdminSubzoneController;
use App\Http\Controllers\Admin\OrgSelectorController;
use App\Http\Controllers\Admin\SuperAdminAuthController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\UserWelfareController;
use App\Http\Controllers\Admin\WelfareConfigController;
use App\Http\Controllers\Admin\WelfareController;
use App\Http\Controllers\Admin\WelfareMemberController;
use App\Http\Controllers\Admin\WorkflowController;
use App\Http\Controllers\Admin\ZoneController;
use App\Http\Controllers\EmissionFactorController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\Inventory\InvCategoryController;
use App\Http\Controllers\Inventory\InvDashboardController;
use App\Http\Controllers\Inventory\InvHazardLevelController;
use App\Http\Controllers\Inventory\InvItemController;
use App\Http\Controllers\Inventory\InvLocationController;
use App\Http\Controllers\Inventory\InvStockController;
use App\Http\Controllers\Inventory\InvTransactionController;
use App\Http\Controllers\Inventory\InvUnitController;
use App\Http\Controllers\KeptKaya\MachineController;
use App\Http\Controllers\Kiosk\KioskApiController;
use App\Http\Controllers\Tabwater\CutmeterController;
use App\Http\Controllers\Tabwater\BudgetYearController;
use App\Http\Controllers\Tabwater\InvoicePeriodController;
use App\Http\Controllers\LineLiffController;
use App\Http\Controllers\Tabwater\MeterRateConfigController;
use App\Http\Controllers\Tabwater\OwePaperController;
use App\Http\Controllers\Tabwater\PaymentController;
use App\Http\Controllers\Tabwater\ReportsController;
use App\Http\Controllers\Tabwater\SettingsController;
use App\Http\Controllers\Tabwater\StaffMobileController;
use App\Http\Controllers\Tabwater\SubzoneController;
use App\Http\Controllers\Tabwater\UserMeterInfosController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\Tabwater\TransferOldDataToNewDBController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SqlToJsonController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\Tabwater\InvoiceController;
use App\Http\Controllers\Tabwater\NotifyController;
use App\Http\Controllers\Tabwater\TabwaterControlController;
use App\Http\Controllers\Tabwater\TabwaterDeviceController;
use App\Http\Controllers\Tabwater\TabwaterPipelineController;
use App\Http\Controllers\Tabwater\TabwaterPipeNodeController;
use App\Http\Controllers\Tabwater\TabWaterProductionDashboardController;
use App\Http\Controllers\Tabwater\TabwaterStationController;
use App\Http\Controllers\Tabwater\TwManMobileController;
use App\Http\Controllers\Tabwater\TwPricingTypeController;
use App\Http\Controllers\Tabwater\UndertakerSubzoneController;
use App\Http\Controllers\Tabwater\WaterLedgerController;

use App\Models\User;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/kiosk_login', function () {
    return view('kiosk/kiosk_login');
});

Route::get('/preview/soft-ui-pro', function () {
    return view('preview.soft-ui-pro');
});

Route::post('/api/check_member', [KioskApiController::class, 'checkMember']);
Route::post('/api/save_session', [KioskApiController::class, 'saveSession']);
Route::get('/kiosk', [KioskApiController::class, 'index']);

Route::middleware(['auth'])->group(function () {
    Route::get('/kiosk/scan/{kioskId}', [\App\Http\Controllers\Kiosk\KioskController::class, 'scanQr'])->name('kiosk.scan');

    // หน้า AI Camera (หน้าหลักที่จะใช้งาน)
    Route::get('/kiosk/session/{kioskId}', [\App\Http\Controllers\Kiosk\KioskController::class, 'sessionPage'])->name('kiosk.session');
});


// Route สำหรับหน้า Web App หลัก (ต้องเปิดด้วยโทรศัพท์ User)
Route::get('/kiosk-app', function () {
    return view('kiosk/kiosk_app');
})->name('kiosk.app');

Route::get('/liff', function () {
    return view('liff');
});

Route::get('/login_staff', function () {
    Auth::logout();
    return view('auth.login_staff');
});

Route::get('acc', function () {
    Auth::logout();
    return view('staff.mobile.staff_accessmenu');
    // return view('staff.mobile');
});
Route::post('/inventory/items/iframe/session', [InvItemController::class, 'startIframeSession'])
    ->name('inventory.items.iframe.session');

Route::get('/logout', function () {
    Auth::logout();
    Session()->invalidate();
    Session()->regenerateToken();
    Session()->flush();

    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';

    // ตรวจสอบคำที่บ่งชี้ถึงอุปกรณ์มือถือ
    $ismobile = preg_match(
        "/(android|avantgo|blackberry|bolt|boost|cello|hiptop|irengin|mobi|mini|mo(bil|si)|ntellect|palm|pda|phone|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|wap|windows ce|xda|xiino)/i",
        $userAgent
    );
    if ($ismobile) {
        return redirect()->route('login_staff');
    }
    return redirect('/');
});

Route::prefix('tabwater/notify')->name('tabwater.notify.')->group(function () {
    Route::get('/', [NotifyController::class, 'index'])->name('index');
    Route::post('/', [NotifyController::class, 'store'])->name('store');
    Route::get('/check-member', [NotifyController::class, 'checkMember'])->name('check_member');
    Route::get('/success/{id}', [NotifyController::class, 'success'])->name('success');
    Route::get('/create', [NotifyController::class, 'create'])->name('create');     // <-- เพิ่มบรรทัดนี้ (หน้าฟอร์มกรอกแจ้งเหตุ)    Route::post('/check-phone', [NotifyController::class, 'checkPhone'])->name('check-phone');
    Route::get('/xx', [NotifyController::class, 'sendHeadNotificationText'])->name('xxx');
});

Route::get('/logout_staff', function () {
    Auth::logout();
    Session()->invalidate();
    Session()->regenerateToken();
    Session()->flush();

    return view('auth/login_staff');
});
if (app()->environment('local', 'testing')) {
    Route::get('/upload-form', function () {
        return view('upload');
    });

    Route::post('/upload-and-convert', [SqlToJsonController::class, 'uploadAndProcess']);

    Route::resource('/test', TestController::class)->except(['create', 'store', 'show', 'edit', 'update', 'destroy']);
    Route::get('/test/textSendLineMessage', [TestController::class, 'textSendLineMessage']);
}


// Auth::routes() removed: auth routes are provided by Breeze in routes/auth.php.
// Keeping both caused duplicate route names (e.g. password.request) that break route:cache.

Route::group(['middleware' => ['auth', 'role:Admin|Super Admin']], function () {
    Route::resource('org-admins', OrgAdminController::class)->except('show');
    Route::get('/ajax/users/search', [OrgAdminController::class, 'searchUsers'])->name('ajax.users.search');
});

Route::get('/accessmenu', [AccessMenusController::class, 'accessmenu'])->middleware(['auth'])->name('accessmenu');
Route::get('/staff_accessmenu', [AccessMenusController::class, 'staff_accessmenu'])->middleware(['auth'])->name('staff_accessmenu');


Route::get('/dashboard', [AccessMenusController::class, 'dashboard'])->middleware(['auth'])->name('dashboard');

Route::get('/lineliff', [LineLiffController::class, 'index'])->name('lineliff.index');
Route::get('/line/dashboard/{pref_id}/{org_id}/{regis?}', [LineLiffController::class, 'dashboard'])->name('lineliff.dashboard');
Route::post('/line/user_line_register', [LineLiffController::class, 'user_line_register']);
Route::post('/line/update_user_by_phone', [LineLiffController::class, 'update_user_by_phone']);
Route::post('/line/login', [LineLiffController::class, 'handleLineLogin']);


Route::middleware(['auth', 'role:Admin|Super Admin'])->prefix('staffs')->name('keptkayas.staffs.')->group(function () {
    Route::get('/', [StaffController::class, 'index'])->name('index');
    Route::get('/create', [StaffController::class, 'create'])->name('create');
    Route::post('/', [StaffController::class, 'store'])->name('store');
    Route::get('/{staff}', [StaffController::class, 'show'])->name('show');
    Route::get('/{staff}/edit', [StaffController::class, 'edit'])->name('edit');
    Route::put('/{staff}', [StaffController::class, 'update'])->name('update');
    Route::delete('/{staff}', [StaffController::class, 'destroy'])->name('destroy');

    // Route สำหรับกดรับงาน (รองรับ Model Binding ผ่าน id ของ $notify)
});

Route::middleware(['auth'])->prefix('staff')->name('staff.')->group(function () {
    // 1. หน้า Dashboard แสดงรายการงานทั้งหมดของ Staff
    Route::get('/dashboard', [StaffController::class, 'dashboard'])->name('dashboard');

    // 2. กดรับงานจาก LINE / หน้าเว็บ (implicit binding)
    Route::get('/job/{notify}/accept', [StaffController::class, 'acceptJob'])->name('job.accept');

    // 3. ดูรายละเอียดงานเฉพาะชิ้น (Detail Page)

    // 4. บันทึกปิดงาน (Complete) เมื่อซ่อมเสร็จแล้ว
    Route::post('/job/{notify}/complete', [StaffController::class, 'completeJob'])->name('job.complete');
});

Route::prefix('zones')->name('zones.')->group(function () {
    Route::get('/getzones/{tambon_id}', [ZoneController::class, 'getZones'])->name('getzones');
});

Route::prefix('tabwater/staff/mobile/')->name('tabwater.staff.mobile.')->group(function () {
    Route::get('{subzone_id}/{status}/members', [StaffMobileController::class, 'members'])->name('members');
    Route::get('{subzone_id}/membersJson', [StaffMobileController::class, 'membersJson'])->name('membersJson');
    Route::get('{meter_id}/meter_reading', [StaffMobileController::class, 'meter_reading'])->name('meter_reading');
    Route::post('process-meter-image', [StaffMobileController::class, 'process_meter_image'])->name('process_meter_image');
    Route::resource('/', StaffMobileController::class)->except(['create', 'show', 'edit', 'update', 'destroy']);
});





Route::get('twmanmobile', [TwManMobileController::class, 'index'])->name('twmanmobile');
Route::get('twmanmobile/main', [TwManMobileController::class, 'main'])->name('twmanmobile.main');
Route::get('twmanmobile/edit_members_subzone_selected', [TwManMobileController::class, 'edit_members_subzone_selected'])->name('twmanmobile.edit_members_subzone_selected');

Route::middleware(['auth', 'role:User|Admin|Super Admin'])->name('admin.')->prefix('admin/')->group(function () {
    Route::post('users/update-metrics', [UserController::class, 'updateMetrics'])->name('users.update_metrics');
    Route::post('users/{id}/attach-meter', [UserController::class, 'attachMeter'])
        ->name('users.attach_meter');
    Route::get('users/edit_invoices/{meter_id}', [UserController::class, 'edit_invoices'])->name('users.edit_invoices');
});

Route::middleware(['auth', 'role:Admin|Super Admin'])->name('admin.')->prefix('admin')->group(function () {
    Route::get('/admin/unknown-images', [ImageController::class, 'indexUnknownImages']);
    Route::get('/register', [UserController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [UserController::class, 'register']);
    Route::get('/transfer_old_data', [TransferOldDataToNewDBController::class, 'index'])->name('transfer_old_data');
    Route::get('/', [IndexController::class, 'index'])->name('index');
    Route::resource('/roles', RoleController::class)->except('show');
    Route::post('/roles/{role}/permissions', [RoleController::class, 'givePermission'])->name('roles.permissions');
    Route::delete('/roles/{role}/permissions/{permission}', [RoleController::class, 'revokePermission'])->name('roles.permissions.revoke');
    Route::post('/permissions/{permission}/roles', [PermissionController::class, 'assignRole'])->name('permissions.roles');
    Route::delete('/permissions/{permission}/roles/{role}', [PermissionController::class, 'removeRole'])->name('permissions.roles.remove');
    Route::resource('/permissions', PermissionController::class)->except('show');

    Route::resource('workflows', WorkflowController::class)->except('show');

    //tabwater
    Route::prefix('users/')->name('users.')->group(function () {
        Route::get('', [UserController::class, 'index'])->name('index');
        Route::get('staff', [UserController::class, 'staff'])->name('staff');
        Route::get('create', [UserController::class, 'create'])->name('create');
        Route::get('{user_id}/edit/{addmeter?}', [UserController::class, 'edit'])->name('edit');
        Route::post('store', [UserController::class, 'store'])->name('store');
        Route::post('users_search', [UserController::class, 'users_search'])->name('users_search');
        Route::put('{user_id}/update', [UserController::class, 'update'])->name('update');
        Route::get('{user_id}/cancel', [UserController::class, 'cancel'])->name('cancel');
        Route::delete('{meter_id}/destroy', [UserController::class, 'destroy'])->name('destroy');
        Route::get('{user}/history', [UserController::class, 'history'])->name('history');
        Route::post('{user}/roles', [UserController::class, 'assignRole'])->name('roles');
        Route::delete('{user}/roles/{role}', [UserController::class, 'removeRole'])->name('roles.remove');
        Route::get('{user_id}/permissions', [UserController::class, 'givePermission'])->name('permissions');
        Route::delete('{user}/permissions/{permission}', [UserController::class, 'revokePermission'])->name('permissions.revoke');
        Route::post('import', [UserController::class, 'importUsers'])->name('import');
        Route::get('download_template', [UserController::class, 'downloadUserTemplate'])->name('download_template');
        Route::get('{user}/{action?}', [UserController::class, 'show'])->name('show');
        Route::post('update-service', [UserController::class, 'updateService'])->name('update_service');
        Route::get('lookup/module-candidates/{module?}', [UserController::class, 'lookupModuleCandidates'])->name('lookup_candidates');
        Route::post('quick-store', [UserController::class, 'quickStore'])->name('quick_store');
    });


    Route::resource('/invoice_period', InvoicePeriodController::class)->except('show');
    Route::get('/invoice_period/{period_id}/generate', [InvoicePeriodController::class, 'generateInvoice'])->name('invoice_period.generate');

    Route::resource('/metertype', MetertypeController::class)->except('show');


    Route::get('/budgetyear/invoice_period_list/{budgetyear_id}', [BudgetYearController::class, 'invoice_period_list'])->name('budgetyear.invoice_period_list');
    Route::resource('/budgetyear', BudgetYearController::class)->except(['show', 'destroy']);
    Route::resource('/zone', ZoneController::class);
    Route::resource('/subzone', SubzoneController::class)->only(['edit', 'update']);
    Route::get('/subzone/{zone_id}/getSubzone', [SubzoneController::class, 'getSubzone'])->name('subzone.getSubzone');

    Route::get('super_admin/subzone/{id}/edit', [AdminSubzoneController::class, 'edit'])->name('super_admin.subzone.edit');
    Route::put('super_admin/subzone/{id}', [AdminSubzoneController::class, 'update'])->name('super_admin.subzone.update');


    Route::get('/settings/invoice', [SettingsController::class, 'invoice'])->name('settings.invoice');
    Route::post('/settings/organization', [SettingsController::class, 'updateOrganization'])->name('settings.organization.update');
    Route::get('/settings/organization/location-options', [SettingsController::class, 'organizationLocationOptions'])->name('settings.organization.location_options');
    Route::post('/settings/invoice_and_vat', [SettingsController::class, 'update_invoice_and_vat'])->name('settings.invoice_and_vat');
    Route::post('/settings/create_and_update', [SettingsController::class, 'create_and_update'])->name('settings.create_and_update');
    Route::post('/settings/store_users', [SettingsController::class, 'store_users'])->name('settings.store_users');
    Route::resource('/settings', SettingsController::class)->only('index');

    Route::post('/excel/store_invoice', [ExcelController::class, 'store_invoice'])->name('excel.store_invoice');
    Route::post('/excel/import_invoice_byzone', [ExcelController::class, 'import_invoice_byzone'])->name('excel.import_invoice_byzone');
    Route::post('/excel/import_invoice_old', [ExcelController::class, 'import_invoice_old'])->name('excel.import_invoice_old');
    Route::resource('/excel', ExcelController::class)->only(['index', 'create', 'store']);


    Route::get('/owepaper/index', [OwePaperController::class, 'index'])->name('owepaper.index');
    Route::post('/owepaper/print', [OwePaperController::class, 'print'])->name('owepaper.print');
    Route::resource('meter_rates', MeterRateConfigController::class);
    Route::resource('pricing_types', TwPricingTypeController::class)->except('show');

    Route::prefix('settings')->name('settings.')->group(function () {
        //     Route::get('/', [SuperAdminSettingsController::class, 'showSettingsForm'])->name('settings_form');

        //     Route::post('/import-provinces', [SuperAdminSettingsController::class, 'importProvinces'])->name('import.provinces');
        //     Route::get('/export-provinces', [SuperAdminSettingsController::class, 'exportProvinces'])->name('export.provinces');
        //     // Routes สำหรับ Import/Export Districts
        //     Route::post('/import-districts', [SuperAdminSettingsController::class, 'importDistricts'])->name('import.districts');
        //     Route::get('/export-districts', [SuperAdminSettingsController::class, 'exportDistricts'])->name('export.districts');
        //     Route::get('/user-to-tabwater', [SuperAdminSettingsController::class, 'userToTabwater'])->name('user_to_tabwater');

        //     // Routes สำหรับ Import/Export Tambons
        //     Route::post('/import-tambons', [SuperAdminSettingsController::class, 'importTambons'])->name('import.tambons');
        //     Route::get('/export-tambons', [SuperAdminSettingsController::class, 'exportTambons'])->name('export.tambons');

        //     Route::post('/import-tw_zones', [SuperAdminSettingsController::class, 'importTWZones'])->name('import.tw_zones');
        //     Route::get('/export-tw_zones', [SuperAdminSettingsController::class, 'exportTWZones'])->name('export.tw_zones');

        //     // Routes สำหรับ Import/Export TW_ZoneBlocks
        //     Route::post('/import-tw-zoneblocks', [SuperAdminSettingsController::class, 'importTWZoneBlocks'])->name('import.tw_zoneblocks');
        //     Route::get('/export-tw-zoneblocks', [SuperAdminSettingsController::class, 'exportTWZoneBlocks'])->name('export.tw_zoneblocks');

        //     // Routes สำหรับ Import/Export Organizations
        //     Route::post('/import-organizations', [SuperAdminSettingsController::class, 'importOrganizations'])->name('import.organizations');
        //     Route::get('/export-organizations', [SuperAdminSettingsController::class, 'exportOrganizations'])->name('export.organizations');

        //     Route::get('/export-users', [SuperAdminSettingsController::class, 'exportUsers'])->name('export.users');

        //     // Routes สำหรับ Import/Export TwMeters
        //     Route::post('/import-twmeters', [SuperAdminSettingsController::class, 'importTwMeters'])->name('import.tw_meters');
        //     Route::get('/export-twmeters', [SuperAdminSettingsController::class, 'exportTwMeters'])->name('export.tw_meters');
    });


    Route::get('/undertaker_subzone', [UndertakerSubzoneController::class, 'index'])->name('undertaker_subzone');
    Route::get('/undertaker_subzone/create',  [UndertakerSubzoneController::class, 'create'])->name('undertaker_subzone.create');
    Route::post('undertaker_subzone/store', [UndertakerSubzoneController::class, 'store'])->name('undertaker_subzone.store');
    // Route::get('undertaker_subzone/update/{id}', 'UndertakerSubzoneController@update');
    Route::get('/undertaker_subzone/edit/{id}', [UndertakerSubzoneController::class, 'edit']);
    // Route::get('undertaker_subzone/delete/{id}', 'UndertakerSubzoneController@delete');
});




Route::middleware(['auth', 'role:Admin|finance|Super Admin'])->group(function () {
    Route::prefix('payment/')->name('payment.')->group(function () {
        Route::get('paymenthistory/{inv_period}/{subzone_id}', [PaymentController::class, 'paymenthistory'])->name('paymenthistory');
        Route::match(['get', 'post'], 'search', [PaymentController::class, 'search'])->name('search');
        Route::post('index_search_by_suzone', [PaymentController::class, 'index_search_by_suzone'])->name('index_search_by_suzone');
        Route::get('receipt_print/{account_id_fk?}/{payments?}', [PaymentController::class, 'receipt_print'])->name('receipt_print');
        Route::get('receipt_print_history/{account_id_fk?}', [PaymentController::class, 'receipt_print_history'])->name('receipt_print_history');
        Route::post('store_by_inv_no', [PaymentController::class, 'store_by_inv_no'])->name('store_by_inv_no');

        Route::resource('', PaymentController::class)->except(['show', 'edit', 'update'])->parameters(['' => 'payment']);
    });




    Route::prefix('reports/')->name('reports.')->group(function () {
        Route::post('export', [ReportsController::class, 'export'])->name('export');
        Route::get('p17', [WaterLedgerController::class, 'p17Report'])->name('p17');
        Route::get('owe', [ReportsController::class, 'owe'])->name('owe');
        Route::get('ledger', [ReportsController::class, 'ledger'])->name('ledger');
        Route::get('water_used/{from?}', [ReportsController::class, 'water_used'])->name('water_used');
        Route::post('dailypayment', [ReportsController::class, 'dailypayment'])->name('dailypayment');
        Route::post('owe_search', [ReportsController::class, 'owe_search'])->name('owe_search');
        Route::get('meter_record_history/{budgetyear?}/{zone_id?}', [ReportsController::class, 'meter_record_history'])->name('meter_record_history');
    });
});

Route::group(['middleware' => ['auth', 'role:Admin|tabwater|Super Admin']], function () {

    Route::resource('/invoice', InvoiceController::class)->except(['create', 'show', 'destroy']);
    Route::get('/invoice/print/{zone_id}/{curr_inv_prd}', [InvoiceController::class, 'print_invoice'])
        ->name('invoice.print_invoice');
    Route::get('/invoice/zone_create/{zone_id}/{curr_inv_prd}/{new_user?}', [InvoiceController::class, 'zone_create'])->name('invoice.zone_create');
    Route::get('/invoice/export_excel/{zone_id}/{curr_inv_prd}', [InvoiceController::class, 'export_excel'])->name('invoice.export_excel');
    Route::get('/invoice/{subzone_id}/zone_edit/{curr_inv_prd}', [InvoiceController::class, 'zone_edit'])->name('invoice.zone_edit');
    Route::match(['get', 'post'], '/invoice/zone_update/{subzone_id?}', [InvoiceController::class, 'zone_update'])->name('invoice.zone_update');
    Route::match(['get', 'post'], '/invoice/{subzone_id}/zone_update', [InvoiceController::class, 'zone_update']);
    Route::get('/invoice/{subzone_id}/invoiced_lists', [InvoiceController::class, 'invoiced_lists'])->name('invoice.invoiced_lists');
    Route::post('invoice/print_multi_invoice', [InvoiceController::class, 'print_multi_invoice'])->name('invoice.print_multi_invoice');
    Route::get('/owepaper/index', [OwePaperController::class, 'index'])->name('owepaper.index_direct');
    Route::post('/owepaper/print', [OwePaperController::class, 'print'])->name('owepaper.print_direct');

    Route::get('/cutmeter/print_install_meter/{cutmeter_id}', [CutmeterController::class, 'print_install_meter'])->name('cutmeter.print_install_meter');
    Route::get('/cutmeter/cutmeterProgress/{id}', [CutmeterController::class, 'cutmeterProgress'])->name('cutmeter.progress');
    Route::get('/cutmeter/installMeterProgress/{id}', [CutmeterController::class, 'installMeterProgress'])->name('cutmeter.installmeter');
    // create() ต้องการ $user_id (view index1 เรียก /cutmeter/create/{user_id})
    Route::get('/cutmeter/create/{user_id}', [CutmeterController::class, 'create'])->name('cutmeter.create');
    Route::resource('/cutmeter', CutmeterController::class)->except(['show', 'destroy', 'create']);
    // MeterTypeController ไม่มีอยู่แล้ว (ใช้ admin.metertype.*) คงชื่อ route เดิมไว้ให้ view ที่อ้างถึงไม่พัง
    Route::redirect('meter_types', '/admin/metertype')->name('meter_types.index');

    Route::prefix('usermeter_infos')->name('usermeter_infos.')->group(function () {
        Route::get('/edit_invoices/{meter_id}',  [UserMeterInfosController::class, 'edit_invoices'])->name('edit_invoices');
        Route::post('/store_edited_invoice',  [UserMeterInfosController::class, 'store_edited_invoice'])->name('store_edited_invoice');
    });
});

Route::prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/login', [SuperAdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [SuperAdminAuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [SuperAdminAuthController::class, 'logout'])->name('logout');
    Route::middleware(['auth', 'role:Admin|Super Admin'])->group(function () {
        Route::resource('/machines', MachineController::class);
        Route::resource('staff', StaffController::class);
    });
});

Route::middleware(['auth'])->group(function () {
    Route::get('superadmin/dashboard', function () {
        return view('superadmin.dashboard'); // หน้า Dashboard หลัง Login

    })->name('superadmin.dashboard');
});

Route::middleware(['auth'])->prefix('admin/ef')->group(function () {
    Route::get('/', [EmissionFactorController::class, 'index'])->name('ef.index');
    Route::post('/store', [EmissionFactorController::class, 'store'])->name('ef.store');
    Route::put('/{emissionFactor}', [EmissionFactorController::class, 'update'])->name('ef.update');
    Route::post('/import', [EmissionFactorController::class, 'import'])->name('ef.import');
    Route::delete('/{emissionFactor}', [EmissionFactorController::class, 'destroy'])->name('ef.destroy');
});

Route::middleware(['auth'])->name('admin.')->prefix('admin')->group(function () {
    Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
    Route::post('/suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
    Route::get('/suppliers/{id}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit'); // เพิ่มอันนี้
    Route::put('/suppliers/{id}', [SupplierController::class, 'update'])->name('suppliers.update'); // เพิ่มอันนี้
    Route::delete('/suppliers/{id}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');
});

Route::middleware(['auth'])->prefix('inventory')->name('inventory.')->group(function () {
    Route::get('/dashboard', [InvDashboardController::class, 'index'])->name('dashboard');

    // Route สำหรับจัดการพัสดุ (Items)
    Route::prefix('items')->name('items.')->group(function () {
        Route::get('/index', [InvItemController::class, 'index'])->name('index');
        Route::get('/iframe', [InvItemController::class, 'iframeIndex'])->name('iframe');
        Route::post('/iframe/session/end', [InvItemController::class, 'endIframeSession'])->name('iframe.session.end');
        Route::get('/create', [InvItemController::class, 'create'])->name('create');
        Route::get('/{id}/edit', [InvItemController::class, 'edit'])->name('edit');
        Route::put('/{id}', [InvItemController::class, 'update'])->name('update');
        // Route::get('/iframe', [InvItemController::class, 'iframeIndex'])->name('iframe');
        Route::post('/store', [InvItemController::class, 'store'])->name('store');
    });

    Route::prefix('stock')->name('stock.')->group(function () {
        Route::get('/receive/{id}', [InvStockController::class, 'receiveForm'])->name('receive');
        Route::get('/expiring', [InvStockController::class, 'expiringStock'])->name('expiring');
        Route::post('/receive', [InvStockController::class, 'storeReceive'])->name('store_receive');
    });

    Route::prefix('locations')->name('locations.')->group(function () {
        Route::get('/index', [InvLocationController::class, 'index'])->name('index');
        Route::post('/store', [InvLocationController::class, 'store'])->name('store');
        Route::delete('destroy/{loc_id}', [InvLocationController::class, 'destroy'])->name('destroy');
    });


    // กดยืนยันจ่ายของ (ตัดสต็อกข้ามล็อต FIFO)
    Route::post('/transactions/{refNo}/issue', [InvTransactionController::class, 'issueItems'])->name('transactions.issue');

    Route::prefix('/withdraw')->name('withdraw.')->group(function () {

        // หน้าจอเลือกพัสดุหลายรายการ (แนวทางตะกร้าสินค้า)
        Route::get('/multiple', [InvTransactionController::class, 'createMultipleWithdraw'])->name('create_multiple');
        Route::get('/withdraw_print/{refNo}', [InvTransactionController::class, 'showByRef'])->name('show_ref');
        // ฟังก์ชันบันทึกการเบิกหลายรายการ
        Route::post('/multiple/store', [InvTransactionController::class, 'storeMultipleWithdraw'])->name('store_multiple');
        // ปุ่มกดอนุมัติ
        Route::post('/dispense/{refNo}', [InvTransactionController::class, 'dispense'])->name('dispense');
        // หน้าฟอร์มให้เจ้าหน้าที่พัสดุตรวจสอบ/แก้ไขจำนวน/ยกเลิกรายการ ก่อนตัดสต็อก
        Route::get('/dispense-form/{refNo}', [InvTransactionController::class, 'dispenseForm'])->name('dispense_form');

        // บันทึกการจ่ายพัสดุจริงและตัดสต็อก
        Route::post('/dispense-process/{refNo}', [InvTransactionController::class, 'dispenseProcess'])->name('dispense_process');
        Route::get('/{item_id}', [InvTransactionController::class, 'withdrawForm'])->name('form');
        Route::post('/', [InvTransactionController::class, 'storeWithdraw'])->name('store');
        Route::get('/{id}/show', [InvTransactionController::class, 'show'])->name('show');
        Route::post('/{refNo}/approve', [InvTransactionController::class, 'approveStep'])->name('step.approve');
        Route::post('/{refNo}/reject', [InvTransactionController::class, 'rejectStep'])->name('step.reject');
        Route::post('/{id}/approve', [InvTransactionController::class, 'approve'])->name('approve');
    });

    // Route สำหรับ process การ import
    Route::post('import', [InvItemController::class, 'import'])->name('items.import');
    Route::get('download-template', [InvItemController::class, 'downloadTemplate'])->name('items.template');
    Route::resource('units', InvUnitController::class)->only(['index', 'store', 'destroy']);
    Route::resource('categories', InvCategoryController::class)->only(['index', 'store', 'destroy', 'edit', 'update']);
    Route::get('/history', [InvTransactionController::class, 'history'])->name('history');
    Route::resource('hazards', InvHazardLevelController::class)->only(['index', 'store', 'destroy']);
    // ดูใบเบิก

});

//////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////// .MEMBERS. ////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////

Route::middleware(['auth'])->prefix('tabwater/')->name('tabwater.')->group(function () {
    //////////// mebers /////////////////
    //////////// mebers /////////////////
    Route::prefix('members')->name('members.')->group(function () {
        Route::get('/', [UserMeterInfosController::class, 'index'])->name('index');
        Route::get('/add', [UserMeterInfosController::class, 'create'])->name('create');
        Route::post('/bulkstore', [UserMeterInfosController::class, 'bulkStore'])->name('bulkstore');
        Route::delete('/{id}', [UserMeterInfosController::class, 'destroy'])->name('destroy');
        Route::get('/{userId}/add-meter', [UserMeterInfosController::class, 'addMeter'])->name('add_meter');
        Route::post('/{userId}/store-meter', [UserMeterInfosController::class, 'storeAdditionalMeter'])->name('store_meter');  
        Route::get('/{id}/edit', [UserMeterInfosController::class, 'edit'])->name('edit');
        Route::put('/{id}', [UserMeterInfosController::class, 'update'])->name('update');
// ✅ แก้ไขให้เรียกผ่าน Controller (เช่น TabWaterMemberController)
Route::get('/export-excel', [UserMeterInfosController::class, 'exportExcel'])->name('export_excel');   
 });
    //////////// Productions /////////////////
    Route::prefix('production/')->name('production.')->group(function () {
        // หน้า Dashboard หลัก
        Route::get('/dashboard', [TabWaterProductionDashboardController::class, 'index'])->name('dashboard');
        // API สั่งเปิด-ปิดปั๊ม/อุปกรณ์ (Auto/Manual)
        Route::post('/devices/{deviceId}/toggle', [TabWaterProductionDashboardController::class, 'toggleDevice'])->name('device.toggle');
        // บันทึกรายงานคุณภาพน้ำยามเช้า
        Route::post('/quality-logs', [TabWaterProductionDashboardController::class, 'storeQualityLog'])->name('quality.store');
    });

        //////////// settings /////////////////
    Route::prefix('settings/')->name('settings.')->group(function () {
        // แผนที่ GIS รวม (สถานี/จุดท่อ/แนวท่อ)
        Route::prefix('gis')->name('gis.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Tabwater\TabwaterGisController::class, 'index'])->name('index');
            Route::get('/data', [\App\Http\Controllers\Tabwater\TabwaterGisController::class, 'data'])->name('data');
            Route::post('/station', [\App\Http\Controllers\Tabwater\TabwaterGisController::class, 'storeStation'])->name('station.store');
            Route::put('/station/{id}', [\App\Http\Controllers\Tabwater\TabwaterGisController::class, 'updateStation'])->name('station.update');
            Route::delete('/station/{id}', [\App\Http\Controllers\Tabwater\TabwaterGisController::class, 'destroyStation'])->name('station.destroy');
            Route::post('/node', [\App\Http\Controllers\Tabwater\TabwaterGisController::class, 'storeNode'])->name('node.store');
            Route::put('/node/{id}', [\App\Http\Controllers\Tabwater\TabwaterGisController::class, 'updateNode'])->name('node.update');
            Route::delete('/node/{id}', [\App\Http\Controllers\Tabwater\TabwaterGisController::class, 'destroyNode'])->name('node.destroy');
            Route::post('/pipe', [\App\Http\Controllers\Tabwater\TabwaterGisController::class, 'storePipe'])->name('pipe.store');
            Route::put('/pipe/{id}', [\App\Http\Controllers\Tabwater\TabwaterGisController::class, 'updatePipe'])->name('pipe.update');
            Route::delete('/pipe/{id}', [\App\Http\Controllers\Tabwater\TabwaterGisController::class, 'destroyPipe'])->name('pipe.destroy');
        });

        // CRUD สถานีผลิตน้ำ
        Route::get('/stations/gis-map', [TabwaterStationController::class, 'gisMap'])->name('stations.gis_map');
    Route::post('/stations/save-gis-data', [TabwaterStationController::class, 'saveGisData'])->name('stations.save_gis_data');

    // 2. CRUD สถานีผลิตน้ำ (ยกเว้น show หากไม่ได้ใช้งาน)
    Route::resource('stations', TabwaterStationController::class)->except(['show']);
        // CRUD อุปกรณ์/ปั๊มประจำสถานี
        Route::resource('devices', TabwaterDeviceController::class)->except(['show']);

        Route::resource('pipe-nodes', TabwaterPipeNodeController::class)->except(['show']);
        // Pipelines (แนวท่อประปา)
        Route::resource('pipelines', TabwaterPipelineController::class)->except(['show']);

    });

    Route::get('control/index', [TabwaterControlController::class, 'index']);


});







Route::middleware(['auth', 'role:Super Admin'])->name('admin.')->prefix('admin')->group(function () {
    Route::get('/select-organization', [OrgSelectorController::class, 'index'])->name('org_selector');
    Route::post('/set-org-context', [OrgSelectorController::class, 'setContext'])->name('set_org_context');
    Route::get('/reset-org-context', [OrgSelectorController::class, 'resetContext'])->name('reset_org_context');
    });


require __DIR__ . '/auth.php';
require __DIR__ . '/foodwaste_route.php';
require __DIR__ . '/keptkaya_route.php';
require __DIR__ . '/keptkaya_mobile_route.php';
require __DIR__ . '/admin.php';
require __DIR__ . '/annual_trash.php';
// require __DIR__ . '/tabwater.php';


// ==========================================
// ⚙️ Smart Utility GIS (ไฟฟ้า, ประปา, ถนน, อาคาร, รายงาน)
// ==========================================
Route::prefix('smart-utility')->name('smart_utility.')->group(function () {
    Route::get('/', [\App\Http\Controllers\SmartUtility\SmartUtilityController::class, 'index'])->name('index');
    Route::get('/elec', [\App\Http\Controllers\SmartUtility\SmartUtilityController::class, 'elecView'])->name('elec');
    Route::get('/water', [\App\Http\Controllers\SmartUtility\SmartUtilityController::class, 'waterView'])->name('water');
    Route::get('/road', [\App\Http\Controllers\SmartUtility\SmartUtilityController::class, 'roadView'])->name('road');
    Route::get('/bldg', [\App\Http\Controllers\SmartUtility\SmartUtilityController::class, 'bldgView'])->name('bldg');
    Route::get('/reports', [\App\Http\Controllers\SmartUtility\SmartUtilityController::class, 'reportsView'])->name('reports');
    Route::get('/citizen', [\App\Http\Controllers\SmartUtility\SmartUtilityController::class, 'citizen'])->name('citizen');

    // API endpoints
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('/init', [\App\Http\Controllers\SmartUtility\SmartUtilityController::class, 'initData'])->name('init');
        Route::post('/config', [\App\Http\Controllers\SmartUtility\SmartUtilityController::class, 'saveConfig'])->name('config');
        Route::post('/item', [\App\Http\Controllers\SmartUtility\SmartUtilityController::class, 'saveItem'])->name('item.save');
        Route::delete('/item/{id}', [\App\Http\Controllers\SmartUtility\SmartUtilityController::class, 'deleteItem'])->name('item.delete');
        Route::post('/shape', [\App\Http\Controllers\SmartUtility\SmartUtilityController::class, 'saveShape'])->name('shape.save');
        Route::delete('/shape/{id}', [\App\Http\Controllers\SmartUtility\SmartUtilityController::class, 'deleteShape'])->name('shape.delete');
        Route::post('/shapes/clear', [\App\Http\Controllers\SmartUtility\SmartUtilityController::class, 'clearShapes'])->name('shapes.clear');
        Route::post('/ticket', [\App\Http\Controllers\SmartUtility\SmartUtilityController::class, 'createTicket'])->name('ticket.create');
        Route::post('/ticket/{code}/staff-update', [\App\Http\Controllers\SmartUtility\SmartUtilityController::class, 'staffUpdateTicket'])->name('ticket.staff_update');
        Route::post('/ticket/{code}/citizen-reply', [\App\Http\Controllers\SmartUtility\SmartUtilityController::class, 'citizenReplyTicket'])->name('ticket.citizen_reply');
        Route::post('/sync-all', [\App\Http\Controllers\SmartUtility\SmartUtilityController::class, 'syncAll'])->name('sync_all');
        Route::post('/import-json', [\App\Http\Controllers\SmartUtility\SmartUtilityController::class, 'importFullJSON'])->name('import_json');
        Route::post('/clear-all', [\App\Http\Controllers\SmartUtility\SmartUtilityController::class, 'clearAll'])->name('clear_all');
    });
});

Route::get('/tabwater/smart-utility', function () {
    return redirect()->route('smart_utility.water');
})->name('tabwater.smart_utility.index');

Route::prefix('deploy')->group(function () {
    Route::get('/sync', function (\Illuminate\Http\Request $request) {
        $expected = env('DEPLOY_SECRET') ?: 'pios_sync_dev_2026';
        $token = $request->query('token') ?: $request->header('X-Deploy-Token');

        if (empty($token) || !hash_equals((string) $expected, (string) $token)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized token'
            ], 403);
        }

        if ($request->query('action') === 'log') {
            $logPath = storage_path('logs/laravel.log');
            $content = file_exists($logPath) ? file_get_contents($logPath) : 'No log file';
            return response(substr($content, -4000), 200, ['Content-Type' => 'text/plain']);
        }

        if ($request->query('action') === 'import_sql') {
            try {
                \Illuminate\Support\Facades\Artisan::call('pios:import-sql', [
                    '--force' => true,
                ]);
                $output = \Illuminate\Support\Facades\Artisan::output();
                return response()->json([
                    'success' => true,
                    'message' => 'SQL dump imported successfully',
                    'output' => trim($output),
                ]);
            } catch (\Throwable $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Import failed: ' . $e->getMessage(),
                ], 500);
            }
        }

        try {
            $details = [];

            // 1. Sync baseline migrations if missing
            if (\Illuminate\Support\Facades\Schema::hasTable('migrations')) {
                $existing = [
                    '2023_10_30_130210_create_user_merter_infos_table',
                    '2025_07_28_061902_create_staff_table',
                    '2025_09_03_084012_create_organizations_table',
                    '2025_10_12_065735_create_machines_table',
                    '2026_09_25_085045_add_audit_logs_to_inv_transactions_table',
                    '2026_09_25_090011_create_inv_transaction_approvals_table',
                    '2026_09_25_090554_create_approval_workflows_table',
                    '2026_09_25_090555_create_approval_workflow_steps_table',
                    '2026_09_25_103925_add_approvalworkflow_id_column_in_inv_categories_table',
                    '2026_09_25_164846_create_inv_locations_table',
                    '2026_09_25_164847_add_location_id_to_inv_item_details_table',
                    '2026_09_25_171122_add_conversion_rate_to_inv_item_details_table',
                    '2026_09_26_005147_create_suppliers_table',
                    '2026_10_08_110038_create_kp_purchase_routes_table_and_kp_purchase_route_zones_table',
                    '2026_10_09_001549_create_kp_settings_table',
                    '2026_10_09_042443_add_modulename_column_in_inv_transaction_approvals_table',
                    '2026_10_13_025520_create_welfare_members_table',
                    '2026_10_15_135128_create_tabwater_stations_table',
                    '2026_10_15_135129_create_tabwater_station_staffs_table',
                    '2026_10_15_135130_create_tabwater_devices_table',
                    '2026_10_15_135943_create_tabwater_device_logs_table',
                    '2026_10_15_160030_create_tabwater_pipe_nodes_table',
                    '2026_10_15_160058_create_tabwater_pipelines_table',
                ];
                foreach ($existing as $m) {
                    if (!\Illuminate\Support\Facades\DB::table('migrations')->where('migration', $m)->exists()) {
                        \Illuminate\Support\Facades\DB::table('migrations')->insert(['migration' => $m, 'batch' => 1]);
                    }
                }
            }

            \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
            $details['migrate'] = trim(\Illuminate\Support\Facades\Artisan::output());

            if (\Illuminate\Support\Facades\Schema::hasTable('roles')) {
                \Illuminate\Support\Facades\Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\RolesSeeder', '--force' => true]);
                $details['roles_seed'] = trim(\Illuminate\Support\Facades\Artisan::output());
            }

            if (\Illuminate\Support\Facades\Schema::hasTable('users')) {
                \Illuminate\Support\Facades\Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\AdminSeeder', '--force' => true]);
                $details['admin_seed'] = trim(\Illuminate\Support\Facades\Artisan::output());
            }

            \Illuminate\Support\Facades\Artisan::call('optimize:clear');
            $details['optimize_clear'] = trim(\Illuminate\Support\Facades\Artisan::output());

            \Illuminate\Support\Facades\Artisan::call('config:cache');
            $details['config_cache'] = trim(\Illuminate\Support\Facades\Artisan::output());

            \Illuminate\Support\Facades\Artisan::call('view:cache');
            $details['view_cache'] = trim(\Illuminate\Support\Facades\Artisan::output());

            return response()->json([
                'success' => true,
                'message' => 'Deployment synced successfully',
                'details' => $details,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    });
});
