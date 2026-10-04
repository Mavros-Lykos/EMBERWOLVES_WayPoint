# Phase 0 — Operational Reality Check

Reviewed on Day 2, Saturday 26 September 2026, against the challenge booklet, the kick-off session, and the shipped reference tables. This phase decides how the operation works. It does not choose the S1 allocation, and it does not start the product build.

The booklet is the requirements specification. Where the kick-off slides disagree with it, follow the booklet. The Designathon deadline is Tuesday 29 September 2026, 11:59 PM Sri Lanka time (UTC+05:30), not the "8 Sep / IST" line on the kick-off slide.

---

## Why this phase exists

Waypoint runs one network for three brands. Fresh must be in stores before 08:00, chilled freight needs a refrigerated vehicle, some doors accept only a van, Style fills volume before weight, and Tech is heavy and fragile. On a peak day the fleet cannot do all of that. The system we build has to make a legal plan, show the people who load and drive it, and leave a record of what was deferred and why.

Two different bars apply, and they must not be mixed up.

| Bar | What it decides | What enforces it |
|---|---|---|
| Submission feasibility | Task 2B file is legal | The seven rules in the booklet, implemented by `check_allocation.py` |
| Operational policy | Which legal plan we stand behind | Delivery windows, equity, fuel, loading sequence, explanations |

A plan can pass the checker and still be a poor operating decision. A plan that breaks a checker rule is not a submission, however sensible it looks on a map. Passing the checker means the allocation is feasible. It does not mean it is the best one. The booklet says there is no single correct allocation.

---

## Locked facts

These were read from the shipped files. Later activities use these figures and do not restate a different fleet.

**Network.** 120 outlets (Fresh 80, Style 25, Tech 15). Two depots: Peliyagoda 75 outlets, Kandy 45. Twelve districts. Sixty vehicles: 12 refrigerated trucks, 40 dry-box trucks, 8 vans, 4 of those vans refrigerated. That is the booklet fleet, and the file matches it.

**Peliyagoda fleet.** Reefer trucks VEH001–VEH007 (7). Ambient trucks VEH008–VEH034 (27). Reefer vans VEH035 and VEH036, each 1,040 kg and 7.0 m³. Ambient vans VEH037 (1,100 kg, 8.0 m³) and VEH038 (1,200 kg, 9.0 m³). Largest volume in the whole company is 38.0 m³.

**Kandy fleet.** Reefer trucks VEH039–VEH043 (5). Ambient trucks VEH044–VEH056 (13). Reefer vans VEH057 and VEH058. Ambient vans VEH059 and VEH060. S1 cannot use Kandy vehicles. A vehicle serves only its own depot.

**S1 fleet.** The scenario file lists the 38 Peliyagoda vehicles. Ten are `in_workshop`: VEH001, VEH002, VEH004, VEH005 (reefer trucks), VEH012, VEH016, VEH021, VEH022, VEH026 (ambient trucks), VEH035 (reefer van). Twenty-eight are available:

| Available | Count | Ids |
|---|---|---|
| Reefer truck | 3 | VEH003 (5,510 kg, 26.4 m³), VEH006 (6,840 kg, 33.4 m³), VEH007 (3,610 kg, 19.4 m³) |
| Ambient truck | 22 | The Peliyagoda dry trucks that are not in the workshop list |
| Reefer van | 1 | VEH036 (1,040 kg, 7.0 m³) |
| Ambient van | 2 | VEH037, VEH038 |

**S1 demand.** 85 orders, all Peliyagoda. Fresh 75 (49 ambient, 26 chilled), Style 5, Tech 5. A festival is one week away. It is not a payday. There is no monsoon. Ten orders have `deferred_yesterday = 1`.

**One structural miss.** S1-078 (OUT070, Style, Kurunegala, ambient, normal access) is 40.66 m³ and 2,561.6 kg. Every vehicle tops out at 38.0 m³. Orders are not split. S1-078 cannot be served by any vehicle in the company, workshop or not. It is deferred, with that reason. No other S1 order is individually larger than every eligible available vehicle.

