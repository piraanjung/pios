# Architecture Decision Records (ADR) Rule

## Mandatory ADR Updates
- **Record Every Decision & Major Change**: ทุกครั้งที่มีการแก้ไข ออกแบบ หรือเปลี่ยนแปลงระบบ ต้องบันทึกและอัปเดตเอกสาร ADR ภายใต้ไดเรกทอรี `docs/adr/` เสมอ
- **Follow Standard Pattern**: โครงสร้างเอกสารต้องปฏิบัติตาม Pattern ใน [template.md](../../docs/adr/template.md) โดยต้องประกอบด้วย:
  1. `Status` (เช่น Accepted, วันที่ YYYY-MM-DD)
  2. `Context` (ปัญหาหรือบริบทความต้องการ)
  3. `Decision` (การตัดสินใจทางสถาปัตยกรรมและเทคโนโลยีที่เลือกใช้)
  4. `Consequences` (Positive, Negative, และ Constraints สำหรับ AI Agent)
- **Maintain Index**: อัปเดตตารางสารบัญ Index of Decisions ใน [README.md](../../docs/adr/README.md) ทุกครั้งที่มีการเพิ่มหรือแก้ไข ADR
