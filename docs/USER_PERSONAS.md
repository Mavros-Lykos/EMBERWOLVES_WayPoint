# User Personas — Waypoint Dispatch
*Tech-Triathlon 2026 Designathon Submission*

Each persona is grounded in the physical working conditions, pain points, and operational needs described in the Challenge Booklet.

---

## Persona 1: Priya — Store Manager

**Role:** Store Manager, Waypoint Fresh (Outlet OUT-007, Mount Lavinia)  
**Age:** 34 · **Experience:** 6 years in retail store operations  
**Languages:** Primary Sinhala, Fluent English  
**Device:** Countertop POS terminal (14" touchscreen) and personal Android phone

### Working Environment
Priya manages a busy Fresh supermarket that opens to the public at 08:00 AM. Her mornings begin at 07:00 AM coordinating shelf stockers, cashiers, and supplier drop-offs simultaneously. She operates in a well-lit retail environment during daytime hours.

### Core Pain Points
1. **Unpredictable delivery arrival.** Under the legacy system, trucks arrive anywhere between 06:00 AM and 09:30 AM. If Priya assigns floor staff to wait at the dock, checkout queues back up. If she doesn't, perishable goods sit unattended on the loading bay.
2. **Silent deferrals.** When an order is skipped or short-shipped, Priya receives no advance warning. She discovers missing dairy crates only when the truck door opens, leaving shelves empty at store opening.
3. **No record of delivery disputes.** Disagreements over damaged or missing items depend entirely on memory and phone calls, with no photographic evidence or audit trail.

### What Priya Needs from the System
- A dominant ETA countdown visible from 5 feet away on her POS screen
- A persistent 16:00 order cutoff timer so she never misses the deadline
- Transparent deferral reasons when her order is rolled to the next day
- Fast tamper-evident seal verification and item-by-item receiving checklist at the dock

### Design Implication
Priya's screens use the **Serene Retail (Light)** theme — clean white surfaces optimized for well-lit retail environments. Standard 44px touch targets for desktop, increasing to 64px for dock receiving on tablet.

---

## Persona 2: Kamal — Central Fleet Dispatcher

**Role:** Senior Dispatch & Fleet Planning Controller, Peliyagoda Central DC  
**Age:** 48 · **Experience:** 19 years in Sri Lankan route transport  
**Languages:** Fluent English, Sinhala, Working Tamil  
**Device:** Dual 27" 1080p monitors, ergonomic desk, mouse and keyboard

### Working Environment
Kamal works in the Peliyagoda planning office with stable fiber internet. His shift runs from 02:00 PM to 10:30 PM, with peak intensity between 16:00 (order cutoff) and 18:30 when the day's allocation must be finalized across 60 vehicles serving 120 outlets.

### Core Pain Points
1. **Cognitive overload under time pressure.** Between 16:00 and 18:00, Kamal must allocate confirmed orders across 60 vehicles while respecting weight limits, volume limits, temperature requirements, van-only access restrictions, pre-08:00 AM delivery windows, and weekly fuel quotas — all simultaneously.
2. **Silent constraint violations.** Legacy Excel spreadsheets allowed human errors: assigning a 10-ton truck to a narrow Kandy alley, overloading axle weights, or double-booking a vehicle.
3. **Zero field visibility.** Once trucks leave the depot, Kamal has no view of delivery progress. He learns about delays only when angry store managers call.

### What Kamal Needs from the System
- Reactive constraint bars showing capacity utilization (volume %, weight %) that turn amber at 85% and red at 100%
- Split-pane allocation canvas: unallocated orders on one side, vehicle trip blocks on the other
- Consecutive deferral safeguard flagging outlets already deferred the previous day
- Live fleet telemetry map after trucks depart

### Design Implication
Kamal's screens are **high-density desktop layouts** (1920×1080) designed for information scanning. Dense data tables, constraint bars, and split-pane canvases maximize the information visible at a glance.

---

## Persona 3: Nuwan — Dock Supervisor & Loader

**Role:** Loading Bay Supervisor, Peliyagoda Central DC  
**Age:** 29 · **Experience:** 4 years in warehouse operations  
**Languages:** Primary Sinhala, Basic Tamil, Limited English  
**Device:** Ruggedized 10" Android tablet in a rubberized case, mounted on an industrial swing-arm

### Working Environment
Nuwan works the graveyard shift (11:00 PM to 07:30 AM) in a noisy warehouse bay with forklifts, harsh fluorescent lighting, wet concrete floors, and temperatures dropping to near-freezing in the cold room. He wears thick cotton/rubber work gloves at all times.

### Core Pain Points
1. **Loading sequence errors.** If a truck is loaded in the wrong order, the driver has to pull out 5 pallets of canned goods on a rainy roadside at night to reach the milk crates for Stop 1. The correct sequence is reverse-LIFO: last stop loaded first, first stop loaded last (nearest to the rear door).
2. **Mid-load plan changes.** Dispatchers sometimes alter trip plans while loading is 70% complete. Under the legacy system, changes arrive by phone call or a supervisor walking to the bay — high risk of miscommunication.
3. **No shortage documentation.** If a pallet is damaged or missing from the warehouse, Nuwan has no way to formally flag it before the truck departs. The driver gets blamed at the store.

### What Nuwan Needs from the System
- Massive 64px tap targets for gloved operation — no fine gestures, no drag-and-drop, no pinch
- Enforced reverse-LIFO loading sequence displayed as a step-by-step checklist
- Tamper-evident seal entry screen with large digit inputs before truck departure
- Full-screen interruption alert when dispatch changes the plan mid-load

### Design Implication
Nuwan's screens are optimized for **tablet (768px–1024px)** with high-contrast surfaces and the largest interactive elements in the entire system. Every interaction is tap-only.

---

## Persona 4: Saman — Field Delivery Driver

**Role:** Senior Heavy Commercial & Reefer Fleet Driver (Vehicle VEH-003, Refrigerated 4.5T)  
**Age:** 42 · **Experience:** 15 years commercial driving across Sri Lanka  
**Languages:** Primary Sinhala, Fluent Tamil, Limited English  
**Device:** Personal Android smartphone (5.8" screen) mounted on truck cab dashboard

### Working Environment
Saman's shift begins at 03:00 AM. He departs Peliyagoda DC at 04:00 AM to beat Colombo morning traffic. His routes take him through mountain corridors (Kadugannawa, Nuwara Eliya) where mobile coverage drops completely. He drives in pitch darkness, intense windshield glare at sunrise, and sudden monsoon downpours. His truck cab vibrates constantly.

### Core Pain Points
1. **Paper run sheets are fragile.** They get soaked in rain, torn, or lost. When disputes arise over damaged cartons at a store, Saman is blamed with no digital proof of delivery.
2. **No offline capability.** Current phone-based communication fails entirely in dead cellular zones along mountain passes.
3. **Unfamiliar store docks.** Navigating rear store entrances, mall basement service bays, and narrow alleys with low-hanging cables requires dock-specific instructions that paper sheets don't provide.
4. **Rest compliance pressure.** Continuous driving limits (4 hours max) are a safety requirement, but drivers feel penalized for taking breaks.

### What Saman Needs from the System
- 100% offline-first PWA: route cards, stops, manifests, and delivery confirmations work with zero cellular coverage
- Pre-trip inspection gate that validates reefer temperature before the route unlocks
- 64px glare-resistant touch targets designed for single-hand thumb-zone use
- Non-punitive rest break timer with explicit "no penalty" messaging
- One-tap emergency broadcast with GPS coordinates when a breakdown occurs

### Design Implication
Saman's screens are **phone-sized (390×844)** with the highest contrast ratios in the system. Every critical action is anchored in the bottom 120px thumb zone. All data persists in IndexedDB and syncs silently when connectivity returns.
