# Shoppa — Sprint S1 Completion, Dashboards, Tests & Deployment

**Date:** 2026-06-08
**Status:** Approved
**Scope:** Fix S1 bugs · Build 9 role dashboards · Full S1 test suite · Docker + GitHub Actions CI/CD

---

## 1. Context

Shoppa is a Trust-as-a-Service electronics marketplace for the Kenyan market. The core value proposition is device verification: every listed device is physically inspected by a Verifier before going live, and buyers pay into escrow. The verification pipeline and escrow system are the product — everything else is infrastructure around that trust engine.

Sprint S1 (IAM, roles, permissions, auth pages, layouts, base components) is marked complete in `CLAUDE.md` but has five confirmed bugs that must be fixed before new UI work begins. After fixes, every role gets a dedicated production-grade dashboard, a full S1 test suite is written, and a Docker + GitHub Actions deployment pipeline is set up for a single bare-metal staging/prod server.

---

## 2. Sprint S1 Bug Fixes

Five bugs confirmed by code audit. Must be resolved before dashboard work starts.

| # | Location | Bug | Fix |
|---|---|---|---|
| 1 | `resources/views/pages/admin/dashboard.blade.php:1` | Uses `x-layouts.app` (buyer layout) instead of staff layout | Change to `x-layouts.dashboard` |
| 2 | `app/Http/Controllers/Vendor/DashboardController.php` | Returns `view('pages.vendor.dashboard')` but blade lives at `pages/vendors/dashboard.blade.php` | Fix controller to `view('pages.vendors.dashboard')` |
| 3 | `resources/views/pages/vendor/DashboardController.php` | PHP controller file accidentally placed inside the views directory | Delete this file |
| 4 | `resources/views/pages/buyer/browse.php` | Missing `.blade.php` extension — Laravel cannot find or compile this view | Rename to `browse.blade.php` |
| 5 | `app/Actions/Fortify/CreateNewUser.php` | Imports `Laravel\Jetstream\Jetstream` which is not installed | Remove the unused import |

All five fixes are non-breaking and safe to apply atomically in a single commit before any dashboard work.

---

## 3. Dashboard UI Design

### 3.1 Visual System

**Direction:** Refined marketplace meets editorial precision. Not a generic SaaS template.

| Token | Value | Usage |
|---|---|---|
| Sidebar bg | `#0f172a` (slate-900) | Fixed left rail, all roles |
| Sidebar active | sky-500 left border + sky-50 text bg | Active nav item |
| Sidebar accent | blush-400 dot | Unread/alert indicators |
| Content bg | `#f8fafc` (slate-50) | Page background |
| Card surface | `#ffffff` | All cards and panels |
| Card border | `ring-1 ring-slate-900/5 shadow-sm` | Consistent card chrome |
| Stat numeral | `2.5rem / font-700 / text-slate-900` | Hero KPI numbers |
| Stat label | `0.75rem / font-500 / text-slate-500` | Below numeral |
| Primary accent | sky-500 | Buttons, links, focus rings |
| Secondary accent | blush-400 | Decorative, trust badges, highlights |
| Font | Nunito (400/500/600/700) | Entire app |

Sidebar width: `256px` (fixed desktop), slide-over on mobile via Alpine `sidebarOpen`. Topbar: sticky, white, `shadow-sm`, carries role badge + user dropdown.

Stat tiles are arranged in a 4-column grid on ≥lg, 2-column on sm, 1-column on xs. Each tile: white card, large numeral, muted label, optional trend chip (↑/↓ with colour).

### 3.2 Role Dashboards

Each role gets a dedicated page with: greeting header (time-aware), 4 KPI stat tiles, a quick-actions row, and a primary content panel (table or activity feed).

#### Super Admin — `admin.dashboard` (gated by `@role('Super Admin')` overlay on Admin dashboard)

| KPI | Source (S1 stub → real in later sprint) |
|---|---|
| Total users | `User::count()` |
| Total vendors | `Vendor::count()` |
| Open disputes | stub `0` |
| Platform revenue (KSh) | stub `0.00` |