**Chilled volume cannot all fit.** The 26 chilled orders total 181.63 m³. If all four available reefer vehicles ran two trips and every one of those trips were packed to the volume cap with chilled freight, the ceiling would be 2 × (26.4 + 33.4 + 19.4 + 7.0) = 172.4 m³. District grouping, time, and imperfect packing only lower that ceiling. Some chilled volume is deferred even before those effects. Chilled weight, 32,780 kg, sits under the same perfect-pack weight ceiling, so weight is not the binding company-wide limit. Volume is.

---

## How a trip is legal

`check_allocation.py` enforces all of the following. The allocation engine and the hand-built S1 file do too.

1. One brand and one district on a `vehicle_id` + `trip_id`.
2. `temp_requirement = chilled` requires `temp = reefer`. A reefer may also carry ambient freight. An ambient vehicle carries none of the chilled freight.
3. `parking_constraint = van_only` requires `type = van`. `mall_dock` is not `van_only`. A truck may serve a mall if the rest of the rules allow it. The mall restriction is the mall's clock.
4. The vehicle and the outlet share a depot.
5. A served order sits on one vehicle and one trip. No splits.
6. On each trip, total weight ≤ `weight_cap_kg` and total volume ≤ `volume_cap_m3`. Both. At the stated caps, not at a private percentage of them.
7. At most two trips per vehicle per day, across every brand. Fresh trip minutes sum to ≤ 270. Style and Tech trip minutes sum to ≤ 480. Those are separate pools. A vehicle may run one Fresh trip and one Style or Tech trip.

Trip minutes, for the submission and the checker:

```
trip_minutes = depot_to_district_freeflow_min
             + inter_stop_freeflow_min × (number of orders − 1)
             + sum of service_allowance_min for each order's brand and dock_type
```

The return to the depot is not added. The budgets already allow for it. Inter-stop count is orders minus one, including two orders for the same outlet. That is the published formula, and it is what the checker computes.

`decision` is the lowercase string `served` or `deferred`. A served row has `vehicle_id` and `trip_id` of 1 or 2. A deferred row leaves both blank. Keep every supplied `scenario`, `order_ref`, and `outlet_id`. The allocation key is `order_ref`, because an outlet can appear twice. Replace every template placeholder.

The checker does not reject a plan for delivery windows, mall windows, fuel, equity, or loading sequence. Those still belong in the written policy and in the product. Driver availability is not a separate constraint on this fleet. Each vehicle already has a driver.

---

## Reality 1 — A trip is one brand and one district

A Fresh Colombo trip does not pick up Style, and it does not continue into Gampaha. The grouping is done before any packing: `(depot, brand, district)`, then eligible vehicles, then trips.

A second trip is a new departure from the depot after the first. It may be a different brand and a different district. The "on the way back" picture is the wrong model: the vehicle does not collect a Tech order on the return leg. It comes home, and trip 2 is planned on its own. Trip 2 still consumes one of the two trip slots, and its minutes go into the Fresh pool or the Style/Tech pool, whichever brand it is.

Because brands cannot share a box, a half-empty Style truck and a half-empty Tech truck stay half empty. Utilization below a naive mix-all-brands calculation is normal. The dispatcher screen has to show the group and refuse a cross-brand or cross-district drop with the specific reason.

---

## Reality 2 — Both caps, at the stated numbers

The legal test is the stated weight cap and the stated volume cap. A haircut such as "85% of reefer volume" or "never past 90% on Style" is a choice to defer freight that the checker would have accepted. If we use a margin, the policy names it as a choice and names the orders it cost. It is not described as impossible.

What the brands actually do inside those caps:

- Fresh cartons stack. Chilled and ambient may share a reefer. They still count against the same weight and volume caps. The booklet does not define a bulkhead that shrinks the cap.
- Style hangs. Volume binds before weight. The check is still the full `volume_cap_m3`.
- Tech is heavy, fragile, and often a few large pieces. Check weight and volume. Do not invent a no-stack factor that silently shrinks the cap.

S1-078 is the exception that really is impossible: 40.66 m³ against a 38.0 m³ largest vehicle, and orders are whole. The policy says that in one sentence.

---

## Reality 3 — S1 has one reefer van

