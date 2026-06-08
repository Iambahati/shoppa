# CLAUDE.md — Shoppa

> This file is the single source of truth for AI assistants and new developers working on this codebase.
> Read it fully before writing any code, suggesting changes, or answering questions about the project.

---

## What Shoppa is

Shoppa is a **Trust-as-a-Service (TaaS)** electronics marketplace built for the Kenyan and African market.
Its core value proposition is not selling devices — it is **verifying them**.

Every device listed on Shoppa is physically inspected by a Verifier before going live.
Buyers pay into escrow. Funds release to the seller only after the buyer confirms receipt.
A Trust Certificate (QR-backed, UUID-signed) is issued per verified device and is publicly lookupable by IMEI or serial number.

The problem it solves: rampant device counterfeiting in Nairobi (e.g. iPhone 11 hardware modified to present as iPhone 17 Pro Max), stolen device resale, and opaque pricing. Shoppa is the infrastructure that eliminates this — for buyers, sellers, and eventually the whole East African market.

**Never treat Shoppa as "just another marketplace."** The verification pipeline and escrow system are the product. Everything else (listings, orders, payments) is infrastructure around that core trust engine.

---

## Tech stack

| Layer | Choice | Notes |
|---|---|---|
| Framework | Laravel 11 | PHP 8.2+ required |
| Auth | Laravel Fortify | Custom Blade views — no Jetstream |
| Database | PostgreSQL | Use `ilike` not `like` for case-insensitive search |
| Frontend | Blade + Alpine.js + Tailwind CSS | No React, no Vue. Blade only. |
| Build | Vite | `npm run dev` / `npm run build` |
| File storage | Spatie MediaLibrary + S3 | Device photos, Trust Certificates, KYC docs |
| Activity logging | Spatie ActivityLog | Every sensitive action must be logged |
| Queue | Laravel Queue (database driver in dev, Redis in prod) | |
| Cache | Redis | Role lookups cached for 1 hour |

---

## Getting started

```bash
# 1. Install PHP dependencies
composer require laravel/fortify spatie/laravel-activitylog spatie/laravel-medialibrary

# 2. Publish Fortify
php artisan fortify:install

# 3. Register middleware aliases in bootstrap/app.php
# ->withMiddleware(function (Middleware $m) {
#     $m->alias([
#         'role'       => \App\Http\Middleware\CheckRole::class,
#         'permission' => \App\Http\Middleware\CheckPermission::class,
#     ]);
# })

# 4. Set environment
cp .env.example .env
# Set DB_CONNECTION=pgsql and configure DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD

# 5. Run migrations then seed roles/permissions
php artisan migrate
php artisan db:seed

# 6. Install frontend
npm install && npm run dev
```

After seeding, create a Super Admin manually:

```bash
php artisan tinker
>>> $role = App\Models\Role::where('name', 'Super Admin')->first();
>>> App\Models\User::create(['name'=>'Admin','email'=>'admin@shoppa.co.ke','password'=>bcrypt('password'),'role_id'=>$role->id])->markEmailAsVerified();
```

---

## Folder structure — domain-first, not framework-first

