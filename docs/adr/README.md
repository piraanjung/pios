# Architecture Decision Records (ADRs)

ไดเรกทอรีนี้เก็บรวบรวม Architecture Decision Records (ADRs) สำหรับโปรเจกต์ **PIOS (`qa_envsogo_pios` — ระบบบริหารจัดการสิ่งแวดล้อมและบริการสาธารณะอัจฉริยะสำหรับ อปท.)** โดยทุกการเปลี่ยนแปลงเชิงสถาปัตยกรรม การแบ่งขอบเขต Vertical Slice การอัปเกรดเฟรมเวิร์ก มาตรฐานความปลอดภัย Multi-Tenancy และระบบ UI/UX (Soft UI Dashboard PRO Laravel) จะต้องถูกบันทึกไว้ที่นี่เสมอ (อ้างอิงแนวปฏิบัติมาตรฐานจากโปรเจกต์ `bot-trade`)

## Index of Decisions

| ADR | Title | Date | Status |
| :---: | :--- | :---: | :---: |
| [0001](0001-vertical-slice-architecture-adoption.md) | การปรับโครงสร้างระบบสู่ Vertical Slice Architecture (VSA) และ Shared Kernel | 2026-11-05 | **Accepted** |
| [0002](0002-laravel-latest-version-upgrade-strategy.md) | การอัปเกรดโครงสร้างพื้นฐานสู่ Laravel เวอร์ชันล่าสุด (Laravel 12 / PHP 8.3+) และ Vite | 2026-11-05 | **Accepted** |
| [0003](0003-multi-tenancy-isolation-and-security-hardening.md) | การบังคับใช้ระบบแยกข้อมูลหลายองค์กร (Multi-Tenancy) และการปิดช่องโหว่ความปลอดภัยวิกฤต | 2026-11-05 | **Accepted** |
| [0004](0004-unified-master-user-onboarding-and-schema-consolidation.md) | การรวมศูนย์สคีมาฐานข้อมูลและไปป์ไลน์การลงทะเบียนผู้ใช้ (Unified Master User Onboarding & Schema Consolidation) | 2026-11-05 | **Accepted** |
| [0005](0005-soft-ui-dashboard-pro-design-system-standardization.md) | การจัดทำมาตรฐานระบบ UI/UX อ้างอิงตาม Soft UI Dashboard PRO Laravel | 2026-11-05 | **Accepted** |
| [0006](0006-git-hygiene-and-gitignore-policy.md) | การจัดระเบียบ Git Repository, `.gitignore` และการล้างไฟล์ความลับ/ไฟล์ขยะออกจาก Git Index | 2026-11-05 | **Accepted** |
| [0007](0007-comprehensive-testing-strategy-and-qa-pyramid.md) | การออกแบบสถาปัตยกรรมการทดสอบระบบแบบครบวงจร (Comprehensive Testing Strategy & QA Pyramid) | 2026-11-05 | **Accepted** |
| [0008](0008-standardized-api-response-envelope.md) | การกำหนดมาตรฐานโครงสร้างการตอบกลับของ API (Standardized API Response Envelope Pattern) | 2026-11-05 | **Accepted** |
| [0009](0009-standardizing-typography-with-kanit-font.md) | การกำหนดมาตรฐานระบบตัวอักษรของโปรเจกต์ด้วยฟอนต์ Kanit (Standardizing Typography with Kanit Font) | 2026-11-05 | **Proposed** |

---

## Standard ADR Template (บังคับใช้ทุกครั้ง)

แม่แบบมาตรฐานถูกจัดเก็บไว้ที่ไฟล์ 👉 [template.md](template.md)  
**ข้อบังคับสำหรับ AI Agent และผู้พัฒนา:** ก่อนทำการเขียน ADR ฉบับใหม่ทุกครั้ง จะต้องอ่าน Pattern จากไฟล์ `template.md` ก่อนเสมอ และต้องมีหัวข้อ `Constraints (สำหรับ AI Agent)` ครบถ้วนทุกฉบับ

```markdown
# ADR [NUMBER]: [TITLE]

## Status
[Status] (วันที่ YYYY-MM-DD)

## Context
[อธิบายบริบทว่าทำไมถึงต้องมีการตัดสินใจนี้ เช่น ปัญหาที่พบในโค้ดเดิม ช่องโหว่ความปลอดภัย ข้อจำกัดทางสถาปัตยกรรม หรือความต้องการใหม่ทางธุรกิจของ อปท.]

## Decision
[อธิบายการตัดสินใจอย่างชัดเจน โครงสร้างสถาปัตยกรรม (Vertical Slice / Shared Kernel) วิธีการออกแบบ หรือโค้ดมาตรฐานที่เลือกนำมาใช้เพื่อแก้ปัญหา]

## Consequences
- **Positive:** [ข้อดีหรือผลลัพธ์เชิงบวกที่ระบบได้รับจากการตัดสินใจนี้]
- **Negative:** [ข้อจำกัด ข้อควรระวัง หรือ Trade-offs ที่เกิดขึ้น]
- **Constraints (สำหรับ AI Agent):** [ข้อห้ามหรือข้อบังคับที่ AI Agent ต้องปฏิบัติตามอย่างเคร่งครัด ห้ามละเมิดในการเขียนโค้ดหรือแก้ไขระบบครั้งต่อไป]
```
