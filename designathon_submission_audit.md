# Designathon Submission Audit

> **Deadline: Tuesday, September 29, 2026 at 11:59 PM Sri Lanka time (TODAY)**

---

## Current State vs. Required Deliverables

### ✅ DONE: High-Fidelity Prototype (30 HTML Screens)

| Role | Screens | Status | Notes |
|------|---------|--------|-------|
| **AUTH** | `auth_01.html` | ✅ Done | Judge Quick Launch portal |
| **STORE** (Priya) | `store_01` → `store_06` | ✅ Done | Dashboard → Order → Track → History → Receive → Dispute |
| **DISP** (Kamal) | `disp_01` → `disp_08` | ✅ Done | Overview → Allocation → Sequence → Deferral → Live → Telemetry → Breakdown → Crisis |
| **LOAD** (Nuwan) | `load_01` → `load_06` | ✅ Done | Queue → Inspect → Load → Revision Alert → Seal → History |
| **DRV** (Saman) | `drv_01` → `drv_08` | ✅ Done | PTI → Navigation → Arrival → Handoff → Rest → Emergency → Summary → Schedule |
| **SYS** | `sys_01.html` | ✅ Done | Error/offline fallback |

### ✅ DONE: Design Tokens System
- [material-tokens.css](file:///d:/Projects/rootcode/src/styles/material-tokens.css) — Material 3 CSS variable system

---

## ❌ MISSING Deliverables (Must Complete TODAY)

### 1. 🔴 User Personas Document
**Required:** "One persona for each role, grounded in the working conditions and needs in this brief."

We have persona references in AGENTS.md and the sitemap, but we need a **clean, standalone document** with:
- Priya (Store Manager) — environment, pain points, goals
- Kamal (Central Dispatcher) — environment, pain points, goals
- Nuwan (Dock Supervisor) — environment, pain points, goals
- Saman (Field Driver) — environment, pain points, goals

### 2. 🔴 Screen Flow Diagrams
**Required:** "Show each role's screens and include a one-paragraph rationale for every screen."

We need **visual flow diagrams** showing:
- How screens connect per role (Priya's journey, Kamal's journey, etc.)
- Cross-role connections (dispatcher decision → loader revision alert, driver delivery → store receipt)
- One-paragraph rationale per screen explaining "why this screen exists"

### 3. 🔴 Degradation Screen Rationales
**Required:** "Include at least one fully designed failure scenario, with its name and rationale."

We have 3 degradation screens built:
- `disp_08.html` — Festival Capacity Overload (Scenario 1)
- `load_04.html` — Mid-Load Plan Revision (Scenario 2)  
- `drv_06.html` — Mid-Route Vehicle Breakdown (Scenario 3)

But we need **named rationale paragraphs** for each explaining why it matters to Waypoint.

### 4. 🔴 Demo Video (3-5 minutes, YouTube Unlisted)
**Required:** "Upload a three to five minute demo video on YouTube as an unlisted video."

This needs:
- Walkthrough of all 4 role flows
- Discussion of design assumptions
- Show degradation scenarios

### 5. 🟡 AI Tool Disclosure
**Required:** "Explain which work was AI-assisted, which was not, and how you used the tools."

### 6. 🟡 Core Tradeoff Explanation (Optional but recommended)
"Use up to one page or one diagram to explain your main design tradeoff."

### 7. 🟡 Style Guide (Optional but recommended)
We have `material-tokens.css` + `instructions.md` but could formalize it.

### 8. 🔴 Cleanup: Remove Junk Files from Repo Root
These leftover files need to be deleted:
- `auth_01_remote.html`, `disp_01_remote.html`, `drv_01_remote.html`, `load_01_remote.html`, `store_01_remote.html`, `sys_01_remote.html`
- `batch_rewrite.py`, `create_prs.py`

---

## Judging Criteria Alignment

| Criterion | Weight | Our Coverage | Risk |
|-----------|--------|-------------|------|
| **Problem framing** | 25% | SRS + Sitemap cover this well | 🟢 Low |
| **Understanding of user context** | 20% | Personas exist in docs but need standalone document | 🟡 Medium |
| **Degradation screen quality** | 15% | 3 scenarios fully built (above requirement of ≥1) | 🟢 Low |
| **Domain accuracy** | 10% | 120 outlets, 60 vehicles, 16:00 cutoff, van_only — all present in UI | 🟢 Low |
| **Scope and prioritization** | 15% | 30 screens is dense; need to justify scope is focused, not bloated | 🟡 Medium |
| **Visual & interaction design** | 15% | Material 3 is clean and consistent | 🟢 Low |

---

## Recommended Action Plan (Priority Order)

1. **Clean up repo** — Delete junk files (5 min)
2. **Create Personas doc** — 4 role personas with context, pain points, goals (30 min)
3. **Create Screen Flow doc** — Mermaid diagrams showing per-role flows + cross-role connections + per-screen rationale paragraphs (1-2 hrs)
4. **Write Degradation Rationales** — 3 named paragraphs (20 min)
5. **Write AI Disclosure** — Explain tools used (15 min)
6. **Write Core Tradeoff** — "Material 3 light theme for all roles vs. dark/light split" (15 min)
7. **Record Demo Video** — Screen-record browser walkthrough of all 4 flows (1-2 hrs)
8. **Package & Submit** — Export as `TeamName_Designathon.zip`, upload (30 min)

> [!WARNING]
> **The demo video and submission packaging are YOUR tasks** — I can create all the documents, but you need to record and upload the video yourself.