```
app/
├── Actions/Fortify/          # Fortify lifecycle hooks (CreateNewUser assigns Buyer role)
├── Enums/                    # RoleName, PermissionName — single source of truth for all strings
├── Http/
│   ├── Controllers/
│   │   ├── Admin/            # Staff panel: Dashboard, User, Vendor, Product, Dispute
│   │   ├── Buyer/            # Buyer: Dashboard, Browse, Order
│   │   ├── Vendor/           # Seller: Application, Dashboard, Listing
│   │   ├── Verifier/         # Inspection lab: Queue, Inspection
│   │   └── Shared/           # All roles: Profile
│   ├── Middleware/            # CheckRole, CheckPermission
│   └── Requests/             # Grouped by domain: Admin/, Auth/, Vendor/
├── Models/                   # All 26 Eloquent models (see schema section below)
├── Providers/
│   ├── AppServiceProvider    # Blade components, directives (@role, @permission, @staff), view composer
│   └── AuthServiceProvider   # Fortify hooks, Gates, role-aware post-login redirect
├── Services/
│   └── Auth/RoleAssignmentService  # Assigns roles, caches lookups — use this, not direct DB writes
└── View/Components/
    ├── Card/   StatCard
    ├── Form/   Field
    ├── Nav/    Icon, Sidebar, Topbar
    ├── Trust/  CertBadge, VerifiedPill    ← Shoppa-specific, always use these for verification state
    └── Ui/     Alert, Badge, Button

resources/views/
├── components/               # Blade views for all View\Components above
├── layouts/
│   ├── guest.blade.php       # Auth pages (login, register, etc.)
│   ├── app.blade.php         # Authenticated buyers
│   └── dashboard.blade.php   # Staff panel (Admin, Verifier, CS, etc.)
├── pages/
│   ├── auth/                 # login, register, forgot-password, reset-password, verify-email
│   ├── buyer/                # dashboard, browse, product-show, orders/index, orders/show
│   ├── vendor/               # dashboard, apply, listings/{index,create,show,edit}
│   ├── admin/                # dashboard, users/{index,create,show,edit}, vendors/{index,show},
│   │                         # products/index, disputes/index
│   ├── verifier/             # queue, inspect
│   └── shared/               # profile (role-agnostic, uses x-dynamic-component)
└── partials/
    └── flash-message         # Alpine auto-dismiss, maps success/error/warning/info session keys

routes/
├── web.php       # Entry point — requires all sub-files
├── auth.php      # Fortify routes + email verify notice
├── shared.php    # Profile routes — accessible by every role
├── buyer.php     # prefix: /  (dashboard, browse, orders)
├── vendor.php    # prefix: /vendor
├── admin.php     # prefix: /admin
└── verifier.php  # prefix: /verifier + public /verify/{identifier}

database/
├── migrations/   # 10 files — run in numeric order, fully cover all 26 models
└── seeders/      # RoleSeeder → PermissionSeeder → RolePermissionSeeder (always in this order)
```

---

## Roles and permissions

### The role enum

All role strings live in `App\Enums\RoleName`. Never use raw strings for roles in application code.

```php
RoleName::SuperAdmin      // 'Super Admin'  — bypasses all Gates and permission checks
RoleName::Admin           // 'Admin'
RoleName::VendorManager   // 'Vendor Manager'
RoleName::Verifier        // 'Verifier'     — the only role that can issue Trust Certificates
RoleName::CustomerService // 'Customer Service'
RoleName::ContentManager  // 'Content Manager'
RoleName::Vendor          // 'Vendor'       — sellers
RoleName::User            // 'User'         — buyers (public registration assigns this)
RoleName::Guest           // 'Guest'        — browse-only
```

### Checking roles in code

```php
// In controllers / service classes
$user->hasRole(RoleName::Admin);
$user->hasAnyRole(RoleName::Admin, RoleName::SuperAdmin);
$user->isStaff();   // true for Admin, VendorManager, Verifier, CS, ContentManager, SuperAdmin
$user->roleName();  // returns ?RoleName enum

// In Blade templates
@role('Admin', 'Super Admin')
    <x-ui-button>Admin action</x-ui-button>
@endrole

@permission('verify_devices')
    <x-ui-button>Issue certificate</x-ui-button>
@endpermission

@staff
    {{-- Only visible to internal staff --}}
@endstaff
```

### Checking roles in routes

```php
// Single role
Route::get('/admin', ...)->middleware('role:Admin');

// Multiple roles — comma-separated, no spaces inside role names with spaces
Route::get('/admin', ...)->middleware('role:Super Admin,Admin,Vendor Manager');

// Permission-based
Route::post('/verify', ...)->middleware('permission:verify_devices');
```

### The permission matrix (summary)

| Role | Key permissions |
|---|---|
| Super Admin | `*` all permissions, bypasses Gates |
| Admin | All except `verify_devices`, `issue_certs` |
| Vendor Manager | `view/create/edit/approve_vendors`, `view/edit/manage_orders` |
| Verifier | `verify_devices`, `issue_certs`, `manage_theft_reports` |
| Customer Service | `view_customer_data`, `manage_support_tickets`, `manage_disputes`, `manage_refunds` |
| Content Manager | `view/create/edit_products`, `manage_categories`, `content_manage` |
| Vendor | `view/create/edit/delete_products`, `view/manage_orders` |
| User (Buyer) | `view_products` |
| Guest | `view_products` |

Full matrix is in `database/seeders/RolePermissionSeeder.php`. **Edit the seeder, not the database directly.** Re-run with `php artisan db:seed --class=RolePermissionSeeder` (it truncates before inserting).

