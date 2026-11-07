# แผนการตรวจสอบ `.gitignore` และการทำความสะอาด Git Repository (Git Hygiene Audit)

**โปรเจกต์:** `qa_envsogo_pios`  
**ADR ที่เกี่ยวข้อง:** [ADR-0006](../adr/0006-git-hygiene-and-gitignore-policy.md)

---

## 1. ผลการตรวจสอบสถานะไฟล์ที่ถูก Track ผิดพลาดใน Git Index ปัจจุบัน

จากการรันคำสั่ง `git ls-files` บนกิ่ง `main` พบว่ามีไฟล์ที่ไม่ควรอยู่ในระบบ Version Control ถูก Commit ค้างไว้ใน Git Index รวมทั้งสิ้นกว่า **34,950+ ไฟล์** ดังนี้:

| หมวดหมู่ไฟล์ที่ถูก Track ผิดพลาด | รายการไฟล์ / โฟลเดอร์ใน Git Index | จำนวนไฟล์ | ระดับความเสี่ยงและผลกระทบ |
| :--- | :--- | :---: | :--- |
| **1. ข้อมูลความลับและรหัสผ่าน (Secrets)** | `.env`, `.env.zip` | 2 ไฟล์ | **วิกฤต (Critical):** รหัสผ่าน DB, `APP_KEY`, LINE Channel Secret รั่วไหลใน Git |
| **2. โฟลเดอร์ Dependencies** | `node_modules/`, `vendor/` | 34,846 ไฟล์ | **สูง (High):** ทำให้ Repo หนักหลายร้อย MB และเกิด Git Conflict ตอนอัปเกรด Laravel 12 |
| **3. ไฟล์ขยะของระบบปฏิบัติการ macOS** | `.DS_Store`, `app/**/.DS_Store`, `public/**/.DS_Store`, `resources/**/.DS_Store`, `vendor/**/.DS_Store` | 48 ไฟล์ | **ปานกลาง (Medium):** สร้าง Merge conflict ข้ามเครื่องและรก `git status` |
| **4. ไฟล์ Dump จาก AI / IDE Helper / FTP** | `repomix-output.xml` (2.25 MB), `_ide_helper.php` (936 KB), `.ftpquota`, `my_file.txt`, `index.html` (Root) | 5 ไฟล์ | **ปานกลาง (Medium):** ไฟล์สร้างอัตโนมัติขนาดใหญ่และไฟล์ทดสอบชั่วคราว |
| **5. พาธที่มีอักขระแปลกปลอม** | `"public/imgs/machines.png"`, `"public/keptkaya/js/kp_Step.js"` | 2 ไฟล์ | **สูง (High):** ชื่อโฟลเดอร์ขึ้นต้นด้วยเครื่องหมาย `"` ทำให้ระบบไฟล์บน Windows/Linux พัง |
| **6. ไฟล์และโฟลเดอร์สำรอง (`* copy*`, `_Backup_Unused`)** | `app/Http/Controllers/_Backup_Unused/*`, `resources/views/kiosk copy/*`, `resources/views/**/* copy*.php`, `public/js/accessmenu copy2.js`, `resources/views/staff_accessmenu.blade1.php`, `app/Http/Controllers/TestController.php` | 32 ไฟล์ | **สูง (High):** โค้ดตาย (Dead code) ที่ทำให้ Larastan / IDE ค้นหาคลาสและ View ผิดพลาด |

---

## 2. โครงสร้างไฟล์ `.gitignore` มาตรฐานใหม่ (Laravel 12 + Soft UI PRO + Security)

ไฟล์ `.gitignore` จะถูกปรับปรุงให้ครอบคลุมทุกสภาพแวดล้อมดังนี้:

