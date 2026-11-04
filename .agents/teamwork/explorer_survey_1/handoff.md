# Handoff Report: Explorer 1 (Core User & Access Explorer)

## 1. Observation

Direct, verbatim code observations across the codebase (`c:\xampp\htdocs\qa_envsogo_pios`):

1. **Admin User Management (`UserController.php`):**
   - Path: `app/Http/Controllers/Admin/UserController.php:548`:
     ```php
     TwMeterInfos::create([
         'meter_id'             => $$user->id,
         'user_id'              => $user->id,
         'org_id_fk'            => $orgId,
     ```
     Verbatim variable-variable `$$user->id` creates a dynamic variable with the user's ID string, evaluating to `null` and failing meter attachment.
   - Path: `app/Http/Controllers/Admin/UserController.php:752`:
     ```php
     'organization_id' => $request->organization_id,
     ```
     In `app/Models/User.php:38-63`, `$fillable` contains `"org_id_fk"`, not `"organization_id"`. This causes `org_id_fk` to be omitted and left `NULL` on public registrations.
   - Path: `app/Http/Controllers/Admin/UserController.php:240-243`:
     ```php
     DB::statement('SET FOREIGN_KEY_CHECKS=0;');
     KpUserWastePreference::truncate();
     KpBankAccount::truncate();
     DB::statement('SET FOREIGN_KEY_CHECKS=1;');
     ```
     Hardcoded truncate statements lingering in `_index()`.
   - Path: `app/Http/Controllers/Admin/UserController.php:53-56`:
     ```php
     ->whereHas('kpUserPreference', function ($q) {
         $q->where('org_id_fk', Auth::user()->org_id_fk);
     })
     ```
     Excludes users who do not have a `kpUserPreference` record in the current admin's org (e.g. Tabwater-only users).

2. **Tabwater Customer Onboarding & Meter Management:**
   - Path: `app/Http/Controllers/Tabwater/UserMeterInfosController.php:388-414` (`create`), `417-470` (`bulkStore`):
     Water meter onboarding does not allow creating new users; it only accepts an array of existing `user_ids` from `users` table and creates `TwMeterInfos` records, assigning role `'Tabwater User'`.
   - Path: `routes/web.php:392`:
     ```php
     Route::post('/store_edited_invoice', [UserMeterInfosController::class, 'store_edited_invoice'])->name('store_edited_invoice');
     ```
     Method `store_edited_invoice` does not exist anywhere in `UserMeterInfosController` or any other controller in `app/`.
   - Three duplicate models for meter infos: `TwMeterInfos` (`tw_meter_infos`), `TwUsersInfos` (`tw_meter_infos`), and `TwUsersInfo` (`tw_users_infos`).

3. **LINE LIFF Onboarding:**
   - Path: `app/Http/Controllers/LineLiffController.php:284`:
     ```php
     $account_no = substr('0000', strlen($user->org->id)) . $user->org->id . substr('0000', strlen($user->id)) . $user->id;
     ```
     `User` model only has relation `organization()` (`app/Models/User.php:142-145`), not `org`. Accessing `$user->org->id` throws PHP 8.2 Fatal Error: `Attempt to read property "id" on null`, crashing line registration.
   - Path: `app/Http/Controllers/LineLiffController.php:40-49`:
     ```php
     public function dashboard(Request $request, $pref_id, $org_id)
     {
         $pref = KpUserWastePreference::findOrFail($pref_id);
         $request->session()->put('pref_id_mobile', $pref_id);
         $userId = $pref->user_id;
         $user = User::findOrFail($userId);
         Auth::login($user);
     ```
     Unauthenticated public endpoint performs `Auth::login($user)` with zero token or signature validation.
   - Path: `app/Http/Controllers/LineLiffController.php:196`:
     ```php
     $_user = User::with('wastePreference')->where('phone', $request->phoneNum)->get()->first();
     ```
     `User` model lacks `wastePreference` relationship (only has `kpUserPreference`), throwing `RelationNotFoundException`.
   - Path: `app/Services/UserWasteStatusService.php:25`:
     ```php
     $preference = $user->wastePreference()->firstOrCreate(['user_id' => $user->id]);
     ```
     Throws `BadMethodCallException: Call to undefined method App\Models\User::wastePreference()`.

4. **Kiosk Self-Registration:**
   - Path: `app/Http/Controllers/Api/KioskController.php:151`:
     ```php
     $userId      = 10; //$request->input('userId');
     ```
     Hardcoded `$userId = 10;` assigns every kiosk transaction to User ID 10.
   - Path: `app/Http/Controllers/Kiosk/KioskController.php:261`:
     ```php
     'kp_user_w_pref_id_fk' => 13, // หรือ ID ของ Profile ที่ผูกกับ User
     ```
     Hardcoded preference ID 13.
   - Path: `resources/views/kiosk/kiosk_login.blade.php:116`:
     ```javascript
     const userId = 'USER_' + phoneNumber;
     ```
     No smart card reader integration exists. Identification is phone number or QR code webcam scanning only.
   - Path: `app/Http/Controllers/Kiosk/KioskApiController.php:73-86` (`checkMember`):
     Returns 404 if phone is not found. No self-registration mechanism exists at the kiosk.

