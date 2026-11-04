# Deep Investigation Report: Waste & Welfare Module Onboarding, Architecture, and Inconsistencies

**Date:** 2026-10-25  
**Auditor:** Explorer 2 (Waste & Welfare Module Explorer)  
**Target Application:** `qa_envsogo_pios` (Laravel 10 / PHP 8.2+)  
**Scope:** KeptKaya (Recycle Bank), Annual Trash, Foodwaste, Welfare Modules

---

## 1. Executive Summary

This investigation conducted an exhaustive, read-only audit of member registration, account binding, point/fee accounting, and user-to-member onboarding across four core modules:
1. **KeptKaya (Recycle Bank)**
2. **Annual Trash (ขยะรายปี)**
3. **Foodwaste (ขยะเปียก / อาหาร)**
4. **Welfare Module (ฌาปนกิจ / กองทุนสวัสดิการ)**

### Core Architectural Discoveries:
1. **Extreme Data Model Fragmentation:** Instead of a unified Member/Account abstraction linked to the central `User` entity, each module (and even different controllers within the same module) implements separate, uncoordinated member and preference tables. For example, KeptKaya has **4 separate tables** attempting to track recycle bank balances and points (`kp_recycle_bank_members`, `kp_bank_accounts`, `recycle_bank_accounts`, `kp_accounts`).
2. **Disconnected Workflows:** An admin onboarding a user via `UserWasteController` does not create a `kp_recycle_bank_members` record; subsequently, `KpPurchaseController::select_user` cannot locate this user to buy recyclables. Similarly, adding a user in `AnnualTrashMemberController` creates a member record without any trash bins or subscriptions, while adding a bin in `AnnualTrashController` creates a subscription with a `NULL` `user_id` and `NULL` `org_id_fk`.
3. **Fatal Production Bugs (PHP 8.2+ & SQL):**
   - **Welfare Auto-deduction Crash:** `WelfareController::processAutoDeductionAndNotify` executes `$member->decrement('wallet_balance', $fee)` on `User` models. The `wallet_balance` column does not exist in `users`, causing a fatal SQL exception that aborts all payout approvals and rolls back database transactions.
   - **Welfare Eligibility Check Crash:** `UserWelfareController::checkEligibility` queries `sum('weight')` on `kp_purchase_transactions`, where the actual column is `total_weight`, crashing with a `Column not found` SQL error.
   - **Welfare Config Storage Disconnect:** `WelfareConfigController` writes configuration to the key-value table `kp_settings`, while `UserWelfareController` and `WelfareController` read from `welfare_configs` / `kp_welfare_configs`. Configuration updates made by admins have zero effect on the operational controllers.
   - **PHP 8.2+ Null Property Crash in Annual Trash Admin:** In `resources/views/admin/annual_trash/index.blade.php`, `$sub->user->firstname` throws a fatal error under PHP 8.2+ because `user_id` was left `NULL` during subscription creation in `AnnualTrashController`.
   - **Invalid Eloquent Casts:** `FoodWasteUserPreference` specifies `protected $casts = ['user_id'];` without a key-value type pair.
4. **Severe Multi-Tenant Isolation Breaches:** Multiple endpoints in `AnnualTrashAdminController`, `AdminMemberWasteController`, `UserWasteController`, and `RecycleBankController` omit `org_id_fk` filtering entirely, exposing citizens and transactions across different municipalities.

---

## 2. Module 1: KeptKaya (Recycle Bank) Deep Dive

### 2.1 Route Mapping & Entry Points
Defined primarily in `routes/keptkaya_route.php` and `routes/keptkaya_mobile_route.php`:
- `GET /keptkayas/recycle-bank/members` -> `RecycleBankController@index` (name: `keptkayas.recycle-bank.members.index`)
- `GET /keptkayas/recycle-bank/members/create` -> `RecycleBankController@create` (name: `keptkayas.recycle-bank.members.create`)
- `POST /keptkayas/recycle-bank/members/bulk-store` -> `RecycleBankController@bulkStore` (name: `keptkayas.recycle-bank.members.bulkStore`)
- `GET /keptkayas/recycle-bank/{userId}/history` -> `RecycleBankController@history` (name: `keptkayas.recycle-bank.history`)
- `RESOURCE /keptkayas/users` -> `UserWasteController` (name: `keptkayas.users.*`)
- `GET /keptkayas/waste_bin_users` -> `UserWasteController@waste_bin_users` (name: `keptkayas.waste_bin_users`)
- `POST /keptkayas/waste-service-preferences` -> `UserWasteController@updateWasteServicePreferences` (name: `keptkayas.updateWasteServicePreferences`)
- `GET /keptkayas/purchase/select_user` -> `KpPurchaseController@select_user` (name: `keptkayas.purchase.select_user`)
- `POST /keptkayas/purchase/save-transaction` -> `KpPurchaseController@saveTransaction` (name: `keptkayas.purchase.save_transaction`)

