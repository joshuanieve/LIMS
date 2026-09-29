# AGENTS.md

Instructions for AI coding agents (Claude Code, Cursor, Copilot, etc.) working in this repository.

## Project Context

This is a **Laboratory Information and Management System (LIMS)** — currently the subject of the
user's project proposal. It is a two-part solution living in one git repository:

| Folder | Role | Stack |
|---|---|---|
| `LIMS/` | Web frontend + thin BFF layer | Laravel (Blade), Tailwind CSS v4, Vite, vanilla JS, SweetAlert2, DataTables |
| `LIMS_API/` | Backend API — owns business logic & data | ASP.NET Core Web API (C#), Entity Framework Core, SQL Server |

**The C# API (`LIMS_API`) is the real backend.** Laravel does **not** talk to the database directly for
LIMS domain data — it calls `LIMS_API` over HTTP (via `Http::` facade in `app/Services/*Services.php`,
using the base URL from `config('services.lims_api.url')` / `.env` `LIMS_API_URL`). Laravel's own
`storage/database.sqlite`/session DB is only for framework concerns (auth, sessions), not lab data.

When asked to add a feature, default to: **new EF entity/table → Repository → Service → Controller in
`LIMS_API`**, then a thin Laravel controller/service that calls the new endpoint and a Blade view/JS page
to render it. Don't put business logic or validation rules in Laravel — that belongs in the C# service
layer.

## Architecture Conventions (follow existing patterns)

### `LIMS_API` (C#)
- Strict layering per feature: `Controllers/`, `Services/<Feature>/`, `Repositories/<Feature>/`,
  `Entities/<Feature>/` (request/response DTOs — **not** EF models), `Models/` (EF Core entities used by
  `LimsContext`).
- Every Service and Repository has an interface (`IXxxService`, `IXxxRepo`) registered as `Scoped` in
  `Program.cs`. Constructor-inject the interface, never the concrete class.
- Controllers are thin: `[ApiController]`, `[Route("api/[controller]")]`, delegate to the service, wrap in
  try/catch, return `Ok(...)` / `BadRequest(new { message })`. No EF/LINQ queries in controllers.
- Business/validation rules (required fields, cross-field checks) live in the **Service**, not the
  Repository or Controller.
- All I/O is `async`/`await` (`Task<T>`); don't introduce blocking calls (`.Result`, `.Wait()`).
- JSON is camelCase (`AddJsonOptions` in `Program.cs`) — keep DTO property names PascalCase in C#, they
  serialize to camelCase automatically; don't manually rename.
- New feature = new folder trio (`Entities/<Feature>`, `Repositories/<Feature>`, `Services/<Feature>`) +
  DI registration in `Program.cs`, matching `RequestForm`/`RequestPending`/`AnalysisList`.

### `LIMS` (Laravel)
- Controllers stay thin and call a `App\Services\*Services` class for any `LIMS_API` HTTP call — never
  call `Http::` directly from a controller.
- One feature = one `Service` class in `app/Services`, one `Controller`, one route group in
  `routes/web.php` (grouped with a `use` + comment, matching the existing style for
  `RequestForm`/`RequestSample`/`JobRouting`).
- Frontend assets are organized by feature, not dumped in one file:
  - `resources/js/pages/<feature>.js` — page-specific behavior
  - `resources/js/components/<name>.js` — reusable widgets (e.g. `datatable.js`)
  - `resources/js/services/<name>.js` — fetch/AJAX calls to Laravel routes
  - `resources/css/pages/<feature>.css`, `resources/css/components/<name>.css` — same split
  - `resources/views/<Feature>/` with a `partials/` subfolder for modals/tabs
  - Follow this convention for every new module instead of adding to `app.js`/`app.css` directly.

## Global Design & Alerts — Always Reuse, Never Duplicate

This project intentionally centralizes UI chrome so every page looks and behaves the same. **Do not**
write page-local alert/modal/input styling — extend the global primitives instead.

**Alerts / loading (in `resources/js/app.js`, backed by SweetAlert2):**
- `window.swal_success(title, message)`
- `window.swal_error(title, message)`
- `window.showLoading()` / `window.hideLoading()` (`#loadingOverlay` full-screen spinner)

If a new alert variant is needed (e.g. confirm dialog, toast, warning), add it as a new
`window.swal_*` helper in `app.js` next to the existing ones — do not call `Swal.fire(...)` inline in a
page script. This keeps icon/color/button styling consistent everywhere.

**Design tokens & reusable classes (in `resources/css/app.css`):**
- CSS variables in `:root`: `--primary`, `--secondary`, `--accent`, `--lightblue`, `--semi1white`,
  `--semi2white`, `--white`, `--black`. Use these (or Tailwind's `@theme`) instead of hard-coded hex
  colors in new CSS/blade files.
- Reusable component classes already defined — prefer them over ad-hoc utility soup:
  - Forms: `.lims-form`, `.lims-form-card`, `.lims-form-grid(-2|-3)`, `.lims-form-step`
  - Inputs: `.lims-input`, `.lims-textarea`, `.lims-select`, `.lims-table-input`
  - Modals: `.lims-modal`, `.lims-modal-card`, `.lims-modal-header`, `.lims-modal-content`,
    `.lims-modal-footer`, `.lims-modal-cancel`
  - Sidebar/header: `.lims-sidebar-*`, `.lims-nav-*`, `.lims-header-*`
- New reusable UI pieces go in `app.css` (or a `components/*.css` partial imported by it), scoped with a
  `lims-` prefix, not invented per-page.

## Coding Best Practices (Laravel Boost–style conventions)

Applies to both stacks unless stated otherwise:

1. **Match existing conventions first.** Before adding a pattern, check how the nearest existing
   feature (e.g. `RequestForm`) already solved it and mirror it, rather than introducing a new style.
2. **Validate at the boundary.** Laravel: use `Form Request` classes for input validation instead of
   validating inline in the controller. C#: validate in the Service layer, throw descriptive exceptions
   (as `RequestFormService` does), which the controller translates to `BadRequest`.
3. **Strong typing.** C#: enable nullable-reference-aware code, explicit return types
   (`Task<IActionResult>`, `Task<int>`, etc.), no untyped `dynamic`/`object` in DTOs. PHP: type-hint
   method params/returns and property types (`protected string $baseUrl`, as already done).
4. **No business logic in controllers.** Controllers orchestrate; services decide. Keep controllers
   under ~20 lines per action.
5. **Use dependency injection everywhere** — constructor-inject services/repos (C#) or resolve via the
   container (Laravel); never `new` up a service that talks to the API or DB.
6. **Don't reach around the API.** Laravel must never query `LimsContext`'s SQL Server directly — always
   go through `LIMS_API`.
7. **Keep secrets in config/env**, never hard-code the `LIMS_API` base URL, connection strings, or API
   keys — read them via `config()`/`appsettings.json` + environment variables.
8. **Small, single-purpose files** — one Repository/Service/Entity DTO per feature folder, one JS module
   per page/component, mirroring the folder conventions above instead of growing a "god" file.
9. **Run checks before calling a change done:**
   - Laravel: `vendor/bin/pint` (style) and `php artisan test` if tests exist for the area touched.
   - C#: `dotnet build` (and `dotnet test` if a test project exists) before considering an endpoint done.
10. **Don't add speculative abstractions, feature flags, or config options** for requirements that
    haven't been asked for yet — this is a student project proposal, keep it lean and readable.

## Documentation Rule

**Whenever you make a code change (new feature, endpoint, schema change, route, or significant
refactor), update [`documentation.md`](documentation.md) in the same session** — add/modify the relevant
section (Modules, API Endpoints, Data Model, or Changelog). Do not let `documentation.md` drift out of
sync with the code; treat it as part of the change, not an afterthought.
