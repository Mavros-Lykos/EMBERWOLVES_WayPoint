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

#### AUTH-01 (Unified Role-Switch Portal)
* **Executive Summary:**
  * **Insight:** QA testers and stakeholders waste valuable time managing mock credentials to review different user flows, increasing cognitive load.
  * **Decision:** Implement a unified portal with 1-click persona seed injections, completely bypassing traditional login screens for internal/demo use.
  * **Outcome:** Users experience an instant, frictionless entry directly into the operational reality of all four distinct personas.
* **User Flow & Triggers:** Entry point for the application. Upon selecting a persona, the app routes directly to the respective persona's default dashboard (STORE-01, DISP-01, LOAD-01, or DRV-01).
* **Business Logic & Data:** Injects a mock JWT token and populates the global Redux/Context state with the selected persona's permissions and mock dataset.
* **Offline & Edge Cases:** Requires network for initial bundle load, but seed injection happens entirely client-side.
* **UI & Token Mapping:** Uses `bg-canvas` for the background. The four persona buttons utilize prominent `action-primary` styles with generous 64px tap heights.

### 3.2 System Utilities

#### SYS-01 (Global Navigation Drawer)
* **Executive Summary:**
  * **Insight:** Field operators and dispatchers often encounter connectivity drops without realizing it, leading to unsynced data and panic.
  * **Decision:** Anchor an omnipresent drawer operating entirely client-side that surfaces system health, offline cache, and network status instantly.
  * **Outcome:** Users can perform instant network debugging and trust the system's offline queue without losing their current contextual view.
* **User Flow & Triggers:** Accessed via the hamburger menu/avatar on any screen. Can be dismissed via tapping outside the drawer.
* **Business Logic & Data:** Reads `navigator.onLine` and polls the `/health` endpoint. Reads IndexedDB to display the count of pending offline mutations.
* **Offline & Edge Cases:** If offline, the drawer indicator turns `status-critical` (Red). Allows the user to manually trigger a "Sync Now" retry when the connection returns.
* **UI & Token Mapping:** Drawer background uses `surface-container-highest` with a high-elevation drop shadow to sit above the main UI.

### 3.3 Store Manager Experience (Priya)

#### STORE-01 (Dashboard Hub)
* **Executive Summary:**
  * **Insight:** Store Managers are highly stressed balancing floor staff and incoming deliveries; they don't have time to parse complex logistics tables.
  * **Decision:** Center the dashboard entirely around actionable data: the real-time ETA countdown of the next incoming delivery.
  * **Outcome:** Priya can schedule dock receiving staff "just-in-time", drastically reducing idle labor costs and mental anxiety.
* **User Flow & Triggers:** Default landing page for the Store Manager persona. Leads to `STORE-02` (Ordering) or `STORE-05` (Receiving).
* **Business Logic & Data:** Subscribes to WebSocket updates for active vehicle ETAs assigned to this specific store ID.
* **Offline & Edge Cases:** If the WebSocket disconnects, the ETA timer pauses and a subtle `status-warning` banner appears stating "Live updates paused".
* **UI & Token Mapping:** Employs the Light Mode "Serene Retail" theme. The ETA countdown uses `headline-xl` typography in `text-primary`.

#### STORE-02 (Order Canvas)
* **Executive Summary:**
  * **Insight:** The 16:00 dispatch cutoff is a hard mathematical constraint, and text-heavy order forms cause input errors when rushing.
  * **Decision:** Create a high-contrast canvas with an unmissable visual cutoff countdown and quick-adjust numeric steppers for ambient/chilled units.
  * **Outcome:** Priya submits accurate orders faster with zero ambiguity about whether she beat the daily deadline.
* **User Flow & Triggers:** Accessed from `STORE-01`. Upon submission, routes back to `STORE-01` with a success toast.
* **Business Logic & Data:** Validates current server time against the 16:00 cutoff. Calculates total volume (m³) locally as steppers increase.
* **Offline & Edge Cases:** If submitted exactly at 16:00:00, the server determines the tie-break. If offline, the submit button is disabled to prevent false expectations.
* **UI & Token Mapping:** Numeric steppers utilize 64px `touch-target-min`. The countdown timer pulses `status-warning` when under 30 minutes remain.

#### STORE-03 (Active Order Track)
* **Executive Summary:**
  * **Insight:** "Where is my stock?" is the highest-anxiety question for a retail manager, especially during peak seasons.
  * **Decision:** Build a serene, low-stress tracking experience with clear map telemetry and deterministic progress bars.
  * **Outcome:** Priya trusts the system's delivery timelines, completely eliminating the need to call Dispatch for updates.
