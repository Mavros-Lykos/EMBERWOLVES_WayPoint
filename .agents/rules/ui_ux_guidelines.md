---
name: UI/UX Global Design Guidelines
description: Strict UI/UX rules for the Waypoint Dispatch system, enforcing calm design, cognitive offloading, fatigue-resistant interactions, and radical restraint for logistics workers.
---

# UI/UX Global Design Guidelines for Waypoint Dispatch
*Master Human Factors & Interaction Architecture Specification*

**Operational Context:** The Waypoint Dispatch system is a mission-critical logistics platform serving fatigued drivers on rural mountain passes at 03:30 AM, dock loaders working in noisy warehouse bays with gloved hands, stressed dispatchers managing 120 stores at the 16:00 cutoff, and retail store managers juggling customer queues.

Whenever you are writing frontend code (React, Next.js, Tailwind), generating HTML/CSS, or prompting a UI design tool (like Stitch MCP), you MUST adhere strictly to the following 7 core design laws:

---

## Law 1: Psychological Safety & Circadian Protection (Calm UI)
- **Zero Aggressive Pure Reds:** Never use pure red (`#FF0000`). It triggers acute biological stress, elevated heart rates, and panic in fatigued frontline workers. Always use muted terracottas (`#ef4444`) for errors and warm ambers (`#f59e0b`) for operational warnings.
- **Circadian-Aware Theme Defaulting:**
  - Operations personnel (Dispatcher, Loader, Driver) operate primarily between 10:00 PM and 06:00 AM. Their interfaces must default to **Oceanic Sapphire** (`#080d1a` / `#111d38` deep blue), preserving dark-adapted vision, delivering calm bluish aesthetics, and preventing ocular fatigue.
  - Retail Store Managers operate during daylight store hours; their interface defaults to **Serene Retail** (`#f8fafc`).
- **Low-Noise Surfaces:** Surfaces must be matte and calm. Avoid distracting decorative drop shadows, glassmorphism blurs that hinder readability, or unnecessary visual ornamentation.

---

## Law 2: Cognitive Offloading (Never Make Users Calculate)
- **Always Display the Delta:** Frontline personnel making time-sensitive decisions must never perform mental arithmetic.
  - *Bad:* Displaying `Weight: 3,420kg / 3,000kg`.
  - *Good:* Displaying a high-visibility badge: `+420kg OVERLOADED (Exceeds Truck Capacity)`.
  - *Bad:* Displaying planned arrival `07:45 AM` and current time `07:38 AM`.
  - *Good:* Displaying `7 mins remaining to 08:00 AM Fresh Opening Window`.
- **Exception-First Sorting (Float the Problem):** In lists of 60 vehicles or 120 stores, normal "on-track" statuses must visually recede into the background. The single vehicle experiencing a mechanical delay or reefer temperature rise must automatically float to the top with a distinctive badge.

---

## Law 3: Fatigue-Resistant Frontline Ergonomics
- **The 64px Touch Target Rule:** All interactive controls for Drivers (smartphones) and Loaders (tablets) must have a **minimum touch height of `64px`**. This accommodates thick cotton/rubber warehouse gloves, trembling hands, and road vibration.
- **No Complex Motor Gestures:** Prohibit multi-finger pinches, delicate swipe-to-delete gestures, or small sliders on mobile. Every critical action must be an explicit, high-contrast tap or a deliberate 2-second hold-to-confirm.
- **Single-Hand Thumb-Zone Layout:** On phone-sized screens, all primary action triggers ("Mark Arrived", "Verify Seal", "Pass PTI") must be anchored in the bottom `120px` thumb zone.

---

## Law 4: Radical Restraint & Minimalism (Strictly Judged 15%)
The competition briefing emphasizes: *"A tightly scoped solution with clear rationale will outscore a sprawling one. Restraint is a judged criterion."*
- **Absolute Ban on Feature Bloat:** Under no circumstances should the system include in-app chat systems, driver social feeds, payroll calculators, or generic multi-tier inventory editors.
- **Single-Cycle Operational Focus:** Every screen, button, and metric must directly serve the 6-stage core cycle:
  `Order (Store) -> Allocate (Dispatcher) -> Sequence & Load (Loader) -> Deliver & PoD (Driver) -> Confirm Receipt (Store) -> Predict (Datathon)`.

---

## Law 5: Offline-First Reliability & Optimistic Feedback
- **Zero Blocking Spinners:** The mobile PWA must never lock the screen behind a blocking loading spinner during core actions. All inputs (PoD, Seal ID, Checkbox, Defect) write immediately to local IndexedDB with optimistic UI state changes.
- **Explicit Connection Modes:** The global navigation header must always display the active sync state:
  - `Online (WebSocket)` — Green Pulse
  - `Degraded (Polling 15s)` — Cyan Indicator
  - `Offline (Cached Locally - X Pending Syncs)` — Slate/Teal Badge

---

## Law 6: Trilingual Accessibility & Localization Buffers
- **Trilingual Parity:** The system must natively support English, Sinhala (සිංහල), and Tamil (தமிழ்).
- **30% UI Expansion Buffer:** Sinhala and Tamil script scripts occupy approximately 25-35% more horizontal width than English. All buttons, table cells, and badges must incorporate a 30% width buffer to prevent text clipping or awkward wraps.
- **Icon-First Legibility:** Every status, action, and category must pair text with a globally recognized icon (e.g., Snowflake for Chilled, Clock for Delivery Window, Shield for Tamper Seal).

---

## Law 7: Color-Blind Safety & WCAG 2.1 AA Compliance
- **Dual-Encoded Semantics:** Never convey operational state through color alone. Every badge and alert must combine a distinct color with a dedicated shape/icon:
  - Success: Emerald Green `#10b981` + Checkmark Circle
  - Warning: Amber `#f59e0b` + Warning Triangle
  - Critical: Terracotta `#ef4444` + Octagon / X-Circle
- **Contrast Ratios:** Text on all surfaces must maintain a minimum contrast ratio of `4.5:1` (normal text) and `3.0:1` (large display metrics).
- **Industrial Utility High-Contrast Override:** Provide a 1-tap global toggle that replaces subtle background hues with pure black (`#000000`), white text (`#FFFFFF`), and safety yellow accents (`#FFFF00`) for high-glare tropical sunlight.

---

## Law 8: The Zero-Fluff & Scannability Directive (Cognitive Density Over Verbosity)
- **Zero Narrative / Explanatory Paragraphs:** Never display walls of instructional text, competition disclaimers, or descriptive commentary inside UI components. Real enterprise dispatch systems are scanned in milliseconds.
- **Metric-Driven Visual Affordance:** Replace sentences with data chips, status dots, numerical counters, and delta badges (e.g. `94% Cap • +12m ETA` instead of three lines of text explaining the risk).
- **Strict Micro-Copy Caps:**
  - Card Titles: 2–3 words maximum.
  - Buttons: 1–2 words maximum.
  - Badges/Pills: 1–2 words maximum.
  - Confirmation modals / tooltips: 1 concise line maximum.

