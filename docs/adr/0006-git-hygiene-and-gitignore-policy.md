# ADR 0006: การจัดระเบียบ Git Repository, `.gitignore` และการล้างไฟล์ความลับ/ไฟล์ขยะออกจาก Git Index

## Status
Accepted (วันที่ 2026-11-05)

## Context
จากการตรวจสอบสถานะ Git Repository (`git ls-files`) ของโปรเจกต์ `pios` พบความผิดปกติร้ายแรง:
1. **Tracked Dependency Directories (34,846 ไฟล์):** โฟลเดอร์ `node_modules/` (24,362 ไฟล์) และ `vendor/` (10,484 ไฟล์) ถูก Track อยู่ใน Git Index ทั้งที่มีรายชื่ออยู่ใน `.gitignore` (เกิดจากการถูก `git add -f` หรือ Commit ก่อนมี `.gitignore`) ทำให้ Repository มีขนาดใหญ่เกินจริงและเกิด Conflict ทุกครั้งที่อัปเดตแพ็กเกจ
2. **Tracked Secrets & Credentials:** ไฟล์ `.env` และ `.env.zip` ถูก Commit เข้าสู่ Git Repository ทำให้ข้อมูลรหัสผ่านฐานข้อมูล, `APP_KEY`, และ LINE Token รั่วไหลในประวัติ Git
3. **Tracked OS Junk, AI Dumps & Duplicate Backup Files:**
   - ไฟล์ `.DS_Store` กระจายอยู่ใน Git ถึง 48 ไฟล์
   - ไฟล์รวมโค้ด `repomix-output.xml` (2.25 MB), `_ide_helper.php` (936 KB), `my_file.txt`, `.ftpquota`, `index.html` ใน Root
   - โฟลเดอร์มีเครื่องหมายคำพูดแปลกปลอม `"public/` ใน Git Index
   - ไฟล์สำรองที่ตั้งชื่อ `* copy*.php`, `* copy*.js`, โฟลเดอร์ `resources/views/kiosk copy/`, และ `app/Http/Controllers/_Backup_Unused/` รวมกว่า 30 รายการ

## Decision
1. **อัปเดตไฟล์ `.gitignore` ให้ครอบคลุมตามมาตรฐาน Laravel 12 + macOS + IDE + Security:**
   - เพิกเฉยต่อ `.env`, `.env.*` (ยกเว้น `!.env.example`), `*.zip`, `*.sql`, `*.bak`
   - เพิกเฉยต่อ `.DS_Store`, `**/.DS_Store`, `Thumbs.db`, `.ftpquota`
   - เพิกเฉยต่อ `_ide_helper.php`, `_ide_helper_models.php`, `.phpstorm.meta.php`, `.phpunit.cache`
   - เพิกเฉยต่อ `repomix-output.*`, `my_file.txt`, `/public/build`, `/public/hot`, `/node_modules`, `/vendor`
   - เพิกเฉยต่อไฟล์สำรองชั่วคราว `* copy.*`, `* copy *.*`
2. **ล้างไฟล์ที่ถูก Track ผิดพลาดออกจาก Git Index (`git rm -r --cached`):**
   - ถอด `node_modules/`, `vendor/`, `.env`, `.env.zip`, `.DS_Store`, `repomix-output.xml`, `_ide_helper.php`, `my_file.txt`, `.ftpquota`, `"public/` ออกจาก Git Index โดยไม่ลบไฟล์ `.env`, `vendor/`, `node_modules/` ในเครื่องนักพัฒนา
3. **ลบไฟล์ขยะและไฟล์สำรองที่ซ้ำซ้อน (`* copy*.php`, `_Backup_Unused`) ออกจากโครงสร้างโค้ด** เพื่อไม่ให้ Larastan, IDE หรือ Blade Compiler สับสน

## Consequences
- **Positive:** ลดจำนวนไฟล์ที่ Git ต้อง Track จาก ~38,500 ไฟล์ เหลือเพียง ~1,800 ไฟล์ซอร์สโค้ดจริงทันที, ป้องกันความลับ `.env` รั่วไหลใน Commit ต่อไป, และทำให้การทำงานของ Git Branching / CI/CD รวดเร็วและสะอาด
- **Negative:** Commit ที่ทำ Git Index Cleanup จะมีรายการลบไฟล์ (`D`) จำนวนมาก (เฉพาะใน Git Index) จึงต้องแยกเป็น Commit เฉพาะ (`chore(git): untrack vendor, node_modules, secrets, and junk files`) ไม่ปนกับการแก้โค้ดธุรกิจ
- **Constraints (สำหรับ AI Agent):**
  1. ห้าม Commit ไฟล์ `.env`, `.env.zip`, `vendor/`, `node_modules/`, `.DS_Store`, `repomix-output.xml` หรือไฟล์ที่มีคำว่า `copy` เข้าสู่ Git เด็ดขาด
  2. ห้ามสร้างไฟล์สำรองด้วยการก๊อปปี้เป็นชื่อ `filename copy.php` หรือ `ControllerBackup.php` ในโปรเจกต์ ให้พึ่งพา Git Version Control เสมอ