---

## The Trust Engine — the heart of Shoppa

The verification pipeline is the core product. It has its own status state machine:

```
pending → in_review → verified
                   ↘ rejected
```

### Key rules

1. **An IMEI must be unique across all active listings.** The `imei` column has a unique constraint. Attempting to create a second listing with the same IMEI means it is either a duplicate or a bait-and-switch attempt — reject it.

2. **Only the `Verifier` role can call `certify` or `reject`.** The `InspectionController` gates on `role:Super Admin,Admin,Verifier`. The cert UUID is generated server-side; it cannot be set by a vendor or buyer.

3. **Every status transition must be logged.** Use `activity()->causedBy($user)->performedOn($product)->log(...)`. The `verifier_id` FK on `products` records who certified each device. This is an audit requirement, not optional.

4. **A device flagged in `theft_reports` must be blocked from listing.** The IMEI blacklist check (Sprint 4) runs before the listing is saved.

5. **Trust Certificates expire after `config('shoppa.trust_cert.valid_days')` days** (default 90). After expiry the device needs re-inspection if re-listed.

### Trust components in Blade

Always use these for displaying verification state — never roll your own:

```blade
{{-- Small pill (brand mark, headers) --}}
<x-trust-verified-pill size="sm" />
<x-trust-verified-pill size="lg">Shoppa Verified</x-trust-verified-pill>

{{-- Full status badge with cert ID (product listings, inspection pages) --}}
<x-trust-cert-badge
    status="verified"
    cert-id="{{ $product->trust_cert_uuid }}"
    issued-at="{{ $product->cert_issued_at?->toDateString() }}"
/>

{{-- Other statuses: 'pending' | 'in_review' | 'rejected' | 'unverified' --}}
<x-trust-cert-badge status="pending" />
```

---

## Database schema

### Migration run order

```
000001  roles, permissions, role_permission
000002  users (FK → roles), password_reset_tokens, sessions
000003  user_addresses
000004  vendors, vendor_settings, vendor_reviews
000005  product_categories, product_statuses, product_attributes, product_tags,
        products (trust fields here), product_variants, product_reviews,
        product_tag pivot, browsing_histories, wishlists
000006  order_statuses, orders, order_items,
        order_shipment_statuses, order_shipments,
        order_return_statuses, order_returns,
        order_refund_statuses, order_refunds
000007  payment_methods, payments, escrow_transactions, coupons
000008  commissions, vendor_earnings, vendor_payments
000009  carts, cart_items
000010  activity_log (Spatie), media (Spatie), jobs, job_batches, failed_jobs, cache, cache_locks
```

### Key model relationships

```php
User        → belongsTo Role
            → hasOne Vendor
            → hasMany Order, UserAddress

Vendor      → belongsTo User
            → hasMany Product, VendorEarning, VendorPayment, VendorReview, VendorSetting

Product     → belongsTo Vendor, ProductCategory, ProductStatus, User (verifier)
            → hasMany ProductVariant, ProductReview, OrderItem
            → belongsToMany ProductTag
            // Trust fields: imei, serial_number, verification_status, trust_cert_uuid,
            //               cert_issued_at, verifier_id, condition_grade, battery_health

Order       → belongsTo User, OrderStatus
            → hasMany OrderItem
            → hasOne OrderShipment, Payment

OrderReturn → belongsTo OrderItem, OrderReturnStatus
            → hasOne OrderRefund

Payment     → belongsTo Order, User, PaymentMethod
            // Escrow lives in escrow_transactions linked to payments
```

### Vendor status values

`pending` → `approved` → (optionally) `suspended`
`pending` → `rejected`

Only Admin / Vendor Manager can approve or reject. Approving a vendor does **not** automatically promote the user's role to `Vendor` — that is done explicitly via `RoleAssignmentService::promoteToVendor()`.

### Product verification_status values

`pending` | `in_review` | `verified` | `rejected`

### Order status values (seeded in Sprint 3)

`pending` | `processing` | `shipped` | `delivered` | `completed` | `cancelled` | `disputed`

---

## Blade component reference

All components are registered with hyphenated aliases in `AppServiceProvider`. Use these — never the `x-App.View.Components.*` form.