Quick actions: Create user · Manage roles · View activity log · Seed roles (dev only)

Primary panel: Latest registrations table (user, role, joined).

#### Admin — `admin.dashboard`

| KPI | Source |
|---|---|
| Total users | `User::count()` |
| Pending vendor apps | stub `0` (S2) |
| Orders today | stub `0` (S3) |
| Open disputes | stub `0` (S6) |

Quick actions: Approve vendors · Manage users · View disputes

Primary panel: Quick-access cards to Vendors, Verification Queue, Disputes.

#### Vendor Manager — `admin.vendor-manager.dashboard` *(new route + controller)*

| KPI | Source |
|---|---|
| Pending applications | stub `0` |
| Active vendors | stub `0` |
| Suspended vendors | stub `0` |
| Approvals this week | stub `0` |

Quick actions: Review applications · View vendor list

Primary panel: Pending vendor applications table (name, submitted, status).

#### Verifier — `verifier.dashboard` *(new route + controller)*

| KPI | Source |
|---|---|
| Queue depth (pending) | stub `0` |
| Certified today | stub `0` |
| Rejected today | stub `0` |
| Avg inspection time | stub `—` |

Quick actions: Open queue · View cert history

Primary panel: Top 5 oldest pending devices in queue.

#### Customer Service — `admin.cs.dashboard` *(new route + controller)*

| KPI | Source |
|---|---|
| Open disputes | stub `0` |
| Resolved today | stub `0` |
| Pending refunds | stub `0` |
| Avg resolution (days) | stub `—` |

Quick actions: Manage disputes · Process refunds

Primary panel: Open disputes table (order, buyer, reason, age).

#### Content Manager — `admin.content.dashboard` *(new route + controller)*

| KPI | Source |
|---|---|
| Total products | stub `0` |
| Pending review | stub `0` |
| Published today | stub `0` |
| Categories | stub `0` |

Quick actions: Add product · Manage categories

Primary panel: Recently submitted products table.

#### Vendor — `vendor.dashboard` *(existing, redesigned)*

| KPI | Source |
|---|---|
| Active listings | stub `0` |
| Awaiting verification | stub `0` |
| Orders to fulfil | stub `0` |
| Total earned (KSh) | stub `0.00` |

Quick actions: Add listing · View orders

Primary panel: Recent listings with trust cert badge column.
Callout: Verification upsell banner (amber, not intrusive).

#### Buyer — `buyer.dashboard` *(existing, redesigned)*

| KPI | Source |
|---|---|
| Active orders | stub `0` |
| Total purchases | stub `0` |
| Wishlist count | stub `0` |
| Verified devices owned | stub `0` |

Quick actions: Browse devices · Track orders

Primary panel: Recent orders table with status badge.
Trust callout: Shoppa Verified pill with "every device inspected" message.

#### Guest — no dashboard
Guest users are redirect-only. `RoleName::Guest` maps to `buyer.dashboard` route which redirects unauthenticated users to login. No dedicated guest dashboard page needed.

### 3.3 Layout Rules

- Staff roles (Admin, SuperAdmin, VendorManager, Verifier, CS, ContentManager) use `x-layouts.dashboard`.
- Vendor and Buyer use `x-layouts.app`.
- New staff dashboards for VendorManager, Verifier, CS, ContentManager each need a new controller in the correct domain folder and a new route entry with the correct role middleware.
- All stub values are typed `0` or `'0.00'` — never `null`. Views must not break when stubs return zero.

---

## 4. Test Suite

### 4.1 Configuration

- Driver: SQLite in-memory (already in `phpunit.xml`).
- Base `TestCase` (`tests/TestCase.php`): add `RefreshDatabase` + a `seedRoles()` helper that calls `RoleSeeder`, `PermissionSeeder`, `RolePermissionSeeder`.
- Factory helpers: `UserFactory` states for each role (`asAdmin()`, `asVendor()`, `asBuyer()`, etc.).

### 4.2 Test Files

