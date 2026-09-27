# Activity A1 — Data Reconnaissance & Reference Tables
## Detailed Sub-Task Plan

> This is the foundation layer. Every decision in the Datathon, Hackathon, and Designathon traces back to what we establish here. Do this once, do it properly, document it, and never recompute it.

---

## Why A1 Comes First

The question was raised: *"Can we take this from the data, or should we assume using Sri Lankan context?"*

**Answer: The data is sufficient for all hard constraints. Sri Lankan context is used only for sanity-checking and qualitative design decisions (UI tone, geography visualization, operational realism in personas).**

| Source | Use Data? | Use SL Context? |
|---|---|---|
| Vehicle capacities (weight, volume) | ✅ `vehicles.csv` | Sanity check only |
| Vehicle types (van/truck, reefer/ambient) | ✅ `vehicles.csv` | No |
| District travel times | ✅ `district_travel.csv` | Verify reasonableness |
| Outlet constraints | ✅ `outlets.csv` | UX tone / personas |
| Delivery windows | ✅ `outlets.csv` | SL business hours validate |
| Service handling time | ✅ `service_allowance.csv` | Real-world plausibility |
| Calendar events | ✅ `calendar.csv` | SL festival names for UI |
| Route visualization map | ❌ Not in data | **YES — use Sri Lankan geography** |

---

## Sub-Task A1.1 — Fleet Intelligence Matrix

> **Goal:** Know every vehicle by type, temperature capability, capacity, and depot. Answer the reefer van question definitively.

### What the Data Tells Us (No Assumptions Needed)

**Full Fleet Breakdown — Already Computed from `vehicles.csv`:**

```
PELIYAGODA DEPOT (VEH001–VEH038)
┌─────────────┬────────┬────────┬─────────────────────────────────────┐
│ Category    │ Count  │ VEHs   │ Key Capacities                      │
├─────────────┼────────┼────────┼─────────────────────────────────────┤
│ Reefer Truck│  6     │ 001–007│ Weight: 3,610–6,840kg               │
│             │        │        │ Volume: 19.4–33.4 m³                │
├─────────────┼────────┼────────┼─────────────────────────────────────┤
│ Ambient Trk │ 28     │ 008–034│ Weight: 3,800–7,200kg               │
│             │        │        │ Volume: 22.0–38.0 m³                │
├─────────────┼────────┼────────┼─────────────────────────────────────┤
│ Reefer Van  │  2     │ 035,036│ Weight: 1,040kg  Volume: 7.0 m³     │
├─────────────┼────────┼────────┼─────────────────────────────────────┤
│ Ambient Van │  2     │ 037,038│ Weight: 1,100–1,200kg Vol: 8–9 m³   │
└─────────────┴────────┴────────┴─────────────────────────────────────┘

KANDY DEPOT (VEH039–VEH060)
┌─────────────┬────────┬─────────┬────────────────────────────────────┐
│ Category    │ Count  │ VEHs    │ Key Capacities                     │
├─────────────┼────────┼─────────┼────────────────────────────────────┤
│ Reefer Truck│  5     │ 039–043 │ Weight: 3,610–6,180kg              │
│             │        │         │ Volume: 19.4–29.9 m³               │
├─────────────┼────────┼─────────┼────────────────────────────────────┤
│ Ambient Trk │ 13     │ 044–056 │ Weight: 3,800–7,200kg              │
│             │        │         │ Volume: 22.0–38.0 m³               │
├─────────────┼────────┼─────────┼────────────────────────────────────┤
│ Reefer Van  │  2     │ 057,058 │ Weight: 1,040kg  Volume: 7.0 m³    │
├─────────────┼────────┼─────────┼────────────────────────────────────┤
│ Ambient Van │  2     │ 059,060 │ Weight: 1,200kg  Volume: 9.0 m³    │
└─────────────┴────────┴─────────┴────────────────────────────────────┘
```

**Fleet Totals:** 60 vehicles · 11 reefer trucks · 41 ambient trucks · 4 reefer vans · 4 ambient vans