* **User Flow & Triggers:** Clicked from a specific active order on the Dashboard.
* **Business Logic & Data:** Fetches coordinates from the Dispatch telemetry API. Translates lat/long into a visual map polyline.
* **Offline & Edge Cases:** Map gracefully degrades to a step-based progress bar if map tiles fail to load or connection is spotty.
* **UI & Token Mapping:** Map container utilizes `rounded-lg` with a `border-subtle` stroke.

#### STORE-04 (History Log)
* **Executive Summary:**
  * **Insight:** When items are missing from a delivery, Store Managers immediately assume an error rather than a deliberate logistical deferral.
  * **Decision:** Surface a multi-day audit trail that explicitly highlights deferred items and attaches the exact business reason for the delay.
  * **Outcome:** Transparency eliminates friction between the store and the warehouse, maintaining systemic trust.
* **User Flow & Triggers:** Accessed via the secondary nav rail.
* **Business Logic & Data:** Fetches past 7 days of orders. Filters by status (`DELIVERED`, `DEFERRED`, `CANCELLED`).
* **Offline & Edge Cases:** Read-only data; safely cached in IndexedDB for fast retrieval.
* **UI & Token Mapping:** Uses a dense data table layout with `body-sm` typography. Deferred items use a `bg-warning-subtle` highlight row.

#### STORE-05 (Live Receiving)
* **Executive Summary:**
  * **Insight:** Store receiving docks are loud, chaotic, and require operators to wear gloves, making precise screen taps difficult.
  * **Decision:** Implement fatigue-resistant 64px tap targets and force a mandatory physical seal verification step.
  * **Outcome:** Irrefutable chain-of-custody is established instantly without frustrating Priya with small, error-prone touch points.
* **User Flow & Triggers:** Triggered when the Driver (`DRV-03`) logs arrival at the store.
* **Business Logic & Data:** Requires input of the physical lock seal string. Matches string against the Dispatch manifest hash.
* **Offline & Edge Cases:** Seal verification can happen offline via a downloaded daily manifest hash.
* **UI & Token Mapping:** Primary action button is massive (72px height) using `action-primary` color to accommodate gloved taps.

#### STORE-06 (Damage Dispute)
* **Executive Summary:**
  * **Insight:** Disputing damaged goods over phone/email creates massive delays and paper trails.
  * **Decision:** Integrate a direct camera upload mechanism and quick-tap categorization directly into the receiving flow.
  * **Outcome:** Disputes are resolved on the spot with photographic evidence, instantly notifying warehouse inventory and dispatch.
* **User Flow & Triggers:** Launched from `STORE-05` if a pallet is marked as "Damaged".
* **Business Logic & Data:** Accesses native device camera API. Compresses image client-side before upload to reduce payload.
* **Offline & Edge Cases:** Images are stored as base64 in IndexedDB if the dock has poor WiFi, syncing in the background later.
* **UI & Token Mapping:** Camera viewfinder occupies 60% of the screen. Upload button uses a heavy `status-critical` styling to indicate dispute severity.

### 3.4 Central Fleet Dispatcher Experience (Kamal)

#### DISP-01 (Capacity Overview)
* **Executive Summary:**
  * **Insight:** Dispatchers struggle to manually calculate if current fleet capacity can handle the incoming order volume before the 16:00 cutoff.
  * **Decision:** Aggregate fleet availability and demand into a high-density, bird's-eye view dashboard with aggressive visual indicators.
  * **Outcome:** Kamal can proactively spot and mitigate bottlenecks before they escalate into logistical failures.
* **User Flow & Triggers:** Default landing page for Kamal. Updates live as 16:00 approaches.
* **Business Logic & Data:** Aggregates total M³ and KG ordered against total M³ and KG of active fleet.
* **Offline & Edge Cases:** Heavily relies on WebSocket. Shows a massive "DATA STALE" warning if connection drops for > 10 seconds.
* **UI & Token Mapping:** Dark Mode "Midnight Sapphire". Constraint bars use gradients from `action-primary` (safe) to `status-critical` (overload).

#### DISP-02 (Master Allocation)
* **Executive Summary:**
  * **Insight:** Assigning loads to vehicles requires complex mental math for cubic volume (m³) and weight (kg) limits, leading to dangerous overloads.
  * **Decision:** Build a dual-pane drag-and-drop canvas with live, real-time constraint bars that update with every order assignment.
  * **Outcome:** The system offloads all math. Kamal receives immediate visual feedback, completely preventing physically unviable load assignments.