### 2.2 Member Onboarding & Registration Workflows
There are three completely contradictory pathways to onboard a KeptKaya member:

#### Pathway A: `RecycleBankController::bulkStore` (`app/Http/Controllers/KeptKaya/RecycleBankController.php:70-116`)
- **Mechanism:** Admin views list of users in their organization who do not have a `kp_recycle_bank_members` record (`create()` lines 44-48). Admin selects users and submits.
- **Data Created:**
  - `KpRecycleBankMember` record in `kp_recycle_bank_members`:
    - `org_id_fk`: `getAuthOrgId()`
    - `user_id`: `$userId`
    - `member_number`: `$maxMemberNo + 1` (formatted by accessor as `RB00001`)
    - `zone_id`: `$request->zone_id[$userId] ?? $user->zone_id`
    - `total_points`: 0
    - `balance`: 0
    - `status`: `'active'`
    - `recorder_id`: `Auth::id()`
  - Role: Assigns Spatie role `'Recycle Bank User'`.
- **Missing Integration:** Does NOT create `KpBankAccount`, does NOT create `recycle_bank_accounts`, does NOT create `KpUserWastePreference`.

#### Pathway B: `UserWasteController::store` (`app/Http/Controllers/KeptKaya/UserWasteController.php:198-269`)
- **Mechanism:** Has two sub-modes:
  - **Batch Select Mode (`$request->mode == 'batch_select'`, lines 213-240):**
    - Takes `selected_user_ids`.
    - Creates `KpUserWastePreference` (`kp_user_waste_preferences` table) with `is_recycle_bank = 1`.
    - Creates `KpBankAccount` (`kp_bank_accounts` table) with `account_no = 'RC-' . $org->org_code . '-' . $orgIdStr . $userIdStr`.
    - Assigns Spatie role `'Recycle Bank User'`.
    - **CRITICAL GAP:** Does NOT create `KpRecycleBankMember`!
  - **Manual New User Mode (lines 241-263):**
    - Validates `firstname`, `lastname`, `email`, `password`. (Ignores `username`, `prefix`, `id_card` from form!).
    - Creates new record in `users`.
    - Assigns Spatie role `'User'`.
    - Creates `user->wastePreference()` with `is_recycle_bank = false`, `is_annual_collection = false`.
    - Does NOT create any bank account or member record.

#### Pathway C: Excel Import (`app/Imports/UserImport.php:13-85`)
- Invoked from `Admin\UserController::importUsers` (`app/Http/Controllers/Admin/UserController.php:814`).
- Creates `User` model.
- Creates `KpUserWastePreference` with `'is_waste_bank' => 1`. (**Bug:** `is_waste_bank` is not in `$fillable` of `KpUserWastePreference`, so it is discarded).
- Creates `RecycleBankAccount` (`recycle_bank_accounts` table, NOT `kp_bank_accounts` and NOT `kp_recycle_bank_members`).
- Leaves `org_id_fk` as `NULL` on `RecycleBankAccount`.
- Assigns role `'User'`, but NOT `'Recycle Bank User'`.

### 2.3 The 4 Competing Bank/Account Schemas
The codebase has 4 competing models and tables representing a user's trash bank account and points:

| Model | Table | Primary Key | Foreign Key | Balance Column | Points Column | Usage In Codebase |
|---|---|---|---|---|---|---|
| `KpRecycleBankMember` | `kp_recycle_bank_members` | `id` | `user_id` -> `users.id` | `balance` | `total_points` | `RecycleBankController`, `KpPurchaseController` (lines 38, 228) |
| `KpBankAccount` | `kp_bank_accounts` | `id` | `user_pref_id` / `user_id` | `balance` | `points` | `UserWasteController:228`, `User:267` |
| `RecycleBankAccount` | `recycle_bank_accounts` | `id` | `user_id` -> `users.id` | `balance` | `points` | `UserImport:68`, `RecycleBankController:138` |
| `KPAccounts` | `kp_accounts` | `u_wpref_id_fk` | `u_wpref_id_fk` -> `kp_user_waste_preferences.id` | `balance` | `points` | Static helper methods in `KPAccounts:47` |

### 2.4 Critical Bugs & Logic Flaws in KeptKaya
1. **Disconnected Member Records Breaking Waste Purchase:**
   - When a user is onboarded via `UserWasteController::store` (batch mode), they get `kp_user_waste_preferences` and `kp_bank_accounts`.
   - When the waste truck staff opens `KpPurchaseController::select_user` (`app/Http/Controllers/KeptKaya/KpPurchaseController.php:38-40`), the query filters:
     ```php
     ->whereHas('kpRecycleBankMember', function ($q) use ($orgId) { 
         $q->where('org_id_fk', $orgId)->where('status', 'active'); 
     })
     ```
   - **Result:** The user created via `UserWasteController` CANNOT BE FOUND by the staff. The transaction cannot take place.