#### `tests/Feature/Auth/LoginTest.php`
- Valid credentials → redirected to correct dashboard per role
- Invalid password → validation error, stays on login
- Non-existent email → validation error
- Rate limit: 6th attempt within a minute → 429
- Remember me token persisted in cookie

#### `tests/Feature/Auth/RegisterTest.php`
- Valid data → user created, Buyer role assigned, redirected to email verification notice
- Missing name → validation error
- Invalid email format → validation error
- Duplicate email → validation error
- Password too short (< 8 chars) → validation error
- Password confirmation mismatch → validation error
- Phone is optional — omitting it succeeds

#### `tests/Feature/Auth/PasswordResetTest.php`
- Request reset link with valid email → 200, `passwords.sent` session status
- Request reset link with unknown email → still 200 (no user enumeration)
- Reset with valid token + matching passwords → password updated, redirected to login
- Reset with expired/invalid token → validation error

#### `tests/Feature/Auth/EmailVerificationTest.php`
- Unverified user hitting `/dashboard` → redirected to `/email/verify`
- Verified user → passes through
- Verification link marks email as verified and redirects correctly

#### `tests/Feature/Auth/RoleRedirectTest.php`
- After login, each of the 8 authenticated roles lands on its correct dashboard route
- Roles: SuperAdmin→`admin.dashboard`, Admin→`admin.dashboard`, VendorManager→`admin.vendor-manager.dashboard`, Verifier→`verifier.dashboard`, CS→`admin.cs.dashboard`, ContentManager→`admin.content.dashboard`, Vendor→`vendor.dashboard`, User→`buyer.dashboard`

#### `tests/Feature/Dashboard/AdminDashboardTest.php`
- Admin can GET `/admin/dashboard` → 200
- SuperAdmin can GET `/admin/dashboard` → 200
- Vendor → 403
- Buyer → 403
- Unauthenticated → redirect to login

#### `tests/Feature/Dashboard/VendorDashboardTest.php`
- Vendor can GET `/vendor/dashboard` → 200
- Buyer → 403
- Admin → 403
- Unauthenticated → redirect to login

#### `tests/Feature/Dashboard/BuyerDashboardTest.php`
- Buyer can GET `/dashboard` → 200
- Unauthenticated → redirect to login

#### `tests/Feature/Dashboard/StaffDashboardTest.php`
- VendorManager → `/admin/vendor-manager/dashboard` → 200
- Verifier → `/verifier/dashboard` → 200
- CS → `/admin/cs/dashboard` → 200
- ContentManager → `/admin/content/dashboard` → 200
- Buyer trying each staff route → 403

#### `tests/Feature/Admin/UserCrudTest.php`
- Admin can GET `/admin/users` → 200
- Admin can GET `/admin/users/create` → 200
- Admin POSTing valid user data → user created, redirected to index
- Admin POSTing invalid data → validation errors
- Admin can GET `/admin/users/{id}/edit` → 200
- Admin can PUT valid update → user updated
- Admin can DELETE user → soft-deleted
- Vendor trying any of the above → 403
- Buyer trying any of the above → 403

---

## 5. Docker + GitHub Actions Deployment

### 5.1 Docker Compose Strategy

Single bare-metal server. Two isolated environments running side-by-side via two compose files. Containers and volumes are namespaced to avoid collision.

```
docker-compose.yml          # base service definitions (shared structure)
docker-compose.staging.yml  # staging overrides: ports 8080/8081, staging .env, staging volumes
docker-compose.prod.yml     # prod overrides: ports 80/443, prod .env, prod volumes
```

**Services (both environments):**

| Service | Image | Notes |
|---|---|---|
| `app` | `php:8.2-fpm` custom | Composer deps baked in at build time |
| `nginx` | `nginx:stable-alpine` | Vhost config mounted as volume |
| `postgres` | `postgres:16-alpine` | Data in named volume |
| `redis` | `redis:7-alpine` | Ephemeral cache + queue |
| `queue` | same `app` image | Runs `php artisan queue:work` |
| `scheduler` | same `app` image | Runs `php artisan schedule:run` via supercronic |

