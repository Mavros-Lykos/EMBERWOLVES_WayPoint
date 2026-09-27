# Waypoint Dispatch: Figma & UI Industry Standards
*Master Design System Specification for Designathon & Hackathon*

This document serves as the master instruction guide and design system specification for the Waypoint Dispatch platform, establishing enterprise-grade design tokens, typography scales, layout rules, and component architectures for Figma and production code.

---

## 1. Multi-Theme Token Architecture

To satisfy the diverse physical environments of our 4 personas, the design system implements a dual-theme architecture with a mandatory high-contrast accessibility override.

### Theme Overview & Semantic Role Assignment

| Theme Name | Target Personas | Physical Environment | Primary Purpose | Base Background | Primary Action |
|---|---|---|---|---|---|
| **Oceanic Sapphire** *(Bluish Nature — Default Ops)* | Dispatcher, Loader, Driver | Low-light control rooms, night highway, 03:00 AM dark cab | Deep blue calming nature, zero circadian strain, high contrast | `#080d1a` (Midnight Navy) | `#2563eb` (Cobalt Blue) |
| **Serene Retail** *(Default Retail)* | Store Manager | Daylight retail counter, supermarket checkout | Clean, trustworthy, daylight legibility | `#f8fafc` (Slate 50) | `#0284c7` (Sky 600) |
| **Industrial Utility** *(A11y Override)* | All (User-toggled) | Direct tropical sun glare, visual impairment, dock dust | Stark contrast, zero ambiguous shading | `#000000` (Pure Black) | `#ffff00` (Safety Yellow) |

---

### Operations Palette (Oceanic Sapphire — Bluish Nature)

```css
/* Surface Tokens */
--color-bg-primary: #080d1a;    /* Deep Midnight Navy canvas */
--color-bg-secondary: #111d38;  /* Elevated cards, tables, modal containers (Sapphire Navy) */
--color-bg-tertiary: #1b2d56;   /* Hover states, active list selections */
--color-bg-subtle: #0d1527;     /* Recessed panels, inset stat cards */

/* Content & Typography Tokens */
--color-text-primary: #f8fafc;  /* 98% contrast crisp white for titles and primary metrics */
--color-text-secondary: #cbd5e1;/* High-readability secondary labels (Soft Blue-Slate) */
--color-text-muted: #8da2c0;    /* Timestamps, auxiliary metadata (Muted Slate-Blue) */
--color-text-inverse: #ffffff;  /* High contrast text on dark blue buttons */

/* Interactive Action Tokens */
--color-action-primary: #2563eb;      /* Main CTA buttons (Cobalt Royal Blue) */
--color-action-primary-hover: #1d4ed8;/* Hover CTA state (Deep Royal Blue) */
--color-action-secondary: #0ea5e9;    /* Secondary actions, navigation accents (Cyan/Sky) */
--color-action-disabled: #334155;     /* Unclickable button background */
--color-border-subtle: #1e325c;       /* Card borders, table dividers (Navy line) */
--color-border-focus: #38bdf8;        /* High-visibility active focus ring (Ice Sky) */

/* Semantic Operational Feedback (Cognitive Offloading) */
--color-status-success: #10b981; /* Completed stops, synced state (Emerald) */
--color-status-warning: #f59e0b; /* Approaching cutoff, 80% capacity (Amber) */
--color-status-critical: #ef4444;/* Overloaded capacity, breakdown (Terracotta) */
--color-status-info: #38bdf8;    /* Planned routes, general updates (Ice Blue) */
```

---

### Retail Palette (Serene Retail — Store Manager)

