# Waypoint Delivery Operations — Master Plan

Day 2, Saturday 26 September 2026. The brief and the datasets landed on Day 1 (Friday 25 September, 00:01 Sri Lanka time). This plan is the build specification for one delivery system and for the two analytical deliveries that sit beside it. No application code starts from this page. Designathon scope is still open, and the Hackathon is graded against whatever that scope is on Day 5.

The booklet overrides the kick-off slides. Deadlines, Sri Lanka time (UTC+05:30):

| Release | Day | Deadline |
|---|---|---|
| Designathon | 5 | Tuesday 29 September 2026, 11:59 PM |
| Hackathon | 10 | Sunday 4 October 2026, 11:59 PM |
| Datathon | 15 | Friday 9 October 2026, 11:59 PM |

The three scores are equal. Missing a phase scores zero for that phase and does not block the next one. Phase 0 (`PHASE_0_FULL.md`) is the operational source of truth. If this file and Phase 0 ever disagree, Phase 0 wins until someone corrects both in the same pass.

---

## What we are building

Waypoint Group plans deliveries for Fresh, Style, and Tech through Peliyagoda and Kandy. Today that work is a spreadsheet, a phone call, a conversation at the dock, and a printed run sheet. After the vehicle leaves, the office cannot see the route. Deferrals are easy to repeat. Proof of delivery is someone's memory.

The system connects five stages for four people:

1. The store manager places the order before 16:00.
2. The dispatcher closes the book and assigns orders to vehicles and trips, or defers them with a reason.
3. The loader loads in unload-order and flags a shortfall before departure.
4. The driver runs the route and records each stop, including with no signal.
5. The store manager confirms receipt and reports what was wrong.

A sixth view, later, lets the dispatcher see next weeks' volume by depot and brand, and see which planned stops are likely to run long or arrive after the window. Those predictions are a separate delivery. The cycle above has to work with no model loaded.

---

## Rules that do not move

Full treatment and the verified S1 numbers are in Phase 0. The short form:

- One brand and one district per trip. A vehicle's second trip may be another brand. At most two trips a day.
- Chilled freight needs a reefer. A reefer may carry ambient freight. `van_only` needs a van. `mall_dock` is a clock, not a van rule. Vehicles stay on their own depot.
- Whole orders. Both the stated weight cap and the stated volume cap, per trip. No private percentage cap.
- Fresh minutes sum to ≤ 270. Style and Tech minutes sum to ≤ 480. Those pools are separate. Trip minutes use the published formula: outbound once, inter-stop × (orders − 1), plus the brand/dock allowance. The return is not added.
- `served` / `deferred` in lowercase. Deferred rows leave `vehicle_id` and `trip_id` blank. `trip_id` is 1 or 2. Key is `order_ref`.
- Windows, mall windows, equity, and fuel are operating rules for the product and for the written policy. `check_allocation.py` does not grade them. Driver headcount is not an extra constraint on the existing fleet.
- Task 1 labels use `arrival_time` from the route leg, not a column named `actual_arrival_time`. Early arrival waits until `window_open_time`. Late means arrival after `window_close_time`. Late rows are still delivered and stay in training.
- Task 2A counts every order, including `deferred` and `not_run`, on the ISO week of `order_date` from `calendar.csv`. Training rows come from `deliveries_train.csv` and `task1_test_inputs.csv`. Chilled volume is 0 for Style and Tech. The output is volume, not a fleet plan.
- Models are trained in code. No pre-trained model except a disclosed preprocessing or synthetic-data use. No proprietary preprocessing API. No low-code or fully automated modelling tool. Saved models are `.h5` or `.pkl`.

---

## S1, locked so the activities stop contradicting each other

85 orders. 28 available Peliyagoda vehicles and 10 in the workshop. Available reefers: trucks VEH003, VEH006, VEH007, and van VEH036. Ambient vans: VEH037, VEH038. Ambient trucks: 22. Festival one week away, not a payday, no monsoon.