VEH035 is in the workshop. VEH036 is the only refrigerated van that can reach `van_only` chilled doors. It runs at most two trips, and each trip is limited to 7.0 m³ and 1,040 kg. The two trips are not a 14.0 m³ bin. An order that does not fit on one trip cannot be spread across them.

The six `van_only` orders are all Fresh Colombo, street access, OUT001–OUT003. Each outlet has an ambient order and a chilled order.

| Order | Outlet | Temp | m³ | kg | Window |
|---|---|---|---|---|---|
| S1-000 | OUT001 | ambient | 0.500 | 97.8 | 05:00–07:30 |
| S1-001 | OUT001 | chilled | 2.445 | 448.6 | 05:00–07:30 |
| S1-002 | OUT002 | ambient | 0.666 | 130.9 | 05:30–08:00 |
| S1-003 | OUT002 | chilled | 1.843 | 329.0 | 05:30–08:00 |
| S1-004 | OUT003 | ambient | 0.870 | 160.6 | 05:00–07:30 |
| S1-005 | OUT003 | chilled | 1.725 | 318.1 | 05:00–07:30 |

Chilled total: 6.013 m³ and 1,095.7 kg. That weight is over 1,040 kg, so the three chilled orders do not share one van trip. Split across VEH036's two trips, the volume and the weight can fit. One worked split is S1-001 + S1-005 on trip 1 (4.17 m³, 766.7 kg, 64 minutes) and S1-003 on trip 2 (1.843 m³, 329 kg, 40 minutes). That split is feasible. It is not the only feasible plan.

Trip 1 in that split still has spare cap. A reefer may carry ambient freight of the same brand and district. S1-000 (0.500 m³, 97.8 kg) fits on that trip and keeps it legal on weight and volume. Deferring one of the chilled van orders and using the freed trip another way is also legal. Activity A4 chooses and explains. Phase 0 does not crown a single van pattern.

Kandy, on any other day, is tighter than this: 10 van-only outlets (8 Fresh, OUT088 Style, OUT093 Tech) and two reefer vans. S1 does not allocate Kandy. The product still has to know those doors exist.

---

## Reality 4 — 270 minutes is a sum of trip minutes, not a ban by district

Fresh vehicles are budgeted 270 minutes for their Fresh trips, corresponding to the 03:30–08:00 operation. Style and Tech share 480 minutes. Return driving is already inside those budgets, so it is not added again, and it is not used as a second clock on top of the sum.

A district name does not forbid a second trip. Compute the first trip. Subtract from 270. A second Fresh trip is legal when its own `trip_minutes` fit in what remains, the vehicle still has a free trip slot, and the trip is one brand and one district.

Minimum Fresh trip, one rear-dock stop, no inter-stop (`outbound + 15`):

| District | Outbound | Inter-stop | One-stop minimum | Room left in 270 |
|---|---|---|---|---|
| Colombo | 24 | 8 | 39 | 231 |
| Gampaha | 37 | 9 | 52 | 218 |
| Kalutara | 64 | 12 | 79 | 191 |
| Galle | 103 | 9 | 118 | 152 |
| Matara | 137 | 10 | 152 | 118 |
| Kurunegala | 127 | 19 | 142 | 128 |
| Puttalam | 173 | 24 | 188 | 82 |
| Kandy | 16 | 6 | 31 | 239 |
| Matale | 35 | 11 | 50 | 220 |
| Kegalle | 53 | 13 | 68 | 202 |
| Nuwara Eliya | 111 | 20 | 126 | 144 |
| Badulla | 186 | 23 | 201 | 69 |

Every district can take a one-stop Fresh trip inside 270, including Puttalam and Badulla. A second trip is then a question about the remainder.

Worked bounds, so nobody repeats the old "this district cannot" line:

