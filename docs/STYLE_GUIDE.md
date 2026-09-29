# Style Guide — Waypoint Dispatch
*Tech-Triathlon 2026 Designathon Submission*

This document records every key design decision made during the Designathon phase, the reasoning behind each choice, and the resulting specifications that govern all 30 screens in the Waypoint Dispatch prototype.

---

## 1. Design System Choice: Why Material Design 3

**Decision:** We adopted Google's Material Design 3 (M3) as our component and token foundation.

**Reasoning:**
- **User technology literacy is unknown.** Our four personas span a 48-year-old dispatcher with 19 years of spreadsheet experience, a 29-year-old warehouse loader with limited English, and a 42-year-old truck driver who primarily uses his phone for calls. We cannot assume familiarity with custom or novel UI patterns. Material Design components (filled buttons, filter chips, navigation rails, badges) are the most widely encountered UI patterns on Android devices across Sri Lanka. Using M3 maximizes recognition and reduces learning curve.
- **Cross-platform consistency.** The system runs on POS terminals (store), dual monitors (dispatch), ruggedized tablets (dock), and personal phones (driver). M3's responsive component system adapts naturally across these form factors without custom breakpoint engineering.
- **Enterprise credibility.** M3 is used by Google Workspace, SAP Fiori adaptations, and internal enterprise tools globally. It communicates "professional enterprise tool" rather than "startup experiment" — appropriate for a logistics platform handling 120 outlets and 60 vehicles daily.

---

## 2. Persona-Based Theme Strategy

### The Decision
All screens use the **M3 Light theme** as the default, with role-specific surface hierarchy adjustments.

### Why Light Theme for All Roles (Not Dark for Operations)

We initially planned a dual-theme approach: dark ("Midnight Oceanic") for drivers, loaders, and night dispatchers; light ("Serene Retail") for store managers. After evaluation, we chose unified M3 Light for the prototype phase because:

1. **Prototype clarity for judges.** Competition judges evaluate the prototype during daytime in well-lit environments. A dark-themed prototype creates unnecessary friction during assessment.
2. **Consistency across the cross-role workflow.** When a driver hands the phone to a store manager for seal verification (DRV-03 → STORE-05 handoff), the theme should not abruptly switch. A unified light base ensures seamless transitions.
3. **Hackathon implementation plan.** During the Hackathon phase, we intend to implement the dark theme variant using M3's built-in dark color scheme support (`prefers-color-scheme: dark` + manual toggle). The token architecture already supports this — every color reference uses `var(--md-sys-color-*)` variables, making the switch a single CSS file change.

### Surface Hierarchy per Role

