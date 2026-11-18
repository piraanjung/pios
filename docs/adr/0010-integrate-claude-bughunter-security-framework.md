# ADR 0010: การผสานรวม Claude-BugHunter Security Framework เข้ากับวงจรการพัฒนา Superpowers

## Status
Accepted (วันที่ 2026-11-18)

## Context
โปรเจกต์ **PIOS (`qa_envsogo_pios`)** เป็นแพลตฟอร์มบริหารจัดการบริการสาธารณะอัจฉริยะสำหรับ อปท. (ประปา `Tabwater`, ธนาคารขยะ `Keptkayas`, กองทุนสวัสดิการ `Welfare`, คลังพัสดุ `Inventory`, ตู้ Kiosk และ LINE LIFF) ที่ทำงานแบบ Multi-Tenant จึงต้องการยกระดับการตรวจสอบช่องโหว่และความปลอดภัยเชิงรุก (Offensive Security & Vulnerability Hunting) ให้เป็นมาตรฐานเดียวกับทุกโปรเจกต์ใน workspace ต่อเนื่องจาก ADR-0003

## Decision
1. **Adopt Claude-BugHunter (`elementalsouls/Claude-BugHunter`):** ผสานรวมชุดทักษะ 83 skills ของ Claude-BugHunter เข้าเป็นขั้นตอนที่ 9 ในวงจรการพัฒนาควบคู่กับ `Superpowers`
2. **Modular Security Rule (`.agents/rules/security-bughunter.md`):** กำหนดให้ AI Agent ต้องเรียกใช้ทักษะ `hunt-*` ที่ตรงกับโมดูล (`hunt-idor`, `hunt-sqli`, `hunt-xss`, `hunt-csrf`, `hunt-auth-bypass`, `hunt-mass-assignment`, `hunt-file-upload`, `triage-validation`, `evidence-hygiene`) ก่อนจบงานทุกครั้ง
3. **7-Question Validation Gate & Evidence Hygiene:** บังคับตรวจสอบการแบ่งแยกข้อมูลระดับ อปท. (`SetTenantConnection` / `org_id`), การป้องกัน IDOR ข้ามครัวเรือน และการห้าม commit ไฟล์ความลับหรือข้อมูลส่วนบุคคลลงใน Git

## Consequences
- **Positive:** ป้องกันช่องโหว่ IDOR ข้ามหน่วยงาน/ครัวเรือน, SQL Injection, XSS บนหน้าเว็บ/LIFF และ Arbitrary File Upload อย่างเป็นระบบ
- **Negative:** มีขั้นตอนการตรวจสอบรายการความปลอดภัยเพิ่มเติมก่อนปิดงาน
- **Constraints (สำหรับ AI Agent):**
  - ห้ามแก้ไข Controller, Vertical Slice, Middleware หรือ Blade View โดยไม่ตรวจสอบตาม `Claude-BugHunter Attack-Surface Mapping` ใน `AGENTS.md`
  - ห้ามบันทึกหรือ commit ค่า `.env`, `APP_KEY`, LINE Token หรือข้อมูลส่วนบุคคลของประชาชนลงใน Git (`evidence-hygiene`)
