# Plan-First Hard Gate & REA Agents Setup Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Enforce the universal Plan-First Approval Gate in repository agent rules and install `rea-agents` (CLI, Skill, and MCP Server) globally for Antigravity.

**Architecture:** Update `GEMINI.md` and `.agents/rules/` with an explicit Universal Plan-First Hard Gate rule, and install `rea-agents@6.9.0` on a compatible Homebrew Node runtime with global Antigravity skill (`~/.gemini/config/skills/reverse-engineer-anything`) and MCP server (`~/.gemini/config/mcp_config.json`) bindings.

**Tech Stack:** Markdown rules, PHPUnit verification test, Homebrew Node.js (`>=22.23.4` / `>=26.12.1`), npm (`rea-agents`), Antigravity MCP & Skills config.

**Spec:** `docs/superpowers/specs/2026-11-14-plan-first-and-rea-setup-design.md`

## Global Constraints
- `rea-agents@6.9.0` requires Node engine `^22.23.4 || ^24.22.1 || >=26.12.1`.
- Default shell `PATH` points to `/opt/homebrew/opt/node@20/bin/node` (`v20.20.2`), so `mcp_config.json` must invoke `rea` using the full path to the compatible Node binary.
- All repository rules in `GEMINI.md` and `.agents/rules/` must explicitly state that autonomous file editing applies ONLY AFTER the user has approved the plan.

## Review Focus
- `mcp_config.json` valid JSON syntax and absolute binary paths so Antigravity's Language Server can spawn the MCP server regardless of shell PATH.
- `SKILL.md` valid YAML frontmatter (`name`, `description`) in `~/.gemini/config/skills/reverse-engineer-anything/SKILL.md`.

---

### Task 1: Enforce Universal Plan-First Hard Gate in Repository Rules

**Files:**
- Create: `.agents/rules/plan-first-gate.md`
- Modify: `.agents/rules/autonomous-editing.md`
- Modify: `GEMINI.md`
- Modify: `tests/Feature/SecurityAndRuntimeHotfixesTest.php`

- [x] **Step 1: Write the failing test in `tests/Feature/SecurityAndRuntimeHotfixesTest.php` verifying the Plan-First rule files and clauses exist**
- [x] **Step 2: Run test to verify it fails**
  Run: `/opt/homebrew/bin/php artisan test --filter=SecurityAndRuntimeHotfixesTest`
- [x] **Step 3: Create `.agents/rules/plan-first-gate.md` and update `.agents/rules/autonomous-editing.md` and `GEMINI.md`**
- [x] **Step 4: Run test to verify it passes**
  Run: `/opt/homebrew/bin/php artisan test --filter=SecurityAndRuntimeHotfixesTest`
- [x] **Step 5: Commit rule changes**

### Task 2: Upgrade Node Runtime & Install `rea-agents` CLI, Skill, and MCP Server

**Files:**
- Create: `~/.gemini/config/skills/reverse-engineer-anything/SKILL.md`
- Modify: `~/.gemini/config/mcp_config.json`

- [x] **Step 1: Upgrade Homebrew `node` / `node@22` to satisfy `^22.23.4 || >=26.12.1` and install `rea-agents@latest`**
- [x] **Step 2: Run `rea-agents setup` / install `reverse-engineer-anything` skill into `~/.gemini/config/skills/reverse-engineer-anything/`**
- [x] **Step 3: Configure `rea` MCP server in `~/.gemini/config/mcp_config.json` with explicit Node path**
- [x] **Step 4: Verify CLI, Skill file, and MCP JSON handshake**
