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
* **AUTH-01 (Unified Role-Switch Portal)**
  * **Insight:** Hackathon judges and QA testers waste valuable time managing mock credentials to review different user flows, increasing cognitive load.
  * **Decision:** Implement a unified portal with 1-click persona seed injections, completely bypassing traditional login screens for the demo.
  * **Outcome:** Judges experience an instant, frictionless entry directly into the operational reality of all four distinct personas.

### 3.2 System Utilities
* **SYS-01 (Global Navigation Drawer)**
  * **Insight:** Field operators and dispatchers often encounter connectivity drops without realizing it, leading to unsynced data and panic.
  * **Decision:** Anchor an omnipresent drawer operating entirely client-side that surfaces system health, offline cache, and network status instantly.
  * **Outcome:** Users can perform instant network debugging and trust the system's offline queue without losing their current contextual view.

### 3.3 Store Manager Experience (Priya)
* **STORE-01 (Dashboard Hub)**
  * **Insight:** Store Managers are highly stressed balancing floor staff and incoming deliveries; they don't have time to parse complex logistics tables.
  * **Decision:** Center the dashboard entirely around actionable data: the real-time ETA countdown of the next incoming delivery.
  * **Outcome:** Priya can schedule dock receiving staff "just-in-time", drastically reducing idle labor costs and mental anxiety.

* **STORE-02 (Order Canvas)**
  * **Insight:** The 16:00 dispatch cutoff is a hard mathematical constraint, and text-heavy order forms cause input errors when rushing.
  * **Decision:** Create a high-contrast canvas with an unmissable visual cutoff countdown and quick-adjust numeric steppers for ambient/chilled units.
  * **Outcome:** Priya submits accurate orders faster with zero ambiguity about whether she beat the daily deadline.

* **STORE-03 (Active Order Track)**
  * **Insight:** "Where is my stock?" is the highest-anxiety question for a retail manager, especially during peak seasons.
  * **Decision:** Build a serene, low-stress tracking experience with clear map telemetry and deterministic progress bars.
  * **Outcome:** Priya trusts the system's delivery timelines, completely eliminating the need to call Dispatch for updates.

* **STORE-04 (History Log)**
  * **Insight:** When items are missing from a delivery, Store Managers immediately assume an error rather than a deliberate logistical deferral.
  * **Decision:** Surface a multi-day audit trail that explicitly highlights deferred items and attaches the exact business reason for the delay.
  * **Outcome:** Transparency eliminates friction between the store and the warehouse, maintaining systemic trust.

* **STORE-05 (Live Receiving)**
  * **Insight:** Store receiving docks are loud, chaotic, and require operators to wear gloves, making precise screen taps difficult.
  * **Decision:** Implement fatigue-resistant 64px tap targets and force a mandatory physical seal verification step.
  * **Outcome:** Irrefutable chain-of-custody is established instantly without frustrating Priya with small, error-prone touch points.

* **STORE-06 (Damage Dispute)**
  * **Insight:** Disputing damaged goods over phone/email creates massive delays and paper trails.
  * **Decision:** Integrate a direct camera upload mechanism and quick-tap categorization directly into the receiving flow.
  * **Outcome:** Disputes are resolved on the spot with photographic evidence, instantly notifying warehouse inventory and dispatch.

### 3.4 Central Fleet Dispatcher Experience (Kamal)
* **DISP-01 (Capacity Overview)**
  * **Insight:** Dispatchers struggle to manually calculate if current fleet capacity can handle the incoming order volume before the 16:00 cutoff.
  * **Decision:** Aggregate fleet availability and demand into a high-density, bird's-eye view dashboard with aggressive visual indicators.
  * **Outcome:** Kamal can proactively spot and mitigate bottlenecks before they escalate into logistical failures.

* **DISP-02 (Master Allocation)**
  * **Insight:** Assigning loads to vehicles requires complex mental math for cubic volume (m³) and weight (kg) limits, leading to dangerous overloads.
  * **Decision:** Build a dual-pane canvas with live, real-time constraint bars that update with every order assignment.
  * **Outcome:** The system offloads all math. Kamal receives immediate visual feedback, completely preventing physically unviable load assignments.