---

### S1 Scenario Fleet — Critical Calculation

Cross-referencing `task2b_peak_day_fleet.csv` (S1 available vehicles, Peliyagoda only):

**In Workshop (cannot use):** VEH001, 002, 004, 005, 012, 016, 021, 022, 026, 035

```
S1 AVAILABLE FLEET — PELIYAGODA
┌──────────────┬───────┬───────────────────────────────────────────────┐
│ Category     │ Count │ Available VEHs                                │
├──────────────┼───────┼───────────────────────────────────────────────┤
│ Reefer Truck │  4    │ VEH003, VEH006, VEH007                        │
│              │       │ (VEH001,002,004,005 in workshop)              │
│              │       │ + checking: VEH003✅ VEH006✅ VEH007✅        │
│              │       │ Only 3 reefer trucks available!               │
├──────────────┼───────┼───────────────────────────────────────────────┤
│ Ambient Truck│ 23    │ VEH008–011, 013–015, 017–020, 023–025,        │
│              │       │ 027–034 (VEH012,016,021,022,026 workshop)     │
├──────────────┼───────┼───────────────────────────────────────────────┤
│ Reefer Van   │  1    │ VEH036 ONLY (VEH035 in workshop!)             │
│              │       │ ⚠️ THIS IS THE CRITICAL CONSTRAINT           │
├──────────────┼───────┼───────────────────────────────────────────────┤
│ Ambient Van  │  2    │ VEH037, VEH038                                │
└──────────────┴───────┴───────────────────────────────────────────────┘
TOTAL S1 AVAILABLE: 30 vehicles (3 reefer trucks + 23 ambient trucks 
                                  + 1 reefer van + 2 ambient vans)
```

> [!CAUTION]
> **ONLY 1 REEFER VAN IS AVAILABLE IN S1.** VEH036 is the only vehicle that can serve `van_only + chilled` orders. This single vehicle can run at most 2 trips (14.0 m³ total, 2,080 kg total across both trips). Any `van_only + chilled` order beyond that is **structurally impossible** to serve and must be deferred. This is the headline finding of the entire allocation.

### Sub-task Actions
- [ ] Verify VEH003 is reefer truck (26.4 m³, 5,510 kg) — confirmed from vehicles.csv
- [ ] Verify VEH036 specs: 7.0 m³, 1,040 kg per trip — very small van
- [ ] Build the "vehicle capability lookup table" as a Python dict / DataFrame for use in all three phases
- [ ] Document fuel quotas per vehicle — needed if fuel constraint is tested in judging

### Sri Lankan Context Sanity Check
- 1,040 kg van capacity is realistic for a Toyota HiAce or Mitsubishi Canter refrigerated van — common in Colombo urban logistics ✅
- 6,840 kg truck is consistent with a medium Isuzu truck — typical for Sri Lankan distribution ✅
- 7.0 m³ reefer van is about right for a HiAce body-build — you can fit roughly 14 standard crates ✅

---

## Sub-Task A1.2 — Outlet Geography Analysis

> **Goal:** Know every outlet's constraints, grouped by district. Build the allocation constraint map.

### Full Outlet Breakdown (Computed from `outlets.csv`)

