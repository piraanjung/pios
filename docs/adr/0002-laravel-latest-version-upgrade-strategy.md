# ADR 0002: การอัปเกรดโครงสร้างพื้นฐานสู่ Laravel เวอร์ชันล่าสุด (Laravel 12 / PHP 8.3+) และ Vite

## Status
Accepted (วันที่ 2026-11-05)

## Context
โปรเจกต์ `pios` ปัจจุบันรันอยู่บน **Laravel 9.52** (`laravel/framework: ^9.2`) ซึ่งสิ้นสุดการสนับสนุนด้านความปลอดภัย (End of Life) ไปแล้ว และยังใช้เครื่องมือรุ่นเก่า ได้แก่:
1. **Laravel Mix 6 (`webpack.mix.js`)** และแพ็กเกจแปลกปลอมที่ไม่เกี่ยวข้องกับเว็บ Laravel เช่น `react-native-inappbrowser-reborn` ใน `package.json`
2. **Third-party Packages รุ่นเก่า:** `spatie/laravel-permission: ^5.5`, `laravel/sanctum: ^2.14.1`, `intervention/image: ^2.7`, `phpunit/phpunit: ^9.5.10`, `nunomaduro/collision: ^6.1`
3. **PHP 8.2+ / 8.3+ Incompatibilities:** พบบั๊กในโค้ดเดิมที่ไม่รองรับ Strict Behaviour ของ PHP 8.2+ เช่น Variable-variable syntax (`$$user->id`), Null property dereference (`$user->org->id`), และ Malformed `$casts = ['user_id']` ใน `FoodWasteUserPreference`

## Decision
1. อัปเกรดระบบเป็นลำดับขั้น (Incremental Stepwise Upgrade: **Laravel 9 -> Laravel 10 -> Laravel 11 -> Laravel 12**) บนรันไทม์ **PHP 8.3+ / 8.4** (ทั้งใน `Dockerfile` และสภาพแวดล้อมพัฒนา):
   - อัปเดต `composer.json` ให้ใช้ `laravel/framework: ^12.0`, `laravel/sanctum: ^4.0`, `spatie/laravel-permission: ^6.10`, `phpunit/phpunit: ^11.0`, `larastan/larastan: ^3.0`, `maatwebsite/excel: ^3.1`
2. ปรับปรุงสถาปัตยกรรม Bootstrap ตามมาตรฐาน **Laravel 11/12 Application Structure**:
   - ใช้ `bootstrap/app.php` (`Application::configure()`) เป็นศูนย์กลางการลงทะเบียน Routing (รวมถึงการ Auto-discover `routes.php` ของแต่ละ Vertical Slice), Middleware (`SetTenantConnection`, Spatie Permission middlewares), และ Exception Handling
   - ย้ายเมธอด `$casts` ใน Eloquent Models สู่เมธอด `protected function casts(): array`
3. แทนที่ **Laravel Mix (`webpack.mix.js`)** ด้วย **Vite (`vite.config.js` + `laravel-vite-plugin`)** และถอดแพ็กเกจ `react-native-inappbrowser-reborn` ออกจาก `package.json`

## Consequences
- **Positive:** ได้รับ Security Patches ล่าสุด, ประสิทธิภาพของ PHP 8.3+/8.4 และ Laravel 12 สูงขึ้น, Build pipeline ด้วย Vite เร็วขึ้นอย่างก้าวกระโดด, และโครงสร้าง `bootstrap/app.php` รองรับการโหลด Route ของ Vertical Slice ได้อย่างคลีน
- **Negative:** ต้องปรับแก้ Breaking Changes ของ Spatie Permission v6 (Namespaces ของ Middleware เปลี่ยนจาก `Spatie\Permission\Middlewares\*` เป็น `Spatie\Permission\Middleware\*` เอกพจน์), การตั้งค่า DB Schema methods และ PHPUnit 11 annotations
- **Constraints (สำหรับ AI Agent):**
  1. ห้ามใช้ `webpack.mix.js` หรือฟังก์ชัน `mix()` ใน Blade Views ใหม่ ให้ใช้ `@vite()` หรือ `asset()` สำหรับ Static Soft UI assets เสมอ
  2. ห้ามใช้ Namespace `Spatie\Permission\Middlewares\*` (พหูพจน์) เด็ดขาด ใน Spatie v6 ต้องใช้ `Spatie\Permission\Middleware\*` (เอกพจน์) เสมอ
  3. ห้ามเขียน `$casts` แบบ Array ที่ไม่มี Value type (เช่น `['user_id']`) และห้ามใช้ `DB::raw()` ซ้อนใน `Schema::hasColumn()` ตามข้อกำหนดของ Laravel 10–12