S1-078 (40.66 m³) cannot ride on any vehicle. The 26 chilled orders total 181.63 m³ against a perfect-pack reefer ceiling of 172.4 m³, so some chilled volume is deferred even before district and time losses. The six Colombo `van_only` orders can be arranged in more than one legal way; VEH036's two trips are separate 7.0 m³ / 1,040 kg trips, and ambient may share a reefer trip. The allocation itself is Activity A4, not a conclusion of this plan.

---

## Activity map

| # | Activity | Release | State on Day 2 |
|---|---|---|---|
| A1 | Reference tables | All | Fleet and geography checked. Join audit not run |
| A2 | Domain model | Design + build | Drafted. Needs the rule corrections in this plan |
| A3 | Experience design | Designathon | Not started. Due in three days |
| A4 | S1 allocation and policy | Datathon | Not started. Facts it needs are ready |
| A5 | Task 1 labels and EDA | Datathon | Label rule locked. Work not started |
| A6 | Task 2A forecast | Datathon | Data rule locked. Work not started |
| A7 | System architecture | Hackathon | After the Day 5 design, not before |
| A8 | Operational build | Hackathon | After A7 |
| A9 | Task 1 models | Datathon | After A5 |
| A10 | Task 2A models | Datathon | After A6 |
| A11 | Release hardening | Hackathon | With A8, finished by Day 10 |
| A12 | Datathon package | Datathon | Day 13–15 |

Designathon is the critical path because the build is judged against it. Label construction and the S1 policy can proceed beside the design. Application code waits until the Day 5 screens are the spec.

---

## A1 — Reference tables

Detail: `ACTIVITY_A1_DETAILED.md`. Phase 0 holds the corrected fleet. A few facts that activities keep getting wrong:

- Company fleet is 12 reefer trucks and 40 dry trucks, not 11 and 41. Peliyagoda has 7 reefer trucks and 27 dry trucks.
- S1 availability is 28 vehicles, not 30. Dry trucks available: 22, not 23.
- Puttalam (173 min) and Badulla (186 min) both fit a one-stop Fresh trip inside 270. A second trip depends on the minutes left, not on the district name.
- `traffic_speed.csv` is `district, hour, monsoon, speed_index` (576 rows = 12 × 24 × 2). `road_conditions.csv` is `district, date, disruption_index` (10,920 rows = 12 × 910 calendar days).
- Calendar runs 2024-01-01 through 2026-06-28. Festival tokens: `new_year`, `thai_pongal`, `vesak`, `poson`, `esala`, `deepavali`, `christmas`.
- Training status: 90,351 `attempted`, 1,543 `deferred`, 413 `not_run`. Only `not_run` lacks a route leg. Route legs: 91,894, matching attempted + deferred.

Still open in A1: the join audit, the actual-versus-allowance service-time profile, and a district sheet of max orders by dock type. Those are notebooks, not more prose.

---

## A2 — Domain model

Detail: `ACTIVITY_A2_DETAILED.md`. The model has to be able to express the rules above without a special case in the UI.

Entities: Outlet, Vehicle, Order, Trip, RouteLeg, and a deferral reason on the order. A trip has one depot, one brand, one district, one vehicle, and `trip_number` 1 or 2. Two trips on the same vehicle may differ in brand and district. Capacity sums are per trip. Fresh minutes and Style/Tech minutes are per vehicle-day.

Route legs in the training file use `arrival_time` for the actual arrival and `leave_outlet_time` for the departure from the door. The application's own column may be called `actual_arrival_time` if the schema says so, and the import maps `arrival_time` onto it. `seq` starts at 0.

State of an order: placed, in the closed book, allocated, deferred, loaded, in transit, delivered, receipt confirmed. A deferred order returns to the next book's queue with the previous reason still visible. A trip moves planned → loading → dispatched → completed. Skipping "loaded" on the way to "delivered" is an illegal transition.

Database-shaped rules: same depot, reefer for chilled, van for `van_only`, one brand and one district on the trip, both caps. Application-shaped rules: the two time pools, the two-trip limit, mall and outlet windows, week-to-date fuel, the reverse load list. Offline mutations are an append-only log on the device, replayed in order, with the device clock as the event time.

