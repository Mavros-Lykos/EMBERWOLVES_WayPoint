# Waypoint Dispatch: Master Information Architecture & Screen Blueprint
*Enterprise Multi-Actor Logistics & Delivery Management Platform — Tech-Triathlon 2026*

---

## 1. Executive System Topology & Architectural Invariants

The **Waypoint Dispatch** platform is architected as a single, unified enterprise Responsive Web Application (Progressive Web Application / PWA) engineered to operate across four fundamentally distinct operational realities in Sri Lanka:

```
┌─────────────────────────────────────────────────────────────────────────────────────────────┐
│                                  UNIFIED WEB APPLICATION                                    │
│                                  (Next.js BFF + Tailwind)                                   │
└──────────────┬──────────────────────────┬──────────────────────────┬────────────────────────┘
               │                          │                          │
        ┌──────▼──────┐            ┌──────▼──────┐            ┌──────▼──────┐
        │  /store/*   │            │ /dispatch/* │            │  /depot/*   │
        │ Store POS / │            │ Desktop 27" │            │ Rugged Bay  │
        │ Counter Tab │            │ Dual Canvas │            │ Tablet PWA  │
        │ Light Theme │            │ Dark Theme  │            │ High-Contr. │
        └─────────────┘            └──────┬──────┘            └──────┬──────┘
                                          │                          │
                                   ┌──────▼──────────────────────────▼──────┐
                                   │                /field/*                │
                                   │         Driver Smartphone PWA          │
                                   │   100% Offline-First (IndexedDB/SW)    │
                                   │         High-Contrast Slate            │
                                   └────────────────────────────────────────┘
```

### Physical & Mathematical Invariants (The Domain Ground Truth)
1. **Network Scale:** 120 retail outlets (80 Fresh, 25 Style, 15 Tech) served by 2 distribution hubs (Peliyagoda Central DC and Kandy Regional Hub).
2. **Fleet Fleet Constraints:** 60 physical vehicles:
   - 12 Refrigerated Trucks ($14.0m^3$, $4,500kg$) — Chilled ($0^\circ C - 4^\circ C$) & Ambient.
   - 40 Dry-Box Trucks ($18.0m^3$, $5,500kg$) — Ambient only.
   - 4 Refrigerated Small Vans ($6.0m^3$, $1,200kg$) — Chilled & Ambient, van_only access.
   - 4 Dry-Box Small Vans ($8.0m^3$, $1,500kg$) — Ambient only, van_only access.
3. **Hard Operational Windows:**
   - 16:00 Daily Order Cutoff for next-day runs.
   - Fresh outlets **must** receive deliveries before store opening at 08:00 AM.
   - Mall outlets accept deliveries strictly within mall access windows.
   - Weekly vehicle fuel quotas ($km$) and maximum 2 routes per vehicle per operating day (Mon-Sat).

---

## 2. Complete Information Architecture (IA) & Route Directory

| Screen ID | Canonical URL Route | Persona & Hardware Form Factor | Evaluated Viewport | Default Theme | Touch Target Standard | Offline Capability |
|---|---|---|---|---|---|---|
| **AUTH-01** | `/auth/login` | All Roles & Competition Judges | Responsive (`390px` to `1920px`) | Midnight Oceanic | `44px` / `64px` | Online Only |
| **SYS-01** | `[Global Drawer]` | All Roles | Responsive (`390px` to `1920px`) | Context-inherited | `44px` / `64px` | Client-side Diagnostic |
| **STORE-01**| `/store/dashboard` | Priya (Store Manager, POS) | `1024px` - `1440px` | Serene Retail (Light) | Standard `44px` | Online / Auto-Retry |
| **STORE-02**| `/store/order/new` | Priya (Store Manager, POS) | `1024px` - `1440px` | Serene Retail (Light) | Standard `44px` | Online / Auto-Retry |
| **STORE-03**| `/store/order/[id]` | Priya (Store Manager, POS) | `1024px` - `1440px` | Serene Retail (Light) | Standard `44px` | Online / Auto-Retry |
| **STORE-04**| `/store/order/history` | Priya (Store Manager, POS) | `1024px` - `1440px` | Serene Retail (Light) | Standard `44px` | Online / Auto-Retry |
| **STORE-05**| `/store/delivery/[id]/receive` | Priya (Store Manager, Dock) | `768px` - `1024px` (Tablet) | Serene Retail (Light) | **Fatigue `64px`** | Local Draft (IndexedDB) |
| **STORE-06**| `/store/delivery/[id]/dispute` | Priya (Store Manager, Dock) | `768px` - `1024px` (Tablet) | Serene Retail (Light) | **Fatigue `64px`** | Local Draft (IndexedDB) |
| **DISP-01** | `/dispatch/overview` | Kamal (Dispatcher, Peliyagoda) | `1920x1080` (High Density) | Midnight Oceanic (Dark) | Desktop Mouse/Key | Server Connected |
| **DISP-02** | `/dispatch/planning` | Kamal (Dispatcher, Peliyagoda) | `1920x1080` (High Density) | Midnight Oceanic (Dark) | Desktop Mouse/Key | Server Connected |
| **DISP-03** | `/dispatch/planning/sequence/[trip_id]` | Kamal (Dispatcher) | Centered Modal (`960px`) | Midnight Oceanic (Elevated) | Desktop Mouse/Key | Server Connected |
| **DISP-04** | `/dispatch/planning/deferral/[order_id]`| Kamal (Dispatcher) | Centered Modal (`720px`) | Midnight Oceanic (Elevated) | Desktop Mouse/Key | Server Connected |
| **DISP-05** | `/dispatch/live` | Kamal (Dispatcher, Control Tower)| `1920x1080` (High Density) | Midnight Oceanic (Dark) | Desktop Mouse/Key | Server Connected |
| **DISP-06** | `/dispatch/vehicle/[vehicle_id]` | Kamal (Dispatcher) | Flyout Drawer (`480px`) | Midnight Oceanic (Elevated) | Desktop Mouse/Key | Server Connected |
| **DISP-07** | `/dispatch/emergency/handoff/[trip_id]` | Kamal (Dispatcher) | High-Density Modal (`1200px`)| Midnight Oceanic (Elevated) | Desktop Mouse/Key | Server Connected |
| **DISP-08** | `/dispatch/crisis/overload` | Kamal (Degradation 1) | `1920x1080` (High Density) | Midnight Oceanic (Crisis) | Desktop Mouse/Key | Server Connected |
| **LOAD-01** | `/depot/queue` | Nuwan (Dock Loader, Bay Tablet)| `768px` - `1024px` (Tablet PWA)| Midnight Oceanic (Contrast)| **Fatigue `64px`** | IndexedDB Staging |
| **LOAD-02** | `/depot/inspect/[trip_id]` | Nuwan (Dock Loader, Bay Tablet)| `768px` - `1024px` (Tablet PWA)| Midnight Oceanic (Contrast)| **Fatigue `64px`** | IndexedDB Staging |
| **LOAD-03** | `/depot/load/[trip_id]` | Nuwan (Dock Loader, Bay Tablet)| `768px` - `1024px` (Tablet PWA)| Midnight Oceanic (Contrast)| **Fatigue `64px`** | IndexedDB Staging |
| **LOAD-04** | `/depot/load/[trip_id]/exception` | Nuwan (Dock Loader, Bay Tablet)| Full-Screen Modal (`768px`) | Midnight Oceanic (Contrast)| **Fatigue `64px`** | IndexedDB Staging |
| **LOAD-05** | `/depot/release/[trip_id]` | Nuwan (Dock Loader, Bay Tablet)| `768px` - `1024px` (Tablet PWA)| Midnight Oceanic (Contrast)| **Fatigue `64px`** | IndexedDB Staging |
| **LOAD-06** | `/depot/load/[trip_id]/alert-revision` | Nuwan (Degradation 2) | Full-Screen Lockout Modal | Industrial Amber Alert | **Fatigue `64px`** | IndexedDB Intercept |
| **DRV-01**  | `/field/pti` | Saman (Driver Smartphone PWA) | `390x844` (Phone Viewport) | Midnight Oceanic (Contrast)| **Fatigue `64px`** | **100% Offline-First** |
| **DRV-02**  | `/field/route` | Saman (Driver Smartphone PWA) | `390x844` (Phone Viewport) | Midnight Oceanic (Contrast)| **Fatigue `64px`** | **100% Offline-First** |
| **DRV-03**  | `/field/stop/[stop_id]` | Saman (Driver Smartphone PWA) | `390x844` (Phone Viewport) | Midnight Oceanic (Contrast)| **Fatigue `64px`** | **100% Offline-First** |
| **DRV-04**  | `/field/stop/[stop_id]/pod` | Saman (Driver Smartphone PWA) | `390x844` (Phone Viewport) | Midnight Oceanic (Contrast)| **Fatigue `64px`** | **100% Offline-First** |
| **DRV-05**  | `/field/stop/[stop_id]/exception` | Saman (Driver Smartphone PWA) | `390x844` (Phone Viewport) | Midnight Oceanic (Contrast)| **Fatigue `64px`** | **100% Offline-First** |
| **DRV-06**  | `/field/break` | Saman (Driver Smartphone PWA) | `390x844` (Phone Viewport) | Midnight Oceanic (Contrast)| **Fatigue `64px`** | **100% Offline-First** |
| **DRV-07**  | `/field/emergency/breakdown` | Saman (Degradation 3) | `390x844` (Phone Viewport) | Industrial Amber Alert | **Fatigue `64px`** | **100% Offline-First** |
| **DRV-08**  | `/field/trip-end` | Saman (Driver Smartphone PWA) | `390x844` (Phone Viewport) | Midnight Oceanic (Contrast)| **Fatigue `64px`** | **100% Offline-First** |