* **DISP-03 (Route Sequencer)**
  * **Insight:** Trucks loaded in the wrong order force drivers to unpack and repack cargo at the first store, wasting hours.
  * **Decision:** Enforce a strict reverse-LIFO (Last-In, First-Out) route sequencing modal at the dispatch level.
  * **Outcome:** Physical loading at the dock and physical unloading at the store are guaranteed to be logically flawless.

* **DISP-04 (Deferral Governance)**
  * **Insight:** Delaying an order without a reason breaks trust with Store Managers and feels arbitrary.
  * **Decision:** Force Dispatchers to select a concrete business reason (e.g., "Capacity Exceeded", "Vehicle Breakdown") when deferring items.
  * **Outcome:** Deferrals become auditable, governed actions that maintain equity across the retail network.

* **DISP-05 (Live Fleet Tower)**
  * **Insight:** Dispatchers need to monitor dozens of moving vehicles simultaneously without losing situational awareness.
  * **Decision:** Deploy an omnipresent, dark-themed map overlay that renders live fleet telemetry with high-luminance accent colors.
  * **Outcome:** Kamal can proactively spot route deviations and delays at a single glance without ocular fatigue.

* **DISP-06 (Vehicle Telemetry)**
  * **Insight:** Deep-diving into a single truck's temperature or fuel data usually forces a page reload, losing the global fleet view.
  * **Decision:** Surface individual vehicle metrics via a non-intrusive slide-out drawer layered over the map.
  * **Outcome:** Kamal inspects granular cold-chain data while keeping the rest of the moving fleet in his peripheral vision.

* **DISP-07 (Emergency Handoff)**
  * **Insight:** Vehicle breakdowns (Scenario 3) create chaos, requiring rapid reallocation of perishable goods before spoilage.
  * **Decision:** Create a specialized rescue flow that instantly pairs a broken-down vehicle with the nearest available rescue truck.
  * **Outcome:** Kamal resolves emergencies in seconds, salvaging the cold-chain cargo and getting the route back on track.

* **DISP-08 (Crisis Overload)**
  * **Insight:** During extreme festival peaks (Scenario 1), volume vastly exceeds capacity, and manual allocation is impossible.
  * **Decision:** Introduce a dedicated "Crisis Mode" that applies equitable constraint algorithms across all stores to share the shortage fairly.
  * **Outcome:** The system automatically triages the network, ensuring no single store is entirely starved of inventory.

### 3.5 Dock Loader Experience (Nuwan)
* **LOAD-01 (Depot Queue)**
  * **Insight:** Dock loaders often waste time figuring out which truck to load next, leading to bay congestion.
  * **Decision:** Present a high-contrast, aggressively prioritized list of departing trips designed for a rugged dock tablet.
  * **Outcome:** Nuwan knows exactly what to prepare next, eliminating guesswork and accelerating bay turnaround times.

* **LOAD-02 (Trip Inspect)**
  * **Insight:** Loading perishable goods into a hot truck destroys inventory, but loaders often skip manual temperature checks if they are tedious.
  * **Decision:** Enforce a mandatory, tap-driven checklist with massive 64px buttons confirming pre-chilled temperatures before loading begins.
  * **Outcome:** The cold chain is mathematically guaranteed before cargo ever leaves the warehouse floor.

* **LOAD-03 (Active Loading)**
  * **Insight:** Misplaced pallets lead to delivery nightmares, but loaders are moving too fast to read dense manifest text.
  * **Decision:** Utilize a visual, step-by-step checklist that spatially reinforces the reverse-LIFO loading sequence.
  * **Outcome:** Spatial visualization drastically reduces human error, ensuring the last box loaded is the first box delivered.

* **LOAD-04 (Load Exception)**
  * **Insight:** Reporting a damaged pallet mid-load usually requires walking back to a computer terminal, breaking workflow momentum.
  * **Decision:** Build a full-screen, quick-tap modal to rapidly report damaged or missing items directly from the tablet.
  * **Outcome:** Dispatch and the receiving Store are instantly alerted to the inventory change before the truck even leaves the bay.

