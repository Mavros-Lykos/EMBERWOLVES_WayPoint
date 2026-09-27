---
name: User Personas & Human Factors Context
description: Deep operational, psychological, and environmental user personas for the 4 actors in Waypoint Dispatch (Store Manager, Dispatcher, Loader, Driver).
---

# User Personas & Human Factors Context
*Waypoint Dispatch: Multi-Actor Logistics & Delivery Management Platform*

To satisfy the **User-Context Fidelity (20%)** and **Domain Accuracy (10%)** judging criteria of the Tech-Triathlon 2026, the Waypoint Dispatch system is tailored to the exact physical, psychological, and linguistic realities of four core frontline actors.

---

## 1. User Persona 1: Priya — Store Manager
**"I don't need a thousand analytics charts. I just need to know when the truck is pulling up so I can get stock on shelves before customers walk in."**

### Profile & Demographics
- **Role:** Store Manager at Waypoint Fresh (Outlet `OUT007`, Mount Lavinia) — also handles Style (`OUT082`) and Tech (`OUT106`) delivery days.
- **Age:** 34 | **Experience:** 6 years in retail store operations.
- **Languages:** Primary Sinhala, Fluent English.
- **Physical Workstation:**
  - Countertop POS terminal (14-inch touchscreen, $1366\times768$ resolution).
  - Personal smartphone (Android) used while walking aisles.
- **Working Hours:** 07:00 AM to 05:00 PM (Fresh outlets open to the public strictly at 08:00 AM).

### Environmental & Cognitive Realities
- **Context:** High-stress, multi-tasking retail floor. Juggling shelf-stocking, cashiers, customer inquiries, and supplier drop-offs.
- **The Core Pain Point:** Under the legacy manual system, deliveries arrive unannounced anywhere between 06:00 AM and 09:30 AM. If goods arrive after 08:00 AM, shelves are empty and customers complain. If Priya assigns floor staff to wait at the dock, customer checkout lines back up.
- **Secondary Pain Point:** When an order is deferred or short-shipped, Priya receives no warning. She only discovers missing milk crates when tearing open the truck door.
- **UX & Design Imperatives for Priya:**
  - **Dominant ETA Display:** High-visibility delivery countdown card (`ETA: 07:15 AM - 18 mins away`) visible from 5 feet away on the POS screen.
  - **One-Glance Order Cutoff:** Persistent visual countdown to the 16:00 order deadline.
  - **Transparent Deferral Taxonomy:** If an order is skipped, the exact reason (*"Chilled fleet capacity saturated - prioritized emergency hospital corridor"*) must appear in bold with guaranteed priority rollover.
  - **Fast Tamper-Evident Sign-off:** Clear digital keypad to verify the loader's seal number and snap quick camera photos of damaged produce before signing.

---

## 2. User Persona 2: Kamal — Central Fleet Dispatcher
**"At 4:00 PM the floodgates open. 120 stores want everything tomorrow. If I make one math mistake, an ambient truck gets sent to a mountain pass with ice cream."**

### Profile & Demographics
- **Role:** Senior Dispatch & Fleet Planning Controller (Peliyagoda Central Depot).
- **Age:** 48 | **Experience:** 19 years in Sri Lankan route transport.
- **Languages:** Fluent English, Sinhala, Working Tamil.
- **Physical Workstation:**
  - Dual 27-inch 1080p monitors in the Peliyagoda central planning office.
  - Stable fiber internet, ergonomic desk, high-density mouse and keyboard navigation.
- **Working Hours:** 02:00 PM to 10:30 PM (Peak intensity window: 16:00 to 18:30 PM planning rush).

### Environmental & Cognitive Realities
- **Context:** Cognitive overload under intense time pressure. Between 16:00 (cutoff) and 18:00, Kamal must allocate confirmed orders across 60 vehicles (12 refrigerated trucks, 40 dry trucks, 8 vans), respecting weight ($kg$), volume ($m^3$), access restrictions (`van_only`), pre-08:00 AM delivery windows, and weekly fuel quotas.
- **The Core Pain Point:** Legacy Excel spreadsheets allowed silent human errors: assigning a 10-ton truck to a narrow alley in Kandy, overloading axle weights, or double-booking a truck during its Trip 1 window.
- **Secondary Pain Point:** Zero visibility after trucks leave the depot. Kamal's phone rings constantly with angry calls from store managers when trucks are stuck in monsoon traffic.
- **UX & Design Imperatives for Kamal:**
  - **Cognitive Offloading (Deltas over Raw Math):** Never show raw calculations. Show reactive constraint bars that turn amber at 85% and red at 100%, with explicit tags (`+1.4m³ OVERLOADED`, `VIOLATION: Van-Only Outlet OUT042 on Heavy Truck`).
  - **Split-Pane Allocation Canvas:** Unallocated orders on the left rail, 60 vehicle trip blocks on the center grid. Drag-and-drop or 1-click batch allocation.
  - **Consecutive Deferral Safeguard:** System automatically flags outlets deferred the previous day, preventing unfair consecutive skips.
  - **Predictive Lateness Overlays:** Datathon ML model overlays predicting service delays and lateness probabilities before trucks depart.