---

## 3. Module 0: Global Access & Judge Walkthrough Suite

### Screen AUTH-01: Unified Role-Switch & Judge Walkthrough Portal (`/auth/login`)
- **Evaluated Viewport:** Responsive (`390px` to `1920px`)
- **Theme:** Midnight Oceanic (`#0f172a`)
- **Wireframe Layout Blueprint:**
```
┌────────────────────────────────────────────────────────────────────────┐
│ [Logo: WAYPOINT DISPATCH]                 [EN | සිං | த] [Contrast Mode]│
├────────────────────────────────────────────────────────────────────────┤
│                                                                        │
│                ┌────────────────────────────────────┐                  │
│                │     HACKATHON JUDGE QUICK LAUNCH   │                  │
│                │  (1-Click Seeded Persona Profiles) │                  │
│                │                                    │                  │
│                │  [ 🏬 STORE MGR: Priya (OUT007) ]  │                  │
│                │  [ 🖥️ DISPATCHER: Kamal (Peliy.) ] │                  │
│                │  [ 📦 LOADER: Nuwan (Bay 04)    ]  │                  │
│                │  [ 🚚 DRIVER: Saman (VEH-003)   ]  │                  │
│                └────────────────────────────────────┘                  │
│                                                                        │
│                ┌────────────────────────────────────┐                  │
│                │ Manual Sign In (Enterprise SSO)   │                  │
│                │ [Username / Staff ID             ] │                  │
│                │ [Password / PIN Code             ] │                  │
│                │ [Primary Action: SIGN IN         ] │                  │
│                └────────────────────────────────────┘                  │
│                                                                        │
│   [Seeded Scenario: Day 1 Baseline — 120 Outlets, 60 Trucks, 4PM Cutoff]│
└────────────────────────────────────────────────────────────────────────┘
```
- **Operational Rationale:**
  Competition judges have strict evaluation time budgets. Requiring manual database seeding or complex logins creates immediate friction. This portal provides 1-click seeded entrypoints for each of the four roles with real data pre-populated, enabling a judge to execute an end-to-end delivery cycle in under five minutes.

---

## 4. Module 1: Store Manager Experience (`/store`)
*Persona: Priya | Environment: Daylight Retail Counter, POS Terminal | Theme: Serene Retail*

### Screen STORE-01: Store Operations Hub & Arrival Tracker (`/store/dashboard`)
- **Evaluated Viewport:** `1024px` Desktop / Tablet Responsive
- **Theme:** Serene Retail (`#f8fafc` Canvas, `#ffffff` Surfaces)
- **Wireframe Layout Blueprint:**
```
┌────────────────────────────────────────────────────────────────────────┐
│ OUT007 - Waypoint Fresh Mount Lavinia      [Cutoff: 02h 14m] [Priya K.]│
├──────────────────────────────────────────┬─────────────────────────────┤
│ 🚚 TODAY'S DELIVERY STATUS               │ 📦 QUICK REORDER BASKET     │
│ ETA: 07:15 AM (18 mins away)             │ Ambient Grocery Daily Core  │
│ Driver: Saman K. | Vehicle: VEH-003 (Reefer│ 32 SKUs | 420 kg | 1.8 m³   │
│ Stop 2 of 4 | Seal # Pending Dock Check  │ [1-Click Reorder for Mon]   │
│ [Track Live Map] [Dock Receiving Prep]   │                             │
├──────────────────────────────────────────┴─────────────────────────────┤
│ 📋 ACTIVE ORDERS SUMMARY                                               │
│ • ORD-8891 (Chilled Perishables) — Allocated (VEH-003) — Delivery Today│
│ • ORD-8890 (Dry Groceries)       — Allocated (VEH-018) — Delivery Today│
│ • ORD-8944 (Tomorrow's Run)      — DRAFT (Closes at 16:00 today)       │
├────────────────────────────────────────────────────────────────────────┤
│ ⚠️ HISTORICAL SERVICE METRIC                                           │
│ Chilled Fill Rate: 78% (Last 14 Days) | 1 Deferral Recorded (Oct 02)   │
└────────────────────────────────────────────────────────────────────────┘
```
- **Operational Rationale:**
  Store managers operate in busy retail environments where scheduling dock receiving staff is a primary headache. This dashboard prioritizes the expected arrival window above all else, enabling Priya to allocate floor stockers to the loading bay exactly when the truck arrives without pulling staff away from customer checkouts prematurely.

---

### Screen STORE-02: Order Placement Canvas (`/store/order/new`)
- **Evaluated Viewport:** `1024px` Desktop Responsive
- **Theme:** Serene Retail (`#f8fafc`)
- **Wireframe Layout Blueprint:**
```
┌────────────────────────────────────────────────────────────────────────┐
│ NEW ORDER: Delivery for Tuesday, Oct 06        [Cutoff Countdown: 01:45]│
├────────────────────────────────────────────────────────────────────────┤
│ CATEGORY: [ (•) Fresh Ambient ] [ Chilled Reefer ] [ Style ] [ Tech ]   │
├────────────────────────────────────────┬───────────────────────────────┤
│ ITEM CATALOG (Fresh Ambient Core)      │ ORDER MANIFEST SUMMARY        │
│ [Search SKU or Item Name...          ] │ Outlets: OUT007 Mount Lavinia │
│                                        │ Vehicle Access: Heavy Truck OK│
│ SKU-101 Rice Samba 5kg     [ - 40 + ]  │ Total Items: 18 SKUs          │
│ SKU-102 White Sugar 1kg    [ - 80 + ]  │ Total Weight: 1,420 kg        │
│ SKU-109 Dhal 1kg           [ - 60 + ]  │ Total Volume: 3.4 m³          │
│ SKU-214 Canned Fish 425g   [ - 30 + ]  │                               │
│                                        │ [ ] MARK AS CRITICAL URGENCY  │
│ [Clear Basket]                         │ [Submit Order Before 16:00]   │
└────────────────────────────────────────┴───────────────────────────────┘
```
- **Operational Rationale:**
  Fresh outlets place up to two separate orders per day (ambient groceries and chilled perishables), while Style and Tech order on weekly or ad-hoc schedules. This canvas enforces clean categorical separation between chilled goods (which require refrigerated fleet allocation) and ambient goods, eliminating data re-entry errors at the central depot and preventing illegal temperature-zone requests before the 4:00 PM cutoff.

---

### Screen STORE-03: Active Order Detail & Live Track Map (`/store/order/[id]`)
- **Evaluated Viewport:** `1024px` Desktop Responsive
- **Theme:** Serene Retail (`#f8fafc`)
- **Wireframe Layout Blueprint:**
```
┌────────────────────────────────────────────────────────────────────────┐
│ ORDER #ORD-8891 (Chilled Dairy & Poultry) — Allocated to VEH-003       │
├────────────────────────────────────────┬───────────────────────────────┤
│ LIVE ROUTE PROGRESS                    │ ORDER SPECIFICATION           │
│ [Map View: Vehicle Moving on A2 Trunk] │ Brand: Waypoint Fresh         │
│ Current Speed: 42 km/h                 │ Required Temp: 0°C to 4°C     │
│ Distance Remaining: 7.4 km             │ Total Weight: 1,120 kg        │
│ Stop Sequence: Stop 2 of 4             │ Total Volume: 2.8 m³          │
│                                        ├───────────────────────────────┤
│ ESTIMATED ARRIVAL WINDOW               │ DRIVER & CREW                 │
│ 07:10 AM - 07:25 AM                    │ Driver: Saman K. (077-xxxxxxx)│
│ (Meets Pre-8 AM Fresh Store Mandate)   │ Truck: Isuzu 4.5T Reefer      │
└────────────────────────────────────────┴───────────────────────────────┘
```
- **Operational Rationale:**
  Gives the store manager complete visibility into approaching deliveries. By seeing live vehicle breadcrumbs, current progress along the stop sequence, and driver contact shortcuts, Priya can confirm that the delivery will meet the mandatory pre-8 AM store opening window and alert her dock crew without calling central dispatch.

---

### Screen STORE-04: Order & Deferral History Log (`/store/order/history`)
- **Evaluated Viewport:** `1024px` Desktop Responsive
- **Theme:** Serene Retail (`#f8fafc`)
- **Wireframe Layout Blueprint:**
```
┌────────────────────────────────────────────────────────────────────────┐
│ ORDER HISTORY & DEFERRAL AUDIT TRAIL — OUT007                          │
├────────────────────────────────────────────────────────────────────────┤
│ Filter: [All Brands] [Fulfilled] [Deferred] [Partial]                  │
├────────────┬─────────────┬──────────┬──────────┬───────────────────────┤
│ ORDER ID   │ DATE PLACED │ CATEGORY │ STATUS   │ RESOLUTION / REASON   │
├────────────┼─────────────┼──────────┼──────────┼───────────────────────┤
│ ORD-8891   │ Oct 04 15:42│ Chilled  │ En Route │ On time (ETA 07:15)   │
│ ORD-8820   │ Oct 02 15:58│ Ambient  │ DEFERRED │ ⚠️ Capacity Peak S1;  │
│            │             │          │          │ Rolled to Oct 03 (P1) │
│ ORD-8755   │ Sep 30 14:10│ Chilled  │ Partial  │ 4 crates short-dock   │
└────────────┴─────────────┴──────────┴──────────┴───────────────────────┘
```
- **Operational Rationale:**
  Under the legacy system, store managers experienced unannounced order deferrals with zero explanation, resulting in contentious telephone arguments with dispatchers. This screen establishes an immutable audit log of deferral decisions and delivery receipts, providing Priya with immediate visibility into why an order was rolled over and confirming its priority status for the following day's run.