2. **Massive Data Leak in Purchase History:**
   - In `RecycleBankController::history($request, $userId)` (`app/Http/Controllers/KeptKaya/RecycleBankController.php:137-143`):
     ```php
     $transactions = KpPurchaseTransaction::with(['details.item', 'details.unit', 'recorder'])
         ->whereHas('user.recycleBankAccount', function ($q) {
             $q->where('status', 'active');
         })
         ->whereBetween('transaction_date', [$startDate, $endDate])
         ->orderBy('transaction_date', 'desc')
         ->get();
     ```
   - **Bug:** The query NEVER filters by `$userId`! When viewing any user's history, the system retrieves purchase transactions for ALL users in the system who have an active `recycleBankAccount`.
3. **Cross-Tenant Leak in `UserWasteController::index`:**
   - In `app/Http/Controllers/KeptKaya/UserWasteController.php:35-84`, the `index` method runs `User::query()->with(...)` without filtering by `org_id_fk`. Users from all tenant organizations are visible.
4. **Undefined Route Crash in `UserWasteController`:**
   - In `app/Http/Controllers/KeptKaya/UserWasteController.php:460`:
     ```php
     return redirect()->route('users.enroll.show')->with(...);
     ```
   - The route `users.enroll.show` is not defined anywhere in the application routes, resulting in a runtime `RouteNotFoundException`.
5. **Form Data Truncation in Manual Member Create:**
   - `resources/views/keptkayas/w/users/create.blade.php:43-100` provides inputs for `username`, `prefix`, `id_card`.
   - `UserWasteController::store` (`lines 205-210, 244-253`) validates only `firstname`, `lastname`, `email`, `password`. All citizen identification data is discarded.

---

## 3. Module 2: Annual Trash Module Deep Dive

### 3.1 Route Mapping & Entry Points
Defined in `routes/keptkaya_route.php` and `routes/annual_trash.php`:
- `RESOURCE /annual_trash` -> `Admin\AnnualTrashAdminController` (only `index` allowed in `routes/annual_trash.php:7`)
- `RESOURCE /keptkayas/annual-members` -> `KeptKaya\AnnualTrashMemberController` (`routes/keptkaya_route.php:212`)
- `GET /keptkayas/{w_user}/waste-bins` -> `AnnualTrashController@index` (`routes/keptkaya_route.php:200`)
- `GET /keptkayas/{w_user}/waste-bins/create` -> `AnnualTrashController@create` (`routes/keptkaya_route.php:201`)
- `POST /keptkayas/{w_user}/waste-bins` -> `AnnualTrashController@store` (`routes/keptkaya_route.php:202`)
- `GET /keptkayas/annual-payments` -> `AnnualTrashSubscriptionController@index` (`routes/keptkaya_route.php:214`)
- `GET /keptkayas/annual-payments/invoice` -> `AnnualTrashSubscriptionController@invoice` (`routes/keptkaya_route.php:215`)
- `POST /keptkayas/annual-payments/{wasteBinSubscription}/payments` -> `AnnualTrashSubscriptionController@storePayment` (`routes/keptkaya_route.php:219`)
- `POST /keptkayas/annual-payments/print-selected-invoices` -> `AnnualTrashSubscriptionController@printSelectedInvoices` (`routes/keptkaya_route.php:220`)

### 3.2 Household, Bin & Fee Account Setup Flow

The Annual Trash module has a completely fractured multi-tier lifecycle:

```
[Master User]
      │
      ├─► Step 1 (Optional): AnnualTrashMemberController@store
      │         └─► Creates `AnnualTrashMember` (member_number = AT00001, user_group_id_fk)
      │         └─► Assigns Role: 'Annual Trash User'
      │         (Does NOT create Bin or Subscription!)
      │
      ├─► Step 2: AnnualTrashController@store
      │         └─► Creates `AnnualTrash` (`kp_waste_bins` table)
      │         └─► Calculates fiscal year & pro-rated monthly fee
      │         └─► Creates `AnnualTrashSubscription` (`annual_trash_subscriptions`)
      │             (CRITICAL BUG: Leaves `user_id` = NULL and `org_id_fk` = NULL!)
      │
      └─► Step 3: AnnualTrashSubscriptionController@storePayment
                └─► Creates `AnnualTrashPayment` (`annual_trash_payments`)
                └─► Updates `AnnualTrashSubscription` status (paid / partially_paid)
```

### 3.3 The 3 Competing Table Schemas for Subscriptions & Payments

