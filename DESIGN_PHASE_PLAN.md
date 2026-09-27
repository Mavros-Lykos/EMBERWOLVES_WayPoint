# UI/UX Design Phase: Master Plan & Screen Manifest

## 1. Design Psychology & Theme Selection

The Kick-off session emphasized the "Intelligent Enterprise", while the Booklet revealed extreme operational realities (midnight loading, 3 AM driving, stressed dispatchers). The users of this system are NOT casual consumers; they are fatigued, time-pressured logistics workers.

**Core UI/UX Principles for this Project:**
1. **Anxiety Reduction (Calm UI):** Users look at this for 8-10 hours a day. Avoid aggressive pure reds (`#FF0000`) or glaring whites. Use muted, deliberate color palettes.
2. **Cognitive Offloading:** Do not make the user calculate anything. Show the delta (e.g., "60 m³ over capacity") rather than raw numbers.
3. **Fatigue-Resistant UX:** For drivers and loaders, use massive touch targets (64px minimum), prevent accidental swipes, and use Icon-First design (language agnostic).

### Proposed Themes (Choose One)

**Theme A: "Midnight Oceanic" (Recommended for Drivers/Dispatchers)**
- **Vibe:** Calm, professional, reduces eye strain in dark environments (pre-dawn driving).
- **Colors:** Deep navy/slate backgrounds (`#0f172a`), muted teal for primary actions (`#14b8a6`), soft off-white text (`#f8fafc`).
- **Why:** Perfect for the 10 PM - 4 AM shift. The cool tones lower physiological stress.

**Theme B: "Industrial Utility" (High Contrast)**
- **Vibe:** Utilitarian, unmistakable clarity, high contrast.
- **Colors:** Deep charcoal (`#1c1917`), vibrant amber for warnings (`#f59e0b`), crisp emerald for success (`#10b981`).
- **Why:** Focuses purely on preventing mistakes. Amber warnings grab attention without inducing panic.

**Theme C: "Serene Retail" (Recommended for Store Managers)**
- **Vibe:** Trustworthy, clean, daytime-oriented.
- **Colors:** Soft cream backgrounds, muted sage green primary (`#65a30d`).
- **Why:** Store Managers use this during retail hours. It needs to feel like a premium B2B ordering portal.

*My Suggestion: We use a unified Design System that defaults to **Theme A (Dark)** for Dispatchers, Loaders, and Drivers, and **Theme C (Light)** for Store Managers.*

---

## 2. Complete Interface Manifest & Connectivity Flow

We must generate the following screens using the Stitch AI MCP. This is how they connect to form the full ecosystem.

### A. The Store Manager Flow (Priya)
1. **Order Placement Canvas:** Selects items, quantities, and urgency.
   *→ Connects to:*
2. **Order Status & ETA Dashboard:** Shows if the order is confirmed, deferred, or allocated. Live ETA map.
   *→ Connects to:*
3. **Receiving & Receipt Screen:** Used at the dock. Confirms Seal Number, item-by-item tick-off, and partial dispute entry.

### B. The Dispatcher Flow (Kamal)
4. **Fleet & Capacity Overview:** The 4:00 PM dashboard. Shows total demand vs. available trucks.
   *→ Connects to:*
5. **The Master Allocation Canvas:** The core screen. A split-pane view showing the unassigned order queue and the vehicle trip blocks. Drag-and-drop interface showing real-time weight/volume constraints.
   *→ Connects to:*
6. **Deferral Decision Modal:** Triggered when forcing an order out. Requires reason selection and shows "consecutive days deferred" warning.
   *→ Connects to:*
7. **Task 2A / Task 1 Overlay:** An analytics toggle that overlays ML predictions (Lateness probability and Next Week's demand).

### C. The Loader Flow (Nuwan)
8. **Depot Loading Queue:** Lists all trucks at the dock, sorted by departure time.
   *→ Connects to:*
9. **Active Loading Checklist:** Reverse-sequence list. Split by Ambient/Chilled. Large buttons.
   *→ Connects to:*
10. **Seal & Release Screen:** Prompts the loader to input the plastic Tamper-Evident Seal number and flag any final shortfalls before releasing the truck.

### D. The Driver Flow (Saman)
11. **Pre-Trip Inspection (PTI):** The very first screen. 9-point checklist (tires, fuel, reefer temp). Must pass to unlock the route.
   *→ Connects to:*
12. **Master Route Card:** (Offline Capable). Shows all stops. Includes the "Take Rest Break" button and a clear "Offline Sync" indicator.
   *→ Connects to:*
13. **Stop Execution Screen:** Trilingual. Shows dock instructions. Buttons to capture Photo Proof of Delivery or trigger Exceptions (e.g., "Store Closed").

### E. The Degradation / Crisis Screens (Hackathon Requirement)
14. **The "Death Spiral" Alert (Dispatcher):** A specialized crisis UI state when festival demand exceeds capacity by >30%. Prompts the dispatcher to switch from "Equity Mode" to "Quota Mode".
15. **Mid-Load Plan Change (Loader):** A full-screen, unmissable amber overlay interrupting the loader when the dispatcher changes the plan mid-execution.

---

## 3. Next Steps Execution Plan

1. **You review this plan and select a Theme (A, B, or C).**
2. **I have created the `.agents/rules/ui_ux_guidelines.md` file in your workspace** so that all AI agents generating code or designs will strictly adhere to these specific psychological principles and constraints.
3. We connect to the **Stitch MCP**.
4. We generate the overarching Design System.
5. We prompt Stitch to generate the highest-priority screens one by one, refining them until they look world-class.
