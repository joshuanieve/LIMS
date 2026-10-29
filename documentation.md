# LIMS — Project Documentation

Living documentation for the Laboratory Information and Management System project proposal.
**Update this file whenever code changes** (new module, endpoint, schema field, or route) — see the
"Documentation Rule" in [`AGENTS.md`](AGENTS.md).

---

## 1. Overview

A LIMS (Laboratory Information and Management System) split into two applications in one repo:

- **`LIMS/`** — Laravel web app. Renders the UI (Blade + Tailwind CSS v4 + vanilla JS) and acts as a
  backend-for-frontend: it receives form submissions/browser requests and forwards the actual work to
  the C# API.
- **`LIMS_API/`** — ASP.NET Core Web API (C#) + Entity Framework Core + SQL Server. Owns all lab data
  and business rules (creating requests, validating samples, listing pending jobs, analysis catalog).

```
Browser ── Blade/JS ──> Laravel (LIMS) ── HTTP (Http:: facade) ──> ASP.NET Core API (LIMS_API) ── EF Core ──> SQL Server
```

## 2. System Workflow & Roles (Business Process)

This is the intended end-to-end business process the system is being built around (source: workflow
diagram provided 2026-10-01). It drives how modules, data model, and future roles should be designed —
treat it as the spec for anything not yet built.

### 2.1 The 8 steps

| # | Step | Actor | What happens | System output |
|---|---|---|---|---|
| 1 | Submit Request for Analysis (RFA) | **Requesting User (Client)** | Fills out RFA form, provides required info, selects lab category/analyses (Chemical and/or Microbiological), submits | **Reference Number** — unique ID for the whole request |
| 2 | Review and Assign to Laboratory | **Administrative Personnel** | Reviews/validates the submitted RFA, assigns it to the appropriate laboratory (Chemical or Microbiological), notifies that section | **RFA Assigned** — request forwarded to the selected laboratory |
| 3 | Assign Laboratory Numbers per Sample | **System (automatic)** | A Laboratory Number is auto-generated for each sample under the RFA. One reference number may have multiple laboratory numbers | **Laboratory Number(s)** — unique ID per sample |
| 4 | Assign Laboratory Parameters to Analysts | **Section Chief / Supervisor** | Views the assigned RFA/samples, breaks each sample into its required parameters (analyses), assigns each parameter to an analyst | **Assigned Parameters** — each parameter gets its own code, linked to the laboratory number, assigned to an analyst |
| 5 | Accept Assignment and Conduct Analysis | **Analyst** | Views assigned parameters, accepts them, conducts the analysis, encodes supporting info if applicable | **Accepted Analysis** |
| 6 | Encode Results | **Analyst** | Encodes test results per assigned parameter, uploads supporting documents if applicable, sets status to Completed | **Encoded Results** |
| 7 | Review and Approve Results (per parameter) | **Section Chief / Supervisor** | Reviews encoded results per parameter, checks for errors/inconsistencies, approves for finalization | **Approved Results (per Parameter)** |
| 8 | Review and Release Test Report | **Division Chief** | Reviews all results under the reference number, confirms every sample is completed/approved, approves the final report | **Final Test Report** — released to the client |

### 2.2 Numbering hierarchy

```
Reference Number (RFA)         e.g. RFA-2026-00123   — one per client request, may contain many samples
└── Laboratory Number (Sample) e.g. LAB-2026-0001     — one per sample, auto-generated under the RFA
    └── Parameter Code         e.g. CH-001, MB-001    — one per analysis required on that sample,
                                                         prefixed by category (CH = Chemical, MB = Microbiological)
```

### 2.3 Status flow of a laboratory parameter (per analysis)

```
Assigned → Accepted → Ongoing → Completed → For Review → Approved
```
- **Assigned** — Section Chief assigns the parameter to an analyst.
- **Accepted** — analyst accepts the assigned parameter.
- **Ongoing** — analysis in progress.
- **Completed** — analyst has encoded the test result.
- **For Review** — Section Chief is reviewing the encoded result.
- **Approved** — result finalized/approved by the Section Chief.

This status lives at the **parameter** level (not the request or sample level) — each analysis on each
sample tracks its own status independently.

### 2.4 Roles & responsibilities

As of 2026-10-15 the 5 roles have concrete `role_id` values and a dashboard each (see §6, §7) — the
names below are what's actually used in code (`LoginController::$limsRoles`,
`DashboardController::ROLE_*`), mapped onto the original business role from the workflow diagram.

| `role_id` | Code name | Business role | Responsibilities |
|---|---|---|---|
| 1 | **Customer** | Requesting User (Client) | Submit RFA, provide required information, track request status, receive final test report and notifications |
| 2 | **AdminOfficer** | Administrative Personnel | Review submitted RFA, assign to the appropriate laboratory (Chemical/Microbial), ensure completeness of information, notify the concerned laboratory/section |
| 3 | **Analyst** | Analyst | Accept assigned parameters, conduct laboratory analysis, encode test results, upload supporting documents (if applicable) |
| 4 | **Section Chief** | Section Chief / Supervisor | Assign laboratory parameters to analysts, review encoded results, approve results, monitor workload and turnaround time |
| 5 | **Director Chief** | Division Chief | Review all results under a reference number, approve the final test report, authorize release to the client |

**This `role_id` numbering is a local placeholder** (`LoginController`'s `$limsRoles` comment flags
it) — it has not been confirmed against the external FFAST system's actual `Role_id` values. Update
both `LoginController::$limsRoles` and `DashboardController::ROLE_*` together if that changes.

### 2.5 Workflow vs. current implementation — gap map

The code today only implements step 1 end-to-end and a read-only view of steps 2–3; steps 4–8 are
**not yet built** (no per-parameter table/entity, no routing/encoding persistence — see §8, §9). The
5-role model now has a dashboard each (§6), but role-based **route/sidebar access control** is still
not implemented — see §9.

## 3. Tech Stack

