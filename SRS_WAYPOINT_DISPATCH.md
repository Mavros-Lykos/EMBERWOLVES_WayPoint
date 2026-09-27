# Waypoint Delivery Planning System
# Software Requirements Specification (SRS)

> **System Name:** Waypoint Dispatch  
> **Version:** 1.0  
> **Date:** September 26, 2026  
> **Purpose:** This SRS serves as the single source of truth for the Designathon (UX), Hackathon (Engineering), and Datathon (ML) phases. Every design screen, every API endpoint, and every model feature traces back to a requirement in this document.

---

## 1. Business Context & Problems (Extracted from Challenge Booklet)

The booklet explicitly states **seven problems** the system must address. These are not suggestions — they are the acceptance criteria. Each is quoted verbatim and mapped to system capabilities.

### P1: "Planning is fragmented"
> *"Orders arrive by phone or message and are entered again in a spreadsheet. The plan depends on one dispatcher's knowledge."*

**What this means operationally:** Right now, a Store Manager at OUT054 (Fresh, Galle) calls Peliyagoda and says "I need 104 units of produce and 547 units of dairy for tomorrow." Someone writes it down. Maybe they mishear. Maybe they write 104 but it's really 140. Maybe the chilled order gets forgotten. Then the dispatcher enters it into a spreadsheet — manually — and builds the plan from memory of which vehicles go where. If that dispatcher is sick, nobody knows the routing logic.

**System must:**
- Replace phone/message ordering with structured digital order placement
- Replace spreadsheet with constraint-aware allocation engine
- Ensure the plan is reproducible, not dependent on one person's memory

### P2: "Delivery progress is difficult to track"
> *"Dispatchers usually learn about a problem only after a driver has reached the outlet."*

**What this means operationally:** The dispatcher sends VEH003 to Galle at 3:30 AM. At 5:00 AM the vehicle breaks down on the Southern Expressway. The dispatcher doesn't know until the driver calls (if they have signal). Meanwhile, 6 Galle outlets are expecting deliveries that will never arrive. Their morning stock won't be on shelves when stores open at 8 AM. The dispatcher can't reroute because they don't know yet.

**System must:**
- Show real-time delivery progress per vehicle, per stop
- Alert the dispatcher when a stop takes abnormally long
- Alert when a vehicle goes silent (possible breakdown or connectivity loss)

### P3: "Deferrals lack a clear record"
> *"Decisions made under pressure can leave the same outlet unserved on consecutive runs."*

**What this means operationally:** Look at the S1 data. S1-083 is OUT074 in Puttalam — `deferred_yesterday=1`, `days_since_last_served=5`. This Fresh outlet with a CHILLED order has been waiting 5 days. And it's about to be deferred again because there isn't enough reefer capacity. Without a record, the dispatcher doesn't even know this outlet has been skipped 5 times. They make decisions under time pressure at midnight and don't remember which outlet got bumped yesterday.

**System must:**
- Record every deferral with: which order, which outlet, what date, what reason, who decided
- Surface deferral history when showing an order to the dispatcher ("⚠️ This outlet was last served 5 days ago")
- Prevent silent consecutive deferrals — force acknowledgment

### P4: "Communication does not support feedback"
> *"Printed run sheets and verbal instructions provide no reliable way to record proof of delivery or flag a loading shortfall before departure."*

**What this means operationally:** Two separate problems in one statement:

**Loading shortfall:** The plan says "load 250 units of dairy on VEH003." But the cold room only has 220 units. The loader has no way to tell the dispatcher except by walking to the office or calling. If the loader loads 220 without flagging it, VEH003 departs, the driver arrives at OUT005, and the Store Manager expects 250. Dispute. Blame. No record.

**Proof of delivery:** The driver delivers to OUT054 and the Store Manager says "I only got 90 units, not 104." The driver says "I delivered 104." It's one person's word against another. Handwritten notes are illegible or lost. Disputes pile up. Waypoint loses money or trust.

**System must:**
- Give loaders the ability to flag shortfalls BEFORE departure (with quantity, reason)
- Automatically update the delivery manifest when a shortfall is flagged
- Capture proof of delivery (signature, photo, item-level confirmation)
- Notify Store Manager of shortfall before the vehicle even arrives

### P5: "Demand is difficult to anticipate"
> *"Waypoint cannot estimate the vehicles, drivers, or refrigerated capacity it will need ahead of paydays and festivals."*

**What this means operationally:** From the calendar data, festivals like thai_pongal ramp up over 9 days (festival_ramp 0.1 → 1.0). Paydays hit on ~25th of each month. During both, Fresh demand surges — people buy more groceries. Style demand surges before Deepavali and Christmas. If the dispatcher doesn't know this is coming, they can't pre-position reefer trucks, can't call in extra drivers, can't negotiate temporary vehicle rentals.

**System must:**
- Surface Task 2A demand forecasts: predicted volume by depot, brand, and week
- Show when demand is expected to exceed fleet capacity (early warning)
- Allow dispatcher to plan ahead: "Next week is Vesak ramp — we'll need 2 more reefer trucks"

### P6: "Service time and lateness are not predicted"
> *"Dispatchers discover delays after they have affected a delivery."*

**What this means operationally:** The booklet tells us traffic data shows Colombo at 7AM has speed_index of 47 — vehicles move at less than half the free-flow speed. A dispatcher who plans a Colombo delivery for 07:00 arrival doesn't know it'll actually arrive at 07:35. By then, it's too late for OUT008 (window closes at 07:30). If the system predicted "this stop has a 63% probability of arriving late," the dispatcher could reorder the route or reassign the vehicle.

**System must:**
- Show predicted service time per stop (from Task 1 model)
- Show lateness probability per stop
- Flag high-risk stops during planning (before dispatch, not after)

### P7: "Field connectivity is unreliable"
> *"Mobile coverage can drop across hill country, the Kandy corridor, and rural districts. Work away from the depot must remain usable offline. Records must reconcile when the connection returns."*

