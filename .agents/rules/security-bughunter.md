# Security & Vulnerability Hunting Rule (`Claude-BugHunter`)

Ref: [elementalsouls/Claude-BugHunter](https://github.com/elementalsouls/Claude-BugHunter)

## 1. Mandatory Security Skill Activation
- Whenever designing, implementing, modifying, or reviewing Laravel controllers/Vertical Slices (`app/Features/`), Multi-Tenancy middleware (`SetTenantConnection`), Eloquent/SQL queries, Blade templates, LINE LIFF endpoints, or Kiosk IoT routes, the agent **MUST** invoke the corresponding **Claude-BugHunter** (`claude-bughunter`) skills alongside **Superpowers**.
- Apply the 4-layer BugHunter methodology:
  1. **Think (`bb-methodology` & `redteam-mindset`):** Analyze multi-tenant trust boundaries (`org_id` / tenant connection), citizen vs. staff privilege levels, and Kiosk/LIFF input vectors.
  2. **Hunt (`hunt-*` vulnerability class skills):** Probe and audit code against specific vulnerability classes (`hunt-idor`, `hunt-sqli`, `hunt-xss`, `hunt-csrf`, `hunt-auth-bypass`, `hunt-ato`, `hunt-mass-assignment`, `hunt-file-upload`, `hunt-business-logic`, `hunt-api-misconfig`).
  3. **Validate (`triage-validation`):** Run the **7-Question Validation Gate** (root cause, reproduction proof, trust boundary violation, real impact) before reporting or claiming security readiness.
  4. **Hygiene (`evidence-hygiene`):** Never expose, log, or commit `.env` secrets, APP_KEY, database passwords, LINE channel tokens, or citizen PII (`token-scan` discipline).

## 2. Pre-Completion Security Gate
- Before `superpowers:verification-before-completion` passes, verify that all newly introduced or modified code paths have been audited against their applicable `hunt-*` vulnerability classes and contain zero hardcoded secrets or unvalidated inputs.