Prediction tables are nullable. A trip with no `pred_service_min` and no `pred_late_prob` is still a valid trip.

---

## A3 — Experience design

Due Tuesday 29 September. One product, four workplaces.

| Person | Where they are | What the product must let them do |
|---|---|---|
| Dispatcher | Peliyagoda office, large screen, stable link | Close the book, assign or defer, see why a drop is illegal, see progress after departure, see who was skipped before |
| Loader | Peliyagoda or Kandy dock, shared tablet, also phone width | Load in reverse route order, flag a short or damaged item before the vehicle goes |
| Driver | Personal phone, often moving, hill-country gaps | Read the next stop offline, record the stop, capture proof, sync later |
| Store manager | Outlet counter, desktop or phone | Place the order before 16:00, see an ETA, receive a deferral with a reason, confirm receipt, report an issue |

Design the screens that complete that work, each with a one-paragraph rationale. Restraint is part of the grade: a tight flow beats a screen for every idea. We are not setting a numeric screen cap. We are refusing any screen that does not change a decision.

Degradation to design in full, named, for example **Reefer shortfall on a festival week**. The dispatcher sees which chilled orders, including `van_only` chilled, cannot be covered, who was already skipped, and the reason that will be stored. The store sees that reason. The loader's list loses the deferred lines. A second degradation is worth adding only if it is finished: the driver offline, or a plan edit while the loader is mid-way through the truck.

Deliverables: personas, flows, the degradation page, prototype link, 3–5 minute unlisted video, AI disclosure, optional tradeoff page, optional style guide. Zip as `TeamName_Designathon.zip`. Form: https://forms.gle/H6dqUZP6pXdGC8Go8. After this deadline the build may depart from the file only with a README note.

---

## A4 — Peak-day allocation (S1)

Produce `submission_task2b.csv` and a policy of about a page. There is no single correct file. There is a legal file and a reasoned one.

Order of work:

1. Start from the locked fleet. Do not rediscover it.
2. Defer S1-078 immediately. Reason: volume above every vehicle, order cannot be split.
3. Group the other 84 by `(brand, district)`.
4. For each group, the eligible vehicles are available, same depot, reefer if any order is chilled, van if any outlet is `van_only`.
5. Pack whole orders into trips under both caps.
6. Place trips onto vehicles. At most two trips per vehicle. Add Fresh minutes into the 270 pool and Style/Tech minutes into the 480 pool. A Fresh trip and a Style or Tech trip may share a vehicle.
7. Use the published trip-time formula, including the order-count inter-stop. Do not add the return.
8. Treat outlet windows, mall windows, and `deferred_yesterday` as policy. Prefer a plan that honors them. If a legal plan breaks a window, say so.
9. Write `served` or `deferred`. Blank vehicle and trip on deferred rows. Keep row identity and order.
10. Run `check_allocation.py`. Read the policy against the file so the prose and the CSV describe the same deferrals.

The policy answers four questions. What ran out (reefer volume, the single reefer van, the 270-minute sum on the long districts). Which deferrals were illegal. Which were preferred because the outlet was already skipped or the freight was chilled. What that preference cost. S1-083 (Puttalam, chilled, skipped yesterday, five days) is discussed by name. It is legal on size. It is not automatically deferred and not automatically served.

Fuel: say that the scenario has no week-to-date consumption, so the quota was not used as a deferral reason.

---

## A5 — Service time and lateness

Test file: 5,014 rows in `task1_test_inputs.csv`, all dispatched (`attempted` or `deferred`). Preserve `delivery_id` order in `submission_task1.csv`. Fill `pred_service_min` and `pred_late_prob` in `[0, 1]`.

Training label:

```
effective_start = max(arrival_time, window_open_time)
service_min     = leave_outlet_time - effective_start
is_late         = arrival_time > window_close_time
```