| Model | Table | Foreign Keys | Fee Columns | Payment Model | Payment Table |
|---|---|---|---|---|---|
| `AnnualTrashSubscription` (`app/Models/AnnualTrash/AnnualTrashSubscription.php`) | `annual_trash_subscriptions` | `waste_bin_id`, `payrate_permonth_id_fk`, `user_id`, `org_id_fk` | `annual_fee`, `month_fee`, `total_paid_amt` | `AnnualTrashPayment` | `annual_trash_payments` |
| `WasteBinSubscription` (`app/Models/KeptKaya/WasteBinSubscription.php`) | `kp_waste_bin_subscriptions` | `waste_bin_id`, `payrate_permonth_id_fk` | `annual_fee`, `month_fee`, `total_paid_amt` | `WasteBinPayment` | `waste_bin_payments` |
| Migration Definition (`2025_10_07_005925_create_waste_bin_subscriptions_table.php`) | `waste_bin_subscriptions` | `waste_bin_id` -> `waste_bins.id` | `annual_fee`, `month_fee`, `total_paid_amt` | `WasteBinPayment` | `waste_bin_payments` |

### 3.4 Excel / CSV Import Status
- **Finding:** There is **NO Excel/CSV import** for Annual Trash members, households, or waste bins anywhere in the application.
- All registrations must be manually selected one-by-one or via checkboxes in `AnnualTrashMemberController::create`.

### 3.5 Critical Bugs & Security Holes in Annual Trash
1. **PHP 8.2+ Fatal Null Property Access in Admin View:**
   - File: `resources/views/admin/annual_trash/index.blade.php:22`:
     ```html
     <td>{{ $sub->user->firstname }} {{ $sub->user->lastname }}</td>
     ```
   - In `AnnualTrashController::store` (`app/Http/Controllers/KeptKaya/AnnualTrashController.php:130-146`), `AnnualTrashSubscription::firstOrCreate` sets `waste_bin_id`, `fiscal_year`, `payrate_permonth_id_fk`, `annual_fee`, `month_fee`, `total_paid_amt`, but **omits `user_id` and `org_id_fk`**.
   - Consequently, `$sub->user` evaluates to `null`. In PHP 8.2+, accessing `$sub->user->firstname` triggers:
     `Fatal Error: Attempt to read property "firstname" on null`.
   - Line 33 also references `$sub->current_debt`, an attribute that does not exist on `AnnualTrashSubscription`.
   - Line 25 references `$sub->billing_status`, but the column is `status`.
2. **Total Multi-Tenant Isolation Breach in `AnnualTrashAdminController`:**
   - In `app/Http/Controllers/Admin/AnnualTrashAdminController.php:13-24`:
     ```php
     $query = AnnualTrashSubscription::with('user');
     if ($request->status) {
         $query->where('billing_status', $request->status);
     }
     $subscriptions = $query->paginate(20);
     ```
   - No `org_id_fk` check exists. Admins from Municipality A can view every subscription in the application across all municipalities.
   - In `updateStatus` (`lines 27-38`), `AnnualTrashSubscription::findOrFail($id)` modifies any subscription without validating organizational ownership.
   - Furthermore, `updateStatus` has no route registered in `routes/annual_trash.php` (`Route::resource('annual_trash', ...)->except(['create', 'store', 'show', 'edit', 'update', 'destroy'])`), making it an unreachable dead method.
3. **Ghost Subscriptions in Invoice Screen:**
   - In `AnnualTrashSubscriptionController::invoice` (`app/Http/Controllers/KeptKaya/AnnualTrashSubscriptionController.php:112-115`):
     ```php
     $invoicesByUser = $invoices->groupBy(function ($item) {
         return $item->AnnualTrash->user_id ?? 0;
     });
     ```
   - If an `AnnualTrash` bin does not have a matching user, invoices collapse under key `0`, rendering orphaned or corrupted invoice groups in the view.

---

## 4. Module 3: Foodwaste Module Deep Dive

### 4.1 Route Mapping & Entry Points
Defined in `routes/foodwaste_route.php`:
- `GET /foodwaste/users/foodwaste_bin_users` -> `UserFoodWasteController@foodwaste_bin_users` (`routes/foodwaste_route.php:94`)
- `POST /foodwaste/users/waste-service-preferences` -> `UserFoodWasteController@updateWasteServicePreferences` (`routes/foodwaste_route.php:96`)
- `RESOURCE /foodwaste/users` -> `UserFoodWasteController` (`routes/foodwaste_route.php:97`)
- `PREFIX /foodwaste/{wasteBin}/waste-bins`:
  - `GET /` -> `UserMatchingAnnualTrashsController@index` (`routes/foodwaste_route.php:100`)
  - `GET /create` -> `UserMatchingAnnualTrashsController@create` (`routes/foodwaste_route.php:101`)
  - `POST /` -> `UserMatchingAnnualTrashsController@store` (`routes/foodwaste_route.php:102`)
  - `GET /edit` -> `UserMatchingAnnualTrashsController@edit` (`routes/foodwaste_route.php:103`)
- `PUT /foodwaste/waste-bins/{waste_bin}` -> `UserMatchingAnnualTrashsController@update` (`routes/foodwaste_route.php:105`)
- `GET /foodwaste/admin/members-waste` -> `AdminMemberWasteController@index` (`routes/foodwaste_route.php:50`)
- `GET /foodwaste/admin/members-waste/{user_id}/batches` -> `AdminMemberWasteController@showBatches` (`routes/foodwaste_route.php:53`)