```gitignore
# =========================================================================
# 1. Laravel Core & Build Artifacts (Laravel 12 + Vite + Mix Legacy)
# =========================================================================
/node_modules
/vendor
/public/build
/public/hot
/public/storage
/public/mix-manifest.json
/storage/*.key
/storage/logs/*.log
/storage/framework/cache/*
!/storage/framework/cache/.gitkeep
/storage/framework/sessions/*
!/storage/framework/sessions/.gitkeep
/storage/framework/views/*
!/storage/framework/views/.gitkeep
/bootstrap/cache/*.php

# =========================================================================
# 2. Environment, Secrets & Archives (NEVER COMMIT)
# =========================================================================
.env
.env.*
!.env.example
*.env.zip
*.zip
*.tar.gz
*.sql
*.sql.gz
*.bak
.ftpquota

# =========================================================================
# 3. Testing, Static Analysis & IDE Helpers
# =========================================================================
.phpunit.result.cache
.phpunit.cache/
/coverage
_ide_helper.php
_ide_helper_models.php
.phpstorm.meta.php
phpstan.result.cache

# =========================================================================
# 4. OS, Editor & AI Context Dumps
# =========================================================================
.DS_Store
**/.DS_Store
Thumbs.db
/.idea
/.vscode
*.swp
*.swo
repomix-output.xml
repomix-output.*
my_file.txt

# =========================================================================
# 5. Accidental Copy / Backup Files Prevention
# =========================================================================
* copy.*
* copy *.*
*copy2.*
```

---

## 3. ขั้นตอนการล้าง Git Index อย่างปลอดภัย (Non-Destructive Local File Preservation)

1. **Untrack เฉพาะใน Git Index (โดยไม่ลบไฟล์จริงในเครื่องสำหรับ `.env`, `vendor/`, `node_modules/`):**
   ```bash
   git rm -r --cached node_modules vendor .env .env.zip _ide_helper.php repomix-output.xml .ftpquota my_file.txt '"public'
   find . -name ".DS_Store" -print0 | xargs -0 git rm --cached --ignore-unmatch
   ```
2. **ลบไฟล์ขยะและไฟล์สำรองที่ซ้ำซ้อนออกจาก Working Tree และ Git:**
   ```bash
   git rm -rf "resources/views/kiosk copy" "app/Http/Controllers/_Backup_Unused"
   git rm -f "public/js/accessmenu copy2.js" \
             "resources/views/staff_accessmenu copy 3.blade.php" \
             "resources/views/staff_accessmenu.blade copy.php" \
             "resources/views/staff_accessmenu.blade1.php" \
             "resources/views/kiosk-monitor.blade copy.php" \
             "resources/views/kiosk/monitor.blade copy.php" \
             "resources/views/lineliff/dashboard.blade copy.php" \
             "resources/views/payment/_rc_right_ref_form.blade copy.php" \
             "resources/views/payment/receipt_print_multi.blade copy.php" \
             "resources/views/staff/includes/main_screen.blade copy.php" \
             "resources/views/tabwater/settings/stations/create.blade copy.php" \
             "resources/views/tabwater/settings/stations/index.blade copy.php" \
             "resources/views/inventory/withdraw/withdraw_print.blade copy.php" \
             "resources/views/foodwaste/admin/kp_user/create.blade copy.php" \
             "resources/views/foodwaste/dashboard.blade copy.php" \
             "resources/views/foodwaste/kp_budgetyear/create.blade copy.php" \
             "resources/views/foodwaste/kp_payment/search.blade copy.php" \
             "resources/views/foodwaste/user_payment_per_month/receipt_print.blade copy.php" \
             "resources/views/keptkayas/admin/kp_user/create.blade copy.php" \
             "resources/views/keptkayas/kp_budgetyear/create.blade copy.php" \
             "resources/views/keptkayas/kp_payment/search.blade copy.php" \
             "resources/views/keptkayas/tbank/prices/create.blade copy.php" \
             "resources/views/keptkayas/tbank/prices/form.blade copy.php" \
             "resources/views/keptkayas/user_payment_per_month/receipt_print.blade copy.php"
   ```