| Role | Primary Surface | Cards / Elevated | Rationale |
|---|---|---|---|
| Store (Priya) | `surface` (#FFFBFE) | `surface-container-lowest` | Clean, calm, retail-appropriate |
| Dispatch (Kamal) | `surface` with `surface-container` nav-rail | `surface-container-low` for cards | Dense information, clear card boundaries |
| Loader (Nuwan) | `surface` | `surface-container-lowest` with prominent borders | Maximum contrast for gloved tap targets |
| Driver (Saman) | `surface` (phone) | `surface-container-low` for info cards | High legibility on small phone screen |

---

## 3. Accessibility Decisions

### 3.1 Touch Target Sizing

| Context | Minimum Target | Justification |
|---|---|---|
| **Desktop** (Store dashboard, Dispatch) | 44px | Standard WCAG 2.5.5 recommendation for pointer input |
| **Tablet** (Loader dock) | 56–64px | Nuwan wears thick cotton/rubber gloves. 44px targets cause frequent mistaps. |
| **Phone** (Driver cab) | 64px | Truck vibration + single-hand thumb use + road fatigue. 64px is the minimum for reliable one-tap accuracy under these conditions. |

### 3.2 Color-Blind Safety (Dual Encoding)

Operational status is **never conveyed by color alone**. Every state pairs a distinct color with a dedicated icon:

| State | Color | Icon | CSS Variable |
|---|---|---|---|
| Success / Complete | Emerald `#4CAF50` | `check_circle` | `--md-sys-color-success` |
| Warning / At Risk | Amber `#FF9800` | `warning` | `--md-sys-color-warning` |
| Error / Critical | Red `#F44336` | `error` / `car_crash` | `--md-sys-color-error` |
| Info / Neutral | Blue `#2196F3` | `info` | `--md-sys-color-primary` |

This ensures that a user with protanopia (red-green color blindness) can still distinguish "completed delivery" from "overdue delivery" by icon shape alone.

### 3.3 No Pure Red (#FF0000)

We deliberately avoid pure red (`#FF0000`) in all screens. Research on occupational stress shows that pure red triggers elevated heart rates and acute anxiety in fatigued workers operating in high-pressure environments. We use M3's error color (`#BA1A1A` / `#F44336`) which is a muted red that communicates urgency without inducing panic.

### 3.4 Contrast Ratios

All text meets **WCAG 2.1 AA** standards:
- Normal text: minimum 4.5:1 contrast ratio against surface
- Large text (≥18px): minimum 3.0:1 contrast ratio
- Verified using M3's built-in surface/on-surface color pairings

### 3.5 Trilingual Support (English / Sinhala / Tamil)

**Decision:** The prototype is implemented in English. The design accommodates trilingual deployment through:

- **30% width buffer** on all buttons, badges, and table cells. Sinhala (සිංහල) and Tamil (தமிழ்) scripts occupy 25–35% more horizontal space than English. Fixed-width buttons would clip text.
- **Icon-first design.** Every status, action, and category is paired with a Material Symbol icon (e.g., `ac_unit` for Chilled, `local_shipping` for Vehicle, `lock` for Seal). Icons are language-agnostic and allow a Sinhala-primary loader to operate the system by icon recognition alone.
- **Language switcher** designed as a global header component (EN | සිං | த toggle). Not built as a separate screen — embedded in each screen's top bar.

### 3.6 High-Contrast Override

The design guidelines specify a user-togglable **Industrial Utility High-Contrast mode** (pure black background, white text, safety yellow accents) for use in direct tropical sunlight or by users with visual impairments. This is documented as a Hackathon implementation item using CSS `prefers-contrast: more` media query + manual toggle.

---

## 4. Typography System

### Font Choice: Roboto

**Why Roboto:** It is the canonical M3 typeface, pre-installed on all Android devices, and renders crisply at all sizes from 11px label text to 57px display metrics. Since our driver and loader personas use Android devices, Roboto renders without any web font download latency — critical for offline-first PWA performance.

### Type Scale (M3 Standard)

| Token | Size | Line Height | Weight | Usage |
|---|---|---|---|---|
| `display-large` | 57px | 64px | 400 | Crisis counters (e.g., demand deficit %) |
| `display-medium` | 45px | 52px | 400 | KPI hero numbers (ETA, temperature) |
| `display-small` | 36px | 44px | 400 | Timer countdown digits |
| `headline-large` | 32px | 40px | 400 | Page titles (Sign In, Dashboard) |
| `headline-medium` | 28px | 36px | 400 | Section headers |
| `headline-small` | 24px | 32px | 400 | Card titles |
| `title-large` | 22px | 28px | 400 | Top bar titles |
| `title-medium` | 16px | 24px | 500 | List item primary text, button labels |
| `body-large` | 16px | 24px | 400 | Paragraph text, descriptions |
| `body-medium` | 14px | 20px | 400 | Secondary descriptions |
| `body-small` | 12px | 16px | 400 | Captions, metadata |
| `label-large` | 14px | 20px | 500 | Button text, chip labels |
| `label-medium` | 12px | 16px | 500 | Navigation labels, badge text |
| `label-small` | 11px | 16px | 500 | Overline text, timestamps |

All sizes use `rem` units in implementation for graceful scaling when users adjust browser font size.

---

## 5. Color Token Architecture

### M3 Color Scheme

The design system uses M3's semantic color roles, not hardcoded hex values. Every CSS reference uses `var(--md-sys-color-*)` variables.

| Token | Light Value | Purpose |
|---|---|---|
| `--md-sys-color-primary` | `#1A6B52` (Teal) | Primary actions, active navigation indicators |
| `--md-sys-color-on-primary` | `#FFFFFF` | Text/icons on primary-colored surfaces |
| `--md-sys-color-primary-container` | `#A4F4D5` | Subtle primary backgrounds (info banners) |
| `--md-sys-color-surface` | `#FFFBFE` | Page backgrounds |
| `--md-sys-color-surface-container` | `#F0EDEC` | Navigation rails, elevated panels |
| `--md-sys-color-on-surface` | `#1C1B1F` | Primary text |
| `--md-sys-color-on-surface-variant` | `#49454F` | Secondary text, icons |
| `--md-sys-color-outline` | `#79747E` | Borders, dividers |
| `--md-sys-color-outline-variant` | `#CAC4D0` | Subtle dividers |
| `--md-sys-color-error` | `#BA1A1A` | Errors, critical alerts |
| `--md-sys-color-error-container` | `#FFDAD6` | Error banner backgrounds |

### Operational Feedback Colors (Custom Extensions)

| Token | Value | Usage |
|---|---|---|
| `--md-sys-color-success` | `#4CAF50` | Completed stops, passed inspections, synced state |
| `--md-sys-color-warning` | `#FF9800` | Approaching cutoff, 80–95% capacity, rest timer |

---

## 6. Component Naming Convention

All reusable components follow the M3 naming pattern, implemented as CSS classes in `material-tokens.css`:

| Component | CSS Class | Description |
|---|---|---|
| Filled Button | `.md-filled-button` | Primary actions ("Submit Order", "Confirm Loaded") |
| Outlined Button | `.md-outlined-button` | Secondary actions ("Cancel", "Back") |
| Filter Chip | `.md-filter-chip` | Category/status filters ("Chilled", "Today") |
| Badge | `.md-badge` | Status indicators ("En Route", "Deferred") |
| Navigation Rail | `.nav-rail` | 80px vertical nav for desktop roles |
| Top Bar | `.top-bar` | 64px horizontal header with title and controls |
| Mobile Top Bar | `.mobile-top-bar` | Simplified header for phone screens |
| Bottom Navigation | `.bottom-nav` | 72px phone nav bar (Home / Schedule / Profile) |

---

## 7. Layout Patterns per Persona

### Store Manager (Desktop/Tablet)
```
┌──────┬──────────────────────────┐
│ Nav  │ Top Bar (title + cutoff) │
│ Rail │─────────────────────────│
│ 80px │ Content Area             │
│      │ (cards, tables, forms)   │
└──────┴──────────────────────────┘
```

### Dispatcher (Desktop 1920×1080)
```
┌──────┬──────────────────────────┐
│ Nav  │ Top Bar (title + cutoff) │
│ Rail │─────────────────────────│
│ 80px │ Split-Pane Canvas        │
│      │ (orders | vehicles)      │
└──────┴──────────────────────────┘
```

### Loader (Tablet 768px)
```
┌────────────────────────────────┐
│ Top Bar (title + vehicle ID)   │
├────────────────────────────────┤
│ Content (checklist, large btns)│
├────────────────────────────────┤
│ Action Bar (64px button)       │
└────────────────────────────────┘
```

### Driver (Phone 390px)
```
┌────────────────────────────────┐
│ Mobile Top Bar (compact)       │
├────────────────────────────────┤
│ Content (scrollable)           │
├────────────────────────────────┤
│ Bottom Action (64px button)    │
├────────────────────────────────┤
│ Bottom Nav (optional)          │
└────────────────────────────────┘
```

---

## 8. Spacing System

All spacing follows an **8px base grid**:

| Token | Value | Usage |
|---|---|---|
| `4px` | Half unit | Icon-to-label gaps, tight padding |
| `8px` | 1 unit | Chip padding, compact gaps |
| `12px` | 1.5 units | Nav item padding |
| `16px` | 2 units | Card padding, section gaps |
| `24px` | 3 units | Page padding, major section spacing |
| `32px` | 4 units | Hero section margins |

---

## 9. Offline-First Connectivity Indicators

The system displays explicit sync state in the top bar on offline-capable screens (Driver and Loader):

| State | Visual | Meaning |
|---|---|---|
| **Online** | Green dot + "Online" text | WebSocket/HTTP connected, real-time sync |
| **Degraded** | Amber dot + "Polling" text | WebSocket failed, fallback to 15s HTTP polling |
| **Offline** | `cloud_off` icon + "Offline" text | No connectivity. All actions save to IndexedDB locally. Pending changes sync automatically when connection returns. |

Implemented in `drv_01.html` (sync badge in top bar) and referenced across all driver screens.

---

## 10. Zero-Fluff Directive

**Principle:** Real logistics workers scan interfaces in milliseconds. They do not read instructional paragraphs.

| Rule | Implementation |
|---|---|
| No explanatory text in UI | Zero "Welcome to…" messages, zero "This page helps you…" descriptions |
| Metrics over sentences | Status shown as badges (`94% Vol`, `+12m ETA`, `3 Pending`) not prose |
| Button labels: 2 words max | "Submit Order", "Confirm Loaded", "Lock Seal" |
| Card titles: 3 words max | "Fleet Status", "Loading Queue", "Trip Summary" |
| Delta display over raw numbers | Show `+1.8m³ OVER` instead of `Volume: 15.8 / 14.0 m³` |