**What this means operationally:** A driver serving Nuwara Eliya (111 min from Kandy, hill road) or Badulla (186 min from Kandy) will lose mobile signal for potentially 30-60 minutes at a stretch. During that time they must still:
- See their next stop
- Mark stops as complete
- Record delivery quantities
- Capture proof of delivery

If the app shows a spinner or "No connection" error, the driver goes back to paper notes. The system has failed.

**System must:**
- Pre-download entire trip data to device before departure
- All stop-completion flows work offline with local storage
- Sync queue visible to the driver ("3 stops pending upload")
- Server reconciliation handles conflicts (last-write-wins with timestamp)
- Dispatcher sees "Last sync: 35 min ago" with offline indicator per vehicle
- Multi-tier fallback architecture (App → Button Phone → SMS Gateway → Printed Run Sheet)

---

## 2. The Four User Roles — Deep Persona Grounding

Each persona is built from the booklet's **exact descriptions** of working conditions, plus operational analysis from the real data.

### 2.1 Dispatcher — Kamal, Senior Dispatch Planner

**From the booklet:** *"Works at a large screen in the Peliyagoda planning office with stable connectivity. Builds the daily plan using a spreadsheet and knowledge of outlet restrictions and vehicle capabilities."*

**Working conditions:**
- **Device:** Desktop with large monitor (27"+), stable WiFi
- **Hours:** Starts at 3 PM (pre-cutoff review). Peaks at 4 PM–10 PM (planning). Monitors overnight 10 PM–8 AM
- **Stress points:** The 4 PM cutoff is the daily bottleneck. ~86 orders across 7 districts, 3 brands, must be allocated to ~30 vehicles within 4-6 hours. One wrong decision means an outlet opens with empty shelves
- **Information overload:** Kamal juggles weight limits, volume limits, reefer constraints, van-only outlets, time windows, fuel quotas, and deferral equity simultaneously. Today he does this in his head. The system must make the invisible visible
- **Emotional state:** Tension rises from 4 PM to midnight. After dispatch at 3:30 AM, anxiety about what's happening on the road. Relief when vehicles return safely

**What Kamal needs from the system (in order of urgency):**
1. **Allocation proposal** — system suggests vehicle assignments. Kamal adjusts, not builds from scratch
2. **Constraint violation visibility** — before he commits, show exactly what's infeasible and why
3. **Deferral history per outlet** — "OUT074 has been skipped 5 times this week"
4. **Fleet status dashboard** — which vehicles are available, which are in workshop, weekly fuel remaining
5. **Live tracking post-dispatch** — map with vehicle positions updating as drivers check in
6. **Exception alerts** — anomalies that need human decision (vehicle offline, stop taking too long)
7. **Demand forecast** — next week's predicted volume to pre-plan capacity
8. **Deferral decision support** — when demand exceeds capacity, suggest which orders to defer based on priority rules
9. **End-of-day report** — delivery completion rate, average lateness, deferrals by reason
10. **WMS Inventory Visibility** — alert when ordered quantity exceeds warehouse stock
11. **VIP Override & Handoffs** — capability to inject late orders and transfer trips between drivers mid-route

**What Kamal does NOT need:**
- Individual item-level details (that's the Loader's job)
- Street-level navigation (that's the Driver's job)
- Order placement interface (that's the Store Manager's job)

### 2.2 Loader — Nuwan, Warehouse Loading Operator

**From the booklet:** *"Works at the Peliyagoda or Kandy warehouse dock using a shared tablet or terminal. Printed loading lists can become outdated when plans change. Needs the stop sequence so goods can be loaded in an order that supports unloading. Needs to flag missing or damaged items before a vehicle leaves."*

**Working conditions:**
- **Device:** Shared tablet (10" screen) mounted on dock wall or carried between zones. NOT a personal device — multiple loaders may use it during a shift
- **Environment:** Warehouse dock. Noisy (forklifts, trucks idling). Temperature variation (ambient warehouse ~30°C, cold room 2-4°C). Physical work — moving cartons, pallets, crates
- **Hours:** Loading starts ~10 PM for Fresh vehicles departing at 3:30 AM. 5-6 hours of continuous physical work
- **Hands:** Often gloved (cold room work). Sometimes dirty/wet. Touch targets must be 64px minimum
- **Stress points:** Plan changes. The dispatcher finalizes at 8 PM, Nuwan starts loading at 10 PM. At 11 PM, dispatcher modifies VEH003's trip (removes an order, adds another). Nuwan's printed list is now wrong. If he doesn't see the change, he loads incorrect items

**What Nuwan needs from the system:**
1. **Vehicle-centric loading queue** — "VEH003 is at Bay 2. Here's what goes on it."
2. **Reverse-sequence loading order** — last delivery stop loaded first (deepest in vehicle), first stop last (nearest to door). This is physics — you unload from the back
3. **Temperature zone split** — "CHILLED items: load into reefer compartment FIRST" / "AMBIENT items: load after"
4. **Item checklist with quantities** — tap to confirm each item loaded. Running count
5. **Shortfall flag** — "Only 220 units of dairy in cold room, plan says 250. Flag short: 30 units." This must happen BEFORE vehicle departs. The system should block "Loading Complete" until shortfalls are acknowledged
6. **Real-time plan change alert** — unmissable visual alert (full-screen overlay) when the dispatcher modifies a trip that's currently being loaded. "TRIP UPDATED: 2 items removed. Tap to review."
7. **Loading complete & Seal Protocol** — "VEH003 Trip 1 fully loaded." Enter tamper-evident seal number to establish chain of custody. Release vehicle.

**What Nuwan does NOT need:**
- Route information (he doesn't care where the vehicle goes)
- Allocation logic (he doesn't decide what goes on which truck)
- Store Manager information (he never interacts with outlets)

### 2.3 Driver — Saman, Delivery Driver

**From the booklet:** *"Works on the road using a personal phone. Currently relies on a paper run sheet and phone calls for changes. Design interactions for use when safely stopped. Needs to record delivery outcomes and proof of delivery so disputes do not depend on memory. Needs to record work offline when coverage drops and synchronize it when connectivity returns."*

**Working conditions:**
- **Device:** Personal smartphone (varies widely — could be a budget Android with a 5.5" screen or a newer device). Limited battery life over a 6-8 hour route
- **Environment:** In a truck cab. Cannot use phone while driving. All interactions happen when safely stopped (engine off, vehicle parked at outlet)
- **Hours:** Departs depot 1:30-3:30 AM (Fresh). Returns by 8 AM if lucky. Style/Tech drivers depart 7-8 AM, return by 5-6 PM
- **Connectivity:** Reliable in Colombo and Gampaha. Drops in Kandy corridor, hill country (Nuwara Eliya speed_index drops to 45 in monsoon — roads are slow AND signal is weak), rural Puttalam. Complete blackout possible for 20-60 minutes
- **Fatigue:** Saman wakes at 1 AM for a Fresh run. By stop 5 at 6 AM, he's been awake 5 hours and has done heavy unloading at 4 stops. Interactions must be minimal, large-target, impossible to make errors
- **Physical conditions:** Monsoon rain. Dark pre-dawn roads. Loading dock at rear of outlet vs. curbside carry (dock_type matters physically)

**What Saman needs from the system:**
1. **Route card** — one screen showing: next stop, outlet name, dock type ("Use rear entrance"), items to deliver, delivery window ("Must arrive by 07:30"), predicted arrival time
2. **One-tap navigation** — opens Google Maps/Waze to next stop coordinates
3. **Stop arrival flow** — tap "Arrived" → system records actual_arrival_time → show items to hand over
4. **Delivery confirmation** — item checklist (pre-filled from plan). Tap to confirm each. Capture photo of delivery at dock OR signature on screen. "Mark stop complete"
5. **Exception buttons** — pre-built, not free text: "Outlet closed" / "No receiving staff" / "Access blocked" / "Items damaged in transit" / "Customer refused" / "Partial delivery accepted"
6. **Offline mode** — ALL of the above works without internet. Zero dependency on server. Local storage for everything. No spinners, no "loading" screens, no error dialogs
7. **Sync indicator** — always visible: green "✅ Synced" or amber "🔶 2 stops pending sync"
8. **Battery-conscious design** — dark mode, no background GPS polling (check-in only at stops), no heavy animations
9. **Language & Cognitive Load** — Trilingual interface (Sinhala/Tamil/English) with icon-first design.
10. **Pre-Trip Inspection (PTI)** — Mandatory digital safety checklist before departure.

**What Saman does NOT need:**
- Other drivers' routes (security risk + distraction)
- Allocation logic or fleet status
- Store Manager's order details beyond what he's delivering
- Any feature that requires scrolling or multi-step interaction while fatigued

### 2.4 Store Manager — Priya, Fresh Outlet Manager (OUT007, Colombo)

**From the booklet:** *"Works at the outlet counter using a desktop or phone. Places orders by phone or message without confirmation that they received or scheduled them. Needs an expected arrival time to schedule staff to receive goods. Needs clear notice when an order is deferred, plus a way to confirm receipt and report issues."*

**Working conditions:**
- **Device:** Desktop at back office (for placing orders, reviewing history) OR phone on the shop floor (for checking delivery ETA, confirming receipt while at the receiving dock)
- **Environment:** Retail outlet. Busy from 8 AM opening. Pre-opening (6-8 AM) is receiving time — Priya is at the rear dock checking what arrived
- **Hours:** Priya's relevant hours are 2-4 PM (placing orders before cutoff), 5-8 AM (receiving deliveries)
- **Stress points:** Not knowing if her order was received. Not knowing if it was deferred. Not knowing when the truck will arrive so she can schedule receiving staff. Finding out at 7:55 AM that her chilled dairy order was deferred — she has empty dairy shelves for morning shoppers

**What Priya needs from the system (in priority order):**

**ORDERING (2-4 PM):**
1. **Order placement** — structured form: select items by category, specify quantities, specify chilled/ambient. NOT a free-text message
2. **Order templates / reorder** — "Reorder same as last Tuesday" (Fresh outlets order daily for dry, several times/week for chilled — booklet says this explicitly). Pre-fill from last similar order
3. **Cutoff countdown** — "Order closes in 1h 47m. Modify now."
4. **Order confirmation receipt** — "Your order has been received. 2 line items: 169 units ambient (5.697 m³), 262 units chilled (11.722 m³)"
5. **Urgency flag** — "I am critically low on dairy. Please prioritize." (This feeds into dispatcher's deferral priority scoring)

**WAITING (4 PM – next morning):**
6. **Allocation status** — "Your ambient order has been assigned to VEH014, Trip 1" or "Your chilled order has been deferred — Reason: Refrigerated vehicle capacity exceeded"
7. **Deferral notification** — PROACTIVE push: "Your chilled order (262 units dairy) has been deferred to tomorrow. Reason: All refrigerated trucks committed to higher-priority routes." Must arrive BEFORE Priya sends receiving staff to the dock at 5 AM
8. **ETA tracking** — "Your delivery is on its way. VEH014 is currently at stop 3 of 7. Estimated arrival: 06:45 AM" Updates live
9. **Staff scheduling guidance** — "Expect delivery between 06:30-07:00. Plan receiving staff accordingly."

**RECEIVING (delivery arrives):**
10. **Arrival notification** — "Your delivery has arrived. Driver Saman is at the rear dock."
11. **Receipt confirmation** — ordered vs. delivered checklist. "Ordered: 169 units ambient. Received: 169 ✅" / "Ordered: 262 units chilled. Received: 240 ⚠️ (22 units short — flagged by loader before departure)"
12. **Seal Verification** — Enter the tamper-evident seal number applied by the loader to verify chain of custody before unloading.
13. **Partial Acceptance** — Ability to accept 60 units and reject 40, updating the return manifest automatically.
14. **Issue reporting** — "3 crates of yogurt arrived at 12°C (should be 2-4°C). Temperature breach." Photo evidence
15. **Digital receipt** — replaces handwritten notes. Timestamped, signed, stored

**HISTORY & PLANNING:**
14. **Delivery history** — "Last 30 days: 22 deliveries received, 4 deferred, 1 partial"
15. **Service quality metrics** — "Average arrival: 06:52 AM. Late arrivals: 3 out of 22"
16. **Deferral pattern** — "Your chilled orders have been deferred 4 times in the last 2 weeks. All on festival ramp days." (This gives Priya ammunition to escalate to Waypoint management)

---

## 3. The Seven Workflow Stages — Use Case Catalog

The booklet defines the workflow explicitly. Each stage maps to specific use cases.

### Stage 1: Place Order (Store Manager, before 4 PM)
| UC-ID | Use Case | Actor | Description |
|---|---|---|---|
| UC-101 | Place new order | Store Manager | Select outlet, items, quantities, temp type. Submit before cutoff |
| UC-102 | Reorder from template | Store Manager | Copy a previous order. Modify quantities. Fresh outlets do this daily |
| UC-103 | Modify pending order | Store Manager | Change quantities before cutoff. System warns if cutoff is near |
| UC-104 | Cancel pending order | Store Manager | Remove order before cutoff. After cutoff, cannot cancel |
| UC-105 | Flag urgency | Store Manager | Mark order as critical. System records urgency for dispatcher |
| UC-106 | View order confirmation | Store Manager | See confirmation receipt after submission |

### Stage 2: Close Orders (Dispatcher, at 4 PM)
| UC-ID | Use Case | Actor | Description |
|---|---|---|---|
| UC-201 | View order queue | Dispatcher | All confirmed orders in one view, grouped by brand/district |
| UC-202 | Manual order entry | Dispatcher | Late orders received by phone (edge case — some managers call late) |
| UC-203 | Review demand vs capacity | Dispatcher | Dashboard: total demand volume vs. available fleet capacity. Gap analysis |
| UC-204 | View fleet status | Dispatcher | Which vehicles available, which in workshop, fuel remaining |

### Stage 3: Plan and Allocate (Dispatcher, 4 PM – 10 PM)
| UC-ID | Use Case | Actor | Description |
|---|---|---|---|
| UC-301 | Run auto-allocation | System/Dispatcher | Engine proposes vehicle+trip assignments respecting all constraints |
| UC-302 | Review proposed allocation | Dispatcher | See allocation with constraint status per trip (weight %, volume %, time) |
| UC-303 | Override allocation | Dispatcher | Drag order to different vehicle/trip. System validates in real-time |
| UC-304 | Mark orders as deferred | Dispatcher | Select unallocatable orders. System requires deferral reason |
| UC-305 | View deferral history per outlet | Dispatcher | "OUT074 last served 5 days ago" — informs priority decision |
| UC-306 | Run what-if scenario | Dispatcher | "If I serve S1-083 instead of S1-036, what breaks?" |
| UC-307 | Approve and lock plan | Dispatcher | Finalize allocation. Pushes to loaders. Locks orders from modification |
| UC-308 | View lateness predictions | Dispatcher | Task 1 model shows per-stop lateness probability before dispatch |
| UC-309 | VIP Order Injection | Dispatcher | Override 4 PM cutoff for emergency client escalation |
| UC-310 | Emergency Handoff | Dispatcher | Transfer remaining stops of an active trip to a rescue driver |

### Stage 4: Load (Loader, 10 PM – 3 AM)
| UC-ID | Use Case | Actor | Description |
|---|---|---|---|
| UC-401 | View loading queue | Loader | All trips assigned to their depot, ordered by departure time |
| UC-402 | View loading list for trip | Loader | Items in reverse-route sequence, split by temperature zone |
| UC-403 | Confirm item loaded | Loader | Tap each item as loaded. Running weight/volume tally |
| UC-404 | Flag shortfall | Loader | Mark item as short with quantity and reason. System notifies dispatcher + store manager |
| UC-405 | Flag damaged item | Loader | Mark item damaged before loading. Photo capture. Decision: load or reject |
| UC-406 | Receive plan change alert | Loader | Dispatcher modified trip mid-load. Full-screen alert with diff |
| UC-407 | Complete loading | Loader | All items confirmed. "Release VEH003." Notifies dispatcher |
| UC-408 | Enter Tamper Seal | Loader | Enter plastic seal number applied to truck door to establish custody |

### Stage 5: Deliver (Driver, 3:30 AM – 8 AM / 9 AM – 5 PM)
| UC-ID | Use Case | Actor | Description |
|---|---|---|---|
| UC-500 | Pre-Trip Inspection | Driver | Complete digital safety checklist (tires, fuel, reefer temp) before start |
| UC-501 | View route card | Driver | Next stop details: outlet, dock type, items, window, predicted arrival |
| UC-502 | Navigate to stop | Driver | One-tap launch of external maps app |
| UC-503 | Mark arrival | Driver | Records actual_arrival_time. Compares to window. Shows if on time or late |
| UC-504 | Confirm delivery | Driver | Item-level checklist. Photo or signature capture |
| UC-505 | Report exception | Driver | Pre-built: outlet closed, access blocked, refused, damaged, partial |
| UC-506 | Work offline | Driver | All UC-501 to UC-505 work without internet |
| UC-507 | View sync status | Driver | Pending uploads visible. Auto-sync when connected |
| UC-508 | Complete trip | Driver | All stops done or excepted. "Returning to depot." |
| UC-509 | Take Rest Break | Driver | Pause route for labor compliance without draining trip time budget |

### Stage 6: Confirm Receipt (Store Manager, at delivery)
| UC-ID | Use Case | Actor | Description |
|---|---|---|---|
| UC-601 | Receive arrival notification | Store Manager | Push notification: "Your delivery is here" |
| UC-601b| Verify Tamper Seal | Store Manager | Enter seal number. System validates against loader's entry |
| UC-602 | Confirm receipt | Store Manager | Ordered vs. delivered checklist. Confirm quantities received |
| UC-602b| Partial Acceptance | Store Manager | Adjust received quantities, generating a return manifest for driver |
| UC-603 | Report issue | Store Manager | Damage, temperature breach, wrong items, short delivery |
| UC-604 | View proof of delivery | Store Manager | See photo/signature captured by driver |
| UC-605 | Sign digital receipt | Store Manager | Acknowledge receipt. Replaces handwritten note |

### Stage 7: Plan Future Capacity (Dispatcher, weekly)
| UC-ID | Use Case | Actor | Description |
|---|---|---|---|
| UC-701 | View demand forecasts | Dispatcher | Task 2A predictions: weekly volume by depot and brand |
| UC-702 | Identify capacity gaps | Dispatcher | "Next week: predicted 380 m³. Available fleet: 320 m³. Gap: 60 m³" |
| UC-703 | View festival/payday calendar | Dispatcher | Upcoming events that will spike demand |
| UC-704 | Generate capacity report | Dispatcher | Exportable report for management: "We need 2 more reefer trucks for Vesak week" |

---

## 4. Functional Requirements

### Allocation Engine (FR-001 to FR-010)
| ID | Requirement | Priority |
|---|---|---|
| FR-001 | All orders on a trip must share the same brand AND district | Must |
| FR-002 | Chilled orders only on reefer vehicles | Must |
| FR-003 | Van_only outlets only served by vans | Must |
| FR-004 | Vehicle serves only its home depot's outlets | Must |
| FR-005 | Trip total weight ≤ vehicle weight_cap_kg | Must |
| FR-006 | Trip total volume ≤ vehicle volume_cap_m3 | Must |
| FR-006b| Volume constraint applies packing efficiency factor (Fresh: 80-85%, Tech: 70%) | Must |
| FR-007 | Max 2 trips per vehicle per day | Must |
| FR-008 | Fresh trips total ≤ 270 min per vehicle | Must |
| FR-008b| Wait time before window opens consumes trip time budget | Must |
| FR-009 | Style/Tech trips total ≤ 480 min per vehicle | Must |
| FR-010 | Orders cannot be split across trips or vehicles | Must |

### Order Management (FR-011 to FR-018)
| ID | Requirement | Priority |
|---|---|---|
| FR-011 | Orders close at 4 PM. System enforces cutoff | Must |
| FR-012 | Fresh outlets can have 2 orders for same day (ambient + chilled) | Must |
| FR-013 | Deferred orders carry to next operating day automatically | Must |
| FR-014 | Every deferral records: order_ref, date, reason, decided_by | Must |
| FR-015 | Deferral history visible to dispatcher during planning | Must |
| FR-016 | Store Manager receives deferral notification within 30 min of decision | Must |
| FR-017 | Order confirmation sent to Store Manager on submission | Must |
| FR-018 | Urgency flag available on order placement | Should |

### Loading (FR-019 to FR-024)
| ID | Requirement | Priority |
|---|---|---|
| FR-019 | Loading list shows items in reverse route sequence | Must |
| FR-020 | Loading list splits items by temperature zone | Must |
| FR-021 | Loader can flag shortfall with quantity deficit | Must |
| FR-022 | Shortfall notification sent to dispatcher AND store manager | Must |
| FR-023 | Plan changes during loading trigger real-time alert on loader device | Must |
| FR-024 | Loading complete confirmation required before vehicle dispatch | Should |

### Delivery & Tracking (FR-025 to FR-032)
| ID | Requirement | Priority |
|---|---|---|
| FR-025 | Driver records actual arrival time per stop | Must |
| FR-026 | Driver captures proof of delivery (photo or signature) | Must |
| FR-027 | All driver interactions work fully offline | Must |
| FR-028 | Offline data syncs automatically when connectivity returns | Must |
| FR-029 | Sync uses last-write-wins with local timestamp | Must |
| FR-030 | Dispatcher sees live delivery progress (stop-by-stop) | Must |
| FR-031 | Dispatcher sees offline indicator per vehicle | Must |
| FR-032 | Driver can report exceptions with pre-built categories | Must |
| FR-032b| System supports SMS Gateway fallback for basic button phones | Must |
| FR-032c| Pre-Trip Inspection (PTI) must be passed before dispatch | Must |
| FR-032d| Tamper-evident seal protocol enforced between Loader and Store Manager | Must |

### Receipt & Feedback (FR-033 to FR-037)
| ID | Requirement | Priority |
|---|---|---|
| FR-033 | Store Manager confirms receipt with item-level quantities | Must |
| FR-034 | Store Manager can report issues (damage, temperature, quantity) | Must |
| FR-035 | Store Manager sees delivery ETA while in transit | Must |
| FR-036 | Digital receipt replaces handwritten notes | Must |
| FR-037 | Receipt confirmation is timestamped and stored permanently | Must |

### Forecasting Integration (FR-038 to FR-040)
| ID | Requirement | Priority |
|---|---|---|
| FR-038 | System displays demand forecasts (Task 2A) per depot, brand, week | Should |
| FR-039 | System shows lateness probability per stop (Task 1) during planning | Should |
| FR-040 | System shows predicted service time per stop (Task 1) | Should |

---

## 5. Non-Functional Requirements

### NFR-001: Offline-First Architecture
- Driver app must function with 0% connectivity for up to 2 hours
- All trip data pre-downloaded before departure
- Local storage (IndexedDB) for all stop completions, photos, signatures
- Background sync via Service Worker when connectivity restored
- No UI state that depends on server response for core flows

### NFR-002: Stress-Context UX
- **Dispatcher (night shift):** Dark mode default. High-contrast status indicators (red/amber/green). No aggressive notifications — use persistent banners instead of pop-ups
- **Loader (physical work):** Touch targets ≥ 64px. No swipe gestures (wet/gloved hands). High contrast on shared tablet. Plan change alert must be full-screen overlay — impossible to miss
- **Driver (fatigue/safety):** Maximum 3 taps to complete any action. No scrolling required on core flow. One-handed operation. Battery-efficient (dark mode, no background polling)
- **Store Manager (multitasking):** Notification-first design — important updates come to them, they don't have to go looking. Quick-glance dashboard on phone

### NFR-003: Accessibility
- WCAG 2.1 AA compliance minimum
- Support dark mode and light mode across all roles
- Color-blind safe palette (no red/green-only indicators — use shape + color)
- Text sizing: minimum 16px body, 14px for secondary info
- Screen reader compatible for core flows
- **Language & Cognitive:** Trilingual UI (Sinhala, Tamil, English) mandatory. Icon-first design for Driver/Loader.

### NFR-006: Regulatory & Compliance
- **Food Safety Act:** Temperature logging for reefer vehicles to ensure cold chain integrity.
- **Transport Law:** System prevents allocations that exceed legal Gross Vehicle Weight (payload + tare).
- **Labor Law:** Drivers prompted for mandatory rest breaks after 4 hours of continuous duty.

### NFR-004: Performance
- Allocation engine: produce a valid allocation for 86 orders across 30 vehicles in < 5 seconds
- Page load: < 2 seconds on 3G connection (driver app)
- Offline mode: instant response (local storage reads are synchronous)
- Sync queue: process up to 50 pending mutations in < 10 seconds when reconnected

### NFR-005: Security & Access Control
- Role-based access: 4 roles with strict visibility boundaries
- Store Managers see only their own outlet's data
- Drivers see only their assigned trips
- Loaders see all trips for their depot only
- Dispatcher sees everything at their depot (with cross-depot read access for coordination)
- Auth: JWT with role claims. Session timeout: 8 hours for dispatcher, 12 hours for driver

---

## 6. Edge Case & Failure Scenario Catalog

Every edge case below is grounded in real data from the datasets.

### EC-01: Order Exceeds All Vehicle Capacity
**Data reference:** S1-078 (Style Kurunegala) = 40.66 m³. Largest vehicle = 38.0 m³.
**Scenario:** Store Manager places an order so large no single vehicle can carry it.
**System behavior:** At allocation time, flag as "OVERSIZED ORDER — exceeds maximum vehicle capacity." Offer to split into 2 orders (but booklet says orders can't be split, so → must defer with reason "CAPACITY_EXCEEDED" and notify Store Manager to reorder as smaller batches).

### EC-02: Dual Orders from Same Outlet
**Data reference:** OUT001 has BOTH S1-000 (ambient) and S1-001 (chilled) for the same day.
**Scenario:** Fresh outlets can place 2 orders for the same delivery day — dry groceries + chilled.
**System behavior:** Both orders appear separately in the allocation queue. They may go on different vehicles (ambient on a dry van, chilled on a reefer van). Store Manager sees 2 separate delivery ETAs. The system must NOT merge them silently.

### EC-03: Van-Only Outlet + Chilled + No Reefer Van Available
**Data reference:** S1 has only VEH036 (reefer van). If VEH036 breaks down mid-shift, van_only chilled orders become structurally impossible.
**Scenario:** Single point of failure — the entire van_only chilled supply chain depends on one vehicle.
**System behavior:** Dispatcher sees red alert: "0 reefer vans available. 3 outlets (OUT001, OUT002, OUT003) have chilled orders that CANNOT be served. These orders must be deferred." No workaround exists — the system must make this explicit, not hide it.

### EC-04: Dispatcher Modifies Plan After Loading Starts
**Scenario:** At 11 PM, dispatcher reassigns S1-028 from VEH003 to VEH006. Nuwan (loader) has already loaded S1-028 onto VEH003.
**System behavior:** Full-screen alert on loader's tablet: "⚠️ PLAN CHANGED: S1-028 removed from VEH003. Must be offloaded and moved to VEH006." System tracks "loading status" per item — if status is "loaded" and trip changes, flag a physical intervention required.

### EC-05: Driver Arrives Before Window Opens
**Data reference:** OUT006 window opens at 03:00. If VEH014 departs at 3:30 AM for Colombo (24 min), arrives at 03:54. OUT006 window is already open. But what about OUT005 (window 04:00)? Vehicle might arrive at 03:48 — before window.
**Scenario:** Vehicle arrives early at an outlet whose receiving staff isn't ready.
**System behavior:** Route card shows: "Window opens at 04:00. Arrive early? Wait at outlet. Do NOT attempt delivery before window." Timer showing countdown to window open. Service time calculation starts at max(arrival, window_open).

### EC-06: Driver Goes Offline Mid-Route
**Data reference:** Kandy corridor, Nuwara Eliya, Badulla — connectivity drops expected.
**Scenario:** Driver completes 3 stops online, then loses signal for 45 minutes while completing stops 4 and 5 offline.
**System behavior:** 
- Driver side: seamless — stops 4 and 5 recorded locally, no interruption
- Dispatcher side: "VEH007 — Last sync: 45 min ago. 🔴 Offline. Last known: stop 3 complete."
- When driver reconnects: sync queue replays stops 4 and 5 to server. Timestamps from local clock used. Dispatcher dashboard updates instantly

### EC-07: Loading Shortfall Discovered
**Data reference:** S1-009 (OUT005 chilled) = 250 units, 1696.6 kg, 10.09 m³. What if cold room only has 200 units?
**Scenario:** Loader discovers physical inventory doesn't match plan.
**System behavior:** Loader flags "Short 50 units on S1-009." System: (1) notifies dispatcher → may adjust allocation if total volume now fits a smaller vehicle; (2) notifies Store Manager → "Your chilled order will be 50 units short. Reason: Warehouse inventory insufficient. Remaining 50 units will be included in next available run."

### EC-08: Monsoon Causes Road Disruption
**Data reference:** road_conditions.csv shows disruption_index as low as 56 (Colombo, 2024-01-27). In monsoon months, hill districts likely much worse.
**Scenario:** Overnight flooding on the Colombo-Galle highway. disruption_index drops to 40. Planned travel time doubles.
**System behavior:** If Task 1 model is integrated: lateness probability for all Galle stops jumps to >80%. Dispatcher sees warning during planning: "Road conditions severe on Colombo→Galle route. 5 of 6 Galle stops predicted late. Consider deferring Galle orders." Post-dispatch: actual travel time tracked vs. planned, delay alerts triggered.

### EC-09: Same-Mall Outlets with Conflicting Service Times
**Data reference:** OUT015 and OUT016 both have mall_window 09:00-11:00, service_allowance 59 min each.
**Scenario:** Dispatcher assigns both to same trip. Sequential delivery: 59 + 8 + 59 = 126 min. Window is 120 min. Second outlet misses window.
**System behavior:** Allocation engine detects conflict: "OUT015 + OUT016 cannot both be served within 09:00-11:00 window on the same trip. Assign to separate trips or defer one." This is a non-obvious constraint that most teams will miss.

### EC-10: Festival Ramp Demand Surge
**Data reference:** Calendar shows festival_ramp rising 0.1 → 1.0 over 9 days before thai_pongal. S1 scenario is "festival one week away."
**Scenario:** 7 days before a major festival, Fresh chilled demand surges 30-50%. More outlets order chilled. Reefer capacity that was adequate last week is now insufficient.
**System behavior:** Demand forecast dashboard shows: "Predicted chilled volume next week: 180 m³. Available reefer capacity: 120 m³. Shortfall: 60 m³." Dispatcher can act: request temporary reefer vehicle rental, pre-communicate expected deferrals to store managers, prioritize outlets that haven't been served recently.

### EC-11: Fresh Outlet Has Empty Shelves (Repeated Deferrals)
**Data reference:** S1-083 — OUT074, Puttalam, chilled, deferred_yesterday=1, days_since_last_served=5.
**Scenario:** A Fresh outlet hasn't received dairy in 5 days. Their cold shelves are empty. Customers are going to competitors. But Puttalam is 173 min away and reefer trucks are committed elsewhere.
**System behavior:** Dispatcher sees escalating urgency: "⚠️ CRITICAL: OUT074 has not been served in 5 days. Previous deferral reasons: CAPACITY_EXCEEDED (×2), TIME_BUDGET (×1). Risk: Customer churn, spoilage of remaining stock." Forces conscious decision — defer with documented justification, or reprioritize at the cost of another outlet.

### EC-12: Vehicle Breaks Down Mid-Route
**Scenario:** VEH003 breaks down at stop 3 of 7. Stops 4-7 are unserved with perishable goods on board.
**System behavior:** Driver taps "Vehicle Breakdown" exception. System: (1) marks remaining stops as "failed — vehicle breakdown"; (2) notifies dispatcher with remaining order list; (3) dispatcher can reassign remaining orders to another vehicle (if capacity exists) or defer to next day; (4) notifies affected store managers: "Your delivery has been delayed due to vehicle breakdown. Updated ETA will follow."

---

## 7. Feature Prioritization Matrix (MoSCoW)

### Must Have (Scores >60% of judging criteria)
- Digital order placement with cutoff enforcement
- Constraint-aware allocation engine (all 10 feasibility rules)
- Deferral management with reason recording and history
- Loading list with reverse sequence and shortfall flagging
- Driver route card with offline delivery confirmation
- Proof of delivery capture
- Store Manager receipt confirmation
- Live delivery progress tracking for dispatcher
- Role-based access control (4 roles)
- Trilingual UI (Sinhala/Tamil/English) and icon-first design for frontline workers
- Tamper-evident seal protocol for chain of custody
- Pre-Trip Inspection (PTI) digital checklist

### Should Have (Differentiators, scores 20-30%)
- Demand forecast dashboard (Task 2A integration)
- Lateness prediction on planning canvas (Task 1 integration)
- Deferral priority recommendation engine
- Store Manager ETA tracking
- Loader plan-change real-time alerts
- Vehicle offline indicator on dispatcher dashboard
- Digital receipt with dispute workflow
- Reorder templates for Store Manager

### Could Have (Creativity points, polish)
- What-if scenario simulator for dispatcher
- Capacity gap report generator
- Delivery performance analytics over time
- Store Manager service quality history
- Battery-optimized driver dark mode
- Map visualization of active routes
- Festival/payday calendar overlay on demand forecast

### Won't Have (Restraint — explicit exclusions)
- GPS real-time vehicle tracking (not in brief — driver check-ins are sufficient)
- Inventory management at outlets (out of scope)
- Payment/invoicing (not mentioned)
- Customer-facing delivery tracking (B2C — this is B2B)
- 5th admin/management role (booklet defines exactly 4 roles)

---

## 8. System Architecture & DevOps Strategy

*This section defines the production-grade deployment architecture required for a mission-critical logistics system, aligning with the Hackathon's Docker and architecture diagram requirements.*

### 8.1 Architectural Pattern
- **Backend-For-Frontend (BFF):** Given the 4 distinct roles, the system uses a core shared domain (Allocation Engine, Feasibility Rules) but exposes role-specific API gateways tailored to each frontend's payload needs.
- **Offline-First Sync Engine:** The Driver/Loader apps use a local-first database strategy (e.g., IndexedDB/PouchDB) syncing to the backend via a conflict-resolution queue (last-write-wins with server-side time arbitration).

### 8.2 Infrastructure (The Stack)
- **Containerization:** 100% Dockerized. A `docker-compose.yml` orchestrates the entire local/Hackathon environment (Web App, API, DB, Cache).
- **Database (ACID Core):** PostgreSQL 16. Uses PostGIS extension for spatial querying (distance/routing calculations). Write-master for order ingestion; Read-replicas for the heavy dispatcher monitoring dashboards.
- **Caching & Message Queue:** Redis. Used for (a) fast lookups of vehicle locations, (b) optimistic locking during concurrent dispatcher edits (preventing double-booking), and (c) caching Task 2A ML demand forecasts.
- **Blob Storage (Media):** S3-compatible storage (MinIO for local dev, AWS S3 for production). Crucial for storing Proof of Delivery (PoD) signatures, damaged cargo photos, and legacy CSV uploads.
- **Load Balancing:** NGINX or Traefik to route traffic based on role (e.g., `/api/driver` vs `/api/dispatcher`), handle SSL termination, and manage DDoS protection.

### 8.3 CI/CD & Uptime Management
- **Pipeline:** GitHub Actions for Continuous Integration (Linting, Unit Tests on the Allocation Validator) and Continuous Deployment (Docker image build and push to registry).
- **High Availability (HA):** Auto-scaling based on CPU load (crucial during the 3:30 PM - 4:00 PM order cutoff rush). Multi-zone deployment to survive localized data center outages.
- **Monitoring & Observability:** Prometheus for metrics (e.g., tracking Allocation Engine execution time), Grafana for dashboards, and Sentry for UI error tracking (crucial for catching offline-sync failures on diverse driver Android devices).

---

## 9. Data Migration & Legacy Integration

*Transitioning Waypoint from spreadsheets and paper to a digital system requires a robust onboarding bridge.*

### 9.1 Historical Data Ingestion
- **Bulk CSV/Excel Uploads:** The Admin/Dispatcher portal includes a "Data Importer" module. Allows uploading of legacy `.csv` or `.xlsx` files for historical deliveries, past deferrals, and vehicle maintenance logs.
- **Schema Validation:** System automatically validates uploaded legacy data against the new strict feasibility rules, flagging malformed historical rows for manual correction.

### 9.2 Master Data & Media Management
- **Document Uploads:** Store managers can upload PDF business registrations or photos of their specific dock layout to assist drivers. Drivers must upload photos of their driving licenses and certifications. All stored securely in Blob Storage via pre-signed URLs.
- **Machine Learning Data Pipeline:** The system exposes historical route records to the Datathon ML pipelines (Task 1 & 2A) via a secure, read-only analytical database view, ensuring production data isn't impacted by heavy analytical queries.

---

## 10. System Flow Diagram

```
  STORE MANAGER                    DISPATCHER                     LOADER                     DRIVER
       │                              │                             │                           │
  [Place Order]                       │                             │                           │
       │──── order ───────────────→   │                             │                           │
       │                         [4PM Cutoff]                       │                           │
       │                              │                             │                           │
       │                     [View Order Queue]                     │                           │
       │                              │                             │                           │
       │                     [Run Auto-Allocation]                  │                           │
       │                              │                             │                           │
       │                     [Review / Override]                    │                           │
       │                              │                             │                           │
       │                     [Mark Deferrals]                       │                           │
       │                              │                             │                           │
  [Receive Deferral Notice] ←─────── │                             │                           │
       │                              │                             │                           │
       │                     [Approve & Lock Plan]                  │                           │
       │                              │───── loading list ────→    │                           │
       │                              │                        [View Loading Queue]             │
       │                              │                             │                           │
       │                              │                        [Load Items / Flag Short]        │
       │                              │                             │                           │
  [Receive Shortfall Notice] ←─────── ├──── shortfall alert ←───── │                           │
       │                              │                             │                           │
       │                              │                        [Loading Complete]                │
       │                              │                             │──── release ────→         │
       │                              │                             │                      [View Route Card]
       │                              │                             │                           │
       │                     [Track Live Progress] ←────────────────│────── check-in ──────     │
       │                              │                             │                      [Arrive at Stop]
       │                              │                             │                           │
  [Receive Arrival Notification] ←─── │                             │                      [Deliver + Confirm]
       │                              │                             │                           │
  [Confirm Receipt / Report Issue]    │                             │                      [Mark Complete]
       │                              │                             │                           │
       │                     [End of Day Report]                    │                      [Return to Depot]
```

---

## 11. Core Tradeoff (Optional Deliverable)

**The Central Tradeoff: Service Equity vs. Operational Efficiency**

The most efficient allocation serves the most orders with the fewest vehicles. But the most equitable allocation prioritizes outlets that have been repeatedly skipped.

Example from S1:
- **Efficient choice:** Defer S1-083 (OUT074 Puttalam chilled, 8.659 m³). Puttalam is 173 min away. Serving it uses an entire reefer truck for one trip to a far district.
- **Equitable choice:** Serve S1-083. This outlet has been deferred 5 consecutive days. Their customers are leaving.

The system doesn't make this choice — the dispatcher does. But the system must make the tradeoff visible: "Serving OUT074 costs 1 reefer truck for the full day. Deferring it means 6 consecutive days without service."

This tradeoff should be the centerpiece of the core tradeoff explanation deliverable.

---

## 12. Degradation Scenario Catalog

| # | Scenario Name | Failure Type | Roles Affected | Depth |
|---|---|---|---|---|
| D1 | Reefer Capacity Overflow on Festival Day | Resource exhaustion | Dispatcher, Store Manager | Full design |
| D2 | Driver Offline in Hill Country | Connectivity loss | Driver, Dispatcher | Full design |
| D3 | Mid-Load Plan Change | Process disruption | Loader, Dispatcher | Full design |
| D4 | Vehicle Breakdown Mid-Route | Physical failure | Driver, Dispatcher, Store Manager | Scenario + flow |
| D5 | Loading Shortfall | Inventory mismatch | Loader, Dispatcher, Store Manager | Scenario + flow |
| D6 | Oversized Order (>max vehicle capacity) | System boundary | Store Manager, Dispatcher | Scenario + flow |

**D1, D2, D3 get full high-fidelity screen designs.** D4-D6 documented as scenarios with flow descriptions but may not get full pixel-perfect screens depending on time.

---

*This SRS is ready for your review. Once approved, it becomes the specification for:*
- *Designathon: Persona cards, screen flows, rationale paragraphs, degradation screens*
- *Hackathon: API endpoints, database operations, UI components*
- *Datathon: Feature engineering informed by the edge cases (structural lateness, deferral history as feature)*