---

### Screen STORE-05: Live Receiving & Seal Verification Gate (`/store/delivery/[id]/receive`)
- **Evaluated Viewport:** `768px` Tablet / POS Responsive
- **Theme:** Serene Retail (`#f8fafc`) — **Fatigue Targets $\ge 64\text{px}$**
- **Wireframe Layout Blueprint:**
```
┌────────────────────────────────────────────────────────────────────────┐
│ DOCK RECEIVING: Trip #TRIP-104 | VEH-003 (Saman K.)                    │
├────────────────────────────────────────────────────────────────────────┤
│ 🔒 STEP 1: TAMPER-EVIDENT SEAL VERIFICATION                            │
│ Inspect the plastic bolt seal on the rear door before cutting:         │
│                                                                        │
│ Depot Issued Seal Number: [ SL - 9 9 4 2 ]                             │
│ Enter Seal Number on Door: [ 9 | 9 | 4 | 2 ]  ──► [ ✓ SEAL VERIFIED ]  │
│                                                                        │
│ 🌡️ STEP 2: TEMPERATURE CHECK AT DOCK                                  │
│ Probe Reading (°C): [ 3.2°C ]  ──► (Normal: 0°C to 4°C Verified)       │
├────────────────────────────────────────────────────────────────────────┤
│ 📦 STEP 3: MANIFEST CRATE AUDIT                                        │
│ [X] 24 Crates Highland Milk 1L        (Delivered 24 / Expected 24)     │
│ [X] 16 Crates Fresh Poultry Chilled   (Delivered 16 / Expected 16)     │
│ [!] 10 Crates Yoghurt 80g             (Delivered 8 / Expected 10) ⚠️   │
├────────────────────────────────────────────────────────────────────────┤
│ [Report 2 Crates Short / Damaged]     [CONFIRM & SIGN RECEIPT (64px)]  │
└────────────────────────────────────────────────────────────────────────┘
```
- **Operational Rationale:**
  The physical transfer of custody between driver and store manager is the highest-risk point for shrinkage and temperature abuse. By requiring the store manager to enter and verify the physical tamper-evident seal before unlocking the digital sign-off, the system guarantees chain-of-custody integrity, ensuring damaged or missing cartons are documented with photographic evidence before the driver departs.

---

### Screen STORE-06: Damage / Shortage Dispute & Photo Claim (`/store/delivery/[id]/dispute`)
- **Evaluated Viewport:** `768px` Tablet Responsive
- **Theme:** Serene Retail (`#f8fafc`)
- **Wireframe Layout Blueprint:**
```
┌────────────────────────────────────────────────────────────────────────┐
│ RECORD DELIVERY EXCEPTION: ORD-8891 (Trip #TRIP-104)                   │
├────────────────────────────────────────────────────────────────────────┤
│ DISCREPANCY TYPE:                                                      │
│ [ (•) Short-shipped at Dock ] [ Damaged in Transit ] [ Temp Spoilage ] │
│                                                                        │
│ AFFECTED SKU: SKU-402 Yoghurt 80g                                      │
│ Ordered: 10 Crates | Actually Received: 8 Crates | Missing: 2 Crates   │
├────────────────────────────────────────────────────────────────────────┤
│ CAMERA EVIDENCE ATTACHMENT:                                            │
│ ┌───────────────────────────┐  ┌────────────────────────────────────┐  │
│ │ [Photo 1: Broken Pallet]  │  │ [ + TAP TO TAKE PHOTO OF DAMAGE ]  │  │
│ └───────────────────────────┘  └────────────────────────────────────┘  │
│ Reason Note: "2 crates crushed under fallen carton; rejected at dock." │
├────────────────────────────────────────────────────────────────────────┤
│ [Back to Manifest]             [SUBMIT EXCEPTION & ISSUE CREDIT NOTE]  │
└────────────────────────────────────────────────────────────────────────┘
```
- **Operational Rationale:**
  Formalizes discrepancy claims at the exact moment of delivery. Eliminates end-of-month accounting friction between store managers and logistics by capturing photographic proof of damaged goods, driver acknowledgment, and automatically triggering a digital credit note in Waypoint's enterprise ERP.

---

## 5. Module 2: Central Fleet Dispatcher Experience (`/dispatch`)
*Persona: Kamal | Environment: Peliyagoda Planning Office, 27" Dual-Monitor | Theme: Midnight Oceanic*

### Screen DISP-01: Fleet Capacity & Demand Intelligence Hub (`/dispatch/overview`)
- **Evaluated Viewport:** High-Density Desktop Canvas (`1920x1080`)
- **Theme:** Midnight Oceanic (`#0f172a` Canvas, `#1e293b` Surfaces)
- **Wireframe Layout Blueprint:**
```
┌──────────────────────────────────────────────────────────────────────────────────────────────────┐
│ WAYPOINT CONTROL TOWER | Peliyagoda DC & Kandy Hub        [16:00 CUTOFF: LOCKED] [Kamal - Lead] │
├──────────────────────────────────────────────────────────────────────────────────────────────────┤
│ 📊 DAILY NETWORK AGGREGATES                                                                      │
│ Total Orders: 184 Confirmed | Total Weight: 142.4T / 190T Cap | Total Vol: 482m³ / 620m³ Cap     │
├───────────────────────────────┬────────────────────────────────┬─────────────────────────────────┤
│ 📍 PELIYAGODA CENTRAL DC      │ 📍 KANDY REGIONAL HUB          │ 🚚 FLEET STATUS READY (60 Units)│
│ Active Orders: 138            │ Active Orders: 46              │ • 12 Reefer Trucks (100% Ready) │
│ Ambient: 84% Capacity Used    │ Ambient: 72% Capacity Used     │ • 40 Dry Trucks (38 Ready, 2 Mnt)│
│ Chilled: 94% Capacity Saturated│ Chilled: 68% Capacity Used    │ • 4 Reefer Vans (100% Ready)    │
│ Fuel Quota: 82% Weekly Balance│ Fuel Quota: 76% Weekly Balance │ • 4 Dry Vans (100% Ready)       │
├───────────────────────────────┴────────────────────────────────┴─────────────────────────────────┤
│ 🔮 DATATHON ML DEMAND SURGE FORECAST (Next 7 Operating Days)                                     │
│ [Chart: Projected Demand vs Fleet Ceiling — Warning: Friday Payday Peak Exceeds Reefer Cap by 22%]│
├──────────────────────────────────────────────────────────────────────────────────────────────────┤
│ [Open Master Planning Canvas (DISP-02)]   [View Historical Deferrals]   [Simulate Peak Crisis]   │
└──────────────────────────────────────────────────────────────────────────────────────────────────┘
```
- **Operational Rationale:**
  At 4:00 PM, Kamal faces an overwhelming flood of demand across 120 stores with hard physical constraints. This overview provides immediate macro-level situational awareness, exposing the total cubic volume and kilogram deficit across ambient and chilled zones before detailed route sequencing begins, allowing Kamal to instantly determine whether standard operations or capacity mitigation policies apply.

---

