# Degradation Scenarios — Waypoint Dispatch

Three operational failure scenarios, each fully designed with a high-fidelity screen, named and justified.

---

## Scenario 1: Festival Capacity Overload & Service Equity Crisis

**Screen:** DISP-08 (`/dispatch/crisis/overload`)  
**Affected Role:** Kamal (Central Dispatcher)

**Justification:**  
Sri Lanka's retail calendar is punctuated by paydays, Poya holidays, New Year festivals, and monsoon-driven demand surges. On these days, confirmed order volume can exceed fleet capacity by 20–30%. When this happens, the dispatcher faces a zero-sum decision: which outlets receive deliveries and which are deferred?

Under the legacy spreadsheet system, dispatchers made these choices under time pressure with no structured framework, leading to the same remote outlets (Puttalam, Nuwara Eliya) being silently deferred on consecutive days while flagship Colombo stores received preferential treatment. This created inequitable service distribution and eroded trust with outlying franchise managers.

**Our design response:** DISP-08 presents two named resolution strategies side-by-side — **Strategy A: Service Equity Mode** (distribute 80% volume to all affected outlets, deferring the shortfall evenly) and **Strategy B: Tier-1 Priority Mode** (fully serve high-revenue stores, completely defer Tier 3). A simulated impact comparison matrix shows the consequences of each choice (total volume delivered, number of outlets receiving zero delivery, orders fully fulfilled, fuel quota impact) before the dispatcher commits. This transforms an opaque, pressure-driven gut decision into a transparent, auditable, consequence-aware policy selection.

---

## Scenario 2: Mid-Load Plan Revision Interruption

**Screen:** LOAD-04 (`/depot/load/[trip_id]/alert-revision`)  
**Affected Role:** Nuwan (Dock Supervisor / Loader)

**Justification:**  
Loading at Peliyagoda begins at 00:30 AM and takes 2–4 hours per vehicle. During this window, the dispatcher may revise the allocation plan — adding an urgent last-minute order, removing a cancelled order, or rebalancing cargo between vehicles due to a breakdown. Under the legacy system, these changes were communicated by phone call or a supervisor walking to the bay, creating a high risk of miscommunication. Loaders continued stacking pallets according to the original printed run sheet, resulting in wrong cargo being dispatched, LIFO sequences being violated, and stores receiving goods meant for other outlets.

**Our design response:** LOAD-04 is a full-screen interruption modal that instantly locks the loading checklist when a plan revision is received from dispatch. It shows a clear delta table: for each affected stop, the old quantity is struck through and the new quantity is highlighted with directional arrows (↑ increase / ↓ decrease). The loader cannot resume loading until they explicitly tap "Acknowledge & Update Checklist," ensuring the revised manifest is understood and accepted before any further pallets are moved. The 56px action button and high-contrast error-container background ensure visibility in noisy, dimly-lit warehouse conditions.

---

## Scenario 3: Mid-Route Vehicle Breakdown & Cold-Chain Spoilage Risk

**Screen:** DRV-06 (`/field/emergency/breakdown`)  
**Affected Role:** Saman (Field Driver)

**Justification:**  
Waypoint's delivery routes traverse mountain corridors (Kadugannawa, Nuwara Eliya) and rural districts where mechanical breakdowns, flat tires, and reefer compressor failures are operationally inevitable. When a refrigerated truck breaks down, the cold chain is compromised — chilled dairy, poultry, and frozen goods begin warming toward the spoilage threshold. Under the legacy system, drivers called dispatch by phone, often with poor reception, and explained their situation verbally. Dispatch then spent 20–40 minutes manually identifying the nearest available rescue vehicle by calling other drivers one by one, during which time the cargo continued to degrade.

**Our design response:** DRV-06 provides a single-tap 160px SOS broadcast button that instantly transmits the driver's GPS coordinates, vehicle ID, and cargo manifest to the dispatch control tower. The driver selects an issue type from a 4-option grid (Engine/Breakdown, Flat Tire, Reefer Failure, Medical/Accident) — each option is a large 100px touch target designed for use with shaking hands or in poor visibility. The screen works 100% offline, queuing the broadcast for transmission when connectivity returns. On the dispatcher's side, DISP-07 immediately surfaces rescue vehicle candidates ranked by ETA and spare capacity, enabling a handoff decision within minutes instead of the legacy 40-minute phone chain.