```
PELIYAGODA DEPOT — 75 Outlets
District     │ Total │ Fresh │ Style │ Tech │ van_only │ mall_dock │ Notes
─────────────┼───────┼───────┼───────┼──────┼──────────┼───────────┼──────────────
Colombo      │  24   │  14   │   6   │   4  │  3(Fresh)│  4+2(S+T) │ Most complex
Gampaha      │  15   │  10   │   3   │   2  │  0       │  2(Style) │
Kalutara     │  10   │   7   │   2   │   1  │  0       │  0        │ 
Galle        │   9   │   6   │   2   │   1  │  0       │  1(Style) │
Matara       │   6   │   4   │   1   │   1  │  0       │  0        │
Kurunegala   │   8   │   5   │   2   │   1  │  0       │  0        │
Puttalam     │   3   │   3   │   0   │   0  │  0       │  0        │ Fresh only

KANDY DEPOT — 45 Outlets
District     │ Total │ Fresh │ Style │ Tech │ van_only │ mall_dock │ Notes
─────────────┼───────┼───────┼───────┼──────┼──────────┼───────────┼──────────────
Kandy        │  20   │  12   │   5   │   3  │  8(F+S+T)│ 2+1(S+T) │ Most van_only
Matale       │   8   │   6   │   1   │   1  │  0       │  0        │
Nuwara Eliya │   6   │   5   │   1   │   0  │  0       │  0        │ Hill country
Badulla      │   6   │   4   │   1   │   1  │  0       │  0        │ Hill country
Kegalle      │   5   │   4   │   1   │   0  │  0       │  0        │
```

### Van-Only Outlets — Full Inventory

```
PELIYAGODA — van_only outlets:
  OUT001  Fresh  Colombo  street  05:00-07:30  (also has chilled in S1)
  OUT002  Fresh  Colombo  street  05:30-08:00  (also has chilled in S1)
  OUT003  Fresh  Colombo  street  05:00-07:30  (also has chilled in S1)

KANDY — van_only outlets:
  OUT076  Fresh  Kandy   street  03:00-08:00
  OUT077  Fresh  Kandy   street  05:00-07:30
  OUT078  Fresh  Kandy   street  03:00-08:00
  OUT079  Fresh  Kandy   street  04:00-07:45
  OUT080  Fresh  Kandy   street  05:30-08:00
  OUT081  Fresh  Kandy   street  03:00-08:00
  OUT082  Fresh  Kandy   street  03:00-08:00
  OUT083  Fresh  Kandy   street  04:00-07:45
  OUT088  Style  Kandy   street  09:00-17:00  ← Style van_only (unusual!)
  OUT093  Tech   Kandy   street  09:00-17:00  ← Tech van_only (unusual!)
```

> [!NOTE]
> Kandy has 10 van_only outlets — 8 Fresh + 1 Style + 1 Tech. This is a significant structural insight for the Kandy depot. Kandy has 2 reefer vans (VEH057, VEH058) to cover all 8 Fresh van_only outlets which could potentially have chilled orders. The Kandy constraint is arguably tighter than Peliyagoda on a normal day.

### Mall Outlets — Delivery Window Matrix

```
Outlet    Brand   District    Mall Window      Window
OUT015    Style   Colombo     09:00-11:00      2 hours
OUT016    Style   Colombo     09:00-11:00      2 hours (same window as 015!)
OUT017    Style   Colombo     10:30-12:30      2 hours
OUT018    Style   Colombo     10:30-12:30      2 hours (same window as 017!)
OUT021    Tech    Colombo     10:30-12:30      2 hours
OUT022    Tech    Colombo     10:00-12:00      2 hours
OUT035    Style   Gampaha     10:30-12:30      2 hours
OUT036    Style   Gampaha     10:30-12:30      2 hours (same window!)
OUT056    Style   Galle       10:00-12:00      2 hours
OUT089    Style   Kandy       10:30-12:30      2 hours
OUT090    Style   Kandy       10:30-12:30      2 hours (same window!)
OUT094    Tech    Kandy       09:00-11:00      2 hours
```

> [!IMPORTANT]
> OUT015 and OUT016 share the **same mall, same window (09:00-11:00)**. They can potentially be served in one trip. Same for OUT017/018, OUT035/036, OUT089/090. This is a grouping opportunity — the allocation engine should detect co-located mall outlets and cluster them. Style service time in a mall is **59 minutes per stop** — fitting 2 mall stops in a 2-hour window is extremely tight: 59 + 59 = 118 min + travel ≈ impossible unless the depot is very close. This needs calculation in A1.5.