### Screen DISP-02: Master Multi-Compartment Allocation Canvas (`/dispatch/planning`)
- **Evaluated Viewport:** High-Density Desktop Canvas (`1920x1080` Multi-Pane)
- **Theme:** Midnight Oceanic (`#0f172a`)
- **Wireframe Layout Blueprint:**
```
┌──────────────────────────────────────────────────────────────────────────────────────────────────┐
│ MASTER ALLOCATION CANVAS — Planning Horizon: Tuesday, Oct 06        [Auto-Allocate] [LOCK PLAN] │
├──────────────────────────────┬───────────────────────────────────────────────────────────────────┤
│ UNALLOCATED ORDERS (34 Left) │ VEHICLE TRIP MATRIX (60 Vehicles | Filter: [Peliyagoda] [Reefer] )│
├──────────────────────────────┼───────────────────────────────────────────────────────────────────┤
│ [Filter: Van Only] [Chilled] │ ┌─ VEH-003 (Isuzu 4.5T Reefer - Peliyagoda) ────────────────────┐ │
│                              │ │ TRIP 1: [OUT007 Fresh] ──► [OUT012 Fresh] ──► [OUT021 Fresh]  │ │
│ • ORD-8902 | OUT042 Kandy    │ │ Vol: 11.2 / 14.0 m³ (80%) ▓▓▓▓▓▓▓▓░░ | Wt: 3,820 / 4,500kg    │ │
│   Chilled: 1.4m³ | 480kg     │ │ Window: All Pre-8 AM Feasible [✓]    | Stops: 3 Stores       │ │
│   [⚠️ Van-Only Constraint]    │ └───────────────────────────────────────────────────────────────┘ │
│                              │ ┌─ VEH-014 (Hino 5.5T Dry Truck - Peliyagoda) ──────────────────┐ │
│ • ORD-8914 | OUT074 Puttalam │ │ TRIP 1: [OUT074 Style] ──► [OUT076 Style]                     │ │
│   Ambient: 6.2m³ | 2,100kg   │ │ Vol: 16.8 / 18.0 m³ (93%) ▓▓▓▓▓▓▓▓▓░ | Wt: 4,100 / 5,500kg    │ │
│   [⚠️ Deferred Yesterday!]   │ │ Window: Mall 10:00-14:00 Feasible [✓]| Stops: 2 Stores       │ │
│                              │ └───────────────────────────────────────────────────────────────┘ │
│ • ORD-8930 | OUT106 Tech     │ ┌─ VEH-045 (Tata 1.2T Dry Van - Peliyagoda) ────────────────────┐ │
│   Tech Goods: 3.1m³ | 940kg  │ │ TRIP 1: [OUT042 Kandy Central] ──► [OUT044 Kandy Alley]       │ │
│   Fragile Appliances         │ │ Vol: 5.4 / 8.0 m³ (67%) ▓▓▓▓▓▓░░░░   | Wt: 880 / 1,500kg     │ │
│                              │ │ [✓ Fits Van-Only Access Outlets]                              │ │
│ [Drag Order to Vehicle Block]│ └───────────────────────────────────────────────────────────────┘ │
└──────────────────────────────┴───────────────────────────────────────────────────────────────────┘
```
- **Operational Rationale:**
  Manual spreadsheet planning caused daily constraint violations, resulting in trucks being rejected at low-clearance mall docks or ambient trucks accidentally receiving ice cream. The Master Allocation Canvas visually prevents human error by mathematically clamping assignments to vehicle types, delivery windows (pre-8 AM Fresh), and volume/weight envelopes, transforming an error-prone 3-hour spreadsheet task into a validated 20-minute workflow.

---

### Screen DISP-03: Route Sequencer & LIFO Unloading Feasibility Modal (`/dispatch/planning/sequence/[trip_id]`)
- **Evaluated Viewport:** Centered Desktop Modal (`960px` width)
- **Theme:** Midnight Oceanic Elevated (`#1e293b`)
- **Wireframe Layout Blueprint:**
```
┌────────────────────────────────────────────────────────────────────────┐
│ TRIP SEQUENCER: VEH-003 (Trip 1 - Departure 04:00 AM)                  │
├────────────────────────────────────────────────────────────────────────┤
│ REVERSE LIFO SEQUENCE (Top = First Delivered / Last Loaded at Bay):   │
│                                                                        │
│ 1. [Stop 1] OUT007 Fresh Mount Lavinia (ETA: 05:45 AM | Window: <08:00)│
│    Unload: 4 Pallets Chilled | Service Time: 22m (ML Pred: 24m) [::]   │
│                                                                        │
│ 2. [Stop 2] OUT012 Fresh Moratuwa      (ETA: 06:30 AM | Window: <08:00)│
│    Unload: 3 Pallets Chilled | Service Time: 18m (ML Pred: 20m) [::]   │
│                                                                        │
│ 3. [Stop 3] OUT021 Fresh Panadura      (ETA: 07:15 AM | Window: <08:00)│
│    Unload: 5 Pallets Chilled | Service Time: 28m (ML Pred: 30m) [::]   │
│                                                                        │
│ Total Distance: 64.2 km | Weekly Fuel Quota Remaining: 420 km [OK]     │
├────────────────────────────────────────────────────────────────────────┤
│ [Re-Optimize Sequence via OSRM]             [CONFIRM SEQUENCE & SAVE]  │
└────────────────────────────────────────────────────────────────────────┘
```
- **Operational Rationale:**
  Sequencing stops correctly is mandatory to prevent roadside cargo restacking. This sequencer computes transit times and ML-predicted dock service durations to ensure all three Fresh supermarket deliveries complete before 08:00 AM, automatically generating the reverse-order loading list for bay loaders.

---

### Screen DISP-04: Consecutive Deferral Governance Modal (`/dispatch/planning/deferral/[order_id]`)
- **Evaluated Viewport:** Centered Desktop Modal (`720px` width)
- **Theme:** Midnight Oceanic Elevated (`#1e293b`)
- **Wireframe Layout Blueprint:**
```
┌────────────────────────────────────────────────────────────────────────┐
│ ⚠️ ORDER DEFERRAL GOVERNANCE: ORD-8914 (OUT074 Puttalam Style)         │
├────────────────────────────────────────────────────────────────────────┤
│ CRITICAL SERVICE EQUITY AUDIT:                                         │
│ • Consecutive Days Deferred: 1 Day (Deferred Yesterday by Kamal)       │
│ • Service Equity Policy: OUTLETS CANNOT BE DEFERRED 2 DAYS IN A ROW    │
│                                                                        │
│ MANDATORY REASON TAXONOMY:                                             │
│ [ (•) Exceeded Refrigerated Fleet Capacity                            ]│
│ [     Depot Stock Shortfall at Central WMS                            ]│
│ [     Weekly Vehicle Fuel Quota Depleted                              ]│
│ [     Access Window Expired                                           ]│
│                                                                        │
│ Business Justification Note:                                           │
│ [Puttalam volume exceeds remaining dry truck cap; bump Tech OUT106    ]│
├────────────────────────────────────────────────────────────────────────┤
│ [Cancel Deferral (Keep on Truck)]     [OVERRIDE WITH SENIOR LOG PASS]  │
└────────────────────────────────────────────────────────────────────────┘
```
- **Operational Rationale:**
  When demand exceeds fleet limits, dispatchers under pressure often defer the same remote store consecutively, starving outlying communities of groceries. This governance modal enforces operational equity by displaying historical deferral tallies directly at the moment of decision, requiring an explicit reason taxonomy before an order can be removed from a trip.

---

### Screen DISP-05: Live Fleet Control Tower & Telemetry Map (`/dispatch/live`)
- **Evaluated Viewport:** High-Density Desktop Canvas (`1920x1080`)
- **Theme:** Midnight Oceanic (`#0f172a`)
- **Wireframe Layout Blueprint:**
```
┌──────────────────────────────────────────────────────────────────────────────────────────────────┐
│ LIVE OPERATIONS CONTROL TOWER — Active Vehicles: 48 | On Time: 45 | Delayed: 3                   │
├────────────────────────────────────────────────────────┬─────────────────────────────────────────┤
│ LIVE GPS TELEMETRY MAP                                 │ ACTIVE VEHICLE MONITORING QUEUE         │
│                                                        │ ┌─ ⚠️ VEH-009 (Kurunegala Run) ───────┐ │
│ [Map: Sri Lanka Western & Central Corridors]           │ │ Delay: +28 mins (Traffic at Alawwa) │ │
│ 🟢 45 Trucks Green (Normal Transit)                    │ │ Stop 3 OUT051 Fresh at Risk of 08:00│ │
│ 🟡 2 Trucks Amber (15-30m Delay Risk)                  │ │ [Reroute] [Call Driver] [Alert Store│ │
│ 🔴 1 Truck Red (Breakdown / Reefer Hazard)             │ └─────────────────────────────────────┘ │
│                                                        │ ┌─ 🟢 VEH-003 (Saman K.) ─────────────┐ │
│                                                        │ │ Stop 2 OUT007 Completed 07:12 AM    │ │
│                                                        │ │ Stop 3 OUT012 In Transit (ETA 07:38)│ │
│                                                        │ └─────────────────────────────────────┘ │
├────────────────────────────────────────────────────────┴─────────────────────────────────────────┤
│ LIVE TIMELINE: 04:00 Departs [===] 06:00 Fresh Stores [===] 08:00 Opens [---] 14:00 Malls [---]  │
└──────────────────────────────────────────────────────────────────────────────────────────────────┘
```
- **Operational Rationale:**
  Prior to this system, dispatchers were completely blind once trucks rolled out of the Peliyagoda gates, only discovering delays when furious store managers telephoned after 8:00 AM. The Live Operations Map continuously synthesizes field driver GPS pings and unload timestamps against pre-8 AM delivery windows, instantly highlighting lagging routes so the dispatcher can intervene hours before a delivery window is breached.

---

### Screen DISP-06: Vehicle Telemetry & Fuel Quota Inspector Drawer (`/dispatch/vehicle/[vehicle_id]`)
- **Evaluated Viewport:** Right Flyout Drawer (`480px` width)
- **Theme:** Midnight Oceanic Elevated (`#1e293b`)
- **Wireframe Layout Blueprint:**
```
┌────────────────────────────────────────────────┐
│ VEHICLE TELEMETRY: VEH-003 (Isuzu 4.5T Reefer) │
├────────────────────────────────────────────────┤
│ STATUS: In Transit (Stop 2 of 4)               │
│ Driver: Saman K. (Driver ID: DRV-084)          │
│ Home Depot: Peliyagoda Central DC              │
├────────────────────────────────────────────────┤
│ ⛽ WEEKLY FUEL QUOTA TRACKER                   │
│ Quota Allowance: 1,200 km / week               │
│ Consumed to Date: 840 km (70%)                 │
│ Today's Route: 164 km (Feasible within Quota)  │
├────────────────────────────────────────────────┤
│ 🌡️ REEFER SENSOR TELEMETRY                    │
│ Cargo Box Temp: 2.8°C (Setpoint: 3.0°C) [✓ OK] │
│ Compressor Status: Active Cycling              │
├────────────────────────────────────────────────┤
│ ⏱️ DRIVER REST COMPLIANCE                      │
│ Shift Duration: 3h 45m | Rest Breaks Taken: 1  │
│ [Initiate Remote Ping]   [Reassign Remaining]  │
└────────────────────────────────────────────────┘
```
- **Operational Rationale:**
  Allows the dispatcher to inspect detailed operational diagnostics for an individual vehicle without navigating away from the live operations map. Surfaces critical fuel quota compliance, real-time reefer compressor temperatures, and driver duty hours to ensure regulatory and operational standards are maintained.