### 4.2 Participant Onboarding & Organic Waste Bin Assignment Flow
There is no dedicated member registration flow for Foodwaste. Instead, it relies on two haphazard entry points:
1. **User Preference Toggle:** An admin visits `/foodwaste/users/foodwaste_bin_users` and submits `updateWasteServicePreferences`, which writes to `FoodWasteUserPreference` (`foodwaste_user_preferences` table).
2. **Bin & IoT Box Assignment:** An admin navigates to `/foodwaste/{wasteBin}/waste-bins/create` and submits `UserMatchingAnnualTrashsController::store`.
   - Links a stock bin (`FoodAnnualTrashStocks`) with status `'active'`.
   - Links an IoT box (`FoodwastIotbox`) with status `'active'`.
   - Creates a record in `food_annual_trashs` (`FoodAnnualTrash` model) linked to `$w_user->id`.

### 4.3 Copy-Paste Defects & Architectural Flaws in Foodwaste
1. **Literal Copy-Paste from KeptKaya:**
   - In `app/Http/Controllers/FoodWaste/UserFoodWasteController.php:282-314`:
     Contains a method literally named `public function aa(Request $request)` identical to line 387 of `KeptKaya\UserWasteController.php`. It manipulates `is_annual_collection` and `is_recycle_bank` on `$user->wastePreference()`, having nothing to do with food waste!
   - In `app/Http/Controllers/FoodWaste/UserFoodWasteController.php:262-280` (`showEligibleForWasteServices`):
     Joins `user_waste_preferences` on `is_annual_collection` and `is_recycle_bank`. Food waste is never referenced.
   - In `app/Http/Controllers/FoodWaste/UserFoodWasteController.php:153-177` (`store`):
     Validates `'name'`, `'email'`, `'password'`. `User::create(['name' => ...])` fails because `name` is not a column in `users`. Then redirects to `users.index`, escaping the Foodwaste module completely.
2. **Route Parameter & Model Binding Mismatch:**
   - In `routes/foodwaste_route.php:99-104`:
     ```php
     Route::prefix('/{wasteBin}/waste-bins')->name('waste_bins.')->group(function () {
         Route::post('/', [UserMatchingAnnualTrashsController::class, 'store'])->name('store');
     ```
   - In `app/Http/Controllers/FoodWaste/UserMatchingAnnualTrashsController.php:50`:
     ```php
     public function store(Request $request, User $w_user)
     ```
   - The route parameter is named `{wasteBin}`, but the controller method accepts `User $w_user`. Laravel implicit route model binding cannot resolve `$w_user` from `{wasteBin}`, resulting in an uninitialized or wrong model instance.
3. **Invalid Eloquent Cast Syntax:**
   - In `app/Models/FoodWaste/FoodWasteUserPreference.php:26-28`:
     ```php
     protected $casts = [
         'user_id',
     ];
     ```
   - In Laravel / PHP 8.2+, `$casts` MUST be an associative array (`'user_id' => 'integer'`). An indexed value causes undefined offset/key runtime errors.
4. **Dead Code in `UserMatchingAnnualTrashsController::edit`:**
   - In `app/Http/Controllers/FoodWaste/UserMatchingAnnualTrashsController.php:96-100`:
     ```php
     public function edit(FoodAnnualTrash $bin)
     {
         return $bin;
         return view('foodwaste.w.waste_bins.edit', compact('AnnualTrash'));
     }
     ```
   - Returns raw JSON model output immediately; view rendering is unreachable.
5. **Cross-Tenant Data Leak in `AdminMemberWasteController`:**
   - In `app/Http/Controllers/FoodWaste/AdminMemberWasteController.php:13-23` (`index`):
     ```php
     $members = User::whereHas('foodwastePreference')
         ->withSum('wasteLogs', 'weight_kg')
         ...
         ->paginate(20);
     ```
   - Lacks any `org_id_fk` filtering. Admins see food waste logs and member statistics across all municipalities.

---

## 5. Module 4: Welfare Module Deep Dive

