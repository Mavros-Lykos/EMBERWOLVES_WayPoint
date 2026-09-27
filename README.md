# Waypoint Dispatch — Tech-Triathlon 2026

*Enterprise Multi-Depot Fleet Allocation & Offline-First Delivery Execution Platform*

---

## 1. Project Overview

Waypoint Dispatch is the mission-critical logistics platform engineered for the **Tech-Triathlon 2026** competition. It manages end-to-end retail replenishment across **120 retail outlets** served by **2 central distribution depots** (Peliyagoda and Kandy) with an active fleet of **60 specialized vehicles** (12 refrigerated trucks, 40 dry trucks, 4 refrigerated vans, 4 dry vans).

The system enforces strict operational invariants:
- **16:00 Non-Negotiable Order Cutoff:** Hard algorithmic freeze for daily store replenishment.
- **Pre-08:00 AM Fresh Delivery Window:** Strict perishable SLA before retail customer doors open.
- **Mountain Corridor Topology:** 100% offline-first PWA resilience along signal-dead mountain passes (Kadugannawa, Nuwara Eliya).
- **Physical Reality Guardrails:** Van-only store access limits, strict volume ($m^3$) & weight ($kg$) payload barriers, reverse-sequence (LIFO) pallet loading, and tamper-evident cold-chain security seals.

---

## 2. Design System & Frontend Architecture

The user interface follows the **Oceanic Sapphire (Bluish Nature)** palette under the **Operate Mode** defined in [DESIGN.md](file:///d:/Projects/rootcode/DESIGN.md) and [PRODUCT.md](file:///d:/Projects/rootcode/PRODUCT.md).

### Visual Tokens & Color Palette
- **Canvas Base:** `#080d1a` (Abyssal Midnight Navy) — Preserves dark-adapted vision and prevents ocular fatigue during nocturnal operations (10:00 PM to 06:00 AM).
- **Surface Cards:** `#111d38` (Deep Sapphire Navy) with subtle 1px border `#1e325c`.
- **Elevated Surfaces:** `#162344` for active selections, modals, and tooltips.
- **Primary Interactive CTA:** `#2563eb` (Cobalt Royal Blue), hover `#1d4ed8`.
- **Accent Signals:** `#38bdf8` (Ice Sky Blue) and `#7bd0ff` (Soft Blue) for telemetry indicators, live pings, and delta vectors.
- **Semantic Status:**
  - Nominal / Synced: Soft Emerald `#10b981`
  - Operational Warning / Approaching Cutoff: Warm Amber `#f59e0b`
  - Critical Overload / Thermal Breach: Muted Terracotta `#ef4444` (**Zero pure red `#FF0000`**).

### The Zero-Fluff & Scannability Directive
Real enterprise operators scan interfaces in under 300 milliseconds. This codebase strictly bans AI slop:
- **No Explanatory Essays:** No introductory paragraphs, competition disclaimers, or "how-to" text inside screen components.
- **Metric Delta Density:** Always display deltas (`+1.8m³ OVERLOADED`, `+12m ETA Delay`) instead of raw numbers or narrative sentences.
- **Strict Micro-Copy Caps:** Action buttons $\le 2$ words; card headers $\le 3$ words; badges $\le 2$ words.
- **No Nested Cards:** Prohibited. Visual structure is maintained through spacing, dividers, and background elevation.

---

## 3. Installed Skills & Customizations

This repository uses Antigravity / Claude Code / Codex workspace skills located in `.agents/skills/`. All AI coding assistants and team members working in this repo have access to:

### 1. `impeccable` ([`.agents/skills/impeccable`](file:///d:/Projects/rootcode/.agents/skills/impeccable))
An open-source design director and UI/UX quality engine created by Paul Bakaus (creator of jQuery UI and former Google Dev Advocate). It enforces 61 deterministic design rules and provides 24 invocable commands:
- `craft-floor.md`: Quality floor, accessibility contrast ($\ge 4.5:1$), and absolute bans on AI slop.
- `operate.md`: Specific rules for high-density app UIs, dashboards, and enterprise tools.
- `distill.md`: Ruthless text and component simplification ("Cut every sentence in half, then do it again").
- `audit.md`, `polish.md`, `typeset.md`, `layout.md`, `colorize.md`.

### 2. `design-system` ([`.agents/skills/design-system`](file:///d:/Projects/rootcode/.agents/skills/design-system))
Industry standards for Figma-to-code translation, 8-point geometric spacing, token naming schemas, and atomic component hierarchies.

---

## 4. How to Install or Update Skills

### Method A: Repository Native (Recommended for Teammates)
All skills are committed directly into git under `.agents/skills/`. Cloning this repository automatically provides the full skill suite with zero extra installation steps:
```bash
git clone <repo-url>
cd rootcode
```

### Method B: Installing Fresh or Updating Impeccable
If you need to install or update Impeccable in another branch or environment:

1. **Via Git Submodule / Vendor Clone:**
   ```bash
   git clone --depth 1 https://github.com/pbakaus/impeccable.git temp_impeccable
   cp -r temp_impeccable/.agents/skills/impeccable .agents/skills/impeccable
   rm -rf temp_impeccable
   ```
2. **Via NPM / NPX:**
   ```bash
   npx -y impeccable install -y --project
   ```
3. **Verify Installation:**
   Ensure `.agents/skills/impeccable/SKILL.md` exists. Your AI assistant will automatically discover the skill on the next turn.

---

## 5. Repository Structure

```
rootcode/
├── .agents/
│   ├── rules/
│   │   ├── agent_personas.md     # 4 Engineering execution personas
│   │   ├── user_personas.md      # Kamal, Nuwan, Saman, Priya operational profiles
│   │   └── ui_ux_guidelines.md   # 8 core design laws & circadian rules
│   └── skills/
│       ├── design-system/        # Token naming & Figma-to-code standards
│       └── impeccable/           # Impeccable design skill, 38 reference playbooks
├── src/
│   └── screens/
│       ├── auth_01.html          # AUTH-01 Role-Switch & Judge Portal
│       └── sys_01.html           # SYS-01 Multi-Tier Diagnostics & Offline Mesh
├── AGENTS.md                     # Root persona directives & workspace invariants
├── PRODUCT.md                    # Canonical product truth (Impeccable init spec)
├── DESIGN.md                     # Canonical design tokens (Google Labs DESIGN.md spec)
├── instructions.md               # Master design system specification
├── COMPREHENSIVE_SITEMAP.md      # 30 screen blueprints & 3 degradation flows
├── SRS_WAYPOINT_DISPATCH.md      # Master software requirements specification
└── README.md                     # This file
```

---

## 6. Prototyped Screen Index

| Screen ID | Figma Frame Name | Route | Description |
|---|---|---|---|
| **AUTH-01** | `Auth / Role-Switch Portal / Default` | `src/screens/auth_01.html` | 1-click role switcher for Kamal, Nuwan, Saman, and Priya with scannable KPI chips, 14ms latency pill, and compact enterprise SSO. |
| **SYS-01** | `System / Health Diagnostics / Normal` | `src/screens/sys_01.html` | Multi-tier degradation diagnostics: WebSocket live telemetry with 60Hz pulse, adaptive polling fallback gauge, mountain SMS modem readiness, and edge node table. |
