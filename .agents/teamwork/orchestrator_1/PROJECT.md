# Project: QA Envsogo PIOS User Registration & Module Membership Audit

## Architecture
- Framework: Laravel (PHP 8.2+)
- Application: `qa_envsogo_pios`
- System Scope: Multi-tenant environmental management platform with modules for Admin User Management, Water Meter (Tabwater), KeptKaya (Recycle Bank), Annual Trash, Foodwaste, Welfare, LINE LIFF citizen registration, and Kiosk self-service registration.

## Feature Inventory
| # | Feature Area | Description | Milestone | Source |
|---|--------------|-------------|-----------|--------|
| 1 | Admin User Management | User creation, edit, import, role/org assignment via UserController | M1 (Survey & Audit) | ORIGINAL_REQUEST §R1 |
| 2 | Tabwater Module | UserMeterInfosController, water meter customer registration, linking User to Meter | M1 (Survey & Audit) | ORIGINAL_REQUEST §R1 |
| 3 | KeptKaya / Recycle Bank | Member registration, waste collection account linking, bank account linking | M1 (Survey & Audit) | ORIGINAL_REQUEST §R1 |
| 4 | Annual Trash Module | Household/bin member registration, annual trash fee account linking | M1 (Survey & Audit) | ORIGINAL_REQUEST §R1 |
| 5 | Foodwaste Module | Food waste participant registration, organic waste account linking | M1 (Survey & Audit) | ORIGINAL_REQUEST §R1 |
| 6 | Welfare Module | UserWelfareController, WelfareMemberController, welfare beneficiary registration | M1 (Survey & Audit) | ORIGINAL_REQUEST §R1 |
| 7 | LINE LIFF Onboarding | LineLiffController, citizen self-registration, LINE UID linking, OTP/verification | M1 (Survey & Audit) | ORIGINAL_REQUEST §R1 |
| 8 | Kiosk Self-Registration | KioskApiController, smart card/citizen ID reading, offline/online sync | M1 (Survey & Audit) | ORIGINAL_REQUEST §R1 |
| 9 | UX & Workflow Bottlenecks | Duplicate forms, fragmented Excel imports, inconsistent user-to-member linking | M2 (Analysis & Redesign) | ORIGINAL_REQUEST §R2 |
| 10 | Security & Multi-Org Isolation | Cross-tenant data leaks, missing auth middleware, unauthenticated endpoints | M3 (Security Audit) | ORIGINAL_REQUEST §R3 |
| 11 | Data Integrity & Transactions | Missing DB transactions, orphaned child records, race conditions | M3 (Integrity Audit) | ORIGINAL_REQUEST §R3 |
| 12 | PHP 8.2+ Compatibility | Dynamic property deprecation, type errors, broken routes/missing methods | M3 (Code Audit) | ORIGINAL_REQUEST §R3 |
| 13 | Architectural Refactoring Plan | Unified As-Is vs To-Be Mermaid workflows, phased refactoring recommendations | M4 (Report Deliverable) | ORIGINAL_REQUEST §R4 |

## Milestones
| # | Name | Scope | Dependencies | Status |
|---|------|-------|-------------|--------|
| 1 | Survey Phase | 3 Explorers map all controllers, models, routes, schemas, security, and bugs | none | DONE |
| 2 | Synthesis & Report Generation | Worker writes comprehensive USER_MEMBER_WORKFLOW_AUDIT_TH.md in Thai | M1 | DONE |
| 3 | Review & Challenge | 2 Reviewers + 2 Challengers verify completeness, accuracy, and line numbers | M2 | DONE |
| 4 | Forensic Audit & Handoff | Forensic Auditor verifies clean git status, zero unauthorized changes | M3 | DONE |

## Code Layout
- Target Deliverable: `c:\xampp\htdocs\qa_envsogo_pios\USER_MEMBER_WORKFLOW_AUDIT_TH.md` (Thai report, 988 lines, 121 KB - VERIFIED)
- Agent Metadata: `c:\xampp\htdocs\qa_envsogo_pios\.agents\teamwork\`
- Existing source code: Read-only, zero modifications confirmed.
