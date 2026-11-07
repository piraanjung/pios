# Git Workflow & Full Autonomous Automation Rules

## 1. Dedicated Task Branching
- Always create and work on a dedicated task branch branched from the primary branch (`main`) before modifying code:
  - Format: `feat/<name>`, `fix/<name>`, `chore/<name>`, `refactor/<name>`
- Never commit directly to the primary branch without a task branch.

## 2. Autonomous Verification & Commit
- Before committing, always run project verification tests and ensure all pass (`verification-before-completion`):
  ```bash
  php artisan test
  ```
- Stage changed files and commit using conventional commit format (e.g., `feat(...)`, `fix(...)`, `chore(...)`).

## 3. Autonomous Push, MR/PR & Merge (Full Autonomous)
- Once verification succeeds, autonomously execute the following without waiting for user approval:
  1. Push the task branch to remote `origin`:
     ```bash
     git push origin <task-branch>
     ```
  2. Switch to primary branch (`main`):
     ```bash
     git checkout main
     git pull origin main
     ```
  3. Merge the task branch into the primary branch:
     ```bash
     git merge <task-branch> --no-ff -m "chore/merge: merge <task-branch> into main [skip ci]"
     ```
  4. Push the updated primary branch to `origin`:
     ```bash
     git push origin main
     ```
- (ระบบทำงานแบบ Full Autonomous: สร้าง branch ใหม่จาก branch หลัก และเปิด MR จากนั้น merge เข้า branch หลัก และ push origin ทันทีโดยไม่ต้องรอถาม)