### Sub-task Actions
- [ ] Build `outlet_profile` lookup table keyed by `outlet_id`
- [ ] Flag co-located mall outlets (same mall_window) as grouping candidates
- [ ] For S1 specifically: list all `van_only` outlets appearing in `task2b_peak_day_scenarios.csv`
- [ ] Note that Puttalam has zero Style/Tech — only 3 Fresh outlets, pure chilled/ambient planning

---

## Sub-Task A1.3 — District Geometry & Sri Lankan Map

> **Goal:** Understand travel times in the context of Fresh's 270-minute window. Map the network geographically.

### District Travel Reference (from `district_travel.csv`)

```
PELIYAGODA DEPOT
District     Road Class  D→D (min)  Inter-stop  Fresh Feasibility
──────────── ──────────  ─────────  ──────────  ──────────────────
Colombo      urban         24        8 min       ✅ 2x trips possible
Gampaha      suburban      37        9 min       ✅ 2x trips possible
Kalutara     suburban      64       12 min       ⚠️  Tight for 2x trips
Galle        highway      103        9 min       ❌ 1 trip max (103 alone!)
Matara       highway      137       10 min       ❌ 1 trip only
Kurunegala   suburban     127       19 min       ❌ 1 trip only
Puttalam     suburban     173       24 min       ❌ CANNOT complete within 270min!

KANDY DEPOT
District     Road Class  D→D (min)  Inter-stop  Fresh Feasibility
──────────── ──────────  ─────────  ──────────  ──────────────────
Kandy        urban         16        6 min       ✅ 2x trips likely
Matale       suburban      35       11 min       ✅ 2x trips possible
Kegalle      suburban      53       13 min       ⚠️  Tight for 2x trips
Nuwara Eliya hill         111       20 min       ❌ 1 trip only (hill road)
Badulla      hill         186       23 min       ❌ Cannot complete Fresh window!
```

### ⚠️ The Puttalam Problem

Puttalam is 173 minutes outbound from Peliyagoda. The Fresh window is 270 minutes **total** (3:30 AM → 8:00 AM). A single Fresh trip to Puttalam:
- Outbound: 173 min
- 3 outlets × 15 min handling (rear_dock): 45 min
- No inter-stop (approximately): ~24 min × 2 = 48 min
- **Total: ~266 min** — barely fits ONE trip. Zero room for a second.

AND: OUT073, OUT074, OUT075 all have early windows (03:00, 05:30, 03:00). The vehicle must leave Peliyagoda by **3:30 AM** at latest to reach a 03:00-window Puttalam outlet on time:
- 03:00 - 173 min = 00:07 AM departure required!

That is before the operating window even starts. **These 3 Puttalam Fresh outlets are chronically at-risk of lateness.** This is a rich feature for Task 1 (lateness prediction) and an important real insight for the Hackathon dispatcher UI.

> [!NOTE]
> Badulla is 186 minutes from Kandy (hill road). Kandy Fresh window same 270 min. Single trip barely feasible. No second trip. OUT110–OUT113 windows: 03:00, 03:00, 04:00, 05:30. A 03:00 window from Kandy means departure at 03:00 - 186 = **00:54 AM**. These are almost guaranteed late arrivals historically. Extremely useful for Task 1 feature engineering (`window_open_time - (depot_to_district_freeflow_min + service_time) < 0` → structural impossibility flag).

### The Sri Lankan Map Context

The 12 districts map to actual Sri Lanka geography. Knowing this helps with:
1. **Design:** Route visualization in the dispatcher UI uses real map coordinates
2. **Task 1 features:** Hill country (Nuwara Eliya, Badulla) has monsoon impact that's disproportionate vs. lowland districts
3. **Task 2A:** Festival demand in certain regions (Kandy → Esala Perahera; all districts → Sinhala New Year)
4. **Personas:** A Badulla driver faces hill-road challenges vs. a Colombo driver