* **User Flow & Triggers:** Accessed after the 16:00 cutoff to begin load planning.
* **Business Logic & Data:** Prevents drag-and-drop if the target vehicle's capacity threshold (weight or volume) is exceeded. 
* **Offline & Edge Cases:** Requires strict optimistic UI updates to prevent drag lag, syncing to the server asynchronously.
* **UI & Token Mapping:** The dual panes use `surface-container-low` with a 1px `border-subtle` divider.

#### DISP-03 (Route Sequencer)
* **Executive Summary:**
  * **Insight:** Trucks loaded in the wrong order force drivers to unpack and repack cargo at the first store, wasting hours.
  * **Decision:** Enforce a strict reverse-LIFO (Last-In, First-Out) route sequencing modal at the dispatch level.
  * **Outcome:** Physical loading at the dock and physical unloading at the store are guaranteed to be logically flawless.
* **User Flow & Triggers:** Triggered when finalizing a vehicle's load in `DISP-02`.
* **Business Logic & Data:** Validates the geographical route sequence against the physical pallet stacking logic in the vehicle bay.
* **Offline & Edge Cases:** If Google Maps Routing API fails, defaults to a manual sequence override.
* **UI & Token Mapping:** Uses a vertical timeline component. Sequence numbers are bold `label-lg` badges.

#### DISP-04 (Deferral Governance)
* **Executive Summary:**
  * **Insight:** Delaying an order without a reason breaks trust with Store Managers and feels arbitrary.
  * **Decision:** Force Dispatchers to select a concrete business reason (e.g., "Capacity Exceeded", "Vehicle Breakdown") when deferring items.
  * **Outcome:** Deferrals become auditable, governed actions that maintain equity across the retail network.
* **User Flow & Triggers:** Appears as a blocking modal when Kamal drags an order into the "Deferred" column.
* **Business Logic & Data:** Submits a `reasonCode` payload to the backend which triggers an immediate push notification to the affected Store Manager (`STORE-04`).
* **Offline & Edge Cases:** Modal cannot be dismissed without selecting a reason.
* **UI & Token Mapping:** Modal uses `bg-glass` over a blurred backdrop to force focus.

#### DISP-05 (Live Fleet Tower)
* **Executive Summary:**
  * **Insight:** Dispatchers need to monitor dozens of moving vehicles simultaneously without losing situational awareness.
  * **Decision:** Deploy an omnipresent, dark-themed map overlay that renders live fleet telemetry with high-luminance accent colors.
  * **Outcome:** Kamal can proactively spot route deviations and delays at a single glance without ocular fatigue.
* **User Flow & Triggers:** Accessed via the primary navigation rail.
* **Business Logic & Data:** Connects to GPS telemetry stream. Renders dozens of markers using WebGL/Canvas for performance.
* **Offline & Edge Cases:** If GPS data is stale, vehicle markers fade to 50% opacity and turn gray.
* **UI & Token Mapping:** Custom dark map tiles to match `bg-canvas`. Vehicle markers use `action-primary` and `status-warning`.

#### DISP-06 (Vehicle Telemetry)
* **Executive Summary:**
  * **Insight:** Deep-diving into a single truck's temperature or fuel data usually forces a page reload, losing the global fleet view.
  * **Decision:** Surface individual vehicle metrics via a non-intrusive slide-out drawer layered over the map.
  * **Outcome:** Kamal inspects granular cold-chain data while keeping the rest of the moving fleet in his peripheral vision.
* **User Flow & Triggers:** Tapping a vehicle marker on `DISP-05`.
* **Business Logic & Data:** Fetches high-frequency (1Hz) sensor data for the selected vehicle.
* **Offline & Edge Cases:** Drawer can be swiped away to immediately return to the map.
* **UI & Token Mapping:** Slide-out drawer uses `surface-container-high` and casts a heavy drop shadow over the map.

#### DISP-07 (Emergency Handoff)
* **Executive Summary:**
  * **Insight:** Vehicle breakdowns (Scenario 3) create chaos, requiring rapid reallocation of perishable goods before spoilage.
  * **Decision:** Create a specialized rescue flow that instantly pairs a broken-down vehicle with the nearest available rescue truck.
  * **Outcome:** Kamal resolves emergencies in seconds, salvaging the cold-chain cargo and getting the route back on track.