---

### Screen DISP-07: Emergency Breakdown Stop Reallocation Canvas (`/dispatch/emergency/handoff/[trip_id]`)
- **Evaluated Viewport:** High-Density Desktop Modal (`1200px` width)
- **Theme:** Midnight Oceanic Elevated (`#1e293b`)
- **Wireframe Layout Blueprint:**
```
┌────────────────────────────────────────────────────────────────────────┐
│ 🚨 EMERGENCY ROUTE RESCUE: VEH-012 Stranded (Engine Failure at Nittambuwa│
├──────────────────────────────────┬─────────────────────────────────────┤
│ STRANDED CARGO (VEH-012)         │ RESCUE VEHICLE CANDIDATES (Nearby)  │
│ • Stop 3: OUT044 Fresh (Chilled) │ ┌─ VEH-019 (3.2 km away - Empty) ─┐ │
│   2 Pallets | 480 kg | 1.2 m³    │ │ Tata 4.5T Reefer | Spare: 8.0 m³ │ │
│ • Stop 4: OUT048 Fresh (Chilled) │ │ Capable of adopting Stops 3 & 4 │ │
│   3 Pallets | 720 kg | 1.8 m³    │ │ [ADOPT REMAINING STOPS (VEH-019)]│ │
│ Total Cargo at Risk: 1,200 kg    │ └─────────────────────────────────┘ │
│ Reefer Battery Backup: 54m Left  │ ┌─ VEH-024 (12 km away) ──────────┐ │
│                                  │ │ Insufficient chilled volume (3m³)│ │
├──────────────────────────────────┴─────────────────────────────────────┤
│ [Broadcast SMS Handoff to Both Drivers]  [Confirm Emergency Transfer]  │
└────────────────────────────────────────────────────────────────────────┘
```
- **Operational Rationale:**
  When a truck breaks down mid-route, dispatchers must rapidly reallocate remaining stops to a nearby vehicle with compatible temperature capability and available volume. This rescue canvas calculates geographic proximity and vehicle constraints to present viable rescue trucks, instantly pushing reassigned manifests to the rescue driver's smartphone.

---

### Screen DISP-08 (Degradation Screen 1): Capacity Overload "Death Spiral" Manager (`/dispatch/crisis/overload`)
- **Evaluated Viewport:** High-Density Desktop Canvas (`1920x1080`)
- **Theme:** Midnight Oceanic (High-Contrast Crisis Styling)
- **Wireframe Layout Blueprint:**
```
┌──────────────────────────────────────────────────────────────────────────────────────────────────┐
│ 🚨 SYSTEM DEGRADATION MODE: FESTIVAL DEMAND CAPACITY OVERLOAD (Total Demand: 134.2% of Capacity)│
├──────────────────────────────────────────────────────────────────────────────────────────────────┤
│ MITIGATION STRATEGY SELECTOR:                                                                    │
│                                                                                                  │
│ [ STRATEGY A: COMMERCIAL QUOTA MODE ]    │ [ (•) STRATEGY B: SERVICE EQUITY RATIONING MODE ]     │
│ Maximizes delivered tonnage and revenue. │ Enforces pro-rata 50% basket distribution across all  │
│ Cuts 24 remote rural stores completely.  │ 120 stores. Guarantees zero store stockouts.         │
├──────────────────────────────────────────┴───────────────────────────────────────────────────────┤
│ SIMULATED IMPACT MATRIX:                                                                         │
│ • Stores Receiving Deliveries: 120 / 120 (100% Equity Coverage)                                  │
│ • Average Basket Fill Rate: 72.4% (Critical Staples Prioritized)                                │
│ • Total Fuel Consumed: 94% of Weekly Quota (Within Limits)                                      │
│ • Deferral Taxonomy Applied: "Systemic Festival Rationing Protocol B-4"                         │
├──────────────────────────────────────────────────────────────────────────────────────────────────┤
│ [Cancel Simulation]                     [EXECUTE EMERGENCY ALLOCATION & NOTIFY ALL STORES]      │
└──────────────────────────────────────────────────────────────────────────────────────────────────┘
```
- **Operational Rationale (Justification for Booklet 15% Degradation Requirement):**
  During seasonal festival peaks (Sinhala/Tamil New Year, Christmas) and national paydays, consumer grocery demand surges up to 40% above maximum fleet capacity. Without an automated degradation workflow, dispatchers enter a cognitive "death spiral," making arbitrary deferrals that collapse service in remote districts. This screen provides structured crisis decision support, allowing management to switch into transparent, policy-backed rationing modes in seconds.

---

## 6. Module 3: Depot Hub Loader Experience (`/depot`)
*Persona: Nuwan | Environment: Peliyagoda/Kandy Docks, 10" Tablet PWA | Theme: Midnight Oceanic High-Contrast*

### Screen LOAD-01: Bay Staging & Departure Countdown Queue (`/depot/queue`)
- **Evaluated Viewport:** `768px` - `1024px` Tablet PWA
- **Theme:** Midnight Oceanic (High Contrast `#0f172a`) — **Fatigue Targets $\ge 64\text{px}$**
- **Wireframe Layout Blueprint:**
```
┌────────────────────────────────────────────────────────────────────────┐
│ PELIYAGODA LOADING DOCKS | Graveyard Shift     [Nuwan - Bay Supervisor]│
├────────────────────────────────────────────────────────────────────────┤
│ ACTIVE BAY LOADING QUEUE (Sorted by Scheduled Departure Time):         │
│                                                                        │
│ ┌─ BAY 04: VEH-003 (Isuzu 4.5T Reefer) ── DEPARTS IN: 42 MINS ──────┐ │
│ │ Driver: Saman K. | Destination: Mount Lavinia / Moratuwa / Panadura │ │
│ │ Cargo: 12 Pallets Chilled Dairy | Staged at Cold Room Door 2        │ │
│ │ Status: Staging Complete ──► [START LIFO LOADING CHECKLIST (64px)] │ │
│ └─────────────────────────────────────────────────────────────────────┘ │
│                                                                        │
│ ┌─ BAY 02: VEH-014 (Hino 5.5T Dry Truck) ── DEPARTS IN: 1h 15m ─────┐ │
│ │ Driver: Sunil P. | Destination: Style Malls Route                   │ │
│ │ Cargo: 18 Pallets Hanging Garments & Cartons                        │ │
│ │ Status: In Staging ──────► [START PRE-LOAD INSPECTION (64px)]       │ │
│ └─────────────────────────────────────────────────────────────────────┘ │
└────────────────────────────────────────────────────────────────────────┘
```
- **Operational Rationale:**
  Warehouse loading docks are high-stress environments where multiple trucks vie for limited dock space between midnight and 4:00 AM. This queue organizes the dock supervisor's night strictly around departure deadlines, ensuring vehicles serving distant provinces with strict pre-8 AM store opening windows are staged, loaded, and released first.

---

### Screen LOAD-02: Vehicle Dock Pre-Load Inspection (`/depot/inspect/[trip_id]`)
- **Evaluated Viewport:** `768px` - `1024px` Tablet PWA
- **Theme:** Midnight Oceanic (High Contrast) — **Fatigue Targets $\ge 64\text{px}$**
- **Wireframe Layout Blueprint:**
```
┌────────────────────────────────────────────────────────────────────────┐
│ PRE-LOAD BAY INSPECTION: VEH-003 (Bay 04)                              │
├────────────────────────────────────────────────────────────────────────┤
│ TAP EACH ITEM TO VERIFY (64px Touch Targets):                         │
│                                                                        │
│ [ ✓ PASS ] 1. Cargo Box Clean & Odor-Free                              │
│                                                                        │
│ [ ✓ PASS ] 2. Floor Dry & Free of Chemical Spills                     │
│                                                                        │
│ [ ✓ PASS ] 3. Reefer Pre-Cooled to Setpoint (Current: 3.1°C <= 4.0°C) │
│                                                                        │
│ [ ✓ PASS ] 4. Load Bars & Cargo Securing Straps Onboard (Min 4 Straps)│
├────────────────────────────────────────────────────────────────────────┤
│ [Report Bay Rejection Defect]          [PROCEED TO LIFO LOADING (64px)]│
└────────────────────────────────────────────────────────────────────────┘
```
- **Operational Rationale:**
  Loading chilled groceries into a dirty or warm truck cargo box causes food safety violations and spoilage before the truck ever leaves the depot. This pre-load gate ensures the loader physically validates the cleanliness, reefer pre-cooling, and cargo straps of the vehicle before any pallets are fork-lifted inside.

---