**District → Approximate GPS Center (for map visualization):**
```
Colombo       6.9271° N, 79.8612° E  (urban coast)
Gampaha       7.0873° N, 79.9993° E  (suburban north)
Kalutara      6.5854° N, 79.9607° E  (coastal south)
Galle         6.0535° N, 80.2210° E  (south coast)
Matara        5.9549° N, 80.5550° E  (far south)
Kurunegala    7.4868° N, 80.3647° E  (northwest)
Puttalam      8.0362° N, 79.8283° E  (far northwest)
Kandy         7.2906° N, 80.6337° E  (central hills)
Matale        7.4675° N, 80.6234° E  (central)
Kegalle       7.2513° N, 80.3464° E  (Sabaragamuwa)
Nuwara Eliya  6.9497° N, 80.7891° E  (high hills)
Badulla       6.9895° N, 81.0557° E  (Uva)
```

### Sub-task Actions
- [ ] Build district feasibility table: max Fresh trips per vehicle per district
- [ ] Flag Puttalam and Badulla as "structural lateness risk" districts → key feature for Task 1
- [ ] Calculate for each district: `max_orders_per_trip` given service allowance and time budget
- [ ] Prepare map GeoJSON data for dispatcher UI (Hackathon A8)

---

## Sub-Task A1.4 — Calendar Intelligence

> **Goal:** Understand the temporal drivers of demand and travel disruption.

### What to Extract from `calendar.csv`

The file covers historical + forecast periods (36KB → roughly 3–5 years of daily records).

**Key fields to analyze:**

| Field | What to Look For |
|---|---|
| `festival_ramp` | Distribution: how many days per year have ramp > 0.5? This is "elevated demand" period |
| `is_payday` | How many paydays per month? Sri Lanka: typically end-of-month (28th-31st) |
| `monsoon` | Which months? Sri Lanka: SW monsoon May-Sep, NE monsoon Oct-Jan |
| `festival` | Unique festival names → correlate with demand spikes in deliveries_train |
| `is_operating` | Which days Waypoint doesn't run → gaps in training data (not zero demand!) |
| `iso_week` | Used for Task 2A grouping — verify no ISO week boundary bugs |

### Sri Lankan Festival Calendar (Context for Naming in UI)

These are the festivals the synthetic calendar is almost certainly modeled after:
```
Sinhala & Tamil New Year    April 13-14     → Huge nationwide demand spike
Vesak                       May (poya)      → Moderate; Kandy district big
Esala Perahera              July-August     → Kandy-specific demand spike
Deepavali                   October-Nov     → Style brand (clothing)
Christmas                   December 25     → Fresh + Tech surge
Poson Poya                  June            → Kandy corridor demand
```

**Why this matters:** The S1 scenario says "festival is one week away." `festival_ramp` = ~0.78 at D-7. If we can identify which festival it is from the calendar, we can better characterize the demand surge type (chilled food? clothing? electronics?).

### Sub-task Actions
- [ ] Read first 50 + last 50 rows of `calendar.csv` to understand date range
- [ ] Count operating days per ISO week (should be 6 Mon-Sat)
- [ ] Identify festival dates and map to `festival_ramp` curve
- [ ] Note monsoon months → used as feature flag in Task 1 and Task 2A

---

## Sub-Task A1.5 — Service Allowance Time Budget Analysis

> **Goal:** Build intuition for how handling time dominates trip duration for Style and Tech.

### The Numbers (Already Have From Data)

```
Brand    Dock Type    Service Time    Notes
Fresh    rear_dock    15 min         Fastest — roll-in carts, cold chain practiced
Fresh    street       16 min         +1 min for curbside positioning
Fresh    mall_bay     18 min         +3 min for mall bay sharing/waiting
Style    rear_dock    38 min         2.5× Fresh — hanging garments, bulky cartons
Style    street       46 min         Longest curbside — garments can't be rushed
Style    mall_bay     59 min         ⚠️ Nearly 1 hour per stop!
Tech     rear_dock    43 min         Heavy items, fragile — dolly + signature
Tech     street       55 min         No dock equipment, manual carry
Tech     mall_bay     55 min         Same as street (mall bay doesn't help much)
```

