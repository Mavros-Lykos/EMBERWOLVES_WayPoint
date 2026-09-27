# Waypoint Dispatch: Figma & UI Industry Standards
*Master Design System Specification for Designathon & Hackathon*

This document serves as the master instruction guide and design system specification for the Waypoint Dispatch platform. It establishes enterprise-grade design tokens, typography scales, layout rules, and component architectures, deeply tied to the physical realities of our end-users.

---

## 1. Multi-Theme & Persona-Driven Token Architecture

To satisfy the diverse physical environments of our 4 personas, the design system implements a **Dual-Theme Architecture**. We pair the clean, minimalist Serene Light theme with a specialized Night Operations Dark theme to protect our frontline workers.

### Semantic Role Assignment

| Theme Name | Target Personas | Physical Environment | Primary Purpose | Base Background |
|---|---|---|---|---|
| **Serene Oceanic Light** | Store Manager, Execs | Daylight retail counter, well-lit offices | Clean, trustworthy, daylight legibility | `#f8fafc` (Slate 50) |
| **Midnight Sapphire Dark** | Dispatcher, Loader, Driver | Low-light control rooms, night highway, 03:00 AM dark cab | Prevent ocular fatigue, preserve dark-adapted vision | `#0f172a` (Slate 900) |
| **Industrial Utility** *(A11y)* | All (User-toggled) | Direct tropical sun glare, visual impairment | Stark contrast, zero ambiguous shading | `#000000` (Pure Black) |

---

### Serene Oceanic Light Palette (Default / Retail Ops)

```css
/* Surface Tokens */
--color-bg-canvas: #f8fafc;    /* Fresh, calming slate-50 canvas */
--color-bg-glass: rgba(255, 255, 255, 0.7); /* Translucent white glass */
--color-bg-glass-hover: rgba(255, 255, 255, 0.9);

/* Content Tokens */
--color-text-primary: #0f172a;  /* Deep slate readability */
--color-text-secondary: #334155;/* Subtle description */
--color-text-muted: #64748b;    /* Metadata */

/* Action & Semantic */
--color-action-primary: #0ea5e9;       /* Calm Sky Blue CTA */
--color-action-primary-hover: #0284c7;
--color-border-subtle: rgba(0, 0, 0, 0.08); /* Faint structural borders */
```

### Midnight Sapphire Dark Palette (Night Operations)

```css
/* Surface Tokens */
--color-bg-canvas: #0f172a;    /* Deep space slate canvas */
--color-bg-glass: rgba(15, 23, 42, 0.6); /* Translucent dark glass */
--color-bg-glass-hover: rgba(30, 41, 59, 0.8);

/* Content Tokens */
--color-text-primary: #ffffff;  /* High contrast white */
--color-text-secondary: #94a3b8;/* Slate 400 */
--color-text-muted: #64748b;    /* Metadata */

/* Action & Semantic */
--color-action-primary: #0ea5e9;       /* Sky Blue CTA pops on dark bg */
--color-action-primary-hover: #38bdf8;
--color-border-subtle: rgba(255, 255, 255, 0.1); /* Faint light borders */
```

### Shared Semantic Operational Feedback (Both Themes)

```css
--color-status-success: #10b981; /* Completed stops, synced state (Emerald) */
--color-status-warning: #f59e0b; /* Approaching cutoff, 80% capacity (Amber) */
--color-status-critical: #ef4444;/* Overloaded capacity, breakdown (Terracotta) */
```

---

## 2. Typography Hierarchy & Sizing Scale

All typography uses **`rem` units** to ensure graceful scaling.

- **Primary Font Family:** `Inter`, `-apple-system`, `sans-serif`
- **Monospace Font (Timestamps, GPS, IDs):** `JetBrains Mono`, `monospace`

| Style Token | Size (`rem` / `px`) | Line Height | Weight | Usage |
|---|---|---|---|---|
| `font-display-2xl` | `2.25rem` (36px) | `2.5rem` (40px) | `600` SemiBold | Primary KPI metrics, Alerts |
| `font-display-xl` | `1.875rem` (30px) | `2.25rem` (36px) | `600` SemiBold | Store ETA Countdown numbers |
| `font-heading-lg` | `1.5rem` (24px) | `2.0rem` (32px) | `600` SemiBold | Card titles, Modal headers |
| `font-body-lg` | `1.125rem` (18px) | `1.625rem` (26px) | `500` Medium | Driver & Loader button labels |
| `font-caption-sm` | `0.875rem` (14px) | `1.25rem` (20px) | `500` Medium | Secondary labels, status badges |
| `font-micro-xs` | `0.75rem` (12px) | `1.0rem` (16px) | `500` Medium | Timestamps, vehicle capacity |

---

## 3. Spacing System & Layout Grid (8-Point Standard)

Every margin, padding, component height, and layout gap is anchored to an **8-point geometric scale**.

### Responsive Breakpoint System

```css
--breakpoint-mobile: 390px;   /* Driver Smartphone (PWA evaluation) */
--breakpoint-tablet: 768px;   /* Loader Rugged Tablet / Store Counter Tablet */
--breakpoint-desktop: 1440px; /* Standard Dispatcher Workstation */
```

---

## 4. Persona-Specific Ergonomics & Interaction Standards

### 1. Driver & Loader (Fatigue-Resistant UX)
- **64px Touch Target Rule**: All interactive controls on Mobile and Tablet must have a **minimum height of `64px`** to accommodate gloved hands and road vibration.
- **Thumb-Zone Navigation**: Primary actions must anchor to the bottom `120px` of the screen on mobile devices.
- **Hold-to-Confirm**: Destructive or critical actions (like aborting a route) require a 2-second touch-and-hold interaction to prevent accidental misclicks.

### 2. Dispatcher (High-Density Canvas)
- **Exception-First Sorting**: Out of 60 vehicles, the dashboard must suppress "On-Track" data and automatically float delayed or broken-down trucks to the absolute top of the screen.

### 3. Store Manager (Anxiety Reduction)
- **Predictive Countdowns**: Do not show raw ETA times (e.g. `07:45 AM`); instead, always show the delta (e.g. `Arriving in 14 Mins`) to reduce cognitive math.

---

## 5. The Zero-Fluff & Scannability Directive

Real enterprise operators do not read instructional paragraphs.
1. **Ban Explanatory Paragraphs:** Never place meta-explanations inside UI components.
2. **Metric & Data-Chip Density:** Replace sentences with scannable badges (e.g. `94% Vol • +14m ETA`).
3. **Micro-Copy Word Limits:** Action Buttons max 2 words. Card Headers max 3 words.