- Colombo street: 10 orders is 24 + 9×8 + 10×16 = 256 minutes. An 11th street stop is 280 and does not fit. Rear-dock Colombo fits 11 orders (269) and not 12.
- Puttalam, three rear-dock stops: 173 + 2×24 + 3×15 = 266. Four minutes remain. That particular trip cannot be followed by another Fresh trip. A one-stop Puttalam trip (188) leaves 82 minutes, which covers a one-stop Colombo street trip (24 + 16 = 40).
- All four Puttalam S1 orders on one trip: 173 + 3×24 + 4×15 = 305. Over 270. They cannot travel as a single trip. A subset can.
- Badulla, one rear-dock stop, leaves 69 minutes. A one-stop Kandy rear-dock trip is 31 minutes, so a second trip can exist. Three Badulla rear-dock stops are 186 + 2×23 + 3×15 = 277 and do not fit even as the only trip.

Outlet windows are a separate clock. Several Fresh windows open at 03:00, and the Fresh operation starts at 03:30, so the vehicle cannot be at the door at 03:00. Lateness in this problem means arrival after `window_close_time`, not arrival after the window opens. A Puttalam vehicle that leaves at 03:30 arrives about 06:23. OUT073 and OUT075 close at 08:00, and OUT074 closes at 08:00. That arrival is inside the window. It is a thin slack and a strong Task 1 feature. It is not a reason to call the outlet unservable. The same reading applies to Badulla's early windows against the 186-minute outbound from Kandy.

S1 Puttalam orders:

| Order | Outlet | Temp | m³ | kg | Deferred yesterday | Days since served | Window |
|---|---|---|---|---|---|---|---|
| S1-081 | OUT073 | ambient | 2.380 | 402.3 | 0 | 1 | 03:00–08:00 |
| S1-082 | OUT074 | ambient | 2.618 | 532.1 | 0 | 1 | 05:30–08:00 |
| S1-083 | OUT074 | chilled | 8.659 | 1,588.8 | 1 | 5 | 05:30–08:00 |
| S1-084 | OUT075 | ambient | 1.859 | 336.9 | 0 | 1 | 03:00–08:00 |

S1-083 fits every available reefer truck on size (smallest is VEH007 at 19.4 m³ and 3,610 kg). Alone it costs 188 minutes. It does not, by itself, exhaust the Fresh budget. Whether it is served is a priority decision against the other chilled work those three trucks must do, including the company-wide chilled volume overflow. The policy discusses it. Phase 0 does not pre-defer it.

---

## Reality 5 — Loading order is a warehouse practice

The checker ignores load sequence. The warehouse cannot. The first door on the route is the last freight put on the tail. The loader's list is the reverse of the dispatcher's route. Same data, two orders.

When a reefer carries chilled and ambient, the loader still needs a sequence that can be unloaded at each stop. A zone note on the loading list is a design choice that matches how a reefer is actually worked. It is not a second capacity cap, and it does not change `volume_cap_m3`.

The product shows the loader the sequence, and lets them flag a short or damaged item before the vehicle leaves. A plan that changes while loading has to reach that tablet. Judges will open the loader flow on a phone-width screen, so the list is usable there, not only on a dock monitor.

---

## Reality 6 — The day starts at 16:00, for the next morning

Orders for the next operating day close at 16:00. Anything later waits for the following run. After cutoff the dispatcher has a fixed book: orders, available vehicles, constraints. The useful system produces a legal candidate quickly, lets the dispatcher move orders and see the broken rule immediately, and sends the approved load lists to the warehouse.

The clock from 16:00 to the 03:30 departures below is an operating picture so the screens have a sense of time. It is not a number from the booklet.

```
16:00        Book closes
16:00–19:00  Dispatcher reviews the candidate, overrides, approves
19:00        Load lists reach the warehouse
Late evening Loading for the first Fresh departures
03:30        Fresh operation's planned start
08:00        Fresh window ends
Daytime      Style and Tech use the 480-minute pool
```

Once a vehicle has left, progress has to be visible without a phone call. The driver posts each stop from the handset. Where coverage drops — hill country, the Kandy corridor, rural districts — the handset keeps working and syncs when the connection returns. The dispatcher sees the last confirmed stop and how long ago it was, and can decide whether to call.

---

## Reality 7 — Ten orders are already overdue

Ten of the 85 S1 orders have `deferred_yesterday = 1`.