### Screen LOAD-03: Reverse-Sequence LIFO Loading Checklist (`/depot/load/[trip_id]`)
- **Evaluated Viewport:** `768px` - `1024px` Tablet PWA
- **Theme:** Midnight Oceanic (High Contrast) — **Fatigue Targets $\ge 64\text{px}$**
- **Wireframe Layout Blueprint:**
```
┌────────────────────────────────────────────────────────────────────────┐
│ LIFO LOADING: VEH-003 (Bay 04) | Load from Front to Rear Door          │
├────────────────────────────────────────────────────────────────────────┤
│ 🚚 2D TRUCK CROSS-SECTION COMPARTMENT GUIDE:                           │
│ [FRONT CAB] ──► [CHILLED ZONE 0-4°C] ──► [AMBIENT ZONE] ──► [REAR DOOR]│
├────────────────────────────────────────────────────────────────────────┤
│ LOAD ORDER (STRICT REVERSE UNLOAD SEQUENCE):                           │
│                                                                        │
│ 1. [LOAD FIRST - FRONT CAB] OUT021 Panadura (Last Stop on Route)       │
│    [ ✓ LOADED ] Pallet #P-101 Highland Milk (Chilled) — 480 kg         │
│    [ ✓ LOADED ] Pallet #P-102 Highland Yoghurt (Chilled) — 320 kg      │
│                                                                        │
│ 2. [LOAD SECOND - MID CAB]  OUT012 Moratuwa (Stop 2 on Route)          │
│    [ ✓ LOADED ] Pallet #P-103 Fresh Poultry (Chilled) — 420 kg         │
│                                                                        │
│ 3. [LOAD LAST - REAR DOOR]  OUT007 Mount Lavinia (Stop 1 on Route)     │
│    [   LOAD   ] Pallet #P-104 Highland Milk (Chilled) — 510 kg [SCAN]  │
├────────────────────────────────────────────────────────────────────────┤
│ [Flag Shortage / Damaged Crate]        [PROCEED TO SEAL PROTOCOL]      │
└────────────────────────────────────────────────────────────────────────┘
```
- **Operational Rationale:**
  Loading goods in the wrong physical sequence forces delivery drivers to unload and restack heavy pallets on dark curbsides to access goods for earlier stops, causing severe delays and chilled food spoilage. This checklist strictly enforces Last-In, First-Out (LIFO) loading logic with massive tap targets designed for workers with gloved hands operating in noisy warehouse bays.

---

### Screen LOAD-04: Pallet Shortfall & Crate Damage Flag Modal (`/depot/load/[trip_id]/exception`)
- **Evaluated Viewport:** Full-Screen Tablet Modal (`768px`)
- **Theme:** Midnight Oceanic Elevated (`#1e293b`)
- **Wireframe Layout Blueprint:**
```
┌────────────────────────────────────────────────────────────────────────┐
│ ⚠️ RECORD DOCK SHORTAGE / PALLET DAMAGE: VEH-003                       │
├────────────────────────────────────────────────────────────────────────┤
│ AFFECTED PALLET: Pallet #P-104 (OUT007 Fresh Mount Lavinia)            │
│ SKU: Highland Whole Milk 1L Packets                                    │
│ Expected Count: 40 Crates                                              │
│                                                                        │
│ ACTUAL PHYSICALLY LOADED COUNT: [ 3 | 6 ] Crates                       │
│ SHORTFALL: 4 Crates Missing / Damaged                                  │
│                                                                        │
│ REASON: [ (•) Damaged by Forklift ] [ Cold Room Stock Depleted ]       │
├────────────────────────────────────────────────────────────────────────┤
│ [Snap Photo of Damaged Crate]                                          │
│ [Cancel]                               [CONFIRM SHORTFALL & RECALC]    │
└────────────────────────────────────────────────────────────────────────┘
```
- **Operational Rationale:**
  Historically, when warehouse stock was missing or damaged at the dock, loaders simply scribbled handwritten notes on paper manifests or said nothing, leaving drivers to discover shortfalls at the customer dock. This screen catches discrepancies on the loading dock before the truck wheels roll, instantly updating the digital invoice and notifying dispatch to prevent customer disputes.

---

### Screen LOAD-05: Tamper-Evident Security Seal Locking (`/depot/release/[trip_id]`)
- **Evaluated Viewport:** `768px` - `1024px` Tablet PWA
- **Theme:** Midnight Oceanic (High Contrast) — **Fatigue Targets $\ge 64\text{px}$**
- **Wireframe Layout Blueprint:**
```
┌────────────────────────────────────────────────────────────────────────┐
│ GATE RELEASE & SECURITY SEAL: VEH-003 (Bay 04)                         │
├────────────────────────────────────────────────────────────────────────┤
│ 🔒 STEP 1: APPLY PLASTIC TAMPER-EVIDENT BOLT SEAL TO REAR DOOR:        │
│ Punch in the physical 6-digit seal serial number:                      │
│                                                                        │
│              [ S L  -  9  9  4  2 ]                                    │
│              [ 1 ]  [ 2 ]  [ 3 ]                                       │
│              [ 4 ]  [ 5 ]  [ 6 ]                                       │
│              [ 7 ]  [ 8 ]  [ 9 ]                                       │
│              [Clear] [ 0 ] [Back]                                      │
├────────────────────────────────────────────────────────────────────────┤
│ 🌡️ STEP 2: VERIFY FINAL REEFER TEMPERATURE AT RELEASE:                 │
│ Reading: [ 2.9°C ]  ──► (Confirmed $\le 4.0^\circ C$) [✓ PASS]         │
├────────────────────────────────────────────────────────────────────────┤
│ ══════════════► SWIPE RIGHT TO ISSUE DIGITAL GATE PASS ══════════════► │
└────────────────────────────────────────────────────────────────────────┘
```
- **Operational Rationale:**
  The physical security and cold-chain compliance of high-value electronics and perishable dairy depends entirely on the pre-departure seal. This screen enforces a mandatory digital gate-pass protocol: the loader must input the physical seal serial number and verify reefer temperature before the vehicle is legally permitted to clear the depot security gate.

---

### Screen LOAD-06 (Degradation Screen 2): Mid-Load Dispatch Revision Lockout (`[Alert Modal]`)
- **Evaluated Viewport:** Full-Screen Tablet Modal (`768px`)
- **Theme:** Industrial Amber Alert (`#f59e0b` / `#000000`)
- **Wireframe Layout Blueprint:**
```
┌────────────────────────────────────────────────────────────────────────┐
│ 🛑 STOP LOADING IMMEDIATELY! — DISPATCH PLAN REVISED BY KAMAL          │
├────────────────────────────────────────────────────────────────────────┤
│ VEH-003 MANIFEST MUTATION DETECTED AT 02:48 AM:                        │
│                                                                        │
│ ❌ REMOVED FROM VEHICLE:                                               │
│ • Pallet #P-101 (OUT021 Panadura) — 2 Crates Ice Cream Cancelled       │
│                                                                        │
│ ➕ ADDED TO VEHICLE (PRIORITY S1 CORRIDOR):                            │
│ • Pallet #P-118 (OUT009 Fresh Moratuwa North) — 2 Crates Chilled Butter│
│                                                                        │
│ 📋 RESTAGING INSTRUCTION:                                              │
│ Pallet #P-118 must be staged in MID-CAB COMPARTMENT to preserve LIFO!  │
├────────────────────────────────────────────────────────────────────────┤
│ ═════► PRESS AND HOLD FOR 2 SECONDS TO ACKNOWLEDGE REVISION ═════►     │
└────────────────────────────────────────────────────────────────────────┘
```
- **Operational Rationale (Justification for Booklet 15% Degradation Requirement):**
  When a dispatcher modifies a vehicle's route plan while the loader is physically midway through packing the truck, paper-based operations lead to catastrophic errors (wrong goods loaded, reverse sequence destroyed). This degradation screen freezes the loader's tablet with an unmissable amber alert, highlighting the exact pallet adjustments needed to maintain sequence without unloading the entire truck.

---

## 7. Module 4: Field Delivery Driver Experience (`/field`)
*Persona: Saman | Environment: Truck Cab Dashboard Mount, 3:00 AM Glare, Rural Offline | Theme: Midnight Oceanic High-Contrast*

### Screen DRV-01: Pre-Trip Inspection (PTI) & Reefer Gate (`/field/pti`)
- **Evaluated Viewport:** Smartphone Mobile PWA (`390x844` Phone Viewport)
- **Theme:** Midnight Oceanic (High Contrast) — **Fatigue Targets $\ge 64\text{px}$**
- **Wireframe Layout Blueprint:**
```
┌──────────────────────────────────────────────────┐
│ VEH-003 | PRE-TRIP INSPECTION          [Saman K.]│
├──────────────────────────────────────────────────┤
│ MANDATORY SAFETY GATEWAY (Tap to Verify):        │
│                                                  │
│ [ ✓ PASS ] 1. Tires & Wheel Nuts                 │
│                                                  │
│ [ ✓ PASS ] 2. Foot & Hand Brakes                 │
│                                                  │
│ [ ✓ PASS ] 3. Headlights & Hazard Flashers       │
│                                                  │
│ [ ✓ PASS ] 4. Windshield Wipers & Washer Fluid   │
│                                                  │
│ [ ✓ PASS ] 5. Fuel Tank Level >= 80% Full        │
│                                                  │
│ [ ✓ PASS ] 6. Reefer Temp: 2.9°C (<= 4.0°C) [✓]  │
│                                                  │
│ [ ✓ PASS ] 7. Rear Door Bolt Seal SL-9942 Intact │
├──────────────────────────────────────────────────┤
│ [REPORT DEFECT]   [UNLOCK ROUTE & DEPART (64px)] │
└──────────────────────────────────────────────────┘
```
- **Operational Rationale:**
  Breakdowns on rural Sri Lankan trunk roads cause massive delivery delays and perishable stock spoilage. Mandating a rapid, 90-second digital Pre-Trip Inspection (PTI) with cold-chain temperature verification ensures mechanical defects and faulty cooling compressors are caught inside the depot maintenance yard rather than halfway up the Kandy mountain corridor.

