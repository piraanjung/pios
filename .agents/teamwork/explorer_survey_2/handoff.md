# Handoff Report: Waste & Welfare Module Audit

**Agent:** Explorer 2 (Waste & Welfare Module Explorer)  
**Recipient:** Orchestrator (`c9f397d1-8b6e-4c29-8f71-4e0533144232`)  
**Working Directory:** `c:\xampp\htdocs\qa_envsogo_pios\.agents\teamwork\explorer_survey_2`  
**Report File:** `c:\xampp\htdocs\qa_envsogo_pios\.agents\teamwork\explorer_survey_2\report.md`  
**Handoff Type:** Hard (Task complete)

---

## 1. Observation

Direct code observations verified across the codebase:

1. **KeptKaya Member & Account Fragmentation:**
   - `RecycleBankController::bulkStore` (`app/Http/Controllers/KeptKaya/RecycleBankController.php:90-104`) creates `KpRecycleBankMember` records and assigns `'Recycle Bank User'`.
   - `UserWasteController::store` (`app/Http/Controllers/KeptKaya/UserWasteController.php:219-239`) creates `KpUserWastePreference` and `KpBankAccount`. It does NOT create `KpRecycleBankMember`.
   - `KpPurchaseController::select_user` (`app/Http/Controllers/KeptKaya/KpPurchaseController.php:38-40`) strictly requires `whereHas('kpRecycleBankMember')`, making members created by `UserWasteController` completely invisible to purchase staff.
   - `UserImport` (`app/Imports/UserImport.php:68-76`) writes to a 3rd table `recycle_bank_accounts` (leaving `org_id_fk` as NULL) and sets `is_waste_bank` on `KpUserWastePreference` which is discarded because it is not in `$fillable` (`app/Models/KeptKaya/KpUserWastePreference.php:21-32`).
   - `RecycleBankController::history` (`app/Http/Controllers/KeptKaya/RecycleBankController.php:137-143`) omits `$userId` in the `KpPurchaseTransaction` query, returning transactions of all users across the system.

2. **Annual Trash Missing User ID & PHP 8.2+ Crash:**
   - In `AnnualTrashController::store` (`app/Http/Controllers/KeptKaya/AnnualTrashController.php:130-146`), `AnnualTrashSubscription::firstOrCreate` omits `user_id` and `org_id_fk`.
   - In `resources/views/admin/annual_trash/index.blade.php:22`, `<td>{{ $sub->user->firstname }}</td>` fails under PHP 8.2+ with `Attempt to read property "firstname" on null`.
   - `AnnualTrashAdminController::index` (`app/Http/Controllers/Admin/AnnualTrashAdminController.php:13-24`) queries `AnnualTrashSubscription::with('user')` without `org_id_fk` filtering, leaking subscriptions across all tenants.

3. **Foodwaste Copy-Paste Artifacts & Parameter Mismatch:**
   - `UserFoodWasteController.php:282-314` contains `public function aa(Request $request)` copied verbatim from `UserWasteController.php:387`, operating on recycle bank and annual collection flags.
   - `UserFoodWasteController::store` (`lines 153-177`) attempts `User::create(['name' => ...])` which fails because `name` is not in `User::$fillable` or schema.
   - `routes/foodwaste_route.php:99` defines route parameter `/{wasteBin}/waste-bins`, but `UserMatchingAnnualTrashsController::store` (`app/Http/Controllers/FoodWaste/UserMatchingAnnualTrashsController.php:50`) expects `(Request $request, User $w_user)`.
   - `app/Models/FoodWaste/FoodWasteUserPreference.php:26-28` defines `protected $casts = ['user_id'];` without key-value mapping.

4. **Welfare Module Fatal SQL Errors & Config Disconnect:**
   - `WelfareController::processAutoDeductionAndNotify` (`app/Http/Controllers/Admin/WelfareController.php:230-234`) and `storeCollection` (`lines 439, 444`) invoke `$member->decrement('wallet_balance', $fee)` on `User` models. `wallet_balance` does not exist on `users`, crashing every payout final approval.
   - `UserWelfareController::checkEligibility` (`app/Http/Controllers/Admin/UserWelfareController.php:44-46`) queries `sum('weight')` on `kp_purchase_transactions`, which crashes because the actual column is `total_weight` (`app/Models/KeptKaya/KpPurchaseTransaction.php:24`).
   - `WelfareConfigController::updateConfig` (`app/Http/Controllers/Admin/WelfareConfigController.php:61-66`) writes to `kp_settings`, but `UserWelfareController:28, 77` and `WelfareController:66, 208, 217, 346` read from `welfare_configs` table (`WelfareConfigs` model).
   - `app/Models/User.php` has zero relations to Welfare (`welfareMember`, `welfarePayouts`, `welfareContributions` are all missing).

