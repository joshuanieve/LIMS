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

## 2. Tech Stack

| Layer | Technology |
|---|---|
| Frontend UI | Blade templates, Tailwind CSS v4, Vite, vanilla JS |
| Frontend libs | SweetAlert2 (alerts), DataTables (`datatables.net-dt`), Vanta/Three.js (background fx) |
| Frontend BFF | Laravel (PHP), `Http::` facade to call `LIMS_API` |
| Backend API | ASP.NET Core Web API (C#), Controller → Service → Repository pattern |
| Data access | Entity Framework Core, SQL Server |
| Auth | External SSO-style login (see §6) — JWT issued by an external `UserCont` API, decoded in Laravel, stored in session |

## 3. Repo Layout

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

## 4. Running Locally

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

## 5. Modules (current)

| Module | Laravel route(s) | Laravel views/JS | `LIMS_API` endpoint(s) used |
|---|---|---|---|
| Login / Auth | `POST /ffast`, `POST /logout`, `GET /` | `welcome.blade.php` | External `UserCont`/employee APIs (not `LIMS_API`) |
| Dashboard | `GET /dashboard` | `dashboard/admin.blade.php`, `dashboard/user.blade.php` | none yet — stats are hard-coded placeholders in `DashboardController` |
| Request for Analysis (submit a lab request) | `GET/POST /requestform` | `RequestforAnalysis/requestform.blade.php` + `partials/tab1..tab7` | `GET /api/AnalysisList`, `POST /api/RequestForm` |
| Analysis catalog list | `GET /analysislist/data` | used by `RequestforAnalysis` tabs | `GET /api/AnalysisList` |
| Request Sample (pending requests + view) | `GET /requestsample`, `GET /requestsample/{id}`, `GET /requestsample/view/{id}` | `RequestSample/requestsample.blade.php` | `GET /api/RequestPending`, `GET /api/RequestPending/{id}` |
| Job Routing | `GET /jobrouting` | `JobRouting/jobrouting.blade.php` | none wired yet (data/view actions are commented out in `JobRoutingController`) |

## 6. Auth Flow (as implemented today)

Login does **not** go through `LIMS_API`. `LoginController::login()`:
1. Posts credentials to an external `UserCont` login API (hard-coded IP `10.0.224.35:7152`).
2. Decodes the returned JWT (HS256, hard-coded shared secret in `LoginController`) to get `Emp_No`,
   `email`, `Role_id`.
3. Fetches employee profile + employee list + appointment/division info from another external API
   (`10.0.224.35:7154`).
4. Stores everything in the Laravel session (`logged_in`, `api_token`, `role_id`, `lims_role`,
   `fname`/`lname`/`mname`, `division_id`, etc.) — no local `users` table.
5. `role_id` (1 = user, 2 = admin per `$limsRoles` — note this mapping looks inverted vs. `DashboardController`,
   see §8) drives which dashboard view is shown.

## 7. Data Model (`LIMS_API` / SQL Server, via `LimsContext`)

| Table | Entity | Key fields |
|---|---|---|
| `Lab_AnalysisList` | `LabAnalysisList` | `AnalysisID` (PK), `Analyte`, `Category`, `Method`, `Type` |
| `Lab_Request` | `LabRequest` | `RequestID` (PK), `CustomerName`, `EmailAddress`, `DivisionSection`, `LabAnalysis`, `TargetDate`, `SampleType`, `SampleRetrieval`, `SubInfo1-4`, `Instruction`, `Status`, timestamps |
| `Lab_RequestList` | `LabRequestList` | `TestID` (PK), `RequestID` (FK), `LaboratoryNumber`, `Sample`, `CustomerSampleCode`, `DateTimeCollected`, `PlaceCollected`, `Analysis`, `Status`, timestamps |

One `LabRequest` (the submitted form/"project proposal" request) has many `LabRequestList` rows (one per
sample in the request).

## 8. Known Issues / Tech Debt

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
- **`JobRoutingController`** has its data/view actions commented out — module is UI-only so far.
- Possible **role_id mapping mismatch**: `LoginController` maps `1 => 'user', 2 => 'admin'`, while
  `DashboardController` treats `roleId === 1` as admin and `roleId === 2` as user. Verify which is
  correct against the actual employee API role IDs.

## 9. Changelog

Add a dated entry each time a change is made. Newest on top.

- **2026-09-29** — Added `AGENTS.md` and `documentation.md` to establish project conventions
  (C# API as backend of record, Laravel as BFF/frontend, global SweetAlert2/`lims-*` CSS reuse rules)
  and capture current architecture/known issues.