---

### Screen DRV-02: Master Offline-First Route Card & Rest Break Tracker (`/field/route`)
- **Evaluated Viewport:** Smartphone Mobile PWA (`390x844` Phone Viewport)
- **Theme:** Midnight Oceanic (High Contrast) — **Fatigue Targets $\ge 64\text{px}$**
- **Wireframe Layout Blueprint:**
```
┌──────────────────────────────────────────────────┐
│ TRIP 1: Peliyagoda ──► Panadura    [🟢 100% SYNC]│
├──────────────────────────────────────────────────┤
│ ⏱️ REST BREAK MONITOR                            │
│ Continuous Driving: 2h 15m (Max: 4h 00m)         │
│ [TAKE 15-MIN TEA / REST BREAK (64px)]            │
├──────────────────────────────────────────────────┤
│ STOPS SEQUENCE (3 Outlets Today):                │
│                                                  │
│ ┌─ STOP 1: OUT007 Fresh Mount Lavinia ─────────┐ │
│ │ Delivery Window: 06:00 - 08:00 AM (Pre-Open) │ │
│ │ ETA: 07:15 AM (18 mins away)                 │ │
│ │ Cargo: 4 Pallets Chilled | Rear Door Access  │ │
│ │ ──► [OPEN STOP INSTRUCTIONS & NAVIGATE]      │ │
│ └──────────────────────────────────────────────┘ │
│                                                  │
│ ┌─ STOP 2: OUT012 Fresh Moratuwa ──────────────┐ │
│ │ Delivery Window: 06:30 - 08:00 AM (Pre-Open) │ │
│ │ Status: Queued | Next Stop                   │ │
│ └──────────────────────────────────────────────┘ │
│                                                  │
│ ┌─ STOP 3: OUT021 Fresh Panadura ──────────────┐ │
│ │ Delivery Window: 07:00 - 08:00 AM (Pre-Open) │ │
│ │ Status: Queued | Final Drop                  │ │
│ └──────────────────────────────────────────────┘ │
├──────────────────────────────────────────────────┤
│ [🚨 REPORT EMERGENCY / BREAKDOWN]                │
└──────────────────────────────────────────────────┘
```
- **Operational Rationale:**
  Drivers operate along mountain corridors (e.g., Kandy, Nuwara Eliya) where cellular connectivity frequently drops to zero for hours. This screen serves as the offline-first operational anchor: it functions completely without an internet connection, allowing the driver to view stop sequences, track driving hours for labor law compliance, and navigate without data freezes.

---

### Screen DRV-03: Turn-by-Turn Dock Navigation & Stop Arrival (`/field/stop/[stop_id]`)
- **Evaluated Viewport:** Smartphone Mobile PWA (`390x844` Phone Viewport)
- **Theme:** Midnight Oceanic (High Contrast) — **Fatigue Targets $\ge 64\text{px}$**
- **Wireframe Layout Blueprint:**
```
┌──────────────────────────────────────────────────┐
│ STOP 1 of 3: OUT007 Fresh Mount Lavinia          │
├──────────────────────────────────────────────────┤
│ 📍 DOCK & ACCESS INSTRUCTIONS:                   │
│ • Entrance: Rear service alley off Station Road  │
│ • Clearance: 3.8m canopy (Heavy truck OK)        │
│ • Night Guard Contact: Nimal (071-xxxxxxx)       │
│                                                  │
│ [ 🗺️ OPEN IN EXTERNAL MAPS (Google / Waze) ]    │
│ [ 📞 CALL STORE MANAGER (Priya)            ]     │
├──────────────────────────────────────────────────┤
│ ARRIVAL REGISTRATION:                            │
│ Scheduled Window: 06:00 AM - 08:00 AM            │
│ Current Time: 07:12 AM [✓ ON TIME]               │
│                                                  │
│ ┌──────────────────────────────────────────────┐ │
│ │       [ MARK ARRIVED AT DOCK (64px) ]        │ │
│ └──────────────────────────────────────────────┘ │
├──────────────────────────────────────────────────┤
│ [Report Dock Access Blocker (Gate Closed/Blocked)│
└──────────────────────────────────────────────────┘
```
- **Operational Rationale:**
  Store delivery locations vary drastically between curb-side drops, shared mall loading bays, and tight rear alleys. This screen surfaces critical dock instructions and contact shortcuts immediately upon arrival, enabling the driver to log arrival with a single tap and alert the store manager without fumbling through paper paperwork in the dark.

---

### Screen DRV-04: Proof of Delivery (PoD) & Signature Capture (`/field/stop/[stop_id]/pod`)
- **Evaluated Viewport:** Smartphone Mobile PWA (`390x844` Phone Viewport)
- **Theme:** Midnight Oceanic (High Contrast) — **Fatigue Targets $\ge 64\text{px}$**
- **Wireframe Layout Blueprint:**
```
┌──────────────────────────────────────────────────┐
│ PROOF OF DELIVERY: OUT007 Mount Lavinia          │
├──────────────────────────────────────────────────┤
│ [ ✓ ] Bolt Seal SL-9942 Cut in Presence of Mgr   │
│ [ ✓ ] 4 Pallets Chilled Milk Transferred to Dock │
│                                                  │
│ ✍️ STORE MANAGER SIGNATURE:                      │
│ ┌──────────────────────────────────────────────┐ │
│ │                                              │ │
│ │             Priya Kulatunga                  │ │
│ │                                              │ │
│ └──────────────────────────────────────────────┘ │
│ Manager Name: Priya K. | Staff ID: SM-0412       │
│ Auto GPS Stamp: 6.8341° N, 79.8652° E | 07:28 AM │
├──────────────────────────────────────────────────┤
│ ┌──────────────────────────────────────────────┐ │
│ │   [ SAVE PoD TO PHONE & COMPLETE STOP ]      │ │
│ │   (Syncs automatically when signal returns)  │ │
│ └──────────────────────────────────────────────┘ │
└──────────────────────────────────────────────────┘
```
- **Operational Rationale:**
  Disputes over missing cases or damaged goods frequently occurred when drivers relied on handwritten paper run sheets that were lost or illegible. The digital PoD captures the verified seal state, itemized exceptions, and manager signature directly onto the phone's local storage, ensuring delivery proof is immutable even when completing deliveries inside subterranean concrete mall basements with zero mobile signal.

---

### Screen DRV-05: Store Dock Blocker & Exception Modal (`/field/stop/[stop_id]/exception`)
- **Evaluated Viewport:** Smartphone Mobile PWA (`390x844` Phone Viewport)
- **Theme:** Midnight Oceanic Elevated (`#1e293b`)
- **Wireframe Layout Blueprint:**
```
┌──────────────────────────────────────────────────┐
│ ⚠️ REPORT UNLOADING BLOCKER: OUT007              │
├──────────────────────────────────────────────────┤
│ SELECT BLOCKER REASON:                           │
│ [ (•) Alley Blocked by 3rd-Party Vehicle       ] │
│ [     Store Receiving Staff Missing / No Answer] │
│ [     Canopy Gate Locked / Guard Absent        ] │
│ [     Store Power Outage / Cold Room Full      ] │
├──────────────────────────────────────────────────┤
│ [ 📸 TAKE PHOTO PROOF OF BLOCKER ]               │
│ Photo Attached: alley_blocked_lorry.jpg          │
│                                                  │
│ Timer: 15-minute wait allowance running...       │
├──────────────────────────────────────────────────┤
│ [Alert Central Dispatch]      [ABORT STOP (64px)]│
└──────────────────────────────────────────────────┘
```
- **Operational Rationale:**
  Protects drivers from unjust penalties when delays are caused by physical store obstacles (e.g. locked gates or blocked alleys). By snapping a photo and logging an official blocker, the driver automatically alerts dispatch and store management, establishing a factual record before moving to the next stop.

---

### Screen DRV-06: Driver Rest Break & Labor Law Pause (`/field/break`)
- **Evaluated Viewport:** Smartphone Mobile PWA (`390x844` Phone Viewport)
- **Theme:** Midnight Oceanic (High Contrast)
- **Wireframe Layout Blueprint:**
```
┌──────────────────────────────────────────────────┐
│ REST BREAK IN PROGRESS                 [VEH-003] │
├──────────────────────────────────────────────────┤
│ ☕ MANDATORY LABOR LAW REST PAUSE                │
│                                                  │
│              [ 1 4 : 2 0 ]                       │
│           MINUTES REMAINING                      │
│                                                  │
│ Location: Wadduwa Rest Stop (A2 Highway)         │
│ Dispatch Status: Non-Punitive Rest Logged [✓]    │
│ Reefer Status: Temperature Safe (3.1°C) [✓]      │
├──────────────────────────────────────────────────┤
│ Notice: Takes at least 15 minutes to reset your  │
│ continuous driving fatigue allowance.            │
├──────────────────────────────────────────────────┤
│ ┌──────────────────────────────────────────────┐ │
│ │        [ END BREAK & RESUME ROUTE ]          │ │
│ └──────────────────────────────────────────────┘ │
└──────────────────────────────────────────────────┘
```
- **Operational Rationale:**
  Fatigue on 3:00 AM rural shifts is a primary cause of road accidents. This dedicated break screen provides a transparent, non-punitive timer that complies with Sri Lankan labor regulations, giving drivers psychological permission to take tea/rest breaks while assuring dispatch that the vehicle is stationary for legitimate rest rather than mechanical failure.

