# Waypoint Dispatch: Professional Figma Handoff Checklist

This checklist defines exactly how the final Figma file should be structured for the hackathon judges. It ensures that the design file perfectly matches the HTML prototypes we built, reflecting the exact design tokens, components, and degradation scenarios.

## 1. Document Structure & Pages
Your Figma file should have the following distinct pages in the left sidebar:
* **[ 📖 ] Cover & Index:** A clean cover thumbnail and a link to `COMPREHENSIVE_SITEMAP_NEW.md`.
* **[ 🎨 ] Foundations (Tokens):** Explicitly documenting the colors and typography.
* **[ 🧩 ] Component Library:** All reusable elements with their states.
* **[ 💻 ] Screen Flows (Primary):** The happy-path flows for all 4 personas.
* **[ 🚨 ] Degradation Scenarios:** The specific flows proving our 3 scenario architectures.

---

## 2. Foundations (Tokens)
These must exactly match the CSS variables implemented in our HTML prototype (`index.css`):

### Color Palettes
* **Midnight Oceanic (Dark Mode)**
  * `Surface Base`: `#0f172a` (Slate 900)
  * `Surface Container`: `#1e293b` (Slate 800)
  * `Primary Accent`: `#14b8a6` (Teal 500)
  * `Error/Alert`: `#ef4444` (Red 500) & `#f59e0b` (Amber 500 for Industrial Alerts)
* **Serene Retail (Light Mode)**
  * `Surface Base`: `#f8fafc` (Slate 50)
  * `Surface Container`: `#ffffff` (White)
  * `Primary Accent`: `#0ea5e9` (Sky 500)

### Typography
* **Primary (UI/Headings):** `Roboto`
* **Secondary (Data/Metrics):** `Roboto Mono` (Used for ETA countdowns, volume/weight limits, and seal numbers).

---

## 3. Component Library
Figma components must use "Variants" to show the exact states we built in the code:
* **Interactive Touch Targets:** Document the strict `64x64px` minimum size rule for the Driver/Loader personas (Fatigue-Resistant UX).
* **Buttons:** Show `Default`, `:hover`, and `:active` (scale-down) states.
* **Sync Badges:** Create a component for the `Online` (Green) and `Offline` (Amber) pills with the pulsating dot animation variant.
* **Data Cards:** Show the subtle `box-shadow` elevation we implemented on `:hover`.

---

## 4. Primary Screen Flows (The 30 Screens)
Organize the frames logically left-to-right, matching `COMPREHENSIVE_SITEMAP_NEW.md`:
1. **Global Auth:** Show `AUTH-01` branching into the 4 personas.
2. **Store Manager (Light Mode):** `STORE-01` through `STORE-06`.
3. **Dispatcher (Dark Mode, Desktop):** `DISP-01` through `DISP-08`. Highlight the drag-and-drop allocation canvas.
4. **Dock Loader (Dark Mode, Tablet):** `LOAD-01` through `LOAD-06`. Highlight the reverse-LIFO checklist.
5. **Driver (Dark Mode, Mobile):** `DRV-01` through `DRV-08`. Highlight the offline-first warnings.

---

## 5. Degradation Scenarios (Crucial for Judges)
Create dedicated prototyping flows (using Figma's Play button) for the 3 scenarios we focused on:
* **Scenario 1 (Crisis Overload):** Show Kamal navigating to `DISP-08` and activating the algorithmic constraint mode.
* **Scenario 2 (Mid-Load Alert):** Show the transition on Nuwan's tablet (`LOAD-03`) when it gets violently interrupted by the amber lockout screen (`LOAD-06`).
* **Scenario 3 (Emergency Breakdown):** Show Saman hitting the panic button (`DRV-07`), which sends an immediate alert modal to Kamal's control tower (`DISP-07`).

---

## 6. Developer Annotations
Use a bright magenta sticky-note component in Figma to leave notes for the judges explaining the invisible tech:
* **Cognitive Offloading:** Note where mental math was removed (e.g., the capacity progress bars in `DISP-02`).
* **Service Workers:** Drop a sticky note on `DRV-02` explaining that the map and itinerary are cached via IndexedDB for offline access.
* **Semantic HTML:** Note that the navigation utilizes `<nav>` and `<aside>` for A11y compliance.
