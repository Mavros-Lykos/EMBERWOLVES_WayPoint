---
name: Oceanic Sapphire
description: Mission-critical enterprise logistics design system for Waypoint Dispatch
colors:
  bg-canvas: "#080d1a"
  bg-surface-low: "#0d1527"
  bg-surface-card: "#111d38"
  bg-surface-elevated: "#162344"
  bg-surface-highest: "#1c2b4e"
  border-subtle: "#1e325c"
  border-focus: "#38bdf8"
  primary: "#2563eb"
  primary-hover: "#1d4ed8"
  accent-ice: "#38bdf8"
  accent-soft: "#7bd0ff"
  text-primary: "#f8fafc"
  text-secondary: "#cbd5e1"
  text-muted: "#8da2c0"
  status-success: "#10b981"
  status-warning: "#f59e0b"
  status-critical: "#ef4444"
typography:
  display:
    fontFamily: "Space Grotesk, sans-serif"
    fontSize: "2rem"
    fontWeight: 700
    lineHeight: "2.5rem"
    letterSpacing: "-0.02em"
  heading:
    fontFamily: "Space Grotesk, sans-serif"
    fontSize: "1.25rem"
    fontWeight: 600
    lineHeight: "1.75rem"
  body:
    fontFamily: "Geist, sans-serif"
    fontSize: "0.875rem"
    fontWeight: 400
    lineHeight: "1.25rem"
  mono:
    fontFamily: "JetBrains Mono, monospace"
    fontSize: "0.75rem"
    fontWeight: 500
    lineHeight: "1rem"
rounded:
  sm: "4px"
  md: "8px"
  lg: "12px"
  full: "9999px"
spacing:
  xs: "4px"
  sm: "8px"
  md: "16px"
  lg: "24px"
  xl: "32px"
---

## Overview

The Oceanic Sapphire design system establishes a high-density, mission-critical operational workspace for Waypoint Dispatch. Built specifically for fast cognitive scanning during high-stress dispatch cycles and low-light field conditions, it pairs deep midnight nautical tones with crisp cobalt and ice-sky accents. 

Operating under Impeccable's **Operate Mode**, the interface rejects cosmetic flourishes, marketing copy, and explanatory essays in favor of surgical data clarity, high contrast, and tactile physical affordances.

---

## Colors

The palette revolves around deep oceanic depths punctuated by high-potency signal blues:

- **Canvas Foundation (`#080d1a`)**: Abyssal midnight navy base layer for full-screen dashboards, telemetry meshes, and command viewports. Minimizes circadian ocular strain during nocturnal logistics shifts.
- **Surface Layer 1 (`#111d38`)**: Rich sapphire navy for structural card containers, data grids, and control panels.
- **Surface Layer 2 (`#162344`)**: Elevated panels, active row selections, and floating modals.
- **Borders & Dividers (`#1e325c`)**: Razor-thin framing separating functional zones without visual clutter.
- **Primary Interactive Action (`#2563eb`)**: Cobalt Royal Blue for high-priority actions, dispatch releases, and confirmations. Hover state: `#1d4ed8`.
- **Accent Signals (`#38bdf8` & `#7bd0ff`)**: Ice sky blue and soft blue reserved exclusively for transit paths, live tracking vectors, key benchmarks, and telemetry chips.
- **Semantics**: Soft Emerald (`#10b981`) for completed/synced, Amber (`#f59e0b`) for cutoff/capacity risks, Terracotta (`#ef4444`) for critical mechanical or thermal breaches. **Never pure red `#FF0000`.**

---

## Typography

- **Headlines (Space Grotesk)**: Assertive, technical aesthetic suitable for high-level logistics overviews and screen titles.
- **Interface & Body (Geist)**: High-density legibility for manifests, multi-column tables, driver instructions, and parameter grids.
- **Telemetry, Metrics & Codes (JetBrains Mono)**: Mandatory for timestamps, latencies, VINs, seal numbers, $m^3$/$kg$ capacities, and delta figures. Tabular alignment prevents visual jitter during live updates.

---

## Layout

- **Desktop (1440px / 1920px)**: 12-column high-density operations grid. Maximizes vertical viewport utility with compact 40px table rows and tight metric groups.
- **Tablet (768px - 1024px)**: Dock supervisor touch layout. Prioritizes 64px tap targets for LIFO sequencing and physical barcode/seal entry.
- **Mobile (390px PWA)**: Single-column driver layout. Anchors all primary actions to the bottom 120px thumb zone.

---

## Elevation & Depth

Visual hierarchy uses tonal surface stacking paired with sharp, low-opacity oceanic glows rather than diffuse muddy drop shadows:
- **Level 0 (Canvas Base)**: `#080d1a` flat foundation.
- **Level 1 (Card Modules)**: `#111d38` with 1px border `#1e325c` and subtle ambient rim glow: `0 4px 20px -2px rgba(8, 13, 26, 0.7)`.
- **Level 2 (Active Modals & Overlays)**: `#162344` framed by `#2e4880` with focused shadow: `0 8px 32px 0 rgba(0, 0, 0, 0.5)`.

---

## Shapes

- Interactive controls, form fields, and inline indicators use a precise **4px** (`rounded-sm`) to **8px** (`rounded-md`) border radius, preserving the engineered, tool-like posture of mission command software.
- Full pill radii (`rounded-full`) are reserved strictly for continuous status indicators, connectivity badges, and language switches.

---

## Components

### 1. ConstraintBar (Atom)
Displays volume ($m^3$) and weight ($kg$) capacity utilization.
- `0–80%`: Soft Emerald (`#10b981`)
- `81–99%`: Warm Amber (`#f59e0b`)
- `≥100%`: Terracotta (`#ef4444`) with high-contrast text badge showing explicit delta: `+1.8m³ OVERLOADED`.

### 2. FatigueButton (Atom)
Full-width, minimum `64px` height on field devices, bold typography, tactile active scale (`active:scale-[0.98]`).

### 3. StatusBadge (Molecule)
Dual-coded with both unique shape icon AND semantic color for color-blind accessibility.

### 4. TelemetryTicker (Molecule)
Monospace numerals with secondary unit identifiers for instantaneous at-a-glance scanning.

---

## Do's and Don'ts

### DO:
- **Display the Delta**: Show `+12m ETA Delay` or `+420kg Overloaded` instead of making users do mental arithmetic.
- **Strict Micro-Copy**: Cap button labels at 1–2 words (`Launch Trip`, `Pass PTI`, `Confirm Seal`). Cap headers at 2–3 words.
- **Preserve Circadian Rhythm**: Default operations screens to Oceanic Sapphire dark mode.

### DON'T (Absolute Bans from Impeccable Craft Floor):
- **NO Explanatory Paragraphs**: Never write instructional essays, competition disclaimers, or narrative text inside screen components.
- **NO Pure Red (`#FF0000`)**: Always use muted terracotta (`#ef4444`).
- **NO Nested Cards**: Never put cards inside cards. Use subtle dividers, spacing, or inset backgrounds instead.
- **NO Decorative Gradients on Text**: Emphasis must come from typography scale or weight.
- **NO Unnecessary Modals**: Exhaust inline, drawer, or split-pane patterns before reaching for blocking dialogs.