| Order | Outlet | Brand | District | Temp | Days since served |
|---|---|---|---|---|---|
| S1-020 | OUT013 | Fresh | Colombo | ambient | 2 |
| S1-023 | OUT022 | Tech | Colombo | ambient, mall | 5 |
| S1-025 | OUT024 | Tech | Colombo | ambient | 5 |
| S1-038 | OUT032 | Fresh | Gampaha | chilled | 2 |
| S1-041 | OUT034 | Fresh | Gampaha | chilled | 2 |
| S1-045 | OUT043 | Fresh | Kalutara | ambient | 3 |
| S1-050 | OUT046 | Fresh | Kalutara | ambient | 3 |
| S1-068 | OUT063 | Style | Matara | ambient | 5 |
| S1-079 | OUT071 | Style | Kurunegala | ambient | 5 |
| S1-083 | OUT074 | Fresh | Puttalam | chilled | 5 |

This is a policy, not an eighth feasibility rule. A professional allocator tries not to skip the same door again, and tries not to hold chilled freight an extra day, and will still defer either one when the caps or the clocks make service illegal. The policy separates three kinds of deferral:

- Illegal to serve. S1-078 is the clear case. Any chilled order left over the reefer ceiling is in this family once the legal trips are full.
- Served ahead of something else because it was skipped yesterday or because it is chilled.
- Deferred by choice, with the order it displaced and the reason.

Suggested order of attention inside the legal plans: already-skipped chilled, other chilled, already-skipped ambient, then the flexible Style and Tech work. A4 may revise that order. It has to write the order down and show the cost.

---

## Reality 8 — Fuel is real, and S1 cannot prove it binds

Each vehicle has `weekly_fuel_quota_l`. Distance consumes it. Consumption is distance divided by `km_per_l`.

The Task 2B checker does not read fuel. The S1 files do not say how much of this week's quota is already burned. A single Peliyagoda day is unlikely to empty a quota that is sized for a week, so fuel is a weak reason to defer an S1 order. The written policy says that in a sentence, instead of pretending the quota was optimized.

The product does track it. Week-to-date liters per vehicle, with a warning as the quota gets close. Count the distance the vehicle actually drives, including the return to the depot and the inter-stop legs. The time formula omits the return; the fuel figure does not, because the tank does not. Puttalam is about 130 km each way from Peliyagoda, so a there-and-back day is on the order of 260 km plus stops. Far districts are where the weekly quota can bind across Monday–Saturday. The product shows that. It does not invent a remaining-liters column the scenario does not contain.

---

## Reality 9 — What each release has to contain

The three releases are one product and two analytical deliveries. The Datathon is judged on its own. The operating system must run the full cycle with no model loaded. Forecasts and service-time predictions are added on top of that loop, and they are not a condition for Day 10.

**Designathon, due Day 5.** One system with four faces, not four apps. Personas grounded in the actual workplace: dispatcher on a large screen in Peliyagoda, loader on a shared tablet that must also work at phone width, driver on a personal phone including while offline, store manager at the counter. Screen flows with a one-paragraph rationale each. At least one degradation screen, named, with a short reason it matters; depth matters more than the count of failure cases. High-fidelity prototype link. Unlisted YouTube walkthrough of 3–5 minutes. AI disclosure. Optional one-page tradeoff and optional style guide. Package as `TeamName_Designathon.zip`. The file is judged as it stands at the deadline. Later changes are written into the Hackathon README.

**Hackathon, due Day 10.** A responsive web application a judge can run from planning through loading, delivery, and receipt, on phone-width screens for the driver and the loader. Native apps are optional. Public URL, four seeded accounts, GitHub monorepo `TeamName_SolutionName`, README with setup, accounts, a numbered walkthrough, and design departures. `docker compose up` at the repo root brings up the app, the database, and the seed, with `.env.example` beside it. `docs/` holds the architecture diagram, the data model, and the AI disclosure. Unlisted video of 5–8 minutes: all four roles, then the code. The deployment stays up through review and, if the team advances, through the semifinal and the finale. Code pushed after the deadline is ignored.

The product respects capacity, temperature, depot, van access, brand and district grouping, the two trip slots, the 270 and 480 minute pools, delivery windows, and fuel quotas. Offline work away from the depot syncs later.