**Nginx vhosts:**
- Staging: `server_name dev.shoppa.<domain>;` → proxies to `app:9000`
- Prod: `server_name app.shoppa.<domain>;` → proxies to `app:9000`

SSL is handled by Certbot/Let's Encrypt running on the host (not inside Docker), with certs mounted into the nginx container.

**Environment files:**
- `.env.staging` and `.env.prod` live on the server only (never committed). The compose files reference them via `env_file:`.
- `.env.example` is committed and documents all required variables.

### 5.2 GitHub Actions Workflows

#### `ci.yml` — runs on every push and PR

```
Trigger: push (any branch), pull_request
Steps:
  1. Checkout
  2. Setup PHP 8.2 + extensions
  3. Composer install (--no-interaction --prefer-dist)
  4. Copy .env.testing → .env
  5. php artisan key:generate
  6. php artisan test --parallel
  7. npm ci + npm run build (asset build smoke check)
```

#### `deploy-staging.yml` — runs on push to `develop`

```
Trigger: push to develop (after ci.yml passes)
Steps:
  1. SSH into server (SSH_HOST, SSH_USER, SSH_KEY secrets)
  2. cd /var/www/shoppa-staging
  3. git pull origin develop
  4. docker compose -f docker-compose.staging.yml build --no-cache app
  5. docker compose -f docker-compose.staging.yml up -d
  6. docker compose -f docker-compose.staging.yml exec app php artisan migrate --force
  7. docker compose -f docker-compose.staging.yml exec app php artisan config:cache
  8. docker compose -f docker-compose.staging.yml exec app php artisan route:cache
  9. Health check: curl --fail https://dev.shoppa.<domain>/health → expect 200
```

#### `deploy-prod.yml` — runs on push to `main`

```
Trigger: push to main
Environment: production (requires manual approval in GitHub Environments)
Steps: identical to staging but uses docker-compose.prod.yml and app.shoppa.<domain>
```

### 5.3 GitHub Secrets Required

| Secret | Description |
|---|---|
| `SSH_HOST` | Server IP or hostname |
| `SSH_USER` | SSH user (e.g. `deploy`) |
| `SSH_KEY` | Private SSH key (Ed25519 recommended) |

No staging/prod split on secrets — same server, same SSH access.

### 5.4 Server Directory Layout

```
/var/www/
  shoppa-staging/    ← develop branch checkout
    .env.staging
    docker-compose.staging.yml
  shoppa-prod/       ← main branch checkout
    .env.prod
    docker-compose.prod.yml
```

Both are separate git clones of the same repo, on different branches, so a bad staging deploy cannot affect prod.

### 5.5 Health Check Endpoint

Add a single `GET /health` route (no auth, no middleware) that returns `200 OK` with `{"status":"ok"}`. Used by CI deploy step to verify the container is up after restart.

---

## 6. Implementation Order

1. **Fix S1 bugs** (5 files, one commit)
2. **Add health check route** (`routes/web.php`, 2 lines)
3. **New staff dashboard controllers + routes** (VendorManager, Verifier, CS, ContentManager)
4. **Redesign all 9 dashboard views** (impeccable frontend-design, navy sidebar system)
5. **Update `RoleName::dashboardRoute()`** to point new roles to their new dashboard routes
6. **Write test suite** (`tests/Feature/Auth/`, `tests/Feature/Dashboard/`, `tests/Feature/Admin/`)
7. **Docker Compose files** (`docker-compose.yml`, `docker-compose.staging.yml`, `docker-compose.prod.yml`)
8. **GitHub Actions workflows** (`.github/workflows/ci.yml`, `deploy-staging.yml`, `deploy-prod.yml`)
9. **`.env.example`** updated with all required variables

---

## 7. Out of Scope

- Sprint S2–S6 features (vendor KYC, product catalog, trust engine, orders, payments)
- Real data queries replacing stubs (stubs are intentional — they'll be replaced per sprint)
- SSL certificate provisioning (manual step on the server, documented in README)
- M-Pesa integration