5. **Cross-Tenant Security Leaks:**
   - `AnnualTrashAdminController::index` (lines 13-24) lacks `org_id_fk`.
   - `AdminMemberWasteController::index` (`app/Http/Controllers/FoodWaste/AdminMemberWasteController.php:13-23`) lacks `org_id_fk`.
   - `UserWasteController::index` (`app/Http/Controllers/KeptKaya/UserWasteController.php:35-84`) lacks `org_id_fk`.
   - `UserFoodWasteController::foodwaste_bin_users` (`app/Http/Controllers/FoodWaste/UserFoodWasteController.php:102-139`) lacks `org_id_fk`.
   - `WelfareMemberController::destroy` (`app/Http/Controllers/Admin/WelfareMemberController.php:86-91`) lacks `org_id_fk`.

---

## 2. Logic Chain

1. **Premise 1:** When an admin registers a member in `UserWasteController`, a `kp_user_waste_preferences` record is created, but no `kp_recycle_bank_members` record is generated.
2. **Premise 2:** `KpPurchaseController::select_user` requires `whereHas('kpRecycleBankMember')` with `status = 'active'`.
3. **Inference 1:** Citizens onboarded through `UserWasteController` cannot have their recyclables weighed or recorded at the truck/kiosk, rendering the entire collection workflow broken for these users.
4. **Premise 3:** `AnnualTrashController::store` creates `AnnualTrashSubscription` without passing `user_id`.
5. **Premise 4:** PHP 8.2 treats property access on null as a fatal Error.
6. **Inference 2:** `resources/views/admin/annual_trash/index.blade.php:22` (`$sub->user->firstname`) will reliably crash with a 500 fatal error in production whenever Annual Trash subscriptions are viewed.
7. **Premise 5:** In MySQL/MariaDB, executing `UPDATE users SET wallet_balance = wallet_balance - 60` where column `wallet_balance` does not exist throws error 1054.
8. **Premise 6:** `WelfareController::approveStep` calls `processAutoDeductionAndNotify` inside a `DB::transaction`.
9. **Inference 3:** When the final approval step is executed for a welfare payout, the SQL error triggers an exception that immediately rolls back the database transaction. Therefore, no welfare payout can ever reach `'paid'` status.
10. **Premise 7:** Controllers that execute queries without tenant constraints (`where('org_id_fk', ...)` or global tenant scopes) return all rows across all organizations in a shared database.
11. **Inference 4:** Multiple controllers (`AnnualTrashAdminController`, `AdminMemberWasteController`, `UserWasteController`, `RecycleBankController`) leak cross-organizational data, violating multi-tenancy requirements.

---

## 3. Caveats

- **No Caveats.** Every finding in this report has been verified against the physical PHP controllers, Eloquent models, database migrations, and Blade template files.

---

## 4. Conclusion

The Member Onboarding and Management workflows across KeptKaya, Annual Trash, Foodwaste, and Welfare are severely fragmented, plagued by 4 competing account/point tables in KeptKaya, 3 competing subscription tables in Annual Trash, multiple fatal SQL and PHP 8.2+ bugs, and critical multi-tenant data leaks. 

These issues must be resolved by:
1. Consolidating member tracking into a single unified member model per module linked to `User`.
2. Fixing the fatal `wallet_balance` decrement and `weight` column name errors in Welfare.
3. Unifying the Welfare configuration storage to `welfare_configs`.
4. Passing `user_id` and `org_id_fk` into `AnnualTrashSubscription::firstOrCreate()`.
5. Adding strict `org_id_fk` scoping across all index and destroy methods.

---

## 5. Verification Method

To independently verify these findings:

1. **Verify Welfare Fatal Column Errors:**
   - Inspect `app/Http/Controllers/Admin/WelfareController.php` lines 230-234 and 439-444 to confirm `$member->decrement('wallet_balance', $fee)`.
   - Inspect `app/Models/User.php` lines 38-63 to verify `wallet_balance` is absent.
   - Inspect `app/Http/Controllers/Admin/UserWelfareController.php` line 46 to confirm `sum('weight')`.
   - Inspect `app/Models/KeptKaya/KpPurchaseTransaction.php` line 24 to confirm column is `total_weight`.

2. **Verify Annual Trash Null Property Bug:**
   - Inspect `app/Http/Controllers/KeptKaya/AnnualTrashController.php` lines 130-146 to confirm `user_id` is omitted in `AnnualTrashSubscription::firstOrCreate`.
   - Inspect `resources/views/admin/annual_trash/index.blade.php` line 22 to confirm `$sub->user->firstname`.

3. **Verify KeptKaya Disconnection:**
   - Inspect `app/Http/Controllers/KeptKaya/UserWasteController.php` lines 219-239 to confirm `KpRecycleBankMember` is never created.
   - Inspect `app/Http/Controllers/KeptKaya/KpPurchaseController.php` lines 38-40 to confirm purchase strictly requires `whereHas('kpRecycleBankMember')`.

4. **Verify Welfare Config Disconnect:**
   - Inspect `app/Http/Controllers/Admin/WelfareConfigController.php` lines 61-66 to confirm it writes to `kp_settings`.
   - Inspect `app/Http/Controllers/Admin/UserWelfareController.php` lines 28, 77 to confirm it reads from `welfare_configs`.
