# LIFF Member Module

## Purpose

The member LIFF is a LINE mini app for members to link their LINE account to an existing organization account, register when no account exists, and view member service information in one dashboard.

## Member Flow

1. `GET /lineliff` opens the member entry page in `resources/views/lineliff/index.blade.php`.
2. The page initializes LINE LIFF with `services.line_liff.member_id` and reads the member's LINE profile.
3. `POST /api/line/check-user` checks whether the LINE ID is already linked. If it is, the member chooses an organization when necessary.
4. If the LINE ID is not linked, the page verifies a phone number and, when needed, a national ID through `POST /api/line/verify-user`.
5. A new member submits the registration form to `POST /line/user_line_register`. The backend creates the master `users` record and provisions the supported member-service records.
6. The member dashboard is opened at `/line/dashboard/{pref_id}/{org_id}` and rendered by `LineLiffController::dashboard` using `resources/views/lineliff/dashboard.blade.php`.

## Dashboard Sections

- Recycle bank: account balance, points, and carbon reduction from purchase transaction details.
- Food-waste bank: contributed weight, points, compost batch, and recent activity.
- Annual trash: current fiscal-year subscription totals and outstanding amount for users with the `Annual Trash User` role.
- Health and other member tools: calorie summary, QR/scanner modals, issue reports, point history, and the water-service partial where enabled.

## Main Files

- `resources/views/lineliff/index.blade.php`: LIFF initialization, existing-account lookup, organization selection, phone/ID verification, and new-member registration form.
- `resources/views/lineliff/dashboard.blade.php`: member dashboard and service panels.
- `app/Http/Controllers/LineLiffController.php`: dashboard data, LINE registration, and member-facing actions.
- `app/Http/Controllers/Api/LineController.php`: LINE account lookup, identity-linking endpoints, organization selection helpers, and related API routes.
- `routes/web.php`: `/lineliff`, dashboard, and member registration routes.
- `routes/api.php`: LINE check/verify and organization lookup endpoints.

## LIFF Configuration

`config/services.php` reads `LINE_LIFF_MEMBER_ID` into `services.line_liff.member_id`. If the variable is missing or empty, the existing member LIFF ID is used as the default. Set the environment variable when deploying a different LIFF app. After changing it in a deployment that caches Laravel configuration, rebuild the configuration cache.

## Data Sources

The dashboard joins the authenticated master user to organization-scoped service records, including `kp_user_waste_preferences`, recycle-bank accounts and purchase transactions, food-waste accounts/logs, and current-year annual-trash subscriptions. Annual-trash membership itself is stored in `annual_trash_members` and is associated with bins/subscriptions through the member's `user_id`.