* **User Flow & Triggers:** Triggered by an incoming critical alert from a driver (`DRV-07`).
* **Business Logic & Data:** Calculates driving distance of all unassigned/empty fleet vehicles to the breakdown coordinates.
* **Offline & Edge Cases:** Overrides standard capacity checks to allow "cramming" cargo in extreme emergencies.
* **UI & Token Mapping:** Entire screen adopts a red/amber hue to indicate emergency state, heavily utilizing `status-critical`.

#### DISP-08 (Crisis Overload)
* **Executive Summary:**
  * **Insight:** During extreme festival peaks (Scenario 1), volume vastly exceeds capacity, and manual allocation is impossible.
  * **Decision:** Introduce a dedicated "Crisis Mode" that applies equitable constraint algorithms across all stores to share the shortage fairly.
  * **Outcome:** The system automatically triages the network, ensuring no single store is entirely starved of inventory.
* **User Flow & Triggers:** Toggled manually by Dispatch leadership when daily volume exceeds 110% of physical fleet capacity.
* **Business Logic & Data:** Applies a percentage-based haircut algorithm (e.g., all stores receive exactly 80% of their order) rather than fulfilling first-come-first-serve.
* **Offline & Edge Cases:** Requires confirmation via a double-input modal to prevent accidental activation.
* **UI & Token Mapping:** UI introduces a permanent purple/amber banner across the top indicating "Systemic Crisis Mode Active".

### 3.5 Dock Loader Experience (Nuwan)

#### LOAD-01 (Depot Queue)
* **Executive Summary:**
  * **Insight:** Dock loaders often waste time figuring out which truck to load next, leading to bay congestion.
  * **Decision:** Present a high-contrast, aggressively prioritized list of departing trips designed for a rugged dock tablet.
  * **Outcome:** Nuwan knows exactly what to prepare next, eliminating guesswork and accelerating bay turnaround times.
* **User Flow & Triggers:** Default landing page for the Loader.
* **Business Logic & Data:** Sorted strictly by Departure Time. Once a truck is assigned a bay, it moves to the top.
* **Offline & Edge Cases:** Caches the next 5 trips locally so the loader can keep working even if the warehouse WiFi drops.
* **UI & Token Mapping:** High contrast text (`text-primary` on `bg-canvas`). List items are large cards with 24px margins.

#### LOAD-02 (Trip Inspect)
* **Executive Summary:**
  * **Insight:** Loading perishable goods into a hot truck destroys inventory, but loaders often skip manual temperature checks if they are tedious.
  * **Decision:** Enforce a mandatory, tap-driven checklist with massive 64px buttons confirming pre-chilled temperatures before loading begins.
  * **Outcome:** The cold chain is mathematically guaranteed before cargo ever leaves the warehouse floor.
* **User Flow & Triggers:** First step when tapping a trip from `LOAD-01`.
* **Business Logic & Data:** Must check off all 4 physical gates (Cleanliness, Temp, Pallet Jack, Lights). 
* **Offline & Edge Cases:** If the loader inputs a temp above 4°C, the system hard-blocks loading and alerts Dispatch.
* **UI & Token Mapping:** Utilizes oversized toggle switches instead of small checkboxes.

#### LOAD-03 (Active Loading)
* **Executive Summary:**
  * **Insight:** Misplaced pallets lead to delivery nightmares, but loaders are moving too fast to read dense manifest text.
  * **Decision:** Utilize a visual, step-by-step checklist that spatially reinforces the reverse-LIFO loading sequence.
  * **Outcome:** Spatial visualization drastically reduces human error, ensuring the last box loaded is the first box delivered.
* **User Flow & Triggers:** Proceeds immediately after `LOAD-02`.
* **Business Logic & Data:** Displays pallets in the exact reverse order of the delivery route.
* **Offline & Edge Cases:** Can scan barcodes offline, queuing the scans in IndexedDB.
* **UI & Token Mapping:** Uses a top-down visual representation of a truck bed, filling up visually as pallets are tapped/scanned.

#### LOAD-04 (Load Exception)
* **Executive Summary:**
  * **Insight:** Reporting a damaged pallet mid-load usually requires walking back to a computer terminal, breaking workflow momentum.
  * **Decision:** Build a full-screen, quick-tap modal to rapidly report damaged or missing items directly from the tablet.
  * **Outcome:** Dispatch and the receiving Store are instantly alerted to the inventory change before the truck even leaves the bay.