| Layer | Technology |
|---|---|
| Frontend UI | Blade templates, Tailwind CSS v4, Vite, vanilla JS |
| Frontend libs | SweetAlert2 (alerts), DataTables (`datatables.net-dt`), Vanta/Three.js (background fx) |
| Frontend BFF | Laravel (PHP), `Http::` facade to call `LIMS_API` |
| Backend API | ASP.NET Core Web API (C#), Controller → Service → Repository pattern |
| Data access | Entity Framework Core, SQL Server |
| Auth | External SSO-style login (see §7) — JWT issued by an external `UserCont` API, decoded in Laravel, stored in session |

## 4. Repo Layout

```
LIMS-LaboratoryInformationAndManagementSystem/
├── AGENTS.md                  # AI agent instructions (conventions, do's/don'ts)
├── documentation.md           # this file
├── LIMS/                      # Laravel app
│   ├── app/Http/Controllers/  # thin controllers
│   ├── app/Services/          # HTTP clients to LIMS_API
│   ├── resources/js/{pages,components,services}
│   ├── resources/css/{pages,components}
│   ├── resources/views/<Feature>/partials/
│   └── routes/web.php
└── LIMS_API/LIMS_API/         # ASP.NET Core API
    ├── Controllers/
    ├── Services/<Feature>/
    ├── Repositories/<Feature>/
    ├── Entities/<Feature>/    # request/response DTOs
    └── Models/                # EF Core entities + LimsContext
```

## 5. Running Locally

**LIMS_API** (C#):
```
cd LIMS_API
dotnet run --project LIMS_API
```
Swagger UI available in Development at `/swagger`. Default dev port per current controllers: `5198`.

**LIMS** (Laravel):
```
cd LIMS
composer install
npm install
cp .env.example .env   # then set LIMS_API_URL=http://localhost:5198
php artisan key:generate
npm run dev             # Vite dev server
php artisan serve
```
`LIMS_API_URL` (env) → `config('services.lims_api.url')` → used by `App\Services\RequestFormServices`.

## 6. Modules (current) — mapped to workflow steps

| Module | Workflow step(s) covered | Laravel route(s) | Laravel views/JS | `LIMS_API` endpoint(s) used |
|---|---|---|---|---|
| Login / Auth | (cross-cutting) | `POST /ffast`, `POST /logout`, `GET /` | `welcome.blade.php` | External `UserCont`/employee APIs (not `LIMS_API`) |
| Dashboard | (cross-cutting) | `GET /dashboard` | `dashboard/customer.blade.php`, `dashboard/adminofficer.blade.php`, `dashboard/analyst.blade.php`, `dashboard/sectionchief.blade.php`, `dashboard/directorchief.blade.php` — one per role (§2.4), branched in `DashboardController::index()` on `role_id` | none yet — stats are hard-coded placeholders in `DashboardController` |
| Request for Analysis | **Step 1** (Submit RFA) | `GET/POST /requestform` | `RequestforAnalysis/requestform.blade.php` + `partials/tab1..tab7` | `GET /api/AnalysisList`, `POST /api/RequestForm` |
| Analysis catalog list | supports Step 1 | `GET /analysislist/data` | used by `RequestforAnalysis` tabs | `GET /api/AnalysisList` |
| Request Sample | **Steps 2–3** (review pending RFAs, read-only sample list; `LaboratoryNumber` field exists on `LabRequestList` but auto-generation isn't implemented) | `GET /requestsample`, `GET /requestsample/{id}`, `GET /requestsample/view/{id}` | `RequestSample/requestsample.blade.php` | `GET /api/RequestPending`, `GET /api/RequestPending/{id}` |
| Job Routing | **Step 4** target (assign parameters to analysts) — table + "Job Routing Slip" modal list the checked analytes per sample with an analyst dropdown; dropdown has no data source and nothing persists yet | `GET /jobrouting`, `GET /jobrouting/data` | `JobRouting/index.blade.php` + `partials/modal1-route.blade.php` | `Http::get('http://localhost:5198/api/JobRouting')` — **note: this endpoint doesn't exist in `LIMS_API`** (no `JobRoutingController` there), so `data()` currently fails; see §9 |
| Result for Encoding | **Steps 5–6** target (analyst accepts parameters, encodes results) — "Encode Test Results" modal fully wired to real per-sample `labWorks` data (`LabRequestLabWork` in `LIMS_API`); admin-only Reassign action gated via `data-role` | `GET /resultforencoding`, `GET /resultforencoding/data` | `ResultforEncoding/index.blade.php` + `partials/modal1-results.blade.php` | same broken `http://localhost:5198/api/JobRouting` call as Job Routing for the list — copy-pasted, see §9; per-request `labWorks` come nested in that same (broken) response, not a separate endpoint |
| Laboratory Records | Cross-cutting records/audit log — one row per RFA aggregating all its samples (RFA No., customer, sample type, lab number range, received/released dates, turnaround, status). **Entirely temporary placeholder data** (15 hard-coded rows in `LaboratoryRecordsController`) — no `LIMS_API` call, no real pagination (the "Showing 1–15 of 412" count and Previous/Next buttons are static/stubbed) | `GET /laboratory-records` | `LaboratoryRecords/index.blade.php` | none — see above |
| *(not started)* | **Steps 7–8** (approve results per parameter, release final report) | — | — | — |

## 7. Auth Flow (as implemented today)

Login does **not** go through `LIMS_API`. `LoginController::login()`:
1. Posts credentials to an external `UserCont` login API (hard-coded IP `10.0.224.35:7152`).
2. Decodes the returned JWT (HS256, hard-coded shared secret in `LoginController`) to get `Emp_No`,
   `email`, `Role_id`.
3. Fetches employee profile + employee list + appointment/division info from another external API
   (`10.0.224.35:7154`).
4. Stores everything in the Laravel session (`logged_in`, `api_token`, `role_id`, `lims_role`,
   `fname`/`lname`/`mname`, `division_id`, etc.) — no local `users` table.
5. `role_id` (1–5, see §2.4) drives which of the 5 dashboard views `DashboardController::index()` shows.
   `lims_role` (session string) is a separate, display/CSS-gating value from the same `$limsRoles` map
   in `LoginController` — kept as `'admin'` for `role_id` 2 specifically (not `'adminofficer'`) so the
   existing `data-role === 'admin'` check in `resultforencoding.js` (admin-only Reassign action) keeps
   working unchanged.

**Gap:** `role_id` now maps to 5 dashboards (§2.4, §6), but there is still no **route or sidebar**
access control per role — `sidebarroles.blade.php` shows the same full nav to every role, and nothing
stops e.g. an Analyst from visiting `/requestform` or `/reports` directly. `AdminMiddleware`/
`UserMiddleware` exist but aren't registered in `bootstrap/app.php` and only know about 2 roles anyway —
would need replacing with a role-aware middleware covering all 5 before steps 2–8 can have real
per-role access control.

## 8. Data Model (`LIMS_API` / SQL Server, via `LimsContext`)

| Table | Entity | Key fields |
|---|---|---|
| `Lab_AnalysisList` | `LabAnalysisList` | `AnalysisID` (PK), `Analyte`, `Category`, `Method`, `Type` |
| `Lab_Request` | `LabRequest` | `RequestID` (PK), `CustomerName`, `EmailAddress`, `DivisionSection`, `LabAnalysis`, `TargetDate`, `SampleType`, `SampleRetrieval`, `SubInfo1-4`, `Instruction`, `Status`, timestamps |
| `Lab_RequestList` | `LabRequestList` | `TestID` (PK), `RequestID` (FK), `LaboratoryNumber`, `Sample`, `CustomerSampleCode`, `DateTimeCollected`, `PlaceCollected`, `Analysis`, `Status`, timestamps |

One `LabRequest` (the submitted RFA — "Reference Number" in §2.2) has many `LabRequestList` rows (one per
sample — "Laboratory Number" in §2.2).

**Gap vs. the intended workflow:** there is no table yet for individual **parameters** (one row per
analysis per sample, with its own code, assigned analyst, and the 6-stage status in §2.3). Today
`LabRequestList.Analysis` just stores the selected analyses as a single string per sample, and
`LabRequestList.LaboratoryNumber`/`Status` are plain fields with no generation logic or defined status
enum. Steps 4–8 will likely need a new `Lab_RequestParameter` (or similar) table: `ParameterId` (PK),
`TestId` (FK → `Lab_RequestList`), `ParameterCode`, `AnalysisId` (FK → `Lab_AnalysisList`), `AssignedTo`
(analyst), `Status` (enum matching §2.3), timestamps.

## 9. Known Issues / Tech Debt

Track these here until fixed; remove the row once resolved.

- **Security: plaintext DB credentials committed** in `LIMS_API/LIMS_API/Models/LimsContext.cs`
  (`OnConfiguring` hard-codes the SQL Server connection string incl. `sa` password). Should be moved to
  `appsettings.json`/user-secrets/environment and rotated.
- **Security: hard-coded JWT secret** in `LoginController::login()`. Should move to config/env.
- **Inconsistent API base URL usage**: `RequestFormServices` correctly uses
  `config('services.lims_api.url')`, but `AnalysisController` and `RequestSampleController` hard-code
  `http://localhost:5198` directly in the controller. Should be refactored to use the same config +
  dedicated Service class per the convention in `AGENTS.md`.
- **`Dashboard` stats are hard-coded** placeholder arrays in `DashboardController`, not sourced from
  `LIMS_API`. Each role's numbers (`$overdueRequests`, `$categoryBreakdown`, etc.) were also hand-kept
  in sync with `ReportsController`'s - if either changes, check the other.
- **`JobRoutingController::data()` and `ResultforEncodingController::data()` both call
  `Http::get('http://localhost:5198/api/JobRouting')`** — hard-coded (not via `config()`, unlike
  `RequestFormServices`) instead of going through a dedicated `App\Services\*Services` class per
  `AGENTS.md`. (Previously this note also said the endpoint didn't exist in `LIMS_API` at all — stale:
  `LIMS_API/LIMS_API/Controllers/JobRoutingController.cs` exists with a working
  `JobRoutingService`/`JobRoutingRepo`, confirmed 2026-10-27 while adding `AnalysisOthers` to its
  response shape.)
- **Role-based access control still missing** (dashboards are done, gating isn't): `role_id` now drives
  5 distinct dashboards (§2.4, §6), but every other route/page is still open to all 5 roles —
  `sidebarroles.blade.php` has no role conditionals, and `AdminMiddleware`/`UserMiddleware` are
  unregistered dead code that only understand 2 roles anyway. The `role_id` numbering itself is also an
  unconfirmed local placeholder (see §2.4) — needs validating against FFAST's real `Role_id` values.
- **No per-parameter data model yet** (steps 4–8 of the workflow): no table/entity for an individual
  analysis-on-a-sample with its own code, assigned analyst, and status (Assigned → Accepted → Ongoing →
  Completed → For Review → Approved). See §8.
- **No auto-numbering yet** for Reference Number (`RFA-YYYY-NNNNN`) or Laboratory Number
  (`LAB-YYYY-NNNN`) in the formats shown in §2.2 — `RequestId`/`TestId` are currently plain
  auto-increment ints, and `LabRequestList.LaboratoryNumber` has no generation logic.
- **`Lab_AnalysisList.Type` naming doesn't match the UI labels**: the table/API returns `"Chemical
  Analysis"` and `"Biological Analysis"`, while tab 2's `LabAna` radios (and the workflow diagram) use
  `"Chemical"` / `"Microbiological"`. Worked around in JS via a keyword map (`LAB_TYPE_KEYWORDS` in
  `requestform.js`) rather than an exact match — consider renaming the DB values to
  `"Microbiological Analysis"` (and/or aligning on one vocabulary) instead of keeping two naming schemes
  reconciled only in the frontend.

## 10. Changelog

- **2026-10-29** — Replaced the global loading overlay with an animated DNA-helix loader. New files:
  `resources/views/partials/lims-loader.blade.php` (markup, included once in `layouts/app.blade.php`
  right before `</body>`) and `resources/css/lims-loader.css` (added to that layout's `@vite([...])`
  list). Removed the old `#loadingOverlay`/`.loader` markup from `app.blade.php` and the whole `@layer
  Loader { ... }` block (old spinner + `.loading-overlay` full-screen styles) from `app.css` — nothing
  else referenced those selectors. `window.showLoading`/`window.hideLoading` in `app.js` were rewritten
  to target the new `#limsLoader`/`#limsLoaderText` element IDs; the public API is unchanged (still
  `showLoading([message])` / `hideLoading()`, overlay `z-index: 1050` stays below SweetAlert2's 1060) so
  no page calling them needed to change — except that the three existing call sites now pass a
  message appropriate to what they're waiting on: `requestform.js`'s `CreateRequest()` →
  `'Submitting your request…'`, `requestsample.js`'s Accept handler → `'Accepting request…'`,
  `results.js`'s Laboratory Records table load → `'Loading laboratory records…'`.

- **2026-10-29** — Gave the DNA loader a fade in/out, same remove-class/reflow/re-add re-trigger
  pattern as `OpenModal`/`CloseModal` (`app.js`). Added `.lims-loader-fade-in`/`.lims-loader-fade-out`
  keyframes (opacity only — unlike modals' `.lims-fade-in`/`.lims-fade-out`, no `translateY`, since this
  is a full-screen overlay, not a card sliding in) plus a `prefers-reduced-motion` override to
  `lims-loader.css`. `showLoading()` now re-triggers `.lims-loader-fade-in` on every call;
  `hideLoading()` swaps to `.lims-loader-fade-out` and waits `LOADER_FADE_OUT_MS` (200ms, matching the
  CSS animation-duration) before clearing `.is-visible`, so `display:none` doesn't cut the animation
  off instantly.

- **2026-10-29** — Result for Encoding, Job Routing, and Request Sample's table-load fetches
  (`resultforencoding.js`, `jobrouting.js`, `requestsample.js` — each on `DOMContentLoaded`) had no
  loader at all, unlike `results.js`'s Laboratory Records load. Added `showLoading(message)` before each
  fetch and a `finally { hideLoading(); }` (new, these blocks only had `try`/`catch` before) so the
  loader clears whether the load succeeds or fails: `'Loading results for encoding…'`,
  `'Loading samples to route…'`, `'Loading sample requests…'` respectively.
  off instantly.

- **2026-10-28** — Laboratory Records' "N samples · N analyses" count had the same
  `AnalysisOthers`-dropped-by-the-backend gap as Job Routing (see 2026-10-27 entry below): `GetResultsTable`
  (`LIMS_API/LIMS_API/Entities/Results/GetResultsTable.cs`, the `GetRFEListDetails` nested class) and
  `ResultsRepo.cs`'s `.Select(...)` projection never read `LabRequestList.AnalysisOthers`, so a sample with
  e.g. 30 catalog analyses + 1 free-text "other" analysis showed `30 analyses` instead of `31`. Added
  `AnalysisOthers` to both. Frontend fix in `results.js`'s `formatSampleMeta()`: the analysis count now
  adds the comma-split length of `s.analysisOthers` on top of the existing `s.analysis` ID count.
  **LIMS_API needs a restart/rebuild for this to take effect**, same caveat as the Job Routing fix.

- **2026-10-27** — Job Routing now surfaces "Others" (the free-text custom analyses typed in Request for
  Analysis's `AnalysisOthers` field, separate from the checkbox-selected catalog analyses since that
  field split). This required a real backend fix, not just a frontend change: `GetJobRoutingLists`
  (`LIMS_API/LIMS_API/Entities/JobRouting/GetJobRoutingLists.cs`) and `JobRoutingRepo.cs`'s `.Select(...)`
  projection never read `LabRequestList.AnalysisOthers` at all, even though it's already persisted
  correctly end-to-end from the form (verified: `RequestFormController` → `RequestFormRepo` →
  `LabRequestList.AnalysisOthers` column, and already exposed on the `RequestPending` detail endpoint —
  JobRouting's endpoint was the one gap). Added `AnalysisOthers` to both. On the frontend
  (`jobrouting.js`): each sample card now shows a 3rd pill ("N other", new neutral-gray
  `.lims-grid-category-pill.other` style) alongside the existing chemical/microbiological counts when
  `analysisOthers` is non-empty, and the Route modal now lists each custom analysis as its own row
  (labeled "Custom analysis (not in catalog)" in place of a method, with a synthetic `other:<name>`
  select id since there's no real `analysisId` for free text) so Section Chief can still assign an
  analyst to it. Added an `escapeHtml()` helper in `jobrouting.js` for this, since unlike the numeric
  `analysis` IDs used everywhere else in that file, `analysisOthers` is untrusted free text going into
  both an HTML attribute and `innerHTML`.
  **Needs a LIMS_API restart/rebuild to take effect** — the running process was holding the output `.exe`
  locked when this was built, so the change hasn't been picked up by a live `dotnet run`/`dotnet watch`
  session yet.
- **2026-10-26** — `window.swal_confirm` gained a 5th optional `icon` param (default `'question'`) and
  Job Routing's "Cancel Test" prompt (`jobrouting.js`) was switched from an inline `window.Swal.fire(...)`
  call (with `showCancelButton: true`) to use it (passing `'warning'` for the icon, matching what that
  inline call used) — this was the only other Yes/No confirm dialog in the codebase not already going
  through the shared helper; Request Sample's "Accept Request" flow already used it.
- **2026-10-26** — Added a matching fade-out to every modal's close, same 5 modals as the fade-in below.
  New `.lims-fade-out` keyframe in `app.css` (reverse of `.lims-fade-in`, 0.2s). Since closing still has
  to end in `display:none` (the `hidden` class) and that would otherwise cut the animation off instantly,
  every close path now: swaps `lims-fade-in` for `lims-fade-out`, then after a 200ms `setTimeout`
  (matching the CSS duration - `MODAL_FADE_OUT_MS` in `app.js`) removes `flex`/`lims-fade-out` and adds
  `hidden`. Patched in `window.CloseModal` (app.js, covers Job Routing/Result for Encoding) and in each
  one-off close function: `requestform.js`'s `closeAnalysisModal()`/`closeLocationModal()` (the analysis
  modal's inline duplicate close path was also consolidated to call `closeAnalysisModal()` instead of
  repeating the toggle) and `requestsample.js`'s `closeRequestFormModal()`.
- **2026-10-26** — Added a fade-in effect to every modal in the app on open, reusing the existing
  `.lims-fade-in` class (same keyframe already used for DataTable redraws and Logs panel selection) with
  the established remove/reflow/re-add trigger pattern. Patched in `window.OpenModal` (app.js) - covers
  Job Routing's route modal and Result for Encoding's results modal, both of which call it - plus the 3
  modals that toggle `hidden`/`flex` directly instead of going through that shared helper: Request for
  Analysis's analysis and location modals (`requestform.js`) and Request Sample's view modal
  (`requestsample.js`). All 5 modals in the app now fade in consistently.
- **2026-10-26** — Added `window.swal_toast(title, icon?)` to `app.js` (toast: true, position: top-end,
  auto-dismiss) and switched Request Sample's "Accept Request" confirm flow to use it instead of a
  blocking `swal_error` modal — confirming now shows a small "Success" toast in the top-right corner
  instead of a full alert dialog. Still a placeholder: no accept/approve endpoint exists on `LIMS_API`
  for this step yet, so nothing is actually persisted.
- **2026-10-26** — Added `window.swal_confirm(title, message, confirmText?, cancelText?)` to `app.js`
  next to `swal_success`/`swal_error` (resolves to `true`/`false`) — the first Yes/No confirm variant in
  the shared alert helpers, per AGENTS.md's "add new alert variants as a `window.swal_*` helper, don't
  call `Swal.fire` inline" rule. Wired it into Request Sample's new "Accept Request" button: clicking it
  now asks "Accepting this Request will proceed to Section Chief Routing, do you wish to continue?"
  before doing anything.
- **2026-10-26** — Request Sample's view modal footer: added an "Accept Request" button
  (`.lims-form-submit`, same primary-action style used in other modal footers) and floated the existing
  Close button to the opposite side (`mr-auto` on `.lims-modal-cancel`) instead of both buttons grouping
  together via the global `.lims-modal-footer`'s `justify-end` — scoped to this one modal, not a change
  to that shared class.
- **2026-10-26** — Redesigned Director Chief's dashboard hero to match the other 4 roles: welcome card
  (left) + the 4 KPI cards as a compact 2x2 grid (right, `.lims-directorchief-hero-kpis`) sharing one
  row, instead of the KPI row running full-width below the welcome card. Reused the existing
  `.lims-dash-kpi-card` component with a compact modifier (smaller padding/value text, same pattern as
  `.lims-hero-stat-card` elsewhere) rather than introducing a new card style.
- **2026-10-26** — Generalized Section Chief's welcome-card summary badges (`.lims-sc-welcome-badge*` →
  `.lims-welcome-badge*`, no longer role-prefixed) and added the same 2-badge pattern to the Analyst and
  AdminOfficer welcome cards: Analyst shows `$stats['pendingAcceptance']`/`$stats['inProgress']`
  ("N parameters waiting for acceptance" / "N parameters in progress"); AdminOfficer shows
  `count($pendingReview)`/`count($overdueRequests)` ("N requests awaiting review" / "N requests
  overdue"). Also removed Section Chief's bordered label box (`.lims-sc-welcome-label-box`, dropped
  after a closer reference screenshot showed no border there) and enlarged the badge dots from
  `h-1.5 w-1.5` to `h-2.5 w-2.5` to match.
- **2026-10-26** — Redesigned the Section Chief dashboard to match a supplied mockup:
  - **Hero**: welcome card + 2x2 stats share one row (`.lims-sectionchief-hero`, 2 columns this time - no
    3rd slot, unlike AdminOfficer/Analyst). Welcome card gained a bordered label box
    (`.lims-sc-welcome-label-box`) and two small summary pills under the description
    (`.lims-sc-welcome-badge`, blue/amber dot + count, e.g. "5 samples need an analyst") computed
    straight from the existing `$stats` values - no new controller data needed for those.
  - **Analyst Workload** moved up to right after the hero (was previously the last section) and was
    redesigned from a plain label+bar+count list into `.lims-workload-row`: an initials avatar
    (`.lims-workload-avatar`), the analyst's name in secondary blue, a progress bar (green when 100%
    complete, via the same `.complete` modifier pattern as the Reports/other dashboards' progress bars),
    and a stats string that breaks out completed/pending/overdue separately - overdue shown in bold red
    only when present. New controller field: `$analystWorkload[]['overdue']` (pending is now derived as
    `assigned - completed - overdue` in the view, not stored).
  - **Awaiting Parameter Assignment** queue rows now show one category pill per analysis type present
    on the sample instead of a single category (a sample can be both Chemical and Microbiological at
    once) - `$awaitingAssignment` restructured from a single `category` string to a `categories[]` array
    of `{label, count}`, rendered via new `.lims-queue-row-pills` (stacks pills vertically) reusing the
    existing `.lims-grid-category-pill` chemical/microbiological classes.
  - **Awaiting Parameter Assignment** and **For Your Review** moved from two separate full-width queue
    panels into one even 2-column row, same pattern as AdminOfficer/Analyst.
- **2026-10-26** — Redesigned the Analyst and AdminOfficer dashboards' hero sections to match supplied
  mockups, continuing the pattern started on the Customer dashboard (welcome card + stats sharing one
  row instead of stats running full-width below):
  - **AdminOfficer**: hero is now 3 columns (welcome | 2x2 stats | Quick Links), with Quick Links moved
    up from the old bottom Insights row into its own white card (`.lims-admin-hero-quicklinks`,
    `rounded-3xl`/white-80/bordered, matching `.lims-panel`'s look) beside the stats. Added a new
    "Requests per Division" panel (reuses `.lims-dash-bar-list`, same `$divisionBreakdown` data as
    Reports/Director Chief) paired with the existing Category Breakdown panel, and reorganized Awaiting
    Review/Overdue Requests and Recent Requests/Activity into even 2-column rows
    (`.lims-dashboard-insights.lims-dashboard-insights-2col`) instead of one 2fr/1fr row plus a
    standalone full-width queue panel.
  - **Analyst**: hero is now 3 columns (welcome | 2x2 stats | My Turnaround). Replaced the old 2-row
    "My Average vs. Lab Target" bar list with a real per-parameter turnaround widget
    (`.lims-turnaround-*`): a summary block (average days over the last 30, target, on-time ratio) plus
    a scrollable list of the analyst's last 10 completed parameters, each with a green/red dot for
    on-time vs. late against the 8-day target. New controller data: `$turnaroundSummary`,
    `$turnaroundEntries` (replacing `$myTurnaround`/`$maxTurnaroundDays`). My Assigned Parameters and
    Recent Activity moved from a full-width queue panel + 2fr/1fr row into an even 2-column row, same
    pattern as AdminOfficer.
  - New shared CSS: `.lims-hero-stat-card` (renamed from the Customer-only `.lims-customer-stat-card`
    once AdminOfficer and Analyst needed the identical compact stat-card treatment too).
  - **Lesson learned mid-build and worth keeping in mind for any future per-role CSS override**: a
    `.lims-admin-hero-quicklinks .lims-insight-quicklink` override placed in a `@layer` declared
    *earlier* in the file than the layer holding the base `.lims-insight-quicklink` rule is silently
    dead — CSS cascade layers prioritize by declaration order, not specificity, so a later-declared
    layer's rule always wins regardless of how specific an earlier-declared layer's selector is. Had to
    diagnose and fix this once already on this file; new cross-component overrides should double check
    which layer the base rule lives in and declare after it (or go unlayered, which unconditionally
    beats any `@layer`-wrapped rule).
- **2026-10-26** — Redesigned the Customer dashboard's top section to match a supplied mockup: the
  welcome card and the 4 stat cards now share one row (welcome ~62% width, stats as a compact 2x2 grid
  ~38% width) instead of the stat grid running full-width below the welcome card. New CSS is scoped to
  `.lims-customer-hero*` (`dashboard.css`'s `DashboardCustomerHero` layer) so the other 4 role
  dashboards keep their existing full-width 4-across stat grid. Also reordered the stat cards to match
  the mockup (My Total Requests, Completed, In Progress, Total Samples Submitted) and added
  `.lims-table-ref` (bold, secondary-blue) to color the Request No. column in the My Requests table,
  matching the mockup's styling.
- **2026-10-22** — Removed the "reason" block (the italic note under the changes table, e.g. "Value
  below detection limit after re-run.") from the Logs detail panel — dropped `d.reason` rendering in
  `log.js`, the now-unused `.lims-logs-reason` CSS, and the `'reason'` key from all 25 mock entries in
  `LogsController`. (While removing that key with a PowerShell `Set-Content -Encoding utf8` pass, it
  mojibake'd the file's `·`/`–` characters — re-wrote the file via a UTF-8-safe path instead. Lesson:
  don't use PowerShell `Get-Content`/`Set-Content` for in-place edits on files with non-ASCII characters
  in this repo; use Edit/Write instead.)
- **2026-10-22** — Logs page detail panel cleanup: removed the IP address field entirely (from both
  `LogsController`'s 25 mock entries and `log.js`'s rendered field list); merged the separate "Record"
  (lab number) and "Request" (reference number) rows into a single "Record" line — sample-level events
  now show `Ref. #0411 2026-5982`, request-level events (no lab number) show just `Ref. #0412`; and the
  left-hand list now shows each entry's date stacked above its time (`.lims-logs-item-date`/
  `-item-clock` in `log.css`) instead of time only, using a new top-level `date` field added to every
  mock entry (`Oct 13, 2026` for ids 1-6, `Oct 12, 2026` for ids 7-25, matching the only date anchor the
  old data already had). The detail panel's header timestamp is now always derived as
  `${log.date} · ${log.time}` instead of the one-off `detail.timestamp` override entry #3 used to carry
  — removed that now-redundant field.
- **2026-10-16** — Reports page Pipeline Funnel: renumbered the "Step N" hints shown per stage. Request
  Submitted and Lab Number Generated (the two instantaneous, uncounted stages) now show no step number;
  the 5 countable stages are renumbered 1-6 for this funnel specifically (Pending Review=1, Routed to
  Analyst=2, In Progress=3-4, For Final Review=5, Released=6) rather than reusing the business
  workflow's full 1-8 step numbers from §2.1.
- **2026-10-15** — Reference number display format changed system-wide from `RFA-2026-0000` to
  `Ref. #0000` (drops the `RFA-` prefix and the year segment, keeps the existing 4-digit id). Updated
  everywhere a mock reference number string is built: `DashboardController`, `ReportsController`,
  `LogsController`, the dead/commented sample rows in `LaboratoryRecordsController`, and the (also
  currently-unused) `formatReference()` helper duplicated in `jobrouting.js`/`resultforencoding.js`
  (now `` `Ref. #${id.padStart(4,'0')}` ``, no year). Also on the Customer dashboard specifically: removed
  the "Where It Is Right Now" step tracker panel and the "Recent Updates" notifications panel (and their
  now-dead controller data `$trackerSteps`/`$myUpdates` and CSS `.lims-tracker-*`) — the Customer
  dashboard is now just stats + My Requests.
- **2026-10-15** — Replaced the 2-role (`admin`/`user`) dashboard split with one dashboard per each of
  the 5 workflow roles (§2.4): **Customer**, **AdminOfficer**, **Analyst**, **Section Chief**,
  **Director Chief** — `role_id` 1–5 respectively (`DashboardController::ROLE_*` constants,
  `LoginController::$limsRoles`, both flagged as an unconfirmed placeholder numbering pending real
  FFAST `Role_id` values). Deleted `dashboard/admin.blade.php` and `dashboard/user.blade.php`; added
  `dashboard/{customer,adminofficer,analyst,sectionchief,directorchief}.blade.php`. Each shows
  role-appropriate info instead of the old generic/duplicated content:
  - **Customer** — "my requests" stats, a request table scoped to the client, and a new single-request
    step tracker (`.lims-tracker-*`) showing where their latest RFA sits across the 8 workflow steps.
  - **AdminOfficer** — carries over the admin dashboard built earlier today (system-wide stats, Recent
    Requests/Activity, Category Breakdown, Overdue Requests, Quick Links), plus a new "Awaiting Your
    Review" queue (Step 2).
  - **Analyst** — "My Assigned Parameters" queue (Steps 5–6), personal turnaround vs. lab target, own
    recent activity.
  - **Section Chief** — two queues, "Awaiting Parameter Assignment" (Step 4) and "For Your Review"
    (Step 7), plus Analyst Workload (same shape as the Reports page's).
  - **Director Chief** — executive KPI cards (same 4 metrics as the Reports page), a "For Final
    Release" queue (Step 8), Division Breakdown, Category Breakdown.
  New shared CSS in `dashboard.css`: `.lims-queue-*` (generic action-queue row, reused by 4 of the 5
  dashboards instead of one-off components each), `.lims-tracker-*` (Customer's stepper),
  `.lims-dash-kpi-*` and `.lims-dash-bar-*` (duplicates of `report.css`'s KPI-card/bar-list shapes,
  since dashboard pages don't load `report.css`). `lims_role` session value for `role_id` 2 was kept as
  `'admin'` (not `'adminofficer'`) specifically so `resultforencoding.js`'s existing
  `data-role === 'admin'` admin-only gate didn't need touching. See §9 for what's still missing
  (route/sidebar access control per role).
- **2026-10-15** — Redesigned the admin Dashboard (`dashboard/admin.blade.php`). Fixed a real bug first:
  the file had no `@extends('layouts.app')`/`@section('content')`/`@vite(...)` at all, so it was never
  rendering inside the app layout or picking up `dashboard.css` — it only "worked" by accident because
  `app.css`/`datatable.css` are loaded globally. Also wired the view to data `DashboardController` was
  already computing but silently discarding (`$stats`, `$recentRequests` were built then ignored in
  favor of hardcoded markup, including one row duplicated 4 times in the Recent Requests table and the
  same activity line repeated 10 times in Recent Activity). Added `$recentActivity`,
  `$categoryBreakdown`, `$overdueRequests` (admin-only; `$overdueRequests`/`$categoryBreakdown` numbers
  intentionally match `ReportsController`'s so the two pages agree on the same mock system state) and
  replaced the three empty `asdasd` placeholder boxes with a 3-panel **Insights** row: Requests by
  Category (reuses `.lims-grid-category-pill`/`.lims-grid-progress-track/fill` from the global Card Grid
  design), Needs Attention / Overdue Requests (new `.lims-status-overdue` red variant alongside the
  existing pending/processing/completed pill colors), and Quick Links (Boxicons-based shortcuts to
  Request Sample, Job Routing, Result for Encoding, Laboratory Records, Reports). New CSS lives in
  `dashboard.css`'s `DashboardInsights` layer, replacing the old unused `DashboardAddContent` layer.
  `dashboard/user.blade.php` was left untouched — only the admin dashboard was in scope.
- **2026-10-14** — Root-caused why the FlatTable redesign appeared to do nothing even after a hard
  refresh and `php artisan view:clear`: `.lims-flat-table th`/`td` had `text-left !important` **inside**
  an `@apply` directive — Tailwind v4 does not support a trailing `!important` there (it tries to parse
  it as a utility class named `!important` and throws `Cannot apply unknown utility class`), which
  hard-crashed the Vite dev server's compile of the entire `datatable.css` file. Because the dev server
  keeps serving the last successfully-compiled bundle on a build error instead of nothing, the page kept
  showing styling from *before* that edit, with no error visible in the browser — confirmed by fetching
  `http://localhost:5173/resources/css/components/datatable.css` directly, which returned the Vite error
  overlay's JSON payload. Fixed by moving `!important` out of the `@apply` list (there was no actual
  competing rule forcing it in the first place — removed entirely rather than converted to plain CSS).
  **Lesson for future CSS edits in this codebase: `@apply` cannot take a trailing `!important` in
  Tailwind v4; if a declaration needs to win a specificity fight, write it as a separate plain CSS
  property outside the `@apply` list instead (the established pattern already used throughout this
  file).** After any `datatable.css`/`app.css` edit going forward, worth confirming
  `curl http://localhost:5173/<path>` returns 200 before declaring it done, since a build error here is
  otherwise silent to the browser.
- **2026-10-14** — Redesigned `.lims-button-view` (datatable.css) to match the solid-blue, rounded-xl
  button language already used by `.lims-form-cancel`/`.lims-form-submit`/`.lims-add-sample-button`
  (requestform.css), replacing the old sliding-door hover animation (text slides in, icon reveals on
  hover). Scope was intentionally limited to this one class — no JS or blade markup changed, the
  existing `<span class="text">`/`<span class="icon">` structure still works, just renders as a plain
  inline icon+label pill now.
- **2026-10-14** — Added **Design C — List Table** (`@layer ListTable` in `datatable.css`,
  `.lims-list-table`/`.lims-list-table-wrapper` + `.lims-list-name`/`.lims-list-ref`/`.lims-list-meta`/
  `.lims-list-category`/`.lims-list-release`/`.lims-list-submitted`), a third independent table design
  alongside Design A (Card Rows) and Design B (Flat Table) — minimal row dividers, a merged identity
  cell (customer name + `#id` reference badge on one line, sample type as a subtitle), a colored
  category pill (amber=Chemical, blue=Microbiological, via `request.labAnalysis`), and a two-line
  Target Release cell (date, or red "Not yet" if `targetDate` is null) + muted "Submitted" subtitle.
  Its Action column reuses `.lims-button-view` as-is. **Request Sample migrated from Design A to Design
  C** (`RequestSample/index.blade.php`, `requestsample.js` — columns collapsed from 6 to 4: Customer /
  Laboratory Analysis / Target Release / Action). While rebuilding this cell, fixed a pre-existing bug:
  the old "Target Release" column actually rendered `request.dateTime` (the submission date) instead of
  `request.targetDate` — now correctly split into Target Release (`targetDate`) and Submitted
  (`dateTime`). **`.lims-sample-table` (Design A) is marked in `datatable.css` as no longer used by
  Request Sample** (still used by Job Routing and Result for Encoding) so it's clear what's safe to
  delete later and what isn't.
- **2026-10-14** — Redesigned the Request Sample "Request Details" view modal
  (`modal-request.blade.php`/`modal-request.css`), the last page-specific stylesheet that predated the
  site-wide palette overhaul (2026-10-05) and had drifted from it: swapped `var(--primary)`-as-body-text
  for `var(--ink)` (primary is for dark surfaces like the sidebar now, not text) and
  `color-mix(var(--primary) N%)` descriptions for `var(--muted)` throughout; fixed four more instances of
  the `--light-blue` (hyphenated, undefined) typo; removed a dead duplicate `.lims-view-answer.yes/no`
  definition (the file defined it twice - soft pastel pills, then solid `bg-green-500`/`bg-red-500` ring
  pills later in the same file silently winning - deleted the second, kept and cleaned the first,
  switched to `rounded-full` to match the soft-pill convention used everywhere else). Migrated the
  "Samples for Analysis" inner table from Design A to **Design B** (Flat Table — better fit for a dense
  read-only table inside a modal than Card Rows' vertical spacing); added a default `color`/`text-sm` to
  `.lims-flat-table td` in `datatable.css` since this table's rows are built with plain unwrapped text
  (no `.lims-flat-*` typography spans), and scoped a `min-width: 640px` override for this modal's table
  specifically (the shared 960px floor was sized for Laboratory Records' 9 wider columns).
- **2026-10-14** — Fixed the FlatTable typography (§ above): `.lims-flat-id`'s monospace font was never
  actually rendering — `app.css` has `body * { font-family: Poppins !important }`, which silently beats
  any non-`!important` font-family declaration regardless of selector specificity; added `!important` to
  fix it (same class of bug as the `--light-blue` typo fixed earlier, different mechanism). Also: split
  dates (Received/Released) off from `.lims-flat-value` into a new `.lims-flat-date`
  (smaller, italic) so they read distinctly from plain values like Sample Type, which keeps the original
  class; `.lims-flat-dash` (the "–" empty-release state) matches the same smaller/italic treatment since
  it stands in for a date. Turnaround cells now show a small colored status dot next to the note
  (`.lims-flat-turnaround-dot`: green=on time, red=late/warning, amber=pending/in-progress — derived from
  `warning` + `status` in the Blade loop) instead of plain stacked text.
- **2026-10-14** — Laboratory Records: swapped the static Previous/Next pagination for the real
  DataTables.net pagination + search used by every other list page (`new DataTable('#LaboratoryRecordsTable',
  ...)` in `results.js`, same options as Request Sample/Job Routing/Result for Encoding — initializes
  directly against the server-rendered rows, no fetch needed since the data is already in the DOM).
  Removed the custom `.lims-records-pagination` bar from the blade view entirely.
  Also added a genuinely **second, independent table design** to `datatable.css` —
  `@layer FlatTable` (`.lims-flat-table-wrapper`/`.lims-flat-table` + typography classes
  `.lims-flat-id`/`.lims-flat-strong`/`.lims-flat-value`/`.lims-flat-secondary`/`.lims-flat-dash`) —
  explicitly **alongside**, not replacing, `.lims-sample-table`'s Card Rows design from 2026-10-13;
  pages pick whichever fits. This one matches the original Laboratory Records mockup: classic bordered
  grid, left-aligned content, blue uppercase header labels, and a 3-tier font-weight hierarchy (bold
  identifiers/headline values — RFA No., customer name, lab number range, turnaround — medium for plain
  values like sample type/dates, regular+muted for subtitles). `.lims-button-view` was left completely
  unchanged, as asked. `LaboratoryRecords/index.blade.php` switched to the new classes;
  `results.css` now just holds the Tailwind boilerplate with a comment pointing at where the real
  design lives, since there's currently nothing page-specific left to put there.
- **2026-10-14** — Built the Laboratory Records table from a design mockup: one row per RFA
  (aggregating its samples), columns RFA No. / Customer (+ division) / Sample Type / Lab Numbers
  (range + sample/analysis counts) / Received / Released / Turnaround (+ on-time/late/in-progress note)
  / Status / Action. `LaboratoryRecordsController::index()` (previously an empty stub re-using
  `ResultforEncoding`'s old view by copy-paste mistake) now holds **15 hard-coded placeholder records**
  and passes them to a proper `LaboratoryRecords/index.blade.php`. New
  `resources/css/pages/results.css` and `resources/js/pages/results.js`, both linked via the page's
  `@vite`. Reused the shared `.lims-sample-table`/Card-Rows styling and the existing
  `.lims-status-completed`/`-processing`/`-pending` pills (Released/For final review/In progress) and
  `.lims-button-view` rather than inventing new ones — only added CSS for what didn't already exist:
  the two-line cell pattern (`.lims-record-primary`/`.lims-record-secondary`, the latter switching to
  red/bold via a `.warning` modifier for "Late by N days" notes), a left-aligned override for this
  table specifically (the shared table is centered by default), and the static pagination bar
  (`.lims-records-pagination*`). `results.js` only wires the Previous/Next and View button click
  handlers as stubs (`console.log`) — **there is no real pagination or detail view yet**, both need a
  backend endpoint first. No `LIMS_API` call exists for this module at all.
- **2026-10-13** — "Select Analysis" modal (`modal1-analysismodal.blade.php`): genus/species test names
  (Escherichia coli, Salmonella spp., Staphylococcus, Vibrio Cholerae, Vibrio Parahaemolyticus) now
  render in italics, per scientific naming convention — general test names (Coliforms, Total Plate
  Count, etc.) stay upright. Matched via a hardcoded, case-insensitive `$italicAnalytes` list in the
  Blade loop rather than a heuristic, since the list is short and explicit was safer than guessing at a
  "looks like a binomial name" pattern. The analyte list itself is still fully DB-driven
  (`$analysisList` from `GET /api/AnalysisList`) — only the italic flag is a client-side lookup against
  that hardcoded list. **If new species-named tests are added to `Lab_AnalysisList`, add them to
  `$italicAnalytes` too** — there's no automatic detection.
- **2026-10-13** — Fixed the sidebar's scroll behavior. Previously only the brand header + nav were
  wrapped in `.stickysidebar { position: sticky }` — the footer user card sat outside that wrapper in
  normal flow, and `.sidebar` itself was only `min-h-screen` with no height cap or sticky positioning of
  its own, so on any page where the main content was taller than the viewport, scrolling down carried
  the *entire* sidebar (header, nav, and footer alike) out of view with it. Restructured so `.sidebar`
  itself is `position: sticky; top: 0; height: 100vh; overflow: hidden` (pinned to the viewport,
  capped at one screen tall — `app.css`), with the brand header (`.stickysidebar`, now just
  `flex-shrink: 0`) and the user mini-card (`.lims-sidebar-footer`) fixed in place, and
  `<nav class="lims-sidebar-nav" id="sidebar_content2">` as the only child with
  `overflow-y: auto; min-height: 0` — it's now the sole scrolling region, only kicking in if the
  Overview/System link list itself is taller than the space between header and footer.
  `sidebar.blade.php` restructured to match: `@include('layouts.sidebarroles')` moved out from inside
  `.stickysidebar` to be its own direct flex child of `.sidebar`, sitting between the header and the
  footer card.
- **2026-10-13** — Fixed the card-row table redesign: it wasn't actually visible. Root cause —
  DataTables.net-dt's own CSS has `table.dataTable { border-spacing: 0; }`; that selector (class +
  element) beats a plain `.lims-sample-table` class selector on specificity regardless of source order,
  so it silently overrode the `border-spacing: 0 10px` that makes rows look spaced. Added `!important` to
  `.lims-sample-table`'s `border-collapse`/`border-spacing`, its `td` padding, and its `tr` background in
  `datatable.css` — matching the `!important` convention already used everywhere else in that file for
  the same reason (DataTables overrides). Also: the Laboratory No. "badge" shown in the Option A mockup
  was never actually implemented in the row-building JS (only the row card/spacing CSS was ported) —
  added a `.lims-labno-badge` class (blue monospace pill) and wrapped `sample.laboratoryNumber` in it in
  both `jobrouting.js` and `resultforencoding.js` (`requestsample.js`'s outer table has no Lab No. column,
  nothing to change there). Added top/bottom padding to `.lims-sample-table-wrapper` per request.
- **2026-10-13** — Implemented three pieces from the "LIMS Modern Redesign Concept" design canvas
  (https://claude.ai/artifact/LEMKGG7q91V7SBFKk2jDWC) directly into the app:
  - **Tables → Card Rows** (`datatable.css`, shared `.lims-sample-table`): switched from a single
    bordered grid to `border-collapse: separate; border-spacing: 0 10px`, so every row renders as its
    own white card (shadow, hover lift, rounded corners on the first/last cell, blue inset accent on the
    first cell). Kept the real `<table>`/`<tr>`/`<td>` structure — DataTables.net initializes against
    this table on every page that uses it, so switching to div-based rows would have broken
    sorting/paging/search; this is CSS-only, no JS row-building logic touched. Applies automatically to
    **Request Sample, Job Routing, and Result for Encoding** (and anything else that reuses
    `.lims-sample-table`) since the class is global. Did **not** touch `.lims-results-table` (the
    Result-for-Encoding modal's internal parameter/result grid) or `.lims-dashboard-table` — those are
    denser data-entry/widget tables, not what was shown in the Option A mockup, and weren't named in the
    original ask ("request sample, job routing, result for encoding tables").
  - **Sidebar**: added grouped section labels (`.lims-sidebar-section-label` — "Overview" above
    Dashboard, "System" above Logs, in `sidebarroles.blade.php`) and a pinned user mini-card at the
    bottom (`.lims-sidebar-footer`, avatar + name + role, in `sidebar.blade.php`) using `margin-top:
    auto` inside `.sidebar`'s existing flex column. The footer is wired into the existing collapse
    toggle (`sidebar_content3` added alongside `sidebar_content1`/`sidebar_content2` in `app.js`) so it
    hides correctly when the sidebar is minimized. Logo/icon and nav items themselves were left alone —
    only grouping/footer were new in the mockup.
  - **Job Routing "Job Routing Slip" modal**: added the sample-info header (Laboratory No. / Sample,
    `.lims-route-sample-info` in `jobrouting.css`) shown above the parameter rows, matching the
    mockup's "Assign Laboratory Parameters" modal layout. Needed new data on the View button
    (`data-lab-no`, `data-sample-name`, added in `jobrouting.js`'s row template) since the button
    previously only carried `data-request-id`/`data-request-analysis`. Used fresh page-scoped classes
    (`.lims-route-info-*`) rather than the `.lims-view-field`/`.lims-view-label`/`.lims-view-value`
    classes the mockup's visual language was based on, since those live in `modal-request.css`, which
    this page doesn't load (same reasoning as the `.lims-analysis-name` fix on 2026-10-01).
- **2026-10-13** — "Encode Test Results" modal: replaced the per-row "more options" gear icon with a
  dedicated **Action** column holding a "Reassign" button (person icon + label), matching the rest of
  the app's convention of a separate Action column rather than packing controls into a data cell.
  Reassign is **admin-only and gated for real**, not just a CSS hook: `layouts/app.blade.php`'s `<body>`
  now carries `data-role="{{ session('lims_role') }}"`, and `applyReassignVisibility()` in
  `resultforencoding.js` toggles Tailwind's `.hidden` on every `.lims-admin-only` element (the Action
  `<th>` and each row's Action `<td>`) based on it, called right after each render. The Reassign button
  itself doesn't do anything yet (no handler wired) — purely the visibility gating + styling
  (`.lims-reassign-button` in `resultforencoding.css`, replacing the old `.lims-result-options`).
  **Note:** `data-role` only distinguishes `admin`/`user` today — see the role-model gap in §9; once the
  5-role model exists, swap this check for the real "Section Chief/Supervisor" permission rather than a
  generic admin flag, since re-assigning an analyst is a Section Chief action per the workflow (§2.4), not
  necessarily an admin one.
- **2026-10-13** — Wired `resultforencoding.js` to actually populate the "Encode Test Results" modal,
  replacing the 5 static reference rows added earlier the same day (now deleted from
  `modal1-results.blade.php`, as planned). No new endpoint was needed: `GET /resultforencoding/data`
  already returns `labWorks` nested per sample, so `viewRequest()` now looks the clicked sample up out of
  the already-fetched `routinglist` (by `requestId` + the newly added `data-test-id` on the View button)
  instead of making a second request. `renderResultsTable(labWorks)` builds one `<tr>` per
  `LabRequestLabWork`: resolves `labWork.analysis` to a name/method via the existing `analysisList`,
  pre-fills the Unit/Result inputs from `labWork.unit`/`labWork.result`, and sets `has-result` on the row
  when `result` is non-empty. **Analyst name resolution is a placeholder**: `labWork.userId` is matched
  against a hard-coded `analysts` array (`userId` 7/8/9 → Ana Reyes/Mark Lopez/Joy Santos), copied from
  the same stand-in list already in `jobrouting.js` — there is no real employee/analyst API wired in
  anywhere yet. Replace both copies together once one exists; until then keep them in sync by hand.
- **2026-10-13** — Added 5 static reference rows to `modal1-results.blade.php`'s `<tbody>` (Ash, BOD,
  Moisture, pH, Protein — the same classes/structure JS should emit, with `data-lab-work-id`/
  `data-test-id`/`data-analysis-id` placeholders) covering the 3 row states: has a result
  (`has-result` class + filled input), assigned with no result yet, and unassigned
  (`.lims-result-unassigned` "Not assigned"). These are a hand-written template for writing the
  `resultforencoding.js` row-generation logic against — **delete them once that JS renders real rows**,
  they are not meant to ship.
- **2026-10-13** — Built the "Encode Test Results" modal for the Result for Encoding module from a
  design mockup: `ResultforEncoding/partials/modal1-results.blade.php` (new) + new page CSS
  `resources/css/pages/resultforencoding.css`, wired into `index.blade.php`'s `@vite` (was pointing at
  the wrong/commented-out file before) and `@section('modals')` (the include was already there, written
  ahead of the partial existing). Table columns: No. / Parameter / Method / Unit / Result / Analyst,
  matching `LabRequestLabWork` (`unit`, `result`, `userId`) added to `LIMS_API` since the last pass — see
  `GetRFEDetails.cs`/`ResultforEncodingRepo.cs`, which finally closes the "no per-parameter data model"
  gap flagged in §8/§9 on 2026-10-01. Rows with an encoded result get a green left-accent tint
  (`.has-result`), matching the "completed" signal used elsewhere (status pills). Footer intentionally
  left untouched — reuses the existing global `.lims-form-cancel`/`.lims-form-submit` classes as-is.
  **Scope of this pass: markup + CSS only.** `<tbody id="resultsTableBody">` is left empty for JS to
  populate — `resultforencoding.js` already opens the modal on the table's View button
  (`OpenModal("ResultsModal")`) but doesn't fetch/render rows yet, and there's no Laravel route for a
  single-request detail endpoint yet (`routes/web.php` only has `/resultforencoding` and
  `/resultforencoding/data`, no `/resultforencoding/view/{id}`) — needed before the modal can show real
  data.

Add a dated entry each time a change is made. Newest on top.

- **2026-10-05** — Row hover on the shared `.lims-sample-table`/`.lims-dashboard-table` was there but
  effectively invisible (`var(--semi1white)`, a near-white tint, on an already-white row) — strengthened
  to `color-mix(in srgb, var(--secondary) 7%, var(--white))` in `datatable.css` and `dashboard.css`.
  While checking which pages use these table classes, found two new in-progress modules:
  **Job Routing** was renamed from `jobrouting.blade.php` to `JobRouting/index.blade.php`, and a new
  **Result for Encoding** module (`ResultforEncodingController`, `resultforencoding.js`,
  `ResultforEncoding/index.blade.php`) has been scaffolded for workflow steps 5–6 — table shell only, no
  modal yet. Both tables already inherit the fixed hover automatically since they reuse
  `.lims-sample-table`. Logged both in §6/§9 (notably: both modules' `data()` call a `LIMS_API` endpoint
  that doesn't exist — see §9).
- **2026-10-05** — Follow-up redesign pass on three specific pieces, building on the palette/token
  changes from earlier the same day:
  - **Dashboard "Welcome Back" card** (`dashboard.css` `.lims-welcome-card`): now the darkest surface on
    the page — a navy gradient (`var(--primary)` → `#132A4D`) hero banner with a soft accent-colored glow
    in the corner, white heading, light-blue eyebrow, and an inverted white "New Laboratory Request"
    button (navy text) for contrast.
  - **Request-for-Analysis "Select Analysis" modal** (`requestform.css`, `@layer AnalysisModal`): card
    radius bumped to `rounded-3xl`; title switched to `--ink` (was blue); test checkboxes now have a
    real selected state (`:has(.analysis-checkbox:checked)` — border + tint background, previously
    missing entirely) and a hover lift; footer "Apply Selection" button recolored from hardcoded hex to
    the `--secondary`/`--accent` tokens. Also fixed the `--light-blue` (hyphenated, undefined) typo in
    this file's Terms section — same class of bug fixed elsewhere on 2026-10-01.
  - **Job Routing "Job Routing Slip" modal** (`JobRouting/partials/modal1-route.blade.php` +
    new `resources/css/pages/jobrouting.css`, loaded via `jobrouting.blade.php`): the modal previously
    had no title or close (×) button in its header (only the footer Cancel worked) and its parameter rows
    (`.lims-route-row`, built client-side in `jobrouting.js`) had **no CSS at all** — the page never
    loaded `requestform.css`, which is where `.lims-analysis-name`/`.lims-analysis-method` are normally
    defined, so rows rendered as unstyled text. Added a proper title + close button (wired in
    `jobrouting.js` via `closeRouteModalHeader`) and full styling for `.lims-route-row` (card-style row,
    hover lift, name/method typography, `.lims-route-select` width) matching the rest of the app's modal
    language.
- **2026-10-05** — Visual redesign (modern look, blue palette kept/refined). Previewed first as an
  artifact mockup (dashboard + Job Routing/modal screens), then applied via CSS only — no blade markup
  or structure changed except where noted.
  - `resources/css/app.css`: root palette updated — `--primary:#0B1F3A`, `--secondary:#2563EB`,
    `--accent:#3B82F6`, `--lightblue:#93C5FD`, `--semi1white:#F3F7FF`, `--semi2white:#FAFBFF`; added
    `--ink:#0F172A` (headings/numbers) and `--muted:#64748B` (secondary text), so blue is now reserved
    for accents/links/active states rather than used for all text. Header/notification/avatar icon
    buttons switched from a bordered circle to a tinted-fill style; sidebar nav hover/active states
    flattened from a gradient to a solid tint; `.lims-form-card`/`.lims-modal-card` corner radius bumped
    from `rounded-2xl` to `rounded-3xl`.
  - `resources/css/pages/dashboard.css`: replaced a hardcoded, unrelated blue (`#136aac`) used
    throughout with the new tokens (dark `--ink` for headings/numbers, `--secondary` for
    eyebrows/labels); stat icons became tinted circles; status pills switched to the soft-tint style
    (pending=blue, processing=amber, completed=green) matching `datatable.css`. Fixed two real bugs
    found while doing this: `.lims-dashboard-table th` text was `#F4F8FC` at 35% opacity — invisible on
    the table's white background — and `.lims-panel-link:hover` turned the link white (also
    invisible on white); both now get a visible color instead.
  - `resources/css/components/datatable.css`: table header changed from a solid blue bar to a light
    header row (background `--semi1white`, muted uppercase labels), matching the mockup; status pill
    colors unified with `dashboard.css`'s soft-tint scheme (previously each file used different
    pending/processing/completed colors for the same words).
  - Fixed a pre-existing typo in `app.css`/`datatable.css`: several rules referenced the CSS variable
    `--light-blue` (hyphenated), which doesn't exist — the real variable is `--lightblue` — so those
    hover/disabled states (input hover tint, select disabled state, modal close hover, dropdown item
    hover, DataTables pagination buttons) silently did nothing. All occurrences corrected.
  - Intentionally left as-is: `.lims-form-submit`/`.lims-form-cancel` (green submit button — a deliberate
    choice made separately from this redesign) and `welcome.blade.php`/login screen (not covered by the
    mockup; a follow-up if wanted).

- **2026-10-01** — Request for Analysis form (`RequestforAnalysis`): the "Select Analysis" modal
  (`modal1-analysismodal.blade.php`) now filters its test list by the laboratory type (`LabAna`:
  Chemical/Microbiological) chosen in tab 2 — only the matching `data-analysis-type` section is shown,
  and any already-checked tests from the non-matching category are auto-unchecked when the lab type
  changes or the modal is reopened. Logic lives in `getSelectedLabAnalysis()`/`filterAnalysisByLabType()`
  in `resources/js/pages/requestform.js`. Matching is by keyword (`LAB_TYPE_KEYWORDS`), not exact string,
  because `/api/AnalysisList`'s `type` column actually contains `"Chemical Analysis"` /
  `"Biological Analysis"` — not `"Chemical"` / `"Microbiological"` like the radio values (see §9).
- **2026-10-01** — Documented the full 8-step business workflow, roles, numbering hierarchy
  (`RFA-YYYY-NNNNN` → `LAB-YYYY-NNNN` → parameter codes), and per-parameter status flow (§2) from the
  workflow diagram provided by the user. Mapped existing modules to workflow steps (§6) and added the
  concrete data-model/role gaps needed to implement steps 4–8 (§8, §9).
- **2026-09-29** — Added `AGENTS.md` and `documentation.md` to establish project conventions
  (C# API as backend of record, Laravel as BFF/frontend, global SweetAlert2/`lims-*` CSS reuse rules)
  and capture current architecture/known issues.
