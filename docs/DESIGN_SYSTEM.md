---
name: Waypoint Dispatch Operations System
colors:
  surface: '#0f172a'
  surface-dim: '#0f172a'
  surface-bright: '#1e293b'
  surface-container-lowest: '#080d1a'
  surface-container-low: '#0f172a'
  surface-container: '#1e293b'
  surface-container-high: '#1e293b'
  surface-container-highest: '#334155'
  on-surface: '#f8fafc'
  on-surface-variant: '#cbd5e1'
  inverse-surface: '#f8fafc'
  inverse-on-surface: '#0f172a'
  outline: '#475569'
  outline-variant: '#334155'
  surface-tint: '#14b8a6'
  primary: '#14b8a6'
  on-primary: '#020617'
  primary-container: '#0d9488'
  on-primary-container: '#ccfbf1'
  inverse-primary: '#5eead4'
  secondary: '#38bdf8'
  on-secondary: '#082f49'
  secondary-container: '#0284c7'
  on-secondary-container: '#e0f2fe'
  tertiary: '#f59e0b'
  on-tertiary: '#451a03'
  tertiary-container: '#d97706'
  on-tertiary-container: '#fef3c7'
  error: '#ef4444'
  on-error: '#450a0a'
  error-container: '#b91c1c'
  on-error-container: '#fee2e2'
  primary-fixed: '#5eead4'
  primary-fixed-dim: '#14b8a6'
  on-primary-fixed: '#042f2e'
  on-primary-fixed-variant: '#134e4a'
  secondary-fixed: '#bae6fd'
  secondary-fixed-dim: '#38bdf8'
  on-secondary-fixed: '#0c4a6e'
  on-secondary-fixed-variant: '#075985'
  tertiary-fixed: '#fde68a'
  tertiary-fixed-dim: '#f59e0b'
  on-tertiary-fixed: '#78350f'
  on-tertiary-fixed-variant: '#92400e'
  background: '#0f172a'
  on-background: '#f8fafc'
  surface-variant: '#1e293b'
typography:
  headline-xl:
    fontFamily: Inter
    fontSize: 32px
    fontWeight: '700'
    lineHeight: 40px
  headline-lg:
    fontFamily: Inter
    fontSize: 24px
    fontWeight: '600'
    lineHeight: 32px
  headline-md:
    fontFamily: Inter
    fontSize: 20px
    fontWeight: '600'
    lineHeight: 28px
  headline-sm:
    fontFamily: Inter
    fontSize: 16px
    fontWeight: '600'
    lineHeight: 24px
  body-lg:
    fontFamily: Inter
    fontSize: 16px
    fontWeight: '400'
    lineHeight: 24px
  body-md:
    fontFamily: Inter
    fontSize: 14px
    fontWeight: '400'
    lineHeight: 20px
  body-sm:
    fontFamily: Inter
    fontSize: 12px
    fontWeight: '400'
    lineHeight: 16px
  label-lg:
    fontFamily: JetBrains Mono
    fontSize: 14px
    fontWeight: '500'
    lineHeight: 20px
  label-md:
    fontFamily: JetBrains Mono
    fontSize: 12px
    fontWeight: '500'
    lineHeight: 16px
  label-sm:
    fontFamily: JetBrains Mono
    fontSize: 11px
    fontWeight: '500'
    lineHeight: 14px
rounded:
  sm: 0.125rem
  DEFAULT: 0.25rem
  md: 0.375rem
  lg: 0.5rem
  xl: 0.75rem
  full: 9999px
spacing:
  gutter: 1rem
  margin: 1rem
  space-sm: 0.5rem
  space-md: 1rem
  space-lg: 1.5rem
  space-xl: 2rem
---

# Waypoint Dispatch - Professional Design System & Screen Directory

## 1. Core Principles
* **Cognitive Offloading:** System calculates Deltas, constraints, and sequences. No mental math.
* **Anxiety Reduction:** Calm UI, transparent rules, real-time map telemetry, explicit error boundaries.
* **Fatigue-Resistant UX:** Tap-only field interfaces, massive 64px touch targets, high contrast, offline-first reliability.

## 2. Design Tokens & Foundations

### 2.1 Color Palette
**Midnight Oceanic (Dark Theme - Dispatch, Loader, Driver)**
* Primary: #4fc3f7 (Oceanic Blue)
* Surface Container: #111d38 (Deep Sapphire)
* Surface Lowest: #080d1a (Void)
* Error / Alert: #ffb4ab (Industrial Amber)

**Serene Retail (Light Theme - Store Manager)**
* Primary: #00639b (Professional Blue)
* Surface: #f8fafc (Clean Slate)
* Surface Container: #e2e8f0 (Soft Gray)

### 2.2 Typography
* **Primary Font (UI & Headers):** Roboto (Weights: 400, 500, 600)
* **Secondary Font (Data & Metrics):** Roboto Mono (Weights: 400, 500)
* **Scale:** H1 (Title Large) to Body Small, strictly adhering to Material Design 3 type scales.

### 2.3 Component Standards
* **Touch Targets:** Minimum 64x64px for all mobile (Driver/Loader) interactive elements.
* **Badges:** Absolute state indicators (e.g., \Online\, \En Route\, \Draft\).
* **Nav-Rails:** Used for tablet/desktop (Dispatch/Store) for ergonomic edge-of-screen targeting.

---

## 3. Screen Directory & Rationales