**Datathon, due Day 15.** Task 1 and Task 2A predictions, plus the S1 allocation and a policy of about one page. Training labels are constructed, not supplied. No pre-trained model, except a disclosed model used only for synthetic data or preprocessing. No proprietary API for modelling or preprocessing. No low-code or fully automated end-to-end modelling tool. Model files saved as `.h5` or `.pkl`, which is the kick-off format. Every experiment notebook, and `TeamName_FinalNotebook.ipynb` with outputs, including a final cell that loads the saved models and prints inputs and predictions for Task 1 and Task 2A. Architecture diagrams, a preprocessing write-up, both prediction files, `submission_task2b.csv`, the policy, a 3–5 minute unlisted video, and the AI disclosure, zipped as `TeamName_Datathon.zip`. If several submissions arrive, the latest one is the one that is evaluated.

Task 1 label, from the training route legs:

```
effective_start = max(arrival_time, window_open_time)
service_min     = leave_outlet_time - effective_start
is_late         = 1 if arrival_time > window_close_time else 0
```

The column is `arrival_time`, not `actual_arrival_time`. Windows come from the order row. A late arrival is still delivered, so those rows stay in the training set and still have a service time. `attempted` and `deferred` orders have route legs (90,351 and 1,543). The 413 `not_run` orders have no leg and stay out of Task 1. They stay in Task 2A, because they are demand. `seq` and `seq_in_route` start at 0. Join on `route_id` plus that sequence. Clock arithmetic has to survive midnight.

Task 2A training demand is `deliveries_train.csv` and `task1_test_inputs.csv`. Every order is counted once, on the ISO week of `order_date`, using `iso_year` and `iso_week` from `calendar.csv`. The test file has 60 rows: 2 depots × 3 brands × 10 weeks. `pred_chilled_volume_m3` is 0 for Style and Tech. The forecast is volume. It is not a vehicle plan and not a driver plan.

---

## What is decided, and what is still open

| Topic | Decision |
|---|---|
| Trip grouping | `(depot, brand, district)`, then pack |
| Caps | Stated weight and stated volume, both, per trip |
| Time | Published formula. Return excluded. Fresh sum ≤ 270. Style+Tech sum ≤ 480. Two trips total |
| Second trips | Legal whenever the remainder can hold the next trip. Not banned by district name |
| S1 reefer van | VEH036 only. Two trips, each 7.0 m³ / 1,040 kg. Ambient may share |
| S1 reefer trucks | VEH003, VEH006, VEH007 |
| Structural deferral | S1-078. Plus whatever chilled volume cannot fit the legal reefer trips |
| Chilled ceiling | 172.4 m³ perfect-pack upper bound against 181.63 m³ ordered |
| Windows and fuel | Policy and product. Not checker rules. Fuel not provable on S1 |
| Equity | Policy. Ten overdue orders. Does not override an illegal assignment |
| Loader list | Reverse route order. Phone-width. Zone note when a reefer mixes temperatures |
| Datathon models | Separate delivery. The product runs without them |
| S1 allocation itself | Not decided. That is Activity A4 |
| Screen-by-screen scope | Not decided. That is Activity A3, and it is due first |

---

## What this changes in later work

Designathon: one flow from order to receipt. The degradation case worth designing in full is a festival-week morning when reefer capacity cannot cover chilled demand, including the van doors, with a reason on the deferred order and that reason visible to the store. Document any other failure (offline driver, plan changed during loading) only if it is designed properly.

Hackathon: the engine groups, checks both caps, checks both time pools, and refuses illegal drops. The checker rules and the extra operating rules (windows, fuel, offline) are both in the product. Seed S1 so a judge can run the cycle. Do not block the cycle on a model.

Datathon: build labels with the wait-until-open rule. Train Task 2A on history plus the Task 1 test orders. Write the S1 policy so a reader can see what was illegal, what was preferred, and what was given up. Run `check_allocation.py` before the file is called finished.

Activity A1 holds the fleet and geography tables. Where an older line in that file disagrees with this page, this page is the one to follow.