### 5.1 Route Mapping & Entry Points
Defined in `routes/admin.php`:
- `GET /admin/welfare/dashboard` -> `WelfareController@getWelfareSummary` (`routes/admin.php:56`)
- `GET /admin/welfare/collections` -> `WelfareController@collectionIndex` (`routes/admin.php:58`)
- `POST /admin/welfare/collections/{id}/store` -> `WelfareController@storeCollection` (`routes/admin.php:59`)
- `GET /admin/welfare/config` -> `WelfareConfigController@editConfig` (`routes/admin.php:61`)
- `MATCH ['POST','PUT'] /admin/welfare/config/update` -> `WelfareConfigController@updateConfig` (`routes/admin.php:62`)
- `POST /admin/welfare/payout` -> `WelfareController@storePayout` (`routes/admin.php:65`)
- `POST /admin/welfare/payout/{id}/approve` -> `WelfareController@approveStep` (`routes/admin.php:66`)
- `GET /admin/welfare/check-eligibility/{userId}` -> `WelfareController@checkEligibility` (`routes/admin.php:69`)
- `RESOURCE /admin/welfare/members` -> `WelfareMemberController` (`routes/admin.php:71-76`)
- `PREFIX /admin/welfare/member` -> `UserWelfareController` (`routes/admin.php:78-83`)
  - `eligibility` -> `checkEligibility`
  - `request` -> `create`
  - `store` -> `store`
  - `tracking` -> `tracking`

### 5.2 Beneficiary Registration, Eligibility & Approval Flow

```
[Master User in `users`]
        │
        ├─► 1. Admin Bulk Onboarding: `WelfareMemberController::bulkStore`
        │         └─► Creates `WelfareMember` (`welfare_members` table)
        │         └─► Sets `membership_type` ('both' or 'contribution_only'), `status` = 'active'
        │         (Does NOT assign any Spatie role!)
        │
        ├─► 2. Eligibility Check: `UserWelfareController::checkEligibility`
        │         └─► Checks `monthsActive >= minMonths`
        │         └─► Checks `KpPurchaseTransaction::sum('weight') >= minWeight`
        │             (CRITICAL BUG: column `weight` does NOT exist; should be `total_weight`!)
        │
        ├─► 3. Payout Claim Submission: `UserWelfareController::store`
        │         └─► Creates `WelfarePayout` (`welfare_payouts` table)
        │         └─► Sets `current_step_id` from `ApprovalWorkflowStep`
        │         └─► Creates `WelfarePayoutLog` ('submitted')
        │
        ├─► 4. Workflow Step Approvals: `WelfareController::approveStep`
        │         └─► Multi-step approval chain (`currentStep->step_order`)
        │
        └─► 5. Final Approval & Auto-Deduction: `WelfareController::processAutoDeductionAndNotify`
                  └─► Loops through all active `WelfareMember`s
                  └─► Calls `$member->decrement('wallet_balance', $fee)`
                      (FATAL CRASH: `wallet_balance` column does NOT exist in `users`!)
```

### 5.3 Critical Bugs & Architectural Breaches in Welfare

#### Bug 1: Fatal SQL Crash on Final Approval (`wallet_balance` Column Not Found)
- **Location:** `app/Http/Controllers/Admin/WelfareController.php:230-234`:
  ```php
  $userWallet = $member->wallet_balance ?? 0;
  if ($userWallet >= $fee) {
      $member->decrement('wallet_balance', $fee);
  ```
  And lines 439 & 444:
  ```php
  $user->increment('wallet_balance', $amount);
  $user->decrement('wallet_balance', $amount);
  ```
- **Evidence:**
  - Table `users` schema has NO `wallet_balance` column (defined in `app/Models/User.php:38-63`).
  - Search across all migrations for `wallet_balance` returned zero results.
- **Impact:**
  - Whenever a Welfare Payout reaches final approval, `approveStep` invokes `processAutoDeductionAndNotify`.
  - The SQL execution fails with `SQLSTATE[42S22]: Column not found: 1054 Unknown column 'wallet_balance' in 'field list'`.
  - Since this runs inside `DB::beginTransaction()`, the transaction rolls back.
  - **No welfare payout can ever be marked `'paid'` or finalized!**
  - Furthermore, waste bank earnings are stored in `kp_recycle_bank_members.balance` or `kp_bank_accounts.balance`, never on `users`.

#### Bug 2: Fatal SQL Crash on Eligibility Check (`weight` Column Not Found)
- **Location:** `app/Http/Controllers/Admin/UserWelfareController.php:44-46`:
  ```php
  $totalWeight = KpPurchaseTransaction::where('user_id', $user->id)
      ->where('status', 'completed')
      ->sum('weight');
  ```
- **Evidence:**
  - In `app/Models/KeptKaya/KpPurchaseTransaction.php:24`, the column is named `total_weight`.
  - In `WelfareController.php:351`, the same logic correctly uses `sum('total_weight')`.
- **Impact:**
  - When any member visits `admin/welfare/member/eligibility`, the system crashes immediately with `Unknown column 'weight' in 'field list'`.

#### Bug 3: Complete Disconnect in Welfare Configuration Storage
- **Location:**
  - `WelfareConfigController::editConfig` and `updateConfig` (`app/Http/Controllers/Admin/WelfareConfigController.php:19-33, 44-66`):
    Stores settings into the generic key-value table `kp_settings` (`KpSetting::updateOrCreate(...)`).
  - `UserWelfareController::checkEligibility` (`UserWelfareController.php:28, 77`):
    Reads from `WelfareConfigs::where('org_id_fk', $orgId)->first()` (`welfare_configs` table).
  - `WelfareController` (`WelfareController.php:66, 208, 217, 346`):
    Reads from `WelfareConfigs::where('org_id_fk', $orgId)->first()`.
  - In addition, there is an unused model `KpWelfareConfigs` pointing to `kp_welfare_configs`.