| Tag | Class | Purpose |
|---|---|---|
| `<x-ui-button>` | `Ui\Button` | variant: `primary`/`secondary`/`danger`/`ghost`. size: `sm`/`md`/`lg`. Supports `:loading` |
| `<x-ui-badge>` | `Ui\Badge` | color: `stone`/`emerald`/`amber`/`red`/`blue`/`purple`. size: `xs`/`sm`/`md` |
| `<x-ui-alert>` | `Ui\Alert` | type: `info`/`success`/`warning`/`error` |
| `<x-form-field>` | `Form\Field` | Wraps label + input/textarea + inline error. Always use for forms |
| `<x-nav-icon>` | `Nav\Icon` | name: any key in `Icon::resolvePath()`. Self-contained SVG, no CDN |
| `<x-nav-sidebar>` | `Nav\Sidebar` | Builds nav items from authenticated user's role automatically |
| `<x-nav-topbar>` | `Nav\Topbar` | Sticky header with mobile hamburger, notifications bell, role badge, user dropdown |
| `<x-trust-verified-pill>` | `Trust\VerifiedPill` | size: `sm`/`md`/`lg` |
| `<x-trust-cert-badge>` | `Trust\CertBadge` | status + optional cert-id + issued-at |
| `<x-card-stat-card>` | `Card\StatCard` | Dashboard metric tile — label, value, icon, optional trend |

### Adding a new icon

Edit `app/View/Components/Nav/Icon.php`, add a new case to the `match` in `resolvePath()` with the Heroicons path data. No other change needed.

### Adding a new component

1. Create `app/View/Components/{Group}/MyComponent.php`
2. Create `resources/views/components/{group}/my-component.blade.php`
3. Register in `AppServiceProvider::registerComponents()`: `Blade::component('group-my-component', MyComponent::class);`

---

## Business rules encoded in config

`config/shoppa.php` — edit here, not in application code:

```php
'escrow'       => ['release_after_days' => 3]          // Days before auto-release
'verification' => ['fee_min_ksh' => 700, 'fee_max_ksh' => 1000]
'commission'   => ['default_percent' => 5]
'trust_cert'   => ['valid_days' => 90]
```

Access in code: `config('shoppa.escrow.release_after_days')`
Access in Blade: `config('shoppa.verification.fee_min_ksh')`

---

## Sprint roadmap

The codebase was built in 6 sprints. Controllers and views are stubbed for future sprints with `abort(501, 'Implemented in Sprint N.')`. Do not remove these stubs — replace them when the sprint starts.

| Sprint | Focus | Status |
|---|---|---|
| S1 | IAM, roles, permissions, auth pages, all layouts, base components | ✅ Complete |
| S2 | Vendor lifecycle, KYC uploads (Spatie Media), trust scoring job | 🔲 Next |
| S3 | Product catalog, dynamic attributes, device PIM, search | 🔲 |
| S4 | Trust Engine — IMEI validation, inspection workflow, cert generation, theft registry | 🔲 |
| S5 | Buyer journey — cart, orders, compare, wishlist, escrow confirm | 🔲 |
| S6 | Payments (M-Pesa Daraja), escrow state machine, disputes, vendor payouts | 🔲 |

---

## Conventions and rules

### PHP / Laravel

- **Use enums for all status strings and role names.** `RoleName::Admin`, not `'Admin'`. `OrderReturnStatus::APPROVED`, not `'approved'`.
- **Never query roles by raw string outside of seeders.** Use `$user->hasRole(RoleName::X)`.
- **All role assignments go through `RoleAssignmentService`.** It caches the role lookup and is the single place to change if the role table structure changes.
- **Every mutating controller action must log to activity_log** using `activity()->causedBy()->performedOn()->log()`.
- **Controllers stay thin.** Business logic belongs in Service classes under `app/Services/`. Controllers validate, call services, return views or redirects.
- **Use `ilike` for case-insensitive search** (PostgreSQL — `like` is case-sensitive in Postgres).
- **Use `abort_if()` for inline authorisation** inside controllers that don't use policies yet.
- **Soft deletes on User, Vendor, Product, Order.** Never hard-delete these. All queries on these models should use `withTrashed()` consciously when needed.

### Blade / frontend