---

## 3. User Persona 3: Nuwan — Depot Dock Loader & Bay Supervisor
**"It's 2:30 AM, it's freezing in the cold room, the warehouse is roaring with forklifts, and my hands are in heavy gloves. Don't give me tiny buttons."**

### Profile & Demographics
- **Role:** Loading Bay Supervisor & Dock Lead (Peliyagoda Central DC / Kandy Hub).
- **Age:** 29 | **Experience:** 4 years in warehouse operations.
- **Languages:** Primary Sinhala, Basic Tamil, Limited English.
- **Physical Workstation:**
  - Ruggedized 10-inch Android dock tablet mounted on an industrial swing-arm or carried in a rubberized casing.
  - Intermittent Wi-Fi at dock perimeter; harsh fluorescent / sodium lighting.
- **Working Hours:** 11:00 PM to 07:30 AM (Graveyard shift; peak loading window: 00:30 AM to 04:30 AM).

### Environmental & Cognitive Realities
- **Context:** Extreme physical fatigue, sleep disruption, heavy machinery noise, drafty conditions, wet floors, and thick cotton/rubber work gloves.
- **The Core Pain Point:** If a truck is loaded in the wrong sequence, the driver has to pull out 5 pallets of canned goods in the dark on a rainy roadside in Kurunegala to reach a box of milk for Stop 1.
- **Secondary Pain Point:** Dispatchers changing trip plans while loading is 70% complete, leading to wasted labor or incorrect cargo dispatched.
- **UX & Design Imperatives for Nuwan:**
  - **Fatigue-Resistant UI:** Massive **minimum 64px tap targets**, high-contrast borders, no fine gestures (no drag-and-drop, no pinch).
  - **Enforced LIFO Loading Sequence:** Manifest presented in strict reverse order (Stop 4 loaded first at truck head; Stop 1 loaded last at rear door).
  - **Split Compartment Packing Guide:** Unmistakable visual separation between Chilled Bay ($0^\circ C - 4^\circ C$) and Ambient Cargo.
  - **Tamper-Evident Seal Locking Screen:** Clear numeric keypad for Nuwan to punch in the physical plastic seal serial number before issuing the digital Gate Pass.
  - **Emergency Mid-Load Interrupt Alert:** If Kamal alters a plan, Nuwan's tablet locks with a pulsating amber warning detailing exactly which pallets to swap.

---

## 4. User Persona 4: Saman — Field Delivery Driver
**"I'm driving on narrow mountain roads in the dark at 4:00 AM. When I stop, I need the phone to work instantly, even if there's zero mobile network."**

### Profile & Demographics
- **Role:** Senior Heavy Commercial & Reefer Fleet Driver (Vehicle `VEH-003`, Refrigerated 4.5T).
- **Age:** 42 | **Experience:** 15 years commercial driving across Sri Lanka.
- **Languages:** Primary Sinhala, Fluent Tamil, Limited English.
- **Physical Workstation:**
  - Personal Android smartphone (5.8-inch screen) mounted on truck cab dashboard.
  - In-vehicle 12V charger, vibrating mount, intense windshield glare in daylight, pitch darkness at night.
- **Working Hours:** 03:00 AM to 01:00 PM (Trip 1 departs Peliyagoda at 04:00 AM to beat morning Colombo traffic).

### Environmental & Cognitive Realities
- **Context:** Physical road fatigue, intense glare, sudden monsoon downpours, tight rural alleys with low-hanging electrical cables, and dead cellular zones along mountain passes (Kadugannawa, Nuwara Eliya).
- **The Core Pain Point:** Paper run sheets get soaked in rain, torn, or lost. When disputes occur over damaged cartons, drivers are blamed without proof.
- **Secondary Pain Point:** Disorientation when navigating unfamiliar rear store entrances or mall basement service bays with strict height clearances.
- **UX & Design Imperatives for Saman:**
  - **100% Offline-First (PWA):** Route cards, stops, manifests, and signatures function with zero cellular coverage. Data syncs silently in the background when connectivity returns.
  - **Pre-Trip Inspection (PTI) Gate:** Rapid 9-point checklist with cold room temperature validation ($\le 4^\circ C$) before route unlocks.
  - **64px Glare-Resistant Touch Targets:** Single-hand thumb-zone layout with dark slate background (`#0f172a`) and high-contrast text.
  - **One-Tap Arrival & Navigation:** Launches Google Maps/Waze with one tap; one-tap "Mark Arrived" notifies the store manager instantly.
  - **Labor Law Rest Break Compliance:** Non-punitive break timer supporting tea/rest pauses with proactive 20-minute alerts.
  - **Emergency Degradation Mode:** Instant 1-tap breakdown broadcast with cold-chain spoilage countdown timer and peer-to-peer cargo handoff QR codes.
