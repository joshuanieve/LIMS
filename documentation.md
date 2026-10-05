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

| Role | Responsibilities |
|---|---|
| **Requesting User (Client)** | Submit RFA, provide required information, track request status, receive final test report and notifications |
| **Administrative Personnel** | Review submitted RFA, assign to the appropriate laboratory (Chemical/Microbial), ensure completeness of information, notify the concerned laboratory/section |
| **Section Chief / Supervisor** | Assign laboratory parameters to analysts, review encoded results, approve results, monitor workload and turnaround time |
| **Analyst** | Accept assigned parameters, conduct laboratory analysis, encode test results, upload supporting documents (if applicable) |
| **Division Chief** | Review all results under a reference number, approve the final test report, authorize release to the client |

### 2.5 Workflow vs. current implementation — gap map

The code today only implements step 1 end-to-end and a read-only view of steps 2–3; steps 4–8 and the
5-role model are **not yet built**. See §9 for the concrete gaps.

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
| Dashboard | (cross-cutting) | `GET /dashboard` | `dashboard/admin.blade.php`, `dashboard/user.blade.php` | none yet — stats are hard-coded placeholders in `DashboardController` |
| Request for Analysis | **Step 1** (Submit RFA) | `GET/POST /requestform` | `RequestforAnalysis/requestform.blade.php` + `partials/tab1..tab7` | `GET /api/AnalysisList`, `POST /api/RequestForm` |
| Analysis catalog list | supports Step 1 | `GET /analysislist/data` | used by `RequestforAnalysis` tabs | `GET /api/AnalysisList` |
| Request Sample | **Steps 2–3** (review pending RFAs, read-only sample list; `LaboratoryNumber` field exists on `LabRequestList` but auto-generation isn't implemented) | `GET /requestsample`, `GET /requestsample/{id}`, `GET /requestsample/view/{id}` | `RequestSample/requestsample.blade.php` | `GET /api/RequestPending`, `GET /api/RequestPending/{id}` |
| Job Routing | **Step 4** target (assign parameters to analysts) — table + "Job Routing Slip" modal list the checked analytes per sample with an analyst dropdown; dropdown has no data source and nothing persists yet | `GET /jobrouting`, `GET /jobrouting/data` | `JobRouting/index.blade.php` + `partials/modal1-route.blade.php` | `Http::get('http://localhost:5198/api/JobRouting')` — **note: this endpoint doesn't exist in `LIMS_API`** (no `JobRoutingController` there), so `data()` currently fails; see §9 |
| Result for Encoding | **Steps 5–6** target (analyst accepts parameters, encodes results) — early scaffold: table + View button only, no modal/detail view wired yet | `GET /resultforencoding`, `GET /resultforencoding/data` | `ResultforEncoding/index.blade.php` | same broken `http://localhost:5198/api/JobRouting` call as Job Routing — copy-pasted, see §9 |
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
5. `role_id` (1 = user, 2 = admin per `$limsRoles` — note this mapping looks inverted vs.
   `DashboardController`, see §9) drives which dashboard view is shown.

**Gap:** the business workflow (§2.4) needs 5 distinct roles (Requesting User, Administrative Personnel,
Section Chief/Supervisor, Analyst, Division Chief) with different permissions per step. The current
`role_id` → `user`/`admin` mapping only supports 2. This needs to be expanded before steps 2–8 can have
real per-role access control.

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
  `LIMS_API`.
- **`JobRoutingController::data()` and `ResultforEncodingController::data()` both call
  `Http::get('http://localhost:5198/api/JobRouting')`** — hard-coded (not via `config()`, unlike
  `RequestFormServices`) and pointing at an endpoint that doesn't exist in `LIMS_API` (no
  `JobRoutingController` there, only `AnalysisList`/`RequestForm`/`RequestPending`). Both pages' tables
  will fail to load until this is pointed at a real endpoint (likely `/api/RequestPending`, same as
  `RequestSample`/old `JobRouting`, or a new purpose-built one) and moved into a Service class per
  `AGENTS.md`.
- **Role model doesn't match the business workflow**: only `user`/`admin` exist today; the workflow
  (§2.4) needs 5 roles (Requesting User, Administrative Personnel, Section Chief/Supervisor, Analyst,
  Division Chief). Also verify the `role_id` mapping mismatch — `LoginController` maps
  `1 => 'user', 2 => 'admin'`, while `DashboardController` treats `roleId === 1` as admin and
  `roleId === 2` as user.
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