```css
/* Surface Tokens */
--color-retail-bg-primary: #f8fafc;   /* Fresh canvas (Slate 50) */
--color-retail-bg-secondary: #ffffff; /* Crisp white cards, elevation (Pure White) */
--color-retail-bg-tertiary: #f1f5f9;  /* Table headers, hover rows (Slate 100) */

/* Content Tokens */
--color-retail-text-primary: #0f172a;  /* Deep slate readability */
--color-retail-text-secondary: #475569;/* Subtle description */
--color-retail-text-muted: #64748b;    /* Metadata */

/* Action & Semantic */
--color-retail-action: #0284c7;       /* Calm professional retail blue */
--color-retail-action-hover: #0369a1;
--color-retail-border: #e2e8f0;       /* Border dividers */
--color-retail-badge-fresh: #16a34a;  /* Fresh green brand badge */
--color-retail-badge-style: #9333ea;  /* Style purple brand badge */
--color-retail-badge-tech: #2563eb;   /* Tech blue brand badge */
```

---

### Accessibility Override Palette (Industrial Utility)

```css
--color-a11y-bg: #000000;         /* 100% black */
--color-a11y-surface: #121212;
--color-a11y-text: #ffffff;       /* 100% pure white (21:1 contrast) */
--color-a11y-accent: #ffff00;     /* Pure safety yellow */
--color-a11y-border: #ffffff;     /* 2px solid white boundaries */
--color-a11y-alert: #ff3333;      /* High-intensity red */
```

---

## 2. Typography Hierarchy & Sizing Scale

All typography uses **`rem` units** to ensure graceful scaling when users configure their device accessibility font scale up to 200%.

- **Primary Font Family:** `Inter`, `-apple-system`, `BlinkMacSystemFont`, `Segoe UI`, `Roboto`, `sans-serif`
- **Monospace Font (Timestamps, GPS, IDs):** `JetBrains Mono`, `ui-monospace`, `monospace`

| Style Token | Size (`rem` / `px`) | Line Height | Weight | Usage |
|---|---|---|---|---|
| `font-display-2xl` | `2.25rem` (36px) | `2.5rem` (40px) | `700` Bold | Primary Dispatcher KPI metrics, Critical Crisis Alerts |
| `font-display-xl` | `1.875rem` (30px) | `2.25rem` (36px) | `700` Bold | Store ETA Countdown numbers, Screen Page Titles |
| `font-heading-lg` | `1.5rem` (24px) | `2.0rem` (32px) | `600` SemiBold | Card titles, Modal headers, Bay number titles |
| `font-heading-md` | `1.25rem` (20px) | `1.75rem` (28px) | `600` SemiBold | Stop numbers, Vehicle ID headers, Table section titles |
| `font-body-lg` | `1.125rem` (18px) | `1.625rem` (26px) | `400` / `500` | Driver & Loader button labels, primary form inputs |
| `font-body-md` | `1.0rem` (16px) | `1.5rem` (24px) | `400` Regular | Standard body reading text (Absolute minimum for mobile) |
| `font-caption-sm` | `0.875rem` (14px) | `1.25rem` (20px) | `500` Medium | Secondary labels, status badges, column headers |
| `font-micro-xs` | `0.75rem` (12px) | `1.0rem` (16px) | `500` Medium | Timestamps, vehicle capacity sub-units ($m^3$, $kg$) |

---

## 3. Spacing System & Layout Grid (8-Point Standard)

Every margin, padding, component height, and layout gap is anchored to an **8-point geometric scale**:

- `space-1`: `4px` (Sub-element padding, icon gaps)
- `space-2`: `8px` (Badge padding, compact gaps)
- `space-3`: `12px` (Internal card spacing)
- `space-4`: `16px` (Standard container padding, list item gaps)
- `space-6`: `24px` (Section spacing, desktop grid gutters)
- `space-8`: `32px` (Modal padding, large card separators)
- `space-12`: `48px` (Screen section headers)
- `space-16`: `64px` (Mobile & Tablet minimum touch target height)

### Responsive Breakpoint System

```css
/* Responsive Viewport Standards */
--breakpoint-mobile: 390px;   /* Driver Smartphone (PWA evaluation) */
--breakpoint-tablet: 768px;   /* Loader Rugged Tablet / Store Counter Tablet */
--breakpoint-laptop: 1024px;  /* Store Manager Desktop / Compact Dispatcher */
--breakpoint-desktop: 1440px; /* Standard Dispatcher Workstation */
--breakpoint-wide: 1920px;    /* Peliyagoda Dual-Monitor Control Tower */
```