### The Implications — Calculated

**Scenario: Style truck serving 2 Colombo mall stops (OUT015 + OUT016):**
```
Depot → Colombo:           24 min
OUT015 handling (mall):    59 min
Inter-stop travel:          8 min
OUT016 handling (mall):    59 min
                          ─────────
Total trip time:          150 min  ← fits 480-min Style window easily
BUT: Both outlets have 09:00-11:00 window = 120 minutes
Start at 09:00, OUT015 takes 59 min → finish at 09:59
Travel 8 min → arrive OUT016 at 10:07
OUT016 service 59 min → finish at 11:06 → ❌ MISSES THE 11:00 WINDOW!
```

> [!CAUTION]
> **You cannot serve OUT015 and OUT016 (same mall, same window) in sequence with 59-minute service time.** They share the same 09:00-11:00 window and each takes 59 minutes to handle. A single trip can only serve ONE of them within the window, or you need to verify if the actual mall bay allows parallel operations. This is a real operational constraint. In the Hackathon allocation engine, the system must detect this and warn the dispatcher. In the Datathon Task 2B, check if any S1 orders involve same-window mall outlets and handle accordingly.

**Maximum orders per trip by brand (rough guidance):**
```
Fresh (270-min window, Colombo):
  → After 24-min outbound: 246 min remaining
  → At 16 min/stop (street): ~15 stops max (before inter-stop kills it)
  → Practical: 8-10 stops per Fresh Colombo trip

Style (480-min window, Colombo):
  → At 59 min/stop (mall): max 6 stops in full day if all mall
  → At 38 min/stop (rear_dock): ~11 stops
  → Practical: 4-6 Style stops per trip

Tech (480-min window):
  → At 55 min/stop: ~8 stops max
  → Reality: Tech orders are often 1-2 items, but heavy — volume fills fast
```

### Sub-task Actions
- [ ] Build a lookup function: `service_allowance(brand, dock_type) → minutes`
- [ ] Pre-calculate `max_feasible_stops(vehicle, district, brand)` as a planning aid
- [ ] Flag the same-mall-window clustering problem for the Hackathon allocation engine
- [ ] Annotate S1 scenario: which orders have mall_bay dock? Check if any share windows.

---

## Sub-Task A1.6 — Traffic & Road Conditions

> **Goal:** Understand the speed and disruption modifier data for Task 1 feature engineering.

### `traffic_speed.csv` — What It Contains

- `speed_index`: 100 = freeflow, lower = congestion
- Likely indexed by `district`, `hour_of_day`, `monsoon` flag
- Used in Task 1 to estimate actual travel duration vs. planned

**Key Insight:** `actual_travel_duration = planned_travel_duration × (100 / speed_index)` approximately. The Task 1 test data only has *planned* travel times. To estimate actual, we need to model the speed_index for that district/hour/monsoon combination.

### `road_conditions.csv` — What It Contains

- `disruption_index`: 100 = clear, lower = disruption (roadworks, flooding, incidents)
- 248KB → substantial file. Likely indexed by `date` and `district`
- Monsoon months will have systematically lower disruption_index for hill roads
- Used in Task 1 as a feature: `disruption_index` on the test date per district

### Sub-task Actions
- [ ] Read headers of both files to confirm column names
- [ ] Check date range of road_conditions to confirm it covers test period dates
- [ ] Build joined feature: `effective_speed_multiplier = (speed_index / 100) × (disruption_index / 100)` for use in Task 1 travel delay features
- [ ] Validate: does hill country (Nuwara Eliya, Badulla) show lower disruption_index during monsoon months?

---

## Sub-Task A1.7 — Cross-Verification & Sanity Checks

> **Goal:** Catch data inconsistencies before they corrupt models. Every join key verified here.

### Join Key Verification Plan