Join `route_id` + `seq_in_route` to `route_id` + `seq`. Windows are on the order. Exclude the 413 `not_run` rows. Keep deferred rows; they have legs. Keep late rows; they were still delivered. Handle clock wrap at midnight. `service_allowance_min` is the planner's budget, not the label. A model that cannot beat the allowance as a baseline is not finished.

Features are our choice. Useful families: brand, dock, order size, temperature, where the stop sits in the route, window slack (`window_close_time − planned_arrival_time`), district, monsoon, `speed_index`, `disruption_index`, and historical travel ratio. At prediction time the test leg has planned times only. Actuals exist only in training.

Train in code from scratch. Gradient boosting is a sound default for the minutes. A calibrated classifier is a sound default for the probability. Report the label reasoning in the notebook in words, not only in code.

---

## A6 — Weekly volume forecast

`task2a_test_inputs.csv` has 60 rows: depot, brand, `iso_year`, `iso_week`. Fill `pred_total_volume_m3` and `pred_chilled_volume_m3` on the matching `row_id`. Style and Tech chilled predictions are 0.

Build the history from `deliveries_train.csv` and from `task1_test_inputs.csv`. Count each order once, including deferred and never dispatched. Week is the ISO week of `order_date` via `calendar.csv`, not a week computed from scratch and not `dispatch_date`. Six series (2 depots × 3 brands) is the right grain. Chilled, for Fresh, can be a ratio of that series. Time-based validation only. Do not turn the forecast into a vehicle or driver requirement; the booklet asks for volume.

---

## A7 — Architecture

Starts after Day 5, because the design file is the spec.

Recommended shape, not a requirement of the brief: a web client that is usable at phone width, a small API, a relational database that can express the trip constraints, and a device store plus a mutation log for the driver. `docker compose up` is a requirement. The database engine is a choice.

The allocation service accepts the closed book and the available fleet and returns trips plus deferred orders with reason codes. Reason codes we will actually use: `VOLUME_OVER_ANY_VEHICLE`, `NO_REEFER_CAPACITY`, `NO_REEFER_VAN`, `NO_VAN`, `TIME_BUDGET`, `WINDOW`, `DEPOT`, `CHOICE`. The first five can make a row illegal. `WINDOW` and `CHOICE` are policy. The service checks the checker rules and the window rules. Fuel is a warning from week-to-date distance, not a silent rejection, until we have a trustworthy consumed-liters figure.

Offline: the driver stores the route at dispatch, appends stop events locally, and replays them when the link returns. The screen says that it is offline and how many events are waiting. Last-write-wins is acceptable for a single driver's own stop. It is not acceptable for the dispatcher and the driver editing the same trip; the dispatch edit has to be an explicit new version.

Docs to produce with the repo: architecture diagram, data model, AI disclosure, in `docs/`.

---

## A8 — Build the cycle

Must work, on a fresh seed, for a judge who has not seen us:

- Store manager places an order; 16:00 cutoff holds.
- Dispatcher sees one queue and an allocation that respects the rules in Phase 0, and can override a legal plan.
- Illegal drops are refused with the specific rule.
- Loader sees the reverse sequence at phone width and can flag a shortfall.
- Driver completes stops offline and syncs.
- Store manager sees a deferral reason, confirms receipt, and reports an issue.
- Dispatcher sees stops complete after the vehicle has gone.
- Seed includes the shared outlets, vehicles, calendar, and one real day (S1 is the obvious day).
- Four accounts, one per role.

Build after that, if the design included them: forecast page, predicted service and lateness on the route, fuel warning, audit of deferral reasons. None of these may be required for the cycle to finish.

Degradation that the running system owes, matching A3: reefer shortfall with a stored reason, offline driver with recovery, and a plan change that reaches the loader.

---

## A11 — Release