5. **Core Schema & Duplicate Handling:**
   - Path: `database/migrations/2014_10_12_000000_create_users_table.php:25-26`:
     `id_card` and `phone` columns are NOT unique in the database schema.
   - Path: `app/Imports/UserImport.php:55, 68-76`:
     Writes to `recycle_bank_accounts` table instead of `kp_bank_accounts` and specifies `'is_waste_bank' => 1` which is not in `KpUserWastePreference::$fillable`.
   - Three conflicting account models for recycle bank:
     - `App\Models\KeptKaya\KPAccounts` (`kp_accounts`)
     - `App\Models\KpBankAccount` (`kp_bank_accounts`)
     - `App\Models\RecycleBankAccount` (`recycle_bank_accounts`)

---

## 2. Logic Chain

1. **Premise:** User creation and membership onboarding must be reliable, secure, and maintain consistent data across all modules.
2. **Analysis of Observation 1.1 & 1.3:** `UserController::attachMeter` fails at runtime due to `$$user->id`, and `LineLiffController::user_line_register` fails due to `$user->org->id`. Both are PHP 8.2 fatal errors that prevent user/meter registration in their respective entry points.
3. **Analysis of Observation 1.2:** In `UserController::register`, because `organization_id` is not in `$fillable`, new users are created with `org_id_fk = NULL`. Because `UserController::index` filters users by `kpUserPreference.org_id_fk`, and `UserMeterInfosController::create` filters by `users.org_id_fk`, these newly registered users cannot be viewed or attached to services.
4. **Analysis of Observation 3.2:** `LineLiffController::dashboard` accepts `pref_id` directly from the URL and logs the user in via `Auth::login($user)`. Any attacker can enumerate `pref_id` from 1 upwards to gain full administrative or member session access without authentication.
5. **Analysis of Observation 3.3 & 3.4:** `UserWasteStatusService` and `LineLiffController` attempt to call `$user->wastePreference()`, but `User.php` does not define this method. Any workflow triggering `updateServices` or `update_user_by_phone` fails with a fatal exception.
6. **Analysis of Observation 4.1 & 4.2:** Kiosk transaction controllers hardcode `$userId = 10` and `pref_id = 13`. This means transactions are never credited to the citizen who authenticated at the kiosk. Furthermore, without a self-registration flow on the kiosk, unregistered citizens cannot onboard.
7. **Analysis of Observation 5.2 & 5.3:** Because three different tables (`kp_accounts`, `kp_bank_accounts`, `recycle_bank_accounts`) store recycle bank balances across different controllers, a user registered via `UserImport` has an account in `recycle_bank_accounts`, a user registered via `UserService` has an account in `kp_bank_accounts`, and a user registered via `Api\LineController` has an account in `kp_accounts`. Their balances and points are completely fragmented.

---

## 3. Caveats

1. Hardware peripherals (physical ESP8266, ESP32-CAM, physical Kiosk servos and cameras) were investigated purely through controller endpoints, Blade views, and JavaScript code. No physical hardware or IoT broker was connected during this audit.
2. The `envsogo_main` database connection referenced in `User::booted()` is intended for multi-tenant central ID allocation. Central DB state was not inspected directly as this is a local environment audit.
3. No application source code, configuration files, or database tables were modified during this investigation.

---

## 4. Conclusion

The User Registration and Membership Onboarding system in `qa_envsogo_pios` suffers from severe fragmentation and multiple blocking bugs:
1. **Broken Workflows:** Admin water meter attachment, LINE citizen registration, single-user KeptKaya creation, and batch status synchronization are currently inoperable due to fatal PHP 8.2 and method/property mismatch bugs (`$$user->id`, `$user->org->id`, `$user->wastePreference()`).
2. **Critical Security Vulnerabilities:** LINE LIFF dashboard contains an IDOR vulnerability enabling arbitrary account takeover without authentication. LINE account binding allows phone/ID overwriting without OTP verification.
3. **Data Integrity Hazards:** Three conflicting account tables exist for Recycle Bank. Database schemas lack uniqueness constraints on citizen IDs and phone numbers, and public registration drops organization IDs.
4. **Kiosk Mock State:** Kiosk transactions are hardcoded to test IDs (`10` and `13`), and no Smart Card integration or citizen self-registration exists at the kiosk.

A unified onboarding service (`UserService::createMasterUser`), standardized account schema (`kp_bank_accounts`), and token-verified LINE/Kiosk workflows are required to make the onboarding process coherent and reliable.

---

## 5. Verification Method

To independently verify these findings on the codebase:

1. **Verify PHP 8.2 Fatal Errors via PHP CLI:**
   - Inspect `app/Http/Controllers/Admin/UserController.php` lines 545–550 for `$$user->id`.
   - Inspect `app/Http/Controllers/LineLiffController.php` lines 280–288 for `$user->org->id`.
   - Inspect `app/Http/Controllers/KeptKaya/UserWasteController.php` lines 255–262 for `$user->wastePreference()`.
   - Inspect `app/Services/UserWasteStatusService.php` lines 24–26 for `$user->wastePreference()`.

2. **Verify Broken Routes:**
   - Check `routes/web.php:392` against `app/Http/Controllers/Tabwater/UserMeterInfosController.php` to confirm `store_edited_invoice` is absent.

3. **Verify Security IDOR:**
   - Inspect `app/Http/Controllers/LineLiffController.php:40-49` to confirm `Auth::login($user)` is called without middleware or token validation.

4. **Verify Clean Workspace:**
   - Check workspace files to confirm only files under `.agents/teamwork/explorer_survey_1/` have been created or modified.
