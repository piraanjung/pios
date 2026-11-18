# Claude-BugHunter Integration Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Integrate the `Claude-BugHunter` security & bug-hunting skill framework into `pios` alongside `Superpowers`.

**Architecture:**
- Add modular security rule `.agents/rules/security-bughunter.md`.
- Update `AGENTS.md` (and `GEMINI.md` symlink) with the 11-stage Superpowers + Claude-BugHunter lifecycle and repository-specific attack-surface mapping.
- Record ADR 0010 in `docs/adr/` and update `docs/adr/README.md`.

**Tech Stack:** Markdown, Git, PHP 8.2+ / Laravel 11.

---

### Task 1: Integrate Claude-BugHunter into Repository Rules & ADR

- [x] **Step 1: Create `.agents/rules/security-bughunter.md`**
- [x] **Step 2: Update `AGENTS.md` with Claude-BugHunter lifecycle and attack-surface matrix**
- [x] **Step 3: Create ADR 0010 and update `docs/adr/README.md`**
- [x] **Step 4: Commit, push branch, and merge to `develop`**