- `docker compose up` starts the stack, database, and seed. `.env.example` is at the repo root.
- README: setup, four accounts, numbered walkthrough across all four roles, departures from the Day 5 design.
- Public URL. Deployment stays up through review and any later round.
- Monorepo name `TeamName_SolutionName`.
- `docs/` has architecture, data model, AI disclosure.
- Phone-width pass on the driver and the loader.
- Unlisted video, 5–8 minutes: the walkthrough, then the code.
- No pushes after Sunday 4 October, 11:59 PM Sri Lanka time.
- Form: https://forms.gle/WurHAKjbq2XEZQhbA

---

## A9 and A10 — Models

After the labels and the forecast table are trustworthy. Save the final models. The final notebook's last cell loads them and prints inputs and predictions for Task 1 and Task 2A. Keep the other notebooks; the kick-off asks for the experiments as well as the final file. No pre-trained shortcut, no modelling API, no auto-ML product.

---

## A12 — Datathon package

One zip, `TeamName_Datathon.zip`, Friday 9 October, 11:59 PM. Form: https://forms.gle/CcPPmttWdQgHvUdi6. The latest upload is the one that counts.

- `submission_task1.csv`, `submission_task2a.csv`, `submission_task2b.csv`
- The policy
- `check_allocation.py` already passed on that CSV
- `TeamName_FinalNotebook.ipynb` executed, plus the other notebooks
- Model files (`.h5` or `.pkl`)
- Architecture diagrams and the preprocessing note, including the label argument
- Unlisted video, 3–5 minutes: architecture, preprocessing, labels, what went wrong
- AI disclosure

---

## Acceptance criteria

These are the published weights. They are how the releases will be judged, so they are also the checklist. They are not a reason to add screens or features the operation does not need.

**Designathon.** Problem framing 25%. User context 20%. Degradation 15%. Scope and restraint 15%. Visual and interaction design, including one system across roles, 15%. Domain accuracy 10%.

**Hackathon.** Engineering quality and architecture 25%. Functional completeness across four roles 20%. Allocation 20%. Degradation, offline, and recovery 10%. Fidelity to the Day 5 design 10%. Demo video 10%. Creativity 5%.

**Datathon.** Model and architecture 25%. Data work and label construction 20%. Performance on Task 1 and Task 2A 20%. Task 2B feasibility and policy 15%. Creativity 10%. Demo video 10%.

---

## Risks

| Risk | What we do |
|---|---|
| Two documents state two different fleets | Phase 0 is the fleet. A1's older "30 vehicles / 11 reefer trucks" lines are corrected there and ignored if any remain |
| A district is treated as undeliverable | Compute `trip_minutes`. Puttalam and Badulla fit a short trip |
| A margin under the stated cap is called "impossible" | Only S1-078 and whatever cannot fit a legal trip are impossible. A margin is a choice |
| Return time is added on top of the 270 | The formula does not include it. The checker does not either |
| One van pattern is treated as the only legal one | Several are legal, including putting ambient freight on VEH036 |
| Task 2A trained only on `deliveries_train.csv` | Add `task1_test_inputs.csv` |
| Label uses planned arrival, or a column named `actual_arrival_time` | Use `arrival_time`, and start the clock at the later of arrival and window open |
| The product cannot finish a delivery without the model | The cycle is seeded and runnable first |
| Design sprawl | Every screen earns its paragraph or it is cut |
| App built before Day 5 | The spec would move underneath the code. Design first |
| Low-code or a hosted model API in the Datathon | Not allowed. Train in the notebook |

---

## Timeline

```
Day 2  Sat 26 Sep   Plan set corrected. A3 structure. A4 worksheet started. A5 label notes
Day 3  Sun 27 Sep   A3 high-fidelity flows. A4 first legal allocation
Day 4  Mon 28 Sep   A3 video, disclosure, zip. A5 joins
Day 5  Tue 29 Sep   Designathon deadline 11:59 PM
Day 6–9            A7 then A8. A5 and A6 continue beside the build
Day 10 Sun 4 Oct   Hackathon deadline 11:59 PM. Deployment stays up
Day 11–14          A9, A10, policy tightened, videos
Day 15 Fri 9 Oct   Datathon deadline 11:59 PM
```

Next work is A3 and A4 on paper. Not the repository scaffold.
