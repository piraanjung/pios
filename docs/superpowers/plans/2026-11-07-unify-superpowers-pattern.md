# Unified Superpowers Pattern Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Standardize the Superpowers framework and autonomous execution workflow pattern across all repositories in the `homework` workspace (`bot-trade`, `pios`, `workflow-fb-page-auto-post`).

**Architecture:** 
- Unified rule files in `<repo>/.agents/rules/` (`autonomous-editing.md`, `git-workflow.md`, `adr-tracking.md`).
- Unified master `AGENTS.md` specifying exclusive Superpowers methodology, stage-gated lifecycle, skill matrix, full autonomous execution rules (branch -> commit -> push -> merge to primary branch -> push primary), and repo-specific tech guidelines.
- `GEMINI.md` symlinked to `AGENTS.md` for zero-drift cross-harness compatibility.
- Clean up outdated/duplicate rule files (`.cursorrules`, `.agent/rules`, etc.).

**Tech Stack:** Git, Markdown, Go (`bot-trade`, `workflow-fb-page-auto-post`), PHP/Laravel (`pios`).

**Spec:** Unified Superpowers Pattern Specification (agreed in session).

## Global Constraints
- Every repository must adhere to the 9-stage Superpowers lifecycle.
- Full Autonomous execution: create task branch from primary, verify via tests, commit with conventional commit, push task branch, merge into primary branch, push primary branch to origin.
- All repositories must maintain `docs/adr/` with `template.md` and index in `README.md`.
- `GEMINI.md` must be a symlink to `AGENTS.md` in every repository.

---

### Task 1: Standardize `bot-trade`

**Files:**
- Create: `bot-trade/.agents/rules/autonomous-editing.md`
- Create: `bot-trade/.agents/rules/git-workflow.md`
- Create: `bot-trade/.agents/rules/adr-tracking.md`
- Modify: `bot-trade/AGENTS.md`
- Create (symlink): `bot-trade/GEMINI.md -> AGENTS.md`
- Delete: `bot-trade/.agent/rules` (and `.agent/` if empty)
- Create: `bot-trade/docs/superpowers/plans/2026-11-07-unify-superpowers-pattern.md`

- [ ] **Step 1: Create dedicated task branch**
  Run: `git -C bot-trade checkout -b chore/unify-superpowers-pattern master`

- [ ] **Step 2: Create modular rules in `.agents/rules/`**
  Write `autonomous-editing.md`, `git-workflow.md`, and `adr-tracking.md` in `bot-trade/.agents/rules/`.
  Remove `bot-trade/.agent/rules`.

- [ ] **Step 3: Update `bot-trade/AGENTS.md` & create `GEMINI.md` symlink**
  Update `bot-trade/AGENTS.md` with unified Superpowers lifecycle + Go test guidelines.
  Create symlink `bot-trade/GEMINI.md -> AGENTS.md`.

- [ ] **Step 4: Verify tests pass**
  Run: `cd bot-trade && go test -v ./...`
  Expected: All tests pass.

- [ ] **Step 5: Commit, Push branch, Merge to master, and Push master**
  Run git commands to commit on `chore/unify-superpowers-pattern`, push to origin, merge into `master`, and push `master` to origin.

---

### Task 2: Standardize `workflow-fb-page-auto-post`

**Files:**
- Modify: `workflow-fb-page-auto-post/.agents/rules/autonomous-editing.md`
- Modify: `workflow-fb-page-auto-post/.agents/rules/git-workflow.md`
- Modify: `workflow-fb-page-auto-post/.agents/rules/adr-tracking.md`
- Modify: `workflow-fb-page-auto-post/AGENTS.md`
- Replace with symlink: `workflow-fb-page-auto-post/GEMINI.md -> AGENTS.md`
- Delete: `workflow-fb-page-auto-post/.cursorrules`
- Create: `workflow-fb-page-auto-post/docs/superpowers/plans/2026-11-07-unify-superpowers-pattern.md`

- [ ] **Step 1: Create dedicated task branch**
  Run: `git -C workflow-fb-page-auto-post checkout -b chore/unify-superpowers-pattern main`

- [ ] **Step 2: Update modular rules in `.agents/rules/` and remove `.cursorrules`**
  Ensure rules match standard. Remove `.cursorrules`.

- [ ] **Step 3: Update `AGENTS.md` & convert `GEMINI.md` to symlink**
  Update `AGENTS.md`. Link `GEMINI.md -> AGENTS.md`.

- [ ] **Step 4: Verify tests pass**
  Run: `cd workflow-fb-page-auto-post && go test -v ./...`
  Expected: All tests pass.

- [ ] **Step 5: Commit, Push branch, Merge to main, and Push main**
  Run git commands to commit on `chore/unify-superpowers-pattern`, push to origin, merge into `main`, and push `main` to origin.

---

### Task 3: Standardize `pios`

**Files:**
- Remove git-tracked: `pios/.gemini`, `pios/.specify` (already staged `git rm`)
- Create: `pios/.agents/rules/autonomous-editing.md`
- Create: `pios/.agents/rules/git-workflow.md`
- Create: `pios/.agents/rules/adr-tracking.md`
- Update: `pios/AGENTS.md`
- Create (symlink): `pios/GEMINI.md -> AGENTS.md`
- Create: `pios/docs/superpowers/plans/2026-11-07-unify-superpowers-pattern.md`

- [ ] **Step 1: Create dedicated task branch or isolate on current branch**
  Commit spec-kit removal and branch for unified pattern.

- [ ] **Step 2: Create `.agents/rules/` directory and modular rules**
  Write `autonomous-editing.md`, `git-workflow.md`, and `adr-tracking.md`.

- [ ] **Step 3: Update `pios/AGENTS.md` & create `GEMINI.md` symlink**
  Update `pios/AGENTS.md` with unified Superpowers lifecycle + Laravel/VSA guidelines.
  Create symlink `pios/GEMINI.md -> AGENTS.md`.

- [ ] **Step 4: Commit and Push branch to origin**
  Stage rules, AGENTS.md, GEMINI.md, and push branch.