- **Impact:**
  - Any configuration an admin saves in the Welfare Config UI (minimum months, minimum weight, payout amount, per-case fee) is written to `kp_settings` and **completely ignored** by `UserWelfareController` and `WelfareController`.

#### Bug 4: Zero Welfare Relationship Defined on `User` Model
- `app/Models/User.php` defines relationships for Tabwater (`usermeterinfos`), Recycle Bank (`kpRecycleBankMember`, `recycleBankAccount`), Annual Trash (`annualTrashMember`, `annualTrashSubscription`), Foodwaste (`foodwastePreference`, `foodAnnualTrashs`).
- **Finding:** `User.php` has **zero relations** defined for the Welfare module (no `welfareMember()`, `welfarePayouts()`, or `welfareContributions()`). Eager loading `$user->welfareMember` fails.

#### Bug 5: Cross-Tenant Security Hole in `WelfareMemberController::destroy`
- **Location:** `app/Http/Controllers/Admin/WelfareMemberController.php:86-91`:
  ```php
  public function destroy($id)
  {
      $member = WelfareMember::findOrFail($id);
      $member->delete();
  ```
- **Impact:**
  - Does not check `org_id_fk`. An admin from Municipality A can delete a welfare member from Municipality B simply by passing their ID.

---

## 6. Cross-Module Inconsistencies & Duplication Matrix

### 6.1 Member Entity & Role Assignment Inconsistency

| Module | Member Record Table | Model Class | Spatie Role Assigned | Auto-Run ID Format | Syncs with `User`? |
|---|---|---|---|---|---|
| **Core Admin** | `users` | `User` | `'User'` | Integer `id` | Central entity |
| **Tabwater** | `tw_meter_infos` | `TwMeterInfos` | `'Tabwater User'` | Meter Code | Foreign key `user_id` |
| **KeptKaya (Recycle Bank)** | `kp_recycle_bank_members`<br>`kp_bank_accounts`<br>`recycle_bank_accounts`<br>`kp_user_waste_preferences` | `KpRecycleBankMember`<br>`KpBankAccount`<br>`RecycleBankAccount`<br>`KpUserWastePreference` | `'Recycle Bank User'` | `RB00001` (Accessor)<br>`RC-ORG-USER` (String) | Fragmented across 4 tables |
| **Annual Trash** | `annual_trash_members`<br>`kp_waste_bins`<br>`annual_trash_subscriptions` | `AnnualTrashMember`<br>`AnnualTrash`<br>`AnnualTrashSubscription` | `'Annual Trash User'` | `AT00001` (Accessor)<br>Auto Bin Code | Broken (`user_id` is NULL on subscription) |
| **Foodwaste** | `foodwaste_user_preferences`<br>`food_annual_trashs`<br>`foodwaste_accounts` | `FoodWasteUserPreference`<br>`FoodAnnualTrash`<br>`FoodWasteAccount` | **NONE** (No role assigned) | Bin Code | Partial foreign key |
| **Welfare** | `welfare_members` | `WelfareMember` | **NONE** (No role assigned) | None | Foreign key `user_id` (No inverse relation on `User`) |

### 6.2 Address & Geographic Zone Duplication
Citizen address and zoning information is duplicated across 4 distinct tables:
1. `users`: `address`, `zone_id`, `subzone_id`, `tambon_code`, `district_code`, `province_code`.
2. `kp_user_waste_preferences`: Duplicates all 6 fields (`address`, `zone_id`, `subzone_id`, `tambon_code`, `district_code`, `province_code`).
3. `annual_trash_members`: Duplicates `zone_id` and `subzone_id`.
4. `kp_recycle_bank_members`: Duplicates `zone_id`.

**Consequence:** When a user updates their address or zone via profile or admin user management, none of the module tables are updated, causing billing, collection route planning (`KpPurchaseRouteZone`), and reporting to diverge.

### 6.3 Multi-Tenant Isolation Breach Matrix