* **User Flow & Triggers:** Accessed via a persistent "Report Issue" floating action button (FAB).
* **Business Logic & Data:** Removes the item from the manifest and flags it as `DAMAGED_AT_DEPOT`.
* **Offline & Edge Cases:** If offline, the exception is queued and the loader is allowed to continue, syncing later.
* **UI & Token Mapping:** Modal uses a stark `status-warning` header to differentiate it from the standard loading flow.

#### LOAD-05 (Seal Release)
* **Executive Summary:**
  * **Insight:** Unsecured cargo is a massive liability, and paper seal logs are easily lost or forged.
  * **Decision:** Establish a final critical software gate that requires the digital logging of the physical bolt seal number.
  * **Outcome:** An irrefutable, timestamped chain of custody is established between the Loader and the Driver.
* **User Flow & Triggers:** The final step after the last pallet is loaded.
* **Business Logic & Data:** The input string is locked into the manifest and becomes read-only. It is required by `STORE-05` to open the truck.
* **Offline & Edge Cases:** Requires double-entry (typing it twice) to prevent typos, as it cannot be changed later.
* **UI & Token Mapping:** Massive numeric keypad interface to accommodate thick warehouse gloves.

#### LOAD-06 (Alert Revision)
* **Executive Summary:**
  * **Insight:** If Dispatch alters a route while a truck is actively being loaded (Scenario 2), the loader might miss the memo and load the wrong pallets.
  * **Decision:** Trigger an unavoidable, industrial amber lock-out screen that physically halts the tablet UI until the revision is acknowledged.
  * **Outcome:** Mid-load plan revisions are handled safely without any misloaded cargo.
* **User Flow & Triggers:** Pushed server-side via WebSocket when Kamal changes the route in `DISP-03`.
* **Business Logic & Data:** Pauses all scanning abilities. Overwrites the manifest upon acknowledgement.
* **Offline & Edge Cases:** If the tablet is offline during the revision, Dispatch is warned that the revision failed to reach the loader.
* **UI & Token Mapping:** The screen flashes `status-warning` and requires a 3-second long-press to acknowledge, ensuring it isn't accidentally dismissed.

### 3.6 Field Delivery Driver Experience (Saman)

#### DRV-01 (Pre-Trip Inspect)
* **Executive Summary:**
  * **Insight:** Drivers are legally and financially responsible for the cargo, but often rush departures.
  * **Decision:** Implement a 100% offline-first, high-contrast screen forcing physical verification of cold-chain temps and seal integrity.
  * **Outcome:** Saman leaves the depot with total confidence in his vehicle and cargo, backed by a digital audit trail.
* **User Flow & Triggers:** First screen shown to Saman upon login.
* **Business Logic & Data:** Matches the physical seal number Saman enters against the one Nuwan logged in `LOAD-05`.
* **Offline & Edge Cases:** If seals mismatch, a critical alert is triggered and departure is blocked.
* **UI & Token Mapping:** High-contrast `surface-container` background. `headline-lg` text for extreme legibility from the driver's seat.

#### DRV-02 (Active Route)
* **Executive Summary:**
  * **Insight:** Mountain corridors (Kadugannawa) have zero network connectivity, making cloud-based routing apps useless.
  * **Decision:** Build a true offline-capable PWA itinerary, stripping away visual fluff in favor of massive, readable typography.
  * **Outcome:** Saman never loses access to his stop sequence or delivery instructions, regardless of cellular dead zones.
* **User Flow & Triggers:** The default driving state after departing the depot.
* **Business Logic & Data:** Route data is deeply cached in IndexedDB. Uses standard `tel:` links to trigger phone calls to Store Managers.
* **Offline & Edge Cases:** If GPS is lost, relies on manual "I have arrived" button taps by the driver.
* **UI & Token Mapping:** Ultra-minimalist. Current stop is highlighted in `action-primary`, future stops are faded.

#### DRV-03 (Stop Detail)
* **Executive Summary:**
  * **Insight:** Drivers need one hand for the steering wheel/door and only have a thumb free for their device as they approach a dock.
  * **Decision:** Design the interface entirely around single-hand thumb zones, prioritizing precise dock approach notes.
  * **Outcome:** Saman safely navigates tricky receiving bays and logs his arrival time with a single tap.
* **User Flow & Triggers:** Accessed by tapping a stop from `DRV-02`.
* **Business Logic & Data:** Logs a timestamp of arrival which is instantly synced to `STORE-01` and `DISP-05`.
* **Offline & Edge Cases:** Arrival timestamp is saved locally and queued for upload if offline.
* **UI & Token Mapping:** All critical buttons are pinned to the bottom 25% of the screen (the "Thumb Zone").