* **LOAD-05 (Seal Release)**
  * **Insight:** Unsecured cargo is a massive liability, and paper seal logs are easily lost or forged.
  * **Decision:** Establish a final critical software gate that requires the digital logging of the physical bolt seal number.
  * **Outcome:** An irrefutable, timestamped chain of custody is established between the Loader and the Driver.

* **LOAD-06 (Alert Revision)**
  * **Insight:** If Dispatch alters a route while a truck is actively being loaded (Scenario 2), the loader might miss the memo and load the wrong pallets.
  * **Decision:** Trigger an unavoidable, industrial amber lock-out screen that physically halts the tablet UI until the revision is acknowledged.
  * **Outcome:** Mid-load plan revisions are handled safely without any misloaded cargo.

### 3.6 Field Delivery Driver Experience (Saman)
* **DRV-01 (Pre-Trip Inspect)**
  * **Insight:** Drivers are legally and financially responsible for the cargo, but often rush departures.
  * **Decision:** Implement a 100% offline-first, high-contrast screen forcing physical verification of cold-chain temps and seal integrity.
  * **Outcome:** Saman leaves the depot with total confidence in his vehicle and cargo, backed by a digital audit trail.

* **DRV-02 (Active Route)**
  * **Insight:** Mountain corridors (Kadugannawa) have zero network connectivity, making cloud-based routing apps useless.
  * **Decision:** Build a true offline-capable PWA itinerary, stripping away visual fluff in favor of massive, readable typography.
  * **Outcome:** Saman never loses access to his stop sequence or delivery instructions, regardless of cellular dead zones.

* **DRV-03 (Stop Detail)**
  * **Insight:** Drivers need one hand for the steering wheel/door and only have a thumb free for their device as they approach a dock.
  * **Decision:** Design the interface entirely around single-hand thumb zones, prioritizing precise dock approach notes.
  * **Outcome:** Saman safely navigates tricky receiving bays and logs his arrival time with a single tap.

* **DRV-04 (Digital POD)**
  * **Insight:** Capturing signatures on a tiny screen is frustrating, and offline signature data is often lost.
  * **Decision:** Create a streamlined Proof of Delivery (POD) interface with robust 64px touch targets and background Service Worker syncing.
  * **Outcome:** Signatures and seal verifications are captured effortlessly and sync automatically when the truck regains signal.

* **DRV-05 (Stop Exception)**
  * **Insight:** When a dock is blocked or closed, typing out long explanations on a mobile keyboard is infuriating.
  * **Decision:** Offer a fast exception reporting screen with pre-populated, single-tap categorical reasons (e.g., "Dock Blocked").
  * **Outcome:** Saman logs the issue instantly, allowing Dispatch to reroute him without wasting his time.

* **DRV-06 (Rest Break)**
  * **Insight:** Drivers avoid taking mandatory safety breaks if they fear the system will penalize their delivery performance metrics.
  * **Decision:** Introduce a dedicated, non-penalized rest timer screen that explicitly separates labor breaks from active driving time.
  * **Outcome:** Safety compliance increases because Saman knows his performance metrics are protected during rest.

* **DRV-07 (Breakdown)**
  * **Insight:** A vehicle breakdown (Scenario 3) causes immense panic, especially when transporting perishable chilled goods.
  * **Decision:** Deploy an industrial amber alert interface that guides the driver through emergency steps, starts a spoilage timer, and establishes an emergency telemetry pulse.
  * **Outcome:** Panic is replaced with systemic procedure. Dispatch is notified instantly, and the cold-chain cargo is prioritized for rescue.

* **DRV-08 (Trip End)**
  * **Insight:** Reconciling paper logs at the end of a 12-hour shift is exhausting.
  * **Decision:** Provide a clean, automated summary screen that handles offline-to-online data reconciliation in the background.
  * **Outcome:** Saman ends his shift with a clean slate, knowing all liabilities and tasks are officially closed.