| File & Method | Line Numbers | Vulnerability | Impact |
|---|---|---|---|
| `AnnualTrashAdminController@index` | `app/Http/Controllers/Admin/AnnualTrashAdminController.php:13-24` | Missing `org_id_fk` scope on `AnnualTrashSubscription` | Displays subscriptions from all municipalities |
| `AnnualTrashAdminController@updateStatus` | `app/Http/Controllers/Admin/AnnualTrashAdminController.php:27-38` | Missing `org_id_fk` validation on find | Admin can modify status of another municipality's subscription |
| `AdminMemberWasteController@index` | `app/Http/Controllers/FoodWaste/AdminMemberWasteController.php:13-23` | Missing `org_id_fk` scope on `User::whereHas('foodwastePreference')` | Displays food waste participants across all municipalities |
| `UserWasteController@index` | `app/Http/Controllers/KeptKaya/UserWasteController.php:35-84` | Missing `org_id_fk` scope on `User::query()` | Exposes all users in system across tenants |
| `UserFoodWasteController@foodwaste_bin_users` | `app/Http/Controllers/FoodWaste/UserFoodWasteController.php:102-139` | Missing `org_id_fk` scope on `User::with(...)` | Displays user list across all municipalities |
| `RecycleBankController@history` | `app/Http/Controllers/KeptKaya/RecycleBankController.php:137-143` | Missing `$userId` and `org_id_fk` filters on transactions | Leaks all transactions across entire database |
| `WelfareMemberController@destroy` | `app/Http/Controllers/Admin/WelfareMemberController.php:86-91` | Missing `org_id_fk` check before deleting `WelfareMember` | Admin can delete member belonging to another organization |

### 6.4 Missing Database Transactions Matrix

| File & Method | Lines | Operations Performed Without Transaction | Failure Risk |
|---|---|---|---|
| `WelfareMemberController@bulkStore` | `app/Http/Controllers/Admin/WelfareMemberController.php:52-82` | Loops over `$request->user_ids` inserting `WelfareMember` records without `DB::beginTransaction()` | Partial failure leaves inconsistent member batch |
| `WelfareController@storePayout` | `app/Http/Controllers/Admin/WelfareController.php:55-117` | Saves `WelfarePayout` and inserts `WelfarePayoutLog` without transaction | If log insertion fails, orphaned payout remains in DB |
| `UserMatchingAnnualTrashsController@store` | `app/Http/Controllers/FoodWaste/UserMatchingAnnualTrashsController.php:50-88` | Creates `foodAnnualTrashs`, updates `FoodAnnualTrashStocks`, updates `FoodwastIotbox` without transaction | Mid-way failure causes desynchronized stock and bin status |
| `UserWasteController@store` (`batch_select`) | `app/Http/Controllers/KeptKaya/UserWasteController.php:213-240` | Loops over users creating preferences, bank accounts, assigning roles without transaction | Partial failure leaves orphaned accounts and missing roles |

---

## 7. Concrete To-Be Recommendations

### 7.1 Unified Member & Module Subscription Architecture
1. **Consolidate Account Schemas:**
   - Deprecate `recycle_bank_accounts`, `kp_accounts`, and `kp_bank_accounts`. Standardize on a single `kp_recycle_bank_members` table for Recycle Bank, linking directly to `users.id` and `org_id_fk`.
   - Add a virtual or actual `wallet_balance` ledger table (or read from `kp_recycle_bank_members.balance`) rather than assuming a nonexistent `users.wallet_balance` column.
2. **Synchronize Onboarding Pipelines:**
   - When an existing `User` is enabled for Recycle Bank, Annual Trash, Foodwaste, or Welfare, use a unified Service (`UserModuleOnboardingService`) that atomically:
     - Creates the module member/subscription record.
     - Sets default configurations (e.g., auto-run member number).
     - Assigns the standardized Spatie Role (`'Recycle Bank User'`, `'Annual Trash User'`, `'Food Waste User'`, `'Welfare User'`).
3. **Fix Critical SQL & PHP 8.2+ Bugs:**
   - **Welfare Auto-Deduction:** In `WelfareController::processAutoDeductionAndNotify`, replace `$member->decrement('wallet_balance', $fee)` with decrementing `kp_recycle_bank_members.balance` (or checking an explicit wallet model), and add safety checks.
   - **Welfare Eligibility:** In `UserWelfareController::checkEligibility`, change `sum('weight')` to `sum('total_weight')`.
   - **Welfare Config:** Refactor `WelfareConfigController` to read/write `welfare_configs` table (`WelfareConfigs` model) so it synchronizes with `UserWelfareController` and `WelfareController`.
   - **Annual Trash Subscription User ID:** In `AnnualTrashController::store`, pass `'user_id' => $w_user->id` and `'org_id_fk' => $w_user->org_id_fk` into `AnnualTrashSubscription::firstOrCreate()`.
   - **Foodwaste Casts & Routes:** Fix `FoodWasteUserPreference::$casts` to `['user_id' => 'integer']`, remove dead code in `UserMatchingAnnualTrashsController::edit`, and correct route parameter binding (`{user}` instead of `{wasteBin}`).
4. **Enforce Tenant Isolation via Global Scopes or Middleware:**
   - Ensure every controller method queries through `where('org_id_fk', getAuthOrgId())` or utilizes an organizational trait/scope (`BelongsToOrganization`).
5. **Excel/CSV Import Standardization:**
   - Build dedicated, transactional Excel importers for Annual Trash and Welfare members with validation against existing `id_card` or `phone` to attach existing citizens rather than creating broken duplicate records.
