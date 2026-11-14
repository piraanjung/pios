# Autonomous File Editing Rule

## Direct File Editing After Plan Approval
- **Prerequisite (Plan-First Hard Gate):** Never edit, create, or delete files BEFORE presenting a plan and receiving explicit user approval (`.agents/rules/plan-first-gate.md`).
- **During Approved Plan Execution:** ONLY AFTER the user has explicitly approved the plan, the agent must proactively and directly edit, modify, and create files without asking for per-file user confirmation, permission, or manual acceptance during task execution.
- Never prompt the user with phrases like "Should I proceed with editing this file?" or "Please accept these changes" while executing an already-approved plan.
- (ต้องวางแผนและรอให้ผู้ใช้อนุมัติแผนก่อนเสมอ เมื่อผู้ใช้อนุมัติแผนแล้ว จึงให้แก้ไขไฟล์ตามแผนโดยตรงทันทีโดยไม่ต้องถามยืนยันรายไฟล์อีก)