### 3.1 Authentication
* **AUTH-01 (Unified Role-Switch Portal):** Designed as a unified portal with 1-click persona seed injections specifically for the hackathon judges, bypassing tedious credential entry. Reduces cognitive load and instantly drops the user into the operational reality of the specific persona.

### 3.2 System Utilities
* **SYS-01 (Global Navigation Drawer):** An omnipresent drawer operating entirely client-side, ensuring system health, network connectivity, and offline cache status are one tap away. Empowers instant network debugging without losing contextual view.

### 3.3 Store Manager Experience (Priya)
* **STORE-01 (Dashboard Hub):** Focuses on immediately actionable data—specifically the real-time ETA of incoming deliveries. This cognitive offloading allows scheduling of dock staff just-in-time, reducing idle labor costs.
* **STORE-02 (Order Canvas):** An intuitive, high-contrast canvas accommodating ambient and chilled ordering with unmissable visual cues for the 16:00 cutoff. Uses quick-adjust numeric steppers instead of text-heavy forms.
* **STORE-03 (Active Order Track):** Built with clear, real-time map telemetry and progress bars, offering a serene, low-stress tracking experience. Removes the anxiety of "where is my stock?"
* **STORE-04 (History Log):** A multi-day audit trail explicitly highlighting deferred orders. Provides transparency on why stock was delayed without cluttering the main hub.
* **STORE-05 (Live Receiving):** Uses fatigue-resistant 64px tap targets for a busy dock environment. Forces a mandatory physical seal verification, guaranteeing chain of custody.
* **STORE-06 (Damage Dispute):** Incorporates a direct photo-upload mechanism and quick-tap categorizations to resolve delivery disputes on the spot, backed by evidence.

### 3.4 Central Fleet Dispatcher Experience (Kamal)
* **DISP-01 (Capacity Overview):** A high-density dashboard aggressively surfacing the 16:00 cutoff timer and fleet availability. Provides a bird's-eye view of capacity versus demand to anticipate bottlenecks.
* **DISP-02 (Master Allocation):** Features dual-pane capability with live volumetric and weight constraint bars. Translates complex mathematical limits into immediate visual feedback, preventing unviable load assignments.
* **DISP-03 (Route Sequencer):** A centralized modal explicitly enforcing reverse-LIFO (Last-In, First-Out) route sequencing. Offloads sequence logic to ensure physical unloading at store docks is logically sound.
* **DISP-04 (Deferral Governance):** A specialized governance view forcing Kamal to select concrete business reasons (e.g., "Capacity") for delaying orders, maintaining trust and equity.
* **DISP-05 (Live Fleet Tower):** A live control tower with an omnipresent map overlay, rendering fleet movement. Centralizes telemetry to proactively spot deviations.
* **DISP-06 (Vehicle Telemetry):** A non-intrusive flyout drawer surfacing real-time vehicle telemetry (temperature, fuel) without losing situational awareness of the broader fleet map.
* **DISP-07 (Emergency Handoff):** Engineered for high-stress scenarios (Scenario 3), enabling swift reallocation of compromised cargo from a broken-down vehicle to a rescue truck.
* **DISP-08 (Crisis Overload):** A dedicated "Crisis Mode" (Scenario 1) visualizing systemic overload and applying equitable constraint algorithms across all stores during festival peaks.

### 3.5 Dock Loader Experience (Nuwan)
* **LOAD-01 (Depot Queue):** A high-contrast, prioritized list of departing trips designed for a dock tablet. Eliminates guesswork by indicating exactly what to prepare next.
* **LOAD-02 (Trip Inspect):** A mandatory, tap-driven checklist with massive 64px buttons for glove compatibility. Validates vehicle readiness (e.g., pre-chilled temperature) before cargo moves.
* **LOAD-03 (Active Loading):** Utilizes a step-by-step checklist reinforcing the reverse-LIFO loading sequence. Spatial visualization drastically reduces human error.
* **LOAD-04 (Load Exception):** A full-screen modal to rapidly report damaged/missing items from the bay. Instantly alerts Dispatch and Store before the truck leaves.
* **LOAD-05 (Seal Release):** The final critical gate requiring the digital locking of the physical bolt seal number, establishing an irrefutable chain of custody.
* **LOAD-06 (Alert Revision):** An unavoidable, industrial amber lock-out screen (Scenario 2) that physically halts loading if Dispatch alters the plan mid-process.

### 3.6 Field Delivery Driver Experience (Saman)
* **DRV-01 (Pre-Trip Inspect):** A 100% offline-first, high-contrast screen forcing physical verification of cold-chain temps and vehicle state before departure.
* **DRV-02 (Active Route):** An offline-capable itinerary removing visual fluff for massive typography. Guarantees access to stop sequences in zero-connectivity areas.
* **DRV-03 (Stop Detail):** Built with single-hand thumb zones. Provides precise dock approach notes and instantly logs arrival times.
* **DRV-04 (Digital POD):** A streamlined digital signature and seal-verification interface with robust 64px touch targets. Seamless offline-to-online background syncing.
* **DRV-05 (Stop Exception):** A fast exception reporting screen to log blocked docks with a single tap. Removes the frustration of typing explanations.
* **DRV-06 (Rest Break):** A non-penalized rest timer screen separating mandatory labor breaks from driving time, ensuring performance metrics aren't negatively impacted.
* **DRV-07 (Breakdown):** An industrial amber alert interface (Scenario 3) providing guided steps during a breakdown. Starts a spoilage timer and establishes an emergency telemetry pulse.
* **DRV-08 (Trip End):** A clean summary screen handling offline-to-online data reconciliation in the background, ending shift liability cleanly.