#### DRV-04 (Digital POD)
* **Executive Summary:**
  * **Insight:** Capturing signatures on a tiny screen is frustrating, and offline signature data is often lost.
  * **Decision:** Create a streamlined Proof of Delivery (POD) interface with robust 64px touch targets and background Service Worker syncing.
  * **Outcome:** Signatures and seal verifications are captured effortlessly and sync automatically when the truck regains signal.
* **User Flow & Triggers:** Final step at a store dock.
* **Business Logic & Data:** Captures a vector SVG of the signature. 
* **Offline & Edge Cases:** Strongly relies on IndexedDB. The Service Worker will continuously attempt to push the POD to the server until HTTP 200 is received.
* **UI & Token Mapping:** The signature pad occupies 80% of the screen on a crisp `surface-bright` canvas.

#### DRV-05 (Stop Exception)
* **Executive Summary:**
  * **Insight:** When a dock is blocked or closed, typing out long explanations on a mobile keyboard is infuriating.
  * **Decision:** Offer a fast exception reporting screen with pre-populated, single-tap categorical reasons (e.g., "Dock Blocked").
  * **Outcome:** Saman logs the issue instantly, allowing Dispatch to reroute him without wasting his time.
* **User Flow & Triggers:** Triggered from `DRV-03` if a delivery cannot be completed.
* **Business Logic & Data:** Sends an alert to Dispatch. The system automatically recalculates the route, skipping the current stop.
* **Offline & Edge Cases:** If offline, the driver continues to the next stop and the exception is synced later.
* **UI & Token Mapping:** Features a grid of large `64x64px` reason chips.

#### DRV-06 (Rest Break)
* **Executive Summary:**
  * **Insight:** Drivers avoid taking mandatory safety breaks if they fear the system will penalize their delivery performance metrics.
  * **Decision:** Introduce a dedicated, non-penalized rest timer screen that explicitly separates labor breaks from active driving time.
  * **Outcome:** Safety compliance increases because Saman knows his performance metrics are protected during rest.
* **User Flow & Triggers:** Accessed via the persistent navigation bar.
* **Business Logic & Data:** Pauses the active SLA timers on all pending deliveries and updates ETAs on the Dispatch and Store sides.
* **Offline & Edge Cases:** The timer runs locally via `requestAnimationFrame` or `setInterval`, preventing it from desyncing if the phone sleeps.
* **UI & Token Mapping:** Features a calming, massive circular countdown timer. Uses `status-success` green to reinforce that resting is a positive action.

#### DRV-07 (Breakdown)
* **Executive Summary:**
  * **Insight:** A vehicle breakdown (Scenario 3) causes immense panic, especially when transporting perishable chilled goods.
  * **Decision:** Deploy an industrial amber alert interface that guides the driver through emergency steps, starts a spoilage timer, and establishes an emergency telemetry pulse.
  * **Outcome:** Panic is replaced with systemic procedure. Dispatch is notified instantly, and the cold-chain cargo is prioritized for rescue.
* **User Flow & Triggers:** Triggered by a long-press on the persistent "SOS / Breakdown" button.
* **Business Logic & Data:** Immediately triggers SMS fallbacks if data connectivity is lost. Starts a rigid 120-minute spoilage countdown.
* **Offline & Edge Cases:** The system will attempt to send a lightweight SMS telemetry payload if standard HTTP APIs are unreachable.
* **UI & Token Mapping:** The entire screen shifts to `status-warning` (Amber) to signify a critical break in standard procedure.

#### DRV-08 (Trip End)
* **Executive Summary:**
  * **Insight:** Reconciling paper logs at the end of a 12-hour shift is exhausting.
  * **Decision:** Provide a clean, automated summary screen that handles offline-to-online data reconciliation in the background.
  * **Outcome:** Saman ends his shift with a clean slate, knowing all liabilities and tasks are officially closed.
* **User Flow & Triggers:** Triggered when the driver returns to the depot geofence and taps "End Trip".
* **Business Logic & Data:** A background sync manager verifies that 100% of the queued IndexedDB mutations have successfully reached the server.
* **Offline & Edge Cases:** If pending uploads remain, the screen displays a "Syncing... Do not close app" spinner.
* **UI & Token Mapping:** Uses a satisfying `status-success` checkmark animation to provide cognitive closure at the end of a long shift.