---

## 4. Frontline Ergonomics & Interaction Standards

### 1. The 64px Fatigue-Resistant Touch Target Rule
- All interactive controls on Mobile (`/field/*`) and Tablet (`/depot/*`) must have a **minimum height of `64px`** and a touch target bounding box of at least `64x64px`.
- Controls must feature an explicit `active:scale-[0.98]` tactile press animation and optional haptic pulse (`navigator.vibrate(20)`).

### 2. Physical Reality Guardrails
- **No Complex Gestures:** Mobile and tablet interfaces prohibit swipe-to-delete, pinch-to-zoom, or multi-finger gestures. All operations use explicit, single-tap buttons.
- **Accidental Trigger Prevention:** Critical actions (such as "Release Vehicle from Gate" or "Abort Route") require a **deliberate 2-second touch-and-hold** or continuous swipe slider.
- **Single-Hand Thumb Zone:** On mobile screens, all primary action buttons are anchored to the bottom `120px` of the viewport (the physiological thumb sweep zone).

### 3. Trilingual UI Layout Buffer
- All UI labels are designed with a **30% horizontal space buffer** to accommodate expanded text length when rendered in Sinhala (සිංහල) or Tamil (தமிழ்).

---

## 5. Component Hierarchy (Atomic Design System)

All Figma designs and frontend code components must strictly follow this 5-tier architecture:

```
Atoms ──────► Molecules ──────► Organisms ──────► Templates ──────► Pages
(Buttons,     (Search+Filter,   (Vehicle Card,    (Split-Pane       (Live Route,
 Badges,       Keypad Input,     LIFO Checklist,   Master Canvas,    Allocation
 Progress)     Status Pill)      PoD Signoff)      Bay Grid)         Dashboard)
```

### Registered Core Components

1. **`ConstraintBar` (Atom):**
   Displays volume/weight utilization. Colors dynamically: `0-80%` Emerald, `81-99%` Amber, `≥100%` Terracotta.
2. **`FatigueButton` (Atom):**
   Full-width, `64px` height, high-contrast borders, bold text, tactile press state.
3. **`StatusBadge` (Molecule):**
   Dual-coded with both unique shape icon AND semantic color for color-blind accessibility.
4. **`SealVerificationKeypad` (Molecule):**
   10-digit high-contrast numeric keypad with tactile clear and confirm buttons.
5. **`LIFOSequenceList` (Organism):**
   Reverse-ordered manifest showing pallets in physical unloading sequence with compartment division.
6. **`OfflineSyncBanner` (Organism):**
   Persistent system bar communicating active connection mode (`Live`, `Degraded`, `Offline`).

---

## 6. The Zero-Fluff & Scannability Directive (Cognitive Density Over Verbosity)

Real enterprise operators, dispatchers, and drivers do not read instructional paragraphs or marketing copy. High visual text density creates ocular fatigue and slow reaction times.

### Core Rules for UI Copy & Information Architecture:
1. **Ban Explanatory Paragraphs:** Never place meta-explanations, disclaimers, competition descriptions, or "how-to" essays inside screen components. The layout and affordances must be immediately intuitive.
2. **Metric & Data-Chip Density:** Replace sentences with scannable badges, progress rings, and data chips. E.g., instead of *"This vehicle is operating near capacity and might incur delay penalties"*, use `94% Vol • +14m ETA`.
3. **Micro-Copy Word Limits:**
   - Action Buttons: Maximum 2 words (`Launch Trip`, `Pass PTI`, `Confirm Seal`).
   - Card Headers: Maximum 3 words (`Reefer Allocation`, `Store Receiving Window`).
   - Status Badges: Maximum 2 words (`Overloaded`, `On Route`, `SLA Alert`).
4. **Visual Anchors Over Text:** Use status dots, icons (`Snowflake`, `Clock`, `Truck`), and color-coded meters instead of verbal descriptions.

