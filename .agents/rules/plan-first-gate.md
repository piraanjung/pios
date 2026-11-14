# Universal Plan-First Hard Gate Rule

## Mandatory Planning & User Approval Before Any Execution
1. **Plan Before Every Action (วางแผนก่อนลงมือทำทุกครั้ง):**
   - For **EVERY** task—whether a new feature, UI change, bugfix, runtime error hotfix, database migration/import, server configuration, or tool installation—the agent MUST first investigate (using read-only inspection) and present a clear root-cause/context summary and step-by-step plan to the user.
2. **Hard Approval Gate (ห้ามแก้ไขก่อนได้รับอนุมัติ):**
   - **STOP and WAIT** for explicit user approval of the proposed plan before creating/editing files, running database migrations, committing/pushing git changes, or installing packages.
   - Read-only exploration (`read_file`, read-only inspection commands) is permitted to gather facts for the plan, but zero state-changing actions may occur before the user approves the plan.
3. **Post-Approval Execution:**
   - Once the user explicitly approves the plan, write the formal plan in `docs/superpowers/plans/` and execute the steps autonomously using TDD (`RED -> GREEN -> REFACTOR`) and `verification-before-completion`.
