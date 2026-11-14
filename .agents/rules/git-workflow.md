# Git Workflow & Full Autonomous Automation Rules

## 1. Dedicated Task Branching
- Always create and work on a dedicated task branch branched from the primary integration branch (`develop`) before modifying code:
  - Format: `feat/<name>`, `fix/<name>`, `chore/<name>`, `refactor/<name>`
- Never commit directly to `develop` or `main` without a task branch.

## 2. Autonomous Verification & Commit
- Before committing, always run project verification tests and ensure all pass (`verification-before-completion`):
  ```bash
  php artisan test
  ```
- Stage changed files and commit using conventional commit format (e.g., `feat(...)`, `fix(...)`, `chore(...)`).

## 3. Autonomous Push & Merge to Develop (DO NOT Push to Main)
- **CRITICAL RULE:** **DO NOT push directly to `main`**. The `main` branch is reserved strictly for production releases (`release/*` branched from `develop` into `main` for prod deployment).
- Once verification succeeds, autonomously execute the following to integrate into `develop`:
  1. Push the task branch to remote `origin`:
     ```bash
     git push origin <task-branch>
     ```
  2. Switch to the development branch (`develop`):
     ```bash
     git checkout develop
     git pull origin develop
     ```
  3. Merge the task branch into `develop`:
     ```bash
     git merge <task-branch> --no-ff -m "chore/merge: merge <task-branch> into develop [skip ci]"
     ```
  4. Push `develop` to `origin`:
     ```bash
     git push origin develop
     ```
- (ระบบทำงานแบบ Full Autonomous: สร้าง branch ใหม่จาก `develop` จากนั้น merge เข้า `develop` และ push `origin develop` ทันที — ห้าม push เข้า `main` โดยตรงเด็ดขาด)