- **Always use `<x-form-field>` for form inputs.** It handles label, `old()`, and inline validation errors consistently.
- **Never use `<form>` submit to navigate — use `method="POST"` with `@csrf` and `@method('PUT'/'DELETE')` for non-GET/POST.** Blade forms only, no JavaScript form submissions.
- **Flash messages use session keys: `success`, `error`, `warning`, `info`.**  `return redirect()->with('success', 'Done.')` — the `flash-message` partial renders and auto-dismisses them.
- **Layouts**: use `x-layouts-guest` for unauthenticated pages, `x-layouts-app` for buyers, `x-layouts-dashboard` for staff. The `shared/profile` page uses `x-dynamic-component` to pick the right layout by role.
- **Never inline Tailwind strings for status colours.** Use `<x-ui-badge :color="$color">` where `$color` is resolved in a `@php` block or controller.

### Database

- **Migrations are numbered `0001_01_01_00000N_*.php` and must run in order.** Foreign key constraints require this order: roles/permissions → users → vendors → products → orders → payments → carts → activity/media.
- **Never add columns to existing migrations.** Create a new migration: `php artisan make:migration add_X_to_Y_table`.
- **Seeders are idempotent** (`firstOrCreate` for roles/permissions). The `RolePermissionSeeder` truncates before inserting — safe to re-run.

---

## Environment variables needed

```env
APP_NAME=Shoppa
APP_ENV=local
APP_KEY=

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=shoppa
DB_USERNAME=postgres
DB_PASSWORD=

CACHE_DRIVER=redis
QUEUE_CONNECTION=database
SESSION_DRIVER=database

MAIL_MAILER=smtp
MAIL_FROM_ADDRESS=noreply@shoppa.co.ke
MAIL_FROM_NAME=Shoppa

# Sprint 6
MPESA_CONSUMER_KEY=
MPESA_CONSUMER_SECRET=
MPESA_SHORTCODE=
MPESA_PASSKEY=
MPESA_CALLBACK_URL=

# Spatie Media / S3
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=af-south-1
AWS_BUCKET=shoppa-media
MEDIA_DISK=s3
```

---

## Common tasks

### Add a new staff role

1. Add a case to `App\Enums\RoleName`
2. Add `dashboardRoute()` and `label()` entries in the same enum
3. Decide if it is staff — add to `staffRoles()` if yes
4. Add its permission set to `RolePermissionSeeder::matrix()`
5. Add its nav items to `Nav\Sidebar`'s `buildNav()` match
6. Run `php artisan db:seed --class=RoleSeeder && php artisan db:seed --class=RolePermissionSeeder`

### Add a new permission

1. Add a case to `App\Enums\PermissionName`
2. Add it to the relevant role(s) in `RolePermissionSeeder::matrix()`
3. Run `php artisan db:seed --class=PermissionSeeder && php artisan db:seed --class=RolePermissionSeeder`

### Add a new page

1. Create the controller in the correct domain folder (`Admin/`, `Buyer/`, etc.)
2. Add the route to the correct route file (`admin.php`, `buyer.php`, etc.) with appropriate middleware
3. Create the Blade view in `resources/views/pages/{domain}/`
4. Use the correct layout (`x-layouts-app`, `x-layouts-dashboard`, or `x-layouts-guest`)
5. Add a nav item to `Nav\Sidebar` if it belongs in the sidebar

### Re-seed the database cleanly

```bash
php artisan migrate:fresh --seed
```

This drops all tables, re-runs migrations in order, and seeds roles/permissions. Safe in development only.

---

## What not to do

- **Do not use `App\Models\Role::where('name', 'Admin')` directly in application code.** Always go through `RoleName` enum and `RoleAssignmentService`.
- **Do not create Blade components without registering them in `AppServiceProvider`.** Anonymous components from `resources/views/components/` work, but named class-based components need explicit registration.
- **Do not add business logic to migrations.** Migrations are schema only.
- **Do not skip the activity log for verification events.** Every `certify`, `reject`, `revoke`, and `approve_vendor` action must be logged with the acting user's ID. This is a legal audit trail.
- **Do not use `localStorage` or `sessionStorage`.** All state lives server-side (sessions, database) or in Alpine.js reactive data (`x-data`). There is no SPA.
- **Do not hardcode KSh amounts or percentages.** Use `config('shoppa.*')` so business rules change in one place.

---

## Git conventions — HARD REQUIREMENTS

- **Always commit as:** `git -c user.name="Bahati" -c user.email="baha.dev@outlook.com" commit ...`
- **Never add a `Co-Authored-By` trailer** to any commit message. No Claude attribution lines of any kind.
- These two rules apply to every commit in this repository without exception.
