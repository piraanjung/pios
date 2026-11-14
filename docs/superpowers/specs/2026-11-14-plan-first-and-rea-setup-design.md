# Plan-First Hard Gate & REA Agents Setup Design

## Intent & Why
1. **Universal Plan-First Hard Gate:** Ensure that every agent session on the PIOS repository presents a clear root-cause/design analysis and step-by-step plan and waits for explicit user approval BEFORE modifying any files, running migrations, or executing destructive/state-changing commands — regardless of whether the task is a new feature, a bugfix, a hotfix, or a system setup task.
2. **Global Installation of `rea-agents` (`morluto/rea`):** Install and configure the `rea-agents` CLI, MCP server (`~/.gemini/config/mcp_config.json`), and Antigravity skill (`~/.gemini/config/skills/reverse-engineer-anything/`) using a compatible Node.js runtime (`>=22.23.4` or `>=26.12.1`).

## Approaches Considered
- **Approach 1 (Selected by User):**
  - Upgrade Node via Homebrew (`node@22` or `node`) to meet `rea-agents@6.9.0` engine constraints (`^22.23.4 || ^24.22.1 || >=26.12.1`), install `rea-agents@latest` globally, configure the `reverse-engineer-anything` skill in `~/.gemini/config/skills/`, and register the `rea` MCP server in `~/.gemini/config/mcp_config.json`.
  - Update `GEMINI.md`, `.agents/rules/autonomous-editing.md`, and add `.agents/rules/plan-first-gate.md` to enforce the universal Plan-First Approval Gate across all tasks.
- **Approach 2:**
  - Bypass Node engine checks with `--ignore-engines` on existing Node `v26.9.0` and only edit `GEMINI.md` without a dedicated rule file. (Rejected in favor of Approach 1).

## Scope & Constraints
- Repository rule files: `GEMINI.md`, `.agents/rules/autonomous-editing.md`, `.agents/rules/plan-first-gate.md`.
- Global Antigravity customizations: `~/.gemini/config/mcp_config.json`, `~/.gemini/config/skills/reverse-engineer-anything/SKILL.md`.