```
deliveries_train ──[route_id + seq_in_route = route_id + seq]──> route_legs_train
deliveries_train ──[outlet_id]──> outlets.csv
deliveries_train ──[vehicle_id]──> vehicles.csv
route_legs_train ──[date]──> calendar.csv
route_legs_train ──[district]──> district_travel.csv
route_legs_train ──[district + date]──> road_conditions.csv
route_legs_train ──[district + hour + monsoon]──> traffic_speed.csv
```

### Checks to Run

```python
# Check 1: All outlet_ids in deliveries exist in outlets.csv
assert set(deliveries['outlet_id']).issubset(set(outlets['outlet_id']))

# Check 2: All vehicle_ids in deliveries exist in vehicles.csv
assert set(deliveries['vehicle_id'].dropna()).issubset(set(vehicles['vehicle_id']))

# Check 3: Every dispatched delivery has a matching route leg
dispatched = deliveries[deliveries['dispatch_status'] == 'attempted']
for _, row in dispatched.iterrows():
    match = route_legs[(route_legs['route_id'] == row['route_id']) & 
                       (route_legs['seq'] == row['seq_in_route'])]
    assert len(match) == 1  # exactly one leg per delivery

# Check 4: service_time sanity
route_legs['service_time'] = (
    route_legs['leave_outlet_time'] - route_legs['arrival_time']
).dt.total_seconds() / 60
assert route_legs['service_time'].min() > 0  # No negative service times
assert route_legs['service_time'].max() < 300  # No 5-hour deliveries

# Check 5: Refrigerated vehicle carried chilled orders only
chilled = deliveries[deliveries['temp_requirement'] == 'chilled']
assert (chilled['vehicle_temp'] == 'reefer').all()

# Check 6: van_only outlets were served by vans
van_outlets = outlets[outlets['parking_constraint'] == 'van_only']['outlet_id']
van_deliveries = deliveries[deliveries['outlet_id'].isin(van_outlets)]
assert (van_deliveries['vehicle_type'] == 'van').all()
```

### Expected Issues to Watch For

| Issue | Likelihood | Impact |
|---|---|---|
| `not_run` orders have NULL route_id and vehicle_id | Certain | Filter before joining |
| `deferred` orders dispatched on a later date | Certain | Use `order_date` not `dispatch_date` for Task 2A |
| Some `leave_outlet_time` < `arrival_time` (midnight crossover) | Possible | Handle time arithmetic with date context |
| `actual_travel_duration` outliers (accidents/breakdowns) | Likely | Cap at 3× planned or flag as anomalous |
| Missing road_condition rows for some date+district combinations | Possible | Impute with district average |
| Style orders: `order_volume_m3` near vehicle capacity | Expected | Confirms volume-first constraint |

### Sub-task Actions
- [ ] Run all 6 join/integrity checks above (write as a validation script)
- [ ] Profile distributions: service_time by brand+dock_type (compare to service_allowance.csv — actual vs. planned)
- [ ] Profile travel delay ratio by district and monsoon — establishes baseline for Task 1
- [ ] Save clean, joined DataFrame as `master_training_df.pkl` — used in A5 and A9

---

## A1 Output Deliverables

When A1 is complete, we have:

```
├── fleet_matrix.csv          → All 60 vehicles with type/temp/capacity/depot
├── s1_available_fleet.csv    → 30 available Peliyagoda vehicles for S1
├── outlet_profile.csv        → 120 outlets with all constraints + district
├── district_feasibility.csv  → Max Fresh trips, max stops per district
├── calendar_summary.csv      → Festival dates, payday dates, monsoon months
├── validation_report.txt     → All join checks passed/failed
└── master_training_df.pkl    → Clean joined training dataset (for A5/A9)
```

**Time estimate for A1:** 4–6 hours of focused work (one person, one notebook).

**Starting point:** The reefer van count is already answered — **1 reefer van (VEH036) available for S1.** This single fact unlocks the entire Task 2B allocation strategy. Start there.

---

*Next Activity: → A4 (Task 2B Allocation) uses A1 outputs directly. Can begin as soon as the fleet matrix and outlet profile are complete.*