---

### Screen DRV-07 (Degradation Screen 3): Mid-Route Breakdown Emergency (`/field/emergency/breakdown`)
- **Evaluated Viewport:** Smartphone Mobile PWA (`390x844` Phone Viewport)
- **Theme:** Industrial Amber Alert (`#f59e0b` / `#000000`)
- **Wireframe Layout Blueprint:**
```
┌──────────────────────────────────────────────────┐
│ 🚨 VEHICLE EMERGENCY / BREAKDOWN       [VEH-003] │
├──────────────────────────────────────────────────┤
│ SELECT EMERGENCY TYPE:                           │
│ [ (•) Mechanical Engine Failure               ] │
│ [     Reefer Compressor Failure (Cold Hazard) ] │
│ [     Traffic Accident / Road Impasse         ] │
├──────────────────────────────────────────────────┤
│ ⏱️ REEFER SPOILAGE COUNTDOWN TIMER:              │
│ [ 0 1 : 2 4 : 1 0 ] (1h 24m to 4.0°C Breach)     │
├──────────────────────────────────────────────────┤
│ 📡 TELEMETRY STATUS:                             │
│ GPS Broadcast: 6.7812° N, 79.8920° E (Alawwa)    │
│ Network: OFFLINE ──► Broadcast via SMS Gateway [✓]│
│ Alert Sent to: Dispatcher Kamal (Peliyagoda)     │
├──────────────────────────────────────────────────┤
│ ┌──────────────────────────────────────────────┐ │
│ │  [ GENERATE QR MANIFEST FOR RESCUE VEHICLE ] │ │
│ └──────────────────────────────────────────────┘ │
└──────────────────────────────────────────────────┘
```
- **Operational Rationale (Justification for Booklet 15% Degradation Requirement):**
  When a refrigerated truck breaks down on a rural highway, chilled dairy and meats face total spoilage within two hours. This degradation screen immediately classifies the incident, triggers a perishable countdown timer, broadcasts vehicle coordinates through offline-tolerant SMS fallbacks, and prepares a digital handoff manifest so a backup vehicle can scan and adopt the remaining stops seamlessly.

---

### Screen DRV-08: End-of-Trip Summary & Reconciliation (`/field/trip-end`)
- **Evaluated Viewport:** Smartphone Mobile PWA (`390x844` Phone Viewport)
- **Theme:** Midnight Oceanic (High Contrast)
- **Wireframe Layout Blueprint:**
```
┌──────────────────────────────────────────────────┐
│ TRIP COMPLETION: Trip #TRIP-104 (VEH-003)        │
├──────────────────────────────────────────────────┤
│ 🏁 SUMMARY OF DELIVERIES                         │
│ • Stops Completed: 3 / 3 (100% Delivery Success) │
│ • Total Distance Traveled: 68.4 km               │
│ • Empty Crates Returned: 42 Crates Onboard       │
│ • Fuel Consumed Today: 18.2 L                    │
├──────────────────────────────────────────────────┤
│ 🔄 OFFLINE SYNC RECONCILIATION                   │
│ All 3 PoD Records Successfully Synced to Server! │
│ Database Status: Zero Pending Offline Mutations  │
├──────────────────────────────────────────────────┤
│ ┌──────────────────────────────────────────────┐ │
│ │    [ RETURN VEHICLE KEYS & CHECK IN (64px) ] │ │
│ └──────────────────────────────────────────────┘ │
└──────────────────────────────────────────────────┘
```
- **Operational Rationale:**
  Closes the operational loop at the end of the shift. Summarizes completed deliveries, records physical empty crate returns, verifies that all offline IndexedDB mutation queues are synchronized with the central database, and provides the driver with a digital clearance pass before clocking out.

---

## 8. Cross-Actor Reactive Event Bus & State Triggers

The following matrix documents how actions on one screen instantaneously mutate state and UI across the other actors:

| Originating Screen & Actor | User Action | Event Dispatched | Target Screen & Mutated State |
|---|---|---|---|
| **STORE-02** (Priya) | Taps "Submit Order" before 16:00 | `ORDER_CONFIRMED` | **DISP-02** Unallocated queue increments; weight/volume totals reactively update. |
| **DISP-02** (Kamal) | Taps "Lock Plan" at 16:30 | `PLAN_LOCKED` | **LOAD-01** Populates bay staging queues; generates LIFO sequences on **LOAD-03**. |
| **DISP-04** (Kamal) | Defers order due to capacity | `ORDER_DEFERRED` | **STORE-04** Appends audit row with reason; sends automated SMS to Store Manager. |
| **LOAD-04** (Nuwan) | Flags 4 missing crates on dock | `SHORTFALL_RECORDED` | **STORE-01** Decrements expected delivery count; **DISP-05** logs shortfall alert. |
| **LOAD-05** (Nuwan) | Inputs Seal `SL-9942` & Swipes | `TRUCK_SEALED` | **DRV-01** Unlocks route on Saman's smartphone; **STORE-05** registers expected seal ID. |
| **DRV-01** (Saman) | Passes 9-Point PTI checklist | `PTI_PASSED` | **DISP-05** Vehicle status switches from "Pre-Trip" to "Departed Depot". |
| **DRV-03** (Saman) | Taps "Mark Arrived at Dock" | `STOP_ARRIVED` | **STORE-01** Banner flashes: "Truck Arrived at Dock"; **DISP-05** updates ETA accuracy. |
| **DRV-04** (Saman) | Manager signs PoD on phone | `DELIVERY_COMPLETED` | **STORE-01** Order marked "Delivered"; **DISP-05** stop pin turns Emerald Green. |
| **DRV-07** (Saman) | Triggers "Emergency Breakdown" | `CRITICAL_BREAKDOWN` | **DISP-05** Vehicle flashes red; opens **DISP-07** Emergency Rescue Handoff Canvas. |

---

## 9. Comprehensive Degradation & Failure Scenario Matrix (15% Judging Weight)

| Failure Scenario | Named Scenario Title | Triggering Cause | Primary Recovery Flow | Business & Human Value |
|---|---|---|---|---|
| **Degradation 1** | **Festival Demand Capacity Collapse** | Daily demand exceeds physical fleet capacity by $>30\%$ ahead of Sinhala/Tamil New Year. | Dispatcher switches system to **Service Equity Mode** via `DISP-08`, automatically allocating 50% pro-rata baskets across all 120 stores. | Prevents complete grocery stockouts in rural districts, avoids erratic dispatcher panic, and provides transparent mathematical rationing. |
| **Degradation 2** | **Mid-Load Dispatch Revision Alert** | Central Dispatcher alters a vehicle trip manifest while the loader is 70% packed. | Bay tablet locks with full-screen pulsating amber alert (`LOAD-06`), detailing exact pallet diff and restaging steps. | Prevents loading wrong goods, preserves LIFO reverse sequencing, and eliminates hazardous roadside restacking by drivers. |
| **Degradation 3** | **Mid-Route Breakdown & Cold-Chain Spoilage** | Refrigeration compressor fails on rural mountain trunk road. | Driver taps Emergency (`DRV-07`), initiating 90-minute spoilage timer, SMS telemetry broadcast, and digital QR peer-to-peer handoff to rescue truck (`DISP-07`). | Prevents millions in perishable spoilage, eliminates public health food safety hazards, and rescues undelivered store orders. |

---

## 10. Scope & Restraint Justification (Booklet 15% Criterion)

To satisfy the **Restraint and Prioritization** judging criteria, the following features were **intentionally omitted**:

| Omitted Feature | Why It Was Omitted | Operational & Strategic Rationale |
|---|---|---|
| **In-App Social Chat / Free-text Messaging** | Replaced with structured taxonomies and automated event updates. | Fatigued drivers must not text while driving; warehouse loaders wear thick gloves; freeform chat creates ambiguity during disputes. Standardized taxonomies ensure clean data. |
| **Driver Payroll & Salary Management** | Replaced with labor-law driving fatigue break timers. | Driver compensation belongs in Waypoint's enterprise HRMS/payroll. Coupling payroll into a logistics dispatch tool violates clean microservice boundaries. |
| **Multi-Tier Warehouse Inventory Ledger** | Replaced with read-only WMS stock integration and dock shortfall flags. | Waypoint already maintains warehouse inventory in its central WMS. Re-implementing a warehouse ledger creates dangerous data duplication and race conditions. |
| **Customer-Facing Public Parcel Tracking** | Scoped strictly to internal B2B Store Managers and Logistics Actors. | Waypoint Group operates internal B2B retail logistics (Fresh, Style, Tech stores), not D2C courier deliveries. Public parcel tracking adds security risks without business utility. |
