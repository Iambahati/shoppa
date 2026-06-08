# Shoppa S1 Completion: Dashboards, Tests & Deployment — Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use `superpowers:subagent-driven-development` (recommended) or `superpowers:executing-plans` to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Complete Sprint S1 by fixing 5 confirmed bugs, building production-grade dashboards for all 9 roles with a navy/sky/blush design system, delivering a full auth + feature test suite, and wiring up Docker + GitHub Actions CI/CD for a single bare-metal server (staging on `develop`, prod on `main`).

**Architecture:** Laravel 11 Blade-first app with Fortify auth and role-based routing via `CheckRole` middleware. Dashboards are server-rendered Blade views under `x-layouts.dashboard` (staff) or `x-layouts.app` (vendor/buyer). Tests use SQLite in-memory + RefreshDatabase + role seeders. Deployment uses two Docker Compose override files targeting the same server, one per environment, deployed via SSH + git pull.

**Tech Stack:** PHP 8.2, Laravel 11, Fortify, Blade, Alpine.js, Tailwind CSS, Vite, PostgreSQL 16, Redis 7, Docker, GitHub Actions

---

## File Map

### Modified
- `resources/views/pages/admin/dashboard.blade.php` — fix layout + full redesign
- `app/Http/Controllers/Vendor/DashboardController.php` — fix view path from `pages.vendor.dashboard` → `pages.vendors.dashboard`
- `app/Actions/Fortify/CreateNewUser.php` — remove unused Jetstream import
- `app/Enums/RoleName.php` — update `dashboardRoute()` for 4 staff roles + fix `staffRoles()` includes
- `app/View/Components/Card/StatCard.php` — already has trend props, no change needed
- `resources/views/components/card/stat-card.blade.php` — redesign with editorial numerals
- `resources/views/components/nav/_sidebar-inner.blade.php` — sky-500 left border for active state
- `resources/views/components/nav/sidebar.blade.php` — change `bg-stone-900` → `bg-slate-900`
- `app/View/Components/Nav/Sidebar.php` — update 4 staff nav methods to use new dashboard routes
- `resources/views/pages/vendors/dashboard.blade.php` — full redesign
- `resources/views/pages/buyer/dashboard.blade.php` — full redesign
- `routes/web.php` — add health check route
- `routes/admin.php` — add VendorManager, CS, ContentManager dashboard routes
- `routes/verifier.php` — add Verifier dashboard route
- `database/factories/UserFactory.php` — add Admin, VendorManager, Verifier, CS, ContentManager states
- `tests/TestCase.php` — add `seedRoles()` helper
- `.env.example` — add Redis, queue, Docker vars

### Deleted
- `resources/views/pages/vendor/DashboardController.php` — PHP file misplaced in views

### Renamed
- `resources/views/pages/buyer/browse.php` → `resources/views/pages/buyer/browse.blade.php`

### Created
- `app/Http/Controllers/Admin/VendorManagerDashboardController.php`
- `app/Http/Controllers/Admin/CustomerServiceDashboardController.php`
- `app/Http/Controllers/Admin/ContentManagerDashboardController.php`
- `app/Http/Controllers/Verifier/DashboardController.php`
- `resources/views/pages/admin/vendor-manager/dashboard.blade.php`
- `resources/views/pages/admin/cs/dashboard.blade.php`
- `resources/views/pages/admin/content/dashboard.blade.php`
- `resources/views/pages/verifier/dashboard.blade.php`
- `tests/Feature/Auth/LoginTest.php`
- `tests/Feature/Auth/RegisterTest.php`
- `tests/Feature/Auth/PasswordResetTest.php`
- `tests/Feature/Auth/EmailVerificationTest.php`
- `tests/Feature/Auth/RoleRedirectTest.php`
- `tests/Feature/Dashboard/AdminDashboardTest.php`
- `tests/Feature/Dashboard/VendorDashboardTest.php`
- `tests/Feature/Dashboard/BuyerDashboardTest.php`
- `tests/Feature/Dashboard/StaffDashboardTest.php`
- `tests/Feature/Admin/UserCrudTest.php`
- `Dockerfile`
- `docker-compose.yml`
- `docker-compose.staging.yml`
- `docker-compose.prod.yml`
- `docker/nginx/staging.conf`
- `docker/nginx/prod.conf`
- `docker/php/php.ini`
- `.github/workflows/ci.yml`
- `.github/workflows/deploy-staging.yml`
- `.github/workflows/deploy-prod.yml`

---

## Task 1: Fix 5 S1 Bugs

**Files:**
- Modify: `resources/views/pages/admin/dashboard.blade.php:1`
- Modify: `app/Http/Controllers/Vendor/DashboardController.php:17`
- Delete: `resources/views/pages/vendor/DashboardController.php`
- Rename: `resources/views/pages/buyer/browse.php` → `browse.blade.php`
- Modify: `app/Actions/Fortify/CreateNewUser.php:7`

- [ ] **Step 1: Fix admin dashboard layout**

In `resources/views/pages/admin/dashboard.blade.php`, change line 1:
```diff
-<x-layouts.app>
+<x-layouts.dashboard>
```
And the closing tag at the end of the file:
```diff
-</x-layouts.app>
+</x-layouts.dashboard>
```

- [ ] **Step 2: Fix vendor dashboard controller view path**

In `app/Http/Controllers/Vendor/DashboardController.php`, change the view call (both the old and new `DashboardController` variants — there are two files for vendor dashboard, keep only the one in `app/Http/Controllers/Vendor/`):
```php
return view('pages.vendors.dashboard', compact('user', 'vendor', 'stats', 'recentListings'));
```

- [ ] **Step 3: Delete misplaced PHP file**

```bash
rm resources/views/pages/vendor/DashboardController.php
```

- [ ] **Step 4: Rename browse view**

```bash
mv resources/views/pages/buyer/browse.php resources/views/pages/buyer/browse.blade.php
```

- [ ] **Step 5: Remove Jetstream import from CreateNewUser**

In `app/Actions/Fortify/CreateNewUser.php`, remove this line:
```diff
-use Laravel\Jetstream\Jetstream;
```

- [ ] **Step 6: Verify app still boots**

```bash
php artisan route:list --compact 2>&1 | head -20
```
Expected: routes list prints without errors.

- [ ] **Step 7: Commit**

```bash
git -c user.name="Bahati" -c user.email="baha.dev@outlook.com" commit -m "fix: resolve 5 S1 bugs — layout, view paths, misplaced files, import"
```

---

## Task 2: Health Check Route

**Files:**
- Modify: `routes/web.php`

- [ ] **Step 1: Add health route**

In `routes/web.php`, after the home redirect line:
```php
Route::get('/health', fn () => response()->json(['status' => 'ok']))->name('health');
```

- [ ] **Step 2: Verify route is accessible**

```bash
php artisan route:list --name=health
```
Expected: shows `GET /health` with name `health`, no middleware.

- [ ] **Step 3: Commit**

```bash
git -c user.name="Bahati" -c user.email="baha.dev@outlook.com" commit -m "feat: add GET /health endpoint for deployment checks"
```

---

## Task 3: New Staff Dashboard Controllers

**Files:**
- Create: `app/Http/Controllers/Admin/VendorManagerDashboardController.php`
- Create: `app/Http/Controllers/Admin/CustomerServiceDashboardController.php`
- Create: `app/Http/Controllers/Admin/ContentManagerDashboardController.php`
- Create: `app/Http/Controllers/Verifier/DashboardController.php`

- [ ] **Step 1: Create VendorManager dashboard controller**

Create `app/Http/Controllers/Admin/VendorManagerDashboardController.php`:
```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VendorManagerDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $stats = [
            'pending_applications' => 0,
            'active_vendors'       => 0,
            'suspended_vendors'    => 0,
            'approvals_this_week'  => 0,
        ];

        $pendingVendors = collect();

        return view('pages.admin.vendor-manager.dashboard', compact('stats', 'pendingVendors'));
    }
}
```

- [ ] **Step 2: Create CustomerService dashboard controller**

Create `app/Http/Controllers/Admin/CustomerServiceDashboardController.php`:
```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerServiceDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $stats = [
            'open_disputes'    => 0,
            'resolved_today'   => 0,
            'pending_refunds'  => 0,
            'avg_resolution'   => '—',
        ];

        $openDisputes = collect();

        return view('pages.admin.cs.dashboard', compact('stats', 'openDisputes'));
    }
}
```

- [ ] **Step 3: Create ContentManager dashboard controller**

Create `app/Http/Controllers/Admin/ContentManagerDashboardController.php`:
```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContentManagerDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $stats = [
            'total_products'  => 0,
            'pending_review'  => 0,
            'published_today' => 0,
            'categories'      => 0,
        ];

        $recentProducts = collect();

        return view('pages.admin.content.dashboard', compact('stats', 'recentProducts'));
    }
}
```

- [ ] **Step 4: Create Verifier dashboard controller**

Create `app/Http/Controllers/Verifier/DashboardController.php`:
```php
<?php

namespace App\Http\Controllers\Verifier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $stats = [
            'queue_depth'      => 0,
            'certified_today'  => 0,
            'rejected_today'   => 0,
            'avg_inspect_time' => '—',
        ];

        $topQueue = collect();

        return view('pages.verifier.dashboard', compact('stats', 'topQueue'));
    }
}
```

- [ ] **Step 5: Commit**

```bash
git -c user.name="Bahati" -c user.email="baha.dev@outlook.com" commit -m "feat: add staff dashboard controllers for VendorManager, CS, ContentManager, Verifier"
```

---

## Task 4: Add Staff Routes + Update dashboardRoute() + Update Sidebar Nav

**Files:**
- Modify: `routes/admin.php`
- Modify: `routes/verifier.php`
- Modify: `app/Enums/RoleName.php`
- Modify: `app/View/Components/Nav/Sidebar.php`

- [ ] **Step 1: Add three new routes to routes/admin.php**

Add these three route groups inside the existing `Route::prefix('admin')->name('admin.')` group in `routes/admin.php`, after the disputes group:

```php
use App\Http\Controllers\Admin\VendorManagerDashboardController;
use App\Http\Controllers\Admin\CustomerServiceDashboardController;
use App\Http\Controllers\Admin\ContentManagerDashboardController;

// Vendor Manager dashboard
Route::prefix('vendor-manager')->name('vendor-manager.')->middleware('role:Super Admin,Vendor Manager')->group(function () {
    Route::get('/dashboard', [VendorManagerDashboardController::class, 'index'])->name('dashboard');
});

// Customer Service dashboard
Route::prefix('cs')->name('cs.')->middleware('role:Super Admin,Admin,Customer Service')->group(function () {
    Route::get('/dashboard', [CustomerServiceDashboardController::class, 'index'])->name('dashboard');
});

// Content Manager dashboard
Route::prefix('content')->name('content.')->middleware('role:Super Admin,Admin,Content Manager')->group(function () {
    Route::get('/dashboard', [ContentManagerDashboardController::class, 'index'])->name('dashboard');
});
```

Route names produced: `admin.vendor-manager.dashboard`, `admin.cs.dashboard`, `admin.content.dashboard`.

- [ ] **Step 2: Add Verifier dashboard route to routes/verifier.php**

Add inside the existing `Route::prefix('verifier')->name('verifier.')` group, before the queue route:

```php
use App\Http\Controllers\Verifier\DashboardController as VerifierDashboardController;

Route::get('/dashboard', [VerifierDashboardController::class, 'index'])->name('dashboard');
```

Route name: `verifier.dashboard`.

- [ ] **Step 3: Update RoleName::dashboardRoute()**

In `app/Enums/RoleName.php`, update the `dashboardRoute()` method:
```php
public function dashboardRoute(): string
{
    return match ($this) {
        self::SuperAdmin, self::Admin  => 'admin.dashboard',
        self::VendorManager           => 'admin.vendor-manager.dashboard',
        self::Verifier                => 'verifier.dashboard',
        self::CustomerService         => 'admin.cs.dashboard',
        self::ContentManager          => 'admin.content.dashboard',
        self::Vendor                  => 'vendor.dashboard',
        self::User, self::Guest       => 'buyer.dashboard',
    };
}
```

- [ ] **Step 4: Update Sidebar.php nav methods**

In `app/View/Components/Nav/Sidebar.php`, update the four staff nav methods:

```php
private function vendorManagerNav(): array
{
    return [
        ['label' => 'Dashboard',    'route' => 'admin.vendor-manager.dashboard', 'icon' => 'home',  'active' => 'admin.vendor-manager.*'],
        ['label' => 'Vendors',      'route' => 'admin.vendors.index',            'icon' => 'store', 'active' => 'admin.vendors.*'],
    ];
}

private function verifierNav(): array
{
    return [
        ['label' => 'Dashboard',    'route' => 'verifier.dashboard',             'icon' => 'home',   'active' => 'verifier.dashboard'],
        ['label' => 'Inspect queue','route' => 'verifier.queue',                 'icon' => 'shield', 'active' => 'verifier.queue'],
    ];
}

private function customerServiceNav(): array
{
    return [
        ['label' => 'Dashboard',    'route' => 'admin.cs.dashboard',             'icon' => 'home',       'active' => 'admin.cs.*'],
        ['label' => 'Disputes',     'route' => 'admin.disputes.index',           'icon' => 'message-sq', 'active' => 'admin.disputes.*'],
    ];
}

private function contentManagerNav(): array
{
    return [
        ['label' => 'Dashboard',    'route' => 'admin.content.dashboard',        'icon' => 'home',    'active' => 'admin.content.*'],
        ['label' => 'Products',     'route' => 'admin.products.index',           'icon' => 'package', 'active' => 'admin.products.*'],
    ];
}
```

- [ ] **Step 5: Verify all new routes resolve**

```bash
php artisan route:list --name=admin.vendor-manager --name=admin.cs --name=admin.content --name=verifier.dashboard
```
Expected: four routes listed, all with correct middleware.

- [ ] **Step 6: Commit**

```bash
git -c user.name="Bahati" -c user.email="baha.dev@outlook.com" commit -m "feat: add staff dashboard routes, update dashboardRoute() and sidebar nav"
```

---

## Task 5: Redesign StatCard + Update Sidebar Active State & Background

**Files:**
- Modify: `resources/views/components/card/stat-card.blade.php`
- Modify: `resources/views/components/nav/_sidebar-inner.blade.php`
- Modify: `resources/views/components/nav/sidebar.blade.php`

- [ ] **Step 1: Redesign stat-card.blade.php with editorial numerals**

Replace the entire contents of `resources/views/components/card/stat-card.blade.php`:
```blade
@php
    $iconBg = match($iconColor) {
        'blue'    => 'bg-sky-50 text-sky-600',
        'amber'   => 'bg-amber-50 text-amber-600',
        'red'     => 'bg-red-50 text-red-600',
        'purple'  => 'bg-purple-50 text-purple-600',
        'emerald' => 'bg-emerald-50 text-emerald-600',
        default   => 'bg-slate-50 text-slate-600',
    };
    $trendColor = match($trendDir) {
        'down'    => 'text-red-500',
        'neutral' => 'text-slate-400',
        default   => 'text-emerald-600',
    };
    $trendArrow = match($trendDir) {
        'down'    => '↓',
        'neutral' => '—',
        default   => '↑',
    };
@endphp

<div {{ $attributes->merge(['class' => 'relative overflow-hidden rounded-2xl bg-white ring-1 ring-slate-900/5 shadow-sm p-6']) }}>
    <div class="flex items-start justify-between gap-4">
        <div class="min-w-0">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest">{{ $label }}</p>
            <p class="mt-2 text-4xl font-bold text-slate-900 tabular-nums leading-none">{{ $value }}</p>
            @if($trend)
                <p class="mt-2 flex items-center gap-1 text-xs font-semibold {{ $trendColor }}">
                    {{ $trendArrow }} {{ $trend }}
                </p>
            @endif
        </div>
        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl {{ $iconBg }}">
            <x-nav-icon :name="$icon" class="h-5 w-5" />
        </span>
    </div>
</div>
```

- [ ] **Step 2: Update sidebar background from stone to slate**

In `resources/views/components/nav/sidebar.blade.php`, replace both `bg-stone-900` instances with `bg-slate-900`:
```diff
-class="fixed inset-y-0 left-0 z-50 w-64 bg-stone-900 lg:hidden"
+class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 lg:hidden"
```
```diff
-class="hidden lg:fixed lg:inset-y-0 lg:left-0 lg:z-30 lg:flex lg:w-64 lg:flex-col bg-stone-900">
+class="hidden lg:fixed lg:inset-y-0 lg:left-0 lg:z-30 lg:flex lg:w-64 lg:flex-col bg-slate-900">
```

- [ ] **Step 3: Update sidebar active state to sky-500 left border**

In `resources/views/components/nav/_sidebar-inner.blade.php`, replace the nav link `@class` block:
```diff
-'bg-stone-800 text-white'                            => $active,
-'text-stone-400 hover:bg-stone-800 hover:text-white' => !$active,
+'bg-white/10 text-white border-l-2 border-sky-500 rounded-l-none pl-[calc(0.75rem-2px)]' => $active,
+'text-slate-400 hover:bg-white/5 hover:text-white rounded-lg'                            => !$active,
```

Also update the border-t separator and user footer colors in `_sidebar-inner.blade.php`:
```diff
-<div class="mt-auto border-t border-stone-800 pt-4">
+<div class="mt-auto border-t border-slate-800 pt-4">
```
```diff
-<p class="truncate text-xs text-stone-400">
+<p class="truncate text-xs text-slate-400">
```
```diff
-class="text-xs text-stone-500 hover:text-stone-300 transition-colors"
+class="text-xs text-slate-500 hover:text-slate-300 transition-colors"
```

- [ ] **Step 4: Commit**

```bash
git -c user.name="Bahati" -c user.email="baha.dev@outlook.com" commit -m "feat: redesign StatCard with editorial numerals, update sidebar to slate-900 + sky active state"
```

---

## Task 6: Admin / SuperAdmin Dashboard View

**Files:**
- Modify: `resources/views/pages/admin/dashboard.blade.php`

> **Note for executor:** Use the `frontend-design` skill while implementing this view to ensure production-grade, distinctive output.

- [ ] **Step 1: Replace admin dashboard view**

Replace the entire contents of `resources/views/pages/admin/dashboard.blade.php`:
```blade
<x-layouts.dashboard>
    <x-slot:title>Dashboard</x-slot:title>

    {{-- Header --}}
    <div class="mb-8 flex items-start justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">
                Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }},
                {{ explode(' ', auth()->user()->name)[0] }}
            </h2>
            <p class="mt-1 text-sm text-slate-500">{{ now()->format('l, j F Y') }} · Admin panel</p>
        </div>
        @role('Super Admin')
        <span class="inline-flex items-center gap-1.5 rounded-full bg-blush-50 px-3 py-1 text-xs font-semibold text-pink-700 ring-1 ring-pink-200">
            <span class="h-1.5 w-1.5 rounded-full bg-pink-500"></span>
            Super Admin
        </span>
        @endrole
    </div>

    {{-- KPI tiles --}}
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        <x-card-stat-card label="Total users"     :value="number_format($stats['total_users'])"       icon="users"  icon-color="blue" />
        <x-card-stat-card label="Pending vendors" :value="(string) $stats['pending_vendor_apps']"     icon="store"  icon-color="amber" />
        <x-card-stat-card label="Orders today"    :value="(string) $stats['orders_today']"            icon="box"    icon-color="emerald" />
        <x-card-stat-card label="Open disputes"   :value="(string) $stats['disputes_open']"           icon="flag"   icon-color="red" />
    </div>

    {{-- Quick actions --}}
    <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-3">
        <a href="{{ route('admin.vendors.index') }}"
           class="group flex items-center gap-4 rounded-2xl bg-white ring-1 ring-slate-900/5 shadow-sm p-5 hover:ring-sky-400 transition-all duration-150">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                <x-nav-icon name="store" class="h-5 w-5" />
            </span>
            <div class="min-w-0">
                <p class="text-sm font-semibold text-slate-900">Vendor applications</p>
                <p class="text-xs text-slate-500 mt-0.5">Review pending sellers</p>
            </div>
            <x-nav-icon name="chevron-r" class="h-4 w-4 text-slate-300 group-hover:text-sky-500 ml-auto transition-colors" />
        </a>

        <a href="{{ route('verifier.queue') }}"
           class="group flex items-center gap-4 rounded-2xl bg-white ring-1 ring-slate-900/5 shadow-sm p-5 hover:ring-sky-400 transition-all duration-150">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-sky-50 text-sky-600">
                <x-nav-icon name="shield" class="h-5 w-5" />
            </span>
            <div class="min-w-0">
                <p class="text-sm font-semibold text-slate-900">Verification queue</p>
                <p class="text-xs text-slate-500 mt-0.5">Inspect pending devices</p>
            </div>
            <x-nav-icon name="chevron-r" class="h-4 w-4 text-slate-300 group-hover:text-sky-500 ml-auto transition-colors" />
        </a>

        <a href="{{ route('admin.disputes.index') }}"
           class="group flex items-center gap-4 rounded-2xl bg-white ring-1 ring-slate-900/5 shadow-sm p-5 hover:ring-sky-400 transition-all duration-150">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-red-50 text-red-600">
                <x-nav-icon name="flag" class="h-5 w-5" />
            </span>
            <div class="min-w-0">
                <p class="text-sm font-semibold text-slate-900">Open disputes</p>
                <p class="text-xs text-slate-500 mt-0.5">Manage escalations</p>
            </div>
            <x-nav-icon name="chevron-r" class="h-4 w-4 text-slate-300 group-hover:text-sky-500 ml-auto transition-colors" />
        </a>
    </div>

    {{-- Users table --}}
    <div class="rounded-2xl bg-white ring-1 ring-slate-900/5 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-slate-900">Recent registrations</h3>
            <a href="{{ route('admin.users.index') }}" class="text-xs font-medium text-sky-500 hover:text-sky-600 transition-colors">
                View all →
            </a>
        </div>
        <div class="px-6 py-12 text-center">
            <x-nav-icon name="users" class="mx-auto h-8 w-8 text-slate-200" />
            <p class="mt-3 text-sm text-slate-400">User activity loads in Sprint 2.</p>
        </div>
    </div>

</x-layouts.dashboard>
```

- [ ] **Step 2: Verify page renders (requires seeded DB)**

```bash
php artisan route:list --name=admin.dashboard
```
Expected: route listed. No PHP errors in the view file.

- [ ] **Step 3: Commit**

```bash
git -c user.name="Bahati" -c user.email="baha.dev@outlook.com" commit -m "feat: redesign admin dashboard — editorial KPIs, navy layout, quick actions"
```

---

## Task 7: VendorManager Dashboard View

**Files:**
- Create: `resources/views/pages/admin/vendor-manager/dashboard.blade.php`

- [ ] **Step 1: Create view directory and file**

```bash
mkdir -p resources/views/pages/admin/vendor-manager
```

Create `resources/views/pages/admin/vendor-manager/dashboard.blade.php`:
```blade
<x-layouts.dashboard>
    <x-slot:title>Vendor Manager</x-slot:title>

    <div class="mb-8 flex items-start justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">
                Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }},
                {{ explode(' ', auth()->user()->name)[0] }}
            </h2>
            <p class="mt-1 text-sm text-slate-500">{{ now()->format('l, j F Y') }} · Vendor operations</p>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        <x-card-stat-card label="Pending applications" :value="(string) $stats['pending_applications']" icon="store"   icon-color="amber" />
        <x-card-stat-card label="Active vendors"        :value="(string) $stats['active_vendors']"       icon="users"  icon-color="emerald" />
        <x-card-stat-card label="Suspended"             :value="(string) $stats['suspended_vendors']"    icon="flag"   icon-color="red" />
        <x-card-stat-card label="Approvals this week"   :value="(string) $stats['approvals_this_week']"  icon="check"  icon-color="blue" />
    </div>

    <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2">
        <a href="{{ route('admin.vendors.index') }}"
           class="group flex items-center gap-4 rounded-2xl bg-white ring-1 ring-slate-900/5 shadow-sm p-5 hover:ring-sky-400 transition-all duration-150">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                <x-nav-icon name="store" class="h-5 w-5" />
            </span>
            <div class="min-w-0">
                <p class="text-sm font-semibold text-slate-900">Review applications</p>
                <p class="text-xs text-slate-500 mt-0.5">Approve or reject pending sellers</p>
            </div>
            <x-nav-icon name="chevron-r" class="h-4 w-4 text-slate-300 group-hover:text-sky-500 ml-auto transition-colors" />
        </a>

        <a href="{{ route('admin.vendors.index') }}"
           class="group flex items-center gap-4 rounded-2xl bg-white ring-1 ring-slate-900/5 shadow-sm p-5 hover:ring-sky-400 transition-all duration-150">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-sky-50 text-sky-600">
                <x-nav-icon name="users" class="h-5 w-5" />
            </span>
            <div class="min-w-0">
                <p class="text-sm font-semibold text-slate-900">All vendors</p>
                <p class="text-xs text-slate-500 mt-0.5">View and manage active sellers</p>
            </div>
            <x-nav-icon name="chevron-r" class="h-4 w-4 text-slate-300 group-hover:text-sky-500 ml-auto transition-colors" />
        </a>
    </div>

    <div class="rounded-2xl bg-white ring-1 ring-slate-900/5 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100">
            <h3 class="text-sm font-semibold text-slate-900">Pending applications</h3>
        </div>
        @if($pendingVendors->isEmpty())
            <div class="px-6 py-12 text-center">
                <x-nav-icon name="store" class="mx-auto h-8 w-8 text-slate-200" />
                <p class="mt-3 text-sm text-slate-400">No pending applications. Live data arrives in Sprint 2.</p>
            </div>
        @else
            <ul class="divide-y divide-slate-100">
                @foreach($pendingVendors as $vendor)
                    <li class="px-6 py-4 flex items-center justify-between gap-4 hover:bg-slate-50 transition-colors">
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-slate-900 truncate">{{ $vendor->name }}</p>
                            <p class="text-xs text-slate-400 mt-0.5">Submitted {{ $vendor->created_at->diffForHumans() }}</p>
                        </div>
                        <a href="{{ route('admin.vendors.show', $vendor) }}"
                           class="text-xs font-medium text-sky-500 hover:text-sky-600 transition-colors shrink-0">Review →</a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

</x-layouts.dashboard>
```

- [ ] **Step 2: Commit**

```bash
git -c user.name="Bahati" -c user.email="baha.dev@outlook.com" commit -m "feat: add VendorManager dashboard view"
```

---

## Task 8: Verifier Dashboard View

**Files:**
- Create: `resources/views/pages/verifier/dashboard.blade.php`

- [ ] **Step 1: Create verifier dashboard view**

Create `resources/views/pages/verifier/dashboard.blade.php`:
```blade
<x-layouts.dashboard>
    <x-slot:title>Verifier</x-slot:title>

    <div class="mb-8 flex items-start justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">
                Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }},
                {{ explode(' ', auth()->user()->name)[0] }}
            </h2>
            <p class="mt-1 text-sm text-slate-500">{{ now()->format('l, j F Y') }} · Verification lab</p>
        </div>
        <a href="{{ route('verifier.queue') }}"
           class="inline-flex items-center gap-2 rounded-xl bg-sky-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-sky-600 transition-colors">
            <x-nav-icon name="shield" class="h-4 w-4" />
            Open queue
        </a>
    </div>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        <x-card-stat-card label="Queue depth"      :value="(string) $stats['queue_depth']"      icon="layers" icon-color="amber" />
        <x-card-stat-card label="Certified today"  :value="(string) $stats['certified_today']"  icon="shield" icon-color="emerald" />
        <x-card-stat-card label="Rejected today"   :value="(string) $stats['rejected_today']"   icon="flag"   icon-color="red" />
        <x-card-stat-card label="Avg inspect time" :value="$stats['avg_inspect_time']"           icon="cpu"    icon-color="blue" />
    </div>

    <div class="mb-8 rounded-2xl border border-sky-100 bg-sky-50 p-5 flex items-start gap-4">
        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-sky-100 text-sky-600 mt-0.5">
            <x-nav-icon name="shield" class="h-5 w-5" />
        </span>
        <div>
            <p class="text-sm font-semibold text-sky-900">Trust Certificates — full workflow in Sprint 4</p>
            <p class="mt-0.5 text-sm text-sky-700">
                The inspection form, IMEI validation, condition grading, and certificate issuance are implemented in Sprint 4. Use the queue link above to view pending devices.
            </p>
        </div>
    </div>

    <div class="rounded-2xl bg-white ring-1 ring-slate-900/5 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-slate-900">Oldest pending devices</h3>
            <a href="{{ route('verifier.queue') }}" class="text-xs font-medium text-sky-500 hover:text-sky-600 transition-colors">
                Full queue →
            </a>
        </div>
        @if($topQueue->isEmpty())
            <div class="px-6 py-12 text-center">
                <span class="flex h-12 w-12 mx-auto items-center justify-center rounded-full bg-emerald-50">
                    <x-nav-icon name="shield" class="h-6 w-6 text-emerald-500" />
                </span>
                <p class="mt-4 text-sm font-medium text-slate-700">Queue is clear</p>
                <p class="mt-1 text-sm text-slate-400">All submitted devices have been processed.</p>
            </div>
        @else
            <ul class="divide-y divide-slate-100">
                @foreach($topQueue as $product)
                    <li class="px-6 py-4 flex items-center justify-between gap-4 hover:bg-slate-50 transition-colors">
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-slate-900 truncate">{{ $product->name }}</p>
                            <p class="text-xs text-slate-400 mt-0.5 font-mono">{{ $product->imei ?? '—' }}</p>
                        </div>
                        <div class="flex items-center gap-3 shrink-0">
                            <x-trust-cert-badge :status="$product->verification_status" />
                            <a href="{{ route('verifier.inspections.show', $product) }}"
                               class="text-xs font-medium text-sky-500 hover:text-sky-600 transition-colors">Inspect →</a>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

</x-layouts.dashboard>
```

- [ ] **Step 2: Commit**

```bash
git -c user.name="Bahati" -c user.email="baha.dev@outlook.com" commit -m "feat: add Verifier dashboard view"
```

---

## Task 9: Customer Service Dashboard View

**Files:**
- Create: `resources/views/pages/admin/cs/dashboard.blade.php`

- [ ] **Step 1: Create directory and view**

```bash
mkdir -p resources/views/pages/admin/cs
```

Create `resources/views/pages/admin/cs/dashboard.blade.php`:
```blade
<x-layouts.dashboard>
    <x-slot:title>Customer Service</x-slot:title>

    <div class="mb-8">
        <h2 class="text-2xl font-bold text-slate-900">
            Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }},
            {{ explode(' ', auth()->user()->name)[0] }}
        </h2>
        <p class="mt-1 text-sm text-slate-500">{{ now()->format('l, j F Y') }} · Customer support</p>
    </div>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        <x-card-stat-card label="Open disputes"   :value="(string) $stats['open_disputes']"  icon="flag"       icon-color="red" />
        <x-card-stat-card label="Resolved today"  :value="(string) $stats['resolved_today']" icon="check"      icon-color="emerald" />
        <x-card-stat-card label="Pending refunds" :value="(string) $stats['pending_refunds']" icon="box"       icon-color="amber" />
        <x-card-stat-card label="Avg resolution"  :value="$stats['avg_resolution']"           icon="cpu"       icon-color="blue" />
    </div>

    <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2">
        <a href="{{ route('admin.disputes.index') }}"
           class="group flex items-center gap-4 rounded-2xl bg-white ring-1 ring-slate-900/5 shadow-sm p-5 hover:ring-sky-400 transition-all duration-150">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-red-50 text-red-600">
                <x-nav-icon name="flag" class="h-5 w-5" />
            </span>
            <div class="min-w-0">
                <p class="text-sm font-semibold text-slate-900">Manage disputes</p>
                <p class="text-xs text-slate-500 mt-0.5">Review and resolve buyer–seller conflicts</p>
            </div>
            <x-nav-icon name="chevron-r" class="h-4 w-4 text-slate-300 group-hover:text-sky-500 ml-auto transition-colors" />
        </a>

        <a href="{{ route('admin.disputes.index') }}"
           class="group flex items-center gap-4 rounded-2xl bg-white ring-1 ring-slate-900/5 shadow-sm p-5 hover:ring-sky-400 transition-all duration-150">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                <x-nav-icon name="box" class="h-5 w-5" />
            </span>
            <div class="min-w-0">
                <p class="text-sm font-semibold text-slate-900">Process refunds</p>
                <p class="text-xs text-slate-500 mt-0.5">Approve pending return refunds</p>
            </div>
            <x-nav-icon name="chevron-r" class="h-4 w-4 text-slate-300 group-hover:text-sky-500 ml-auto transition-colors" />
        </a>
    </div>

    <div class="rounded-2xl bg-white ring-1 ring-slate-900/5 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-slate-900">Open disputes</h3>
            <a href="{{ route('admin.disputes.index') }}" class="text-xs font-medium text-sky-500 hover:text-sky-600 transition-colors">
                View all →
            </a>
        </div>
        @if($openDisputes->isEmpty())
            <div class="px-6 py-12 text-center">
                <x-nav-icon name="flag" class="mx-auto h-8 w-8 text-slate-200" />
                <p class="mt-3 text-sm text-slate-400">No open disputes. Live data arrives in Sprint 6.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100">
                    <thead class="bg-slate-50">
                        <tr>
                            @foreach(['Order', 'Buyer', 'Reason', 'Age', ''] as $h)
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">{{ $h }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($openDisputes as $dispute)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 text-sm font-medium text-slate-900">#{{ $dispute->order_id }}</td>
                                <td class="px-6 py-4 text-sm text-slate-500">{{ $dispute->order?->user?->name }}</td>
                                <td class="px-6 py-4 text-sm text-slate-500 truncate max-w-xs">{{ $dispute->reason }}</td>
                                <td class="px-6 py-4 text-sm text-slate-400">{{ $dispute->created_at->diffForHumans() }}</td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.disputes.show', $dispute) }}"
                                       class="text-xs font-medium text-sky-500 hover:text-sky-600 transition-colors">Resolve →</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</x-layouts.dashboard>
```

- [ ] **Step 2: Commit**

```bash
git -c user.name="Bahati" -c user.email="baha.dev@outlook.com" commit -m "feat: add Customer Service dashboard view"
```

---

## Task 10: Content Manager Dashboard View

**Files:**
- Create: `resources/views/pages/admin/content/dashboard.blade.php`

- [ ] **Step 1: Create directory and view**

```bash
mkdir -p resources/views/pages/admin/content
```

Create `resources/views/pages/admin/content/dashboard.blade.php`:
```blade
<x-layouts.dashboard>
    <x-slot:title>Content Manager</x-slot:title>

    <div class="mb-8 flex items-start justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">
                Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }},
                {{ explode(' ', auth()->user()->name)[0] }}
            </h2>
            <p class="mt-1 text-sm text-slate-500">{{ now()->format('l, j F Y') }} · Content operations</p>
        </div>
        <a href="{{ route('admin.products.create') }}"
           class="inline-flex items-center gap-2 rounded-xl bg-sky-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-sky-600 transition-colors">
            <x-nav-icon name="layers" class="h-4 w-4" />
            Add product
        </a>
    </div>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        <x-card-stat-card label="Total products"  :value="(string) $stats['total_products']"  icon="package" icon-color="blue" />
        <x-card-stat-card label="Pending review"  :value="(string) $stats['pending_review']"  icon="shield"  icon-color="amber" />
        <x-card-stat-card label="Published today" :value="(string) $stats['published_today']" icon="check"   icon-color="emerald" />
        <x-card-stat-card label="Categories"      :value="(string) $stats['categories']"      icon="layers"  icon-color="purple" />
    </div>

    <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2">
        <a href="{{ route('admin.products.index') }}"
           class="group flex items-center gap-4 rounded-2xl bg-white ring-1 ring-slate-900/5 shadow-sm p-5 hover:ring-sky-400 transition-all duration-150">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-sky-50 text-sky-600">
                <x-nav-icon name="package" class="h-5 w-5" />
            </span>
            <div class="min-w-0">
                <p class="text-sm font-semibold text-slate-900">All products</p>
                <p class="text-xs text-slate-500 mt-0.5">Browse and edit product listings</p>
            </div>
            <x-nav-icon name="chevron-r" class="h-4 w-4 text-slate-300 group-hover:text-sky-500 ml-auto transition-colors" />
        </a>

        <a href="{{ route('admin.products.create') }}"
           class="group flex items-center gap-4 rounded-2xl bg-white ring-1 ring-slate-900/5 shadow-sm p-5 hover:ring-sky-400 transition-all duration-150">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-purple-50 text-purple-600">
                <x-nav-icon name="layers" class="h-5 w-5" />
            </span>
            <div class="min-w-0">
                <p class="text-sm font-semibold text-slate-900">Manage categories</p>
                <p class="text-xs text-slate-500 mt-0.5">Full category editor in Sprint 3</p>
            </div>
            <x-nav-icon name="chevron-r" class="h-4 w-4 text-slate-300 group-hover:text-sky-500 ml-auto transition-colors" />
        </a>
    </div>

    <div class="rounded-2xl bg-white ring-1 ring-slate-900/5 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-slate-900">Recently submitted products</h3>
            <a href="{{ route('admin.products.index') }}" class="text-xs font-medium text-sky-500 hover:text-sky-600 transition-colors">
                View all →
            </a>
        </div>
        @if($recentProducts->isEmpty())
            <div class="px-6 py-12 text-center">
                <x-nav-icon name="package" class="mx-auto h-8 w-8 text-slate-200" />
                <p class="mt-3 text-sm text-slate-400">No products yet. Live data arrives in Sprint 3.</p>
            </div>
        @endif
    </div>

</x-layouts.dashboard>
```

- [ ] **Step 2: Commit**

```bash
git -c user.name="Bahati" -c user.email="baha.dev@outlook.com" commit -m "feat: add Content Manager dashboard view"
```

---

## Task 11: Vendor Dashboard Redesign

**Files:**
- Modify: `resources/views/pages/vendors/dashboard.blade.php`

> **Note for executor:** Use the `frontend-design` skill while implementing this view.

- [ ] **Step 1: Replace vendor dashboard view**

Replace the entire contents of `resources/views/pages/vendors/dashboard.blade.php`:
```blade
<x-layouts.app>
    <x-slot:title>Seller dashboard</x-slot:title>

    {{-- Header --}}
    <div class="mb-8 flex items-start justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">
                {{ $vendor?->name ?? $user->name }}
            </h2>
            <p class="mt-1 text-sm text-slate-500">{{ now()->format('l, j F Y') }} · Seller overview</p>
        </div>
        <a href="{{ route('vendor.listings.create') }}"
           class="inline-flex items-center gap-2 rounded-xl bg-sky-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-sky-600 transition-colors">
            <x-nav-icon name="layers" class="h-4 w-4" />
            Add listing
        </a>
    </div>

    {{-- KPI tiles --}}
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        <x-card-stat-card label="Active listings"       :value="(string) $stats['active_listings']"   icon="layers" icon-color="emerald" />
        <x-card-stat-card label="Awaiting verification" :value="(string) $stats['pending_listings']"  icon="shield" icon-color="amber" />
        <x-card-stat-card label="Orders to fulfil"      :value="(string) $stats['orders_to_fulfil']"  icon="box"    icon-color="blue" />
        <x-card-stat-card label="Total earned (KSh)"    :value="number_format((float) $stats['total_earned_ksh'])" icon="store" icon-color="purple" />
    </div>

    {{-- Verification callout --}}
    <div class="mb-8 rounded-2xl border border-amber-200 bg-amber-50 p-5 flex items-start gap-4">
        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-600 mt-0.5">
            <x-nav-icon name="shield" class="h-5 w-5" />
        </span>
        <div>
            <p class="text-sm font-semibold text-amber-900">Verified devices sell faster</p>
            <p class="mt-0.5 text-sm text-amber-700">
                Devices with a Shoppa Trust Certificate command higher prices and build buyer confidence.
                Verification costs KSh {{ number_format(config('shoppa.verification.fee_min_ksh')) }}–{{ number_format(config('shoppa.verification.fee_max_ksh')) }} per device.
            </p>
        </div>
    </div>

    {{-- Recent listings --}}
    <div class="rounded-2xl bg-white ring-1 ring-slate-900/5 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-slate-900">Recent listings</h3>
            <a href="{{ route('vendor.listings.index') }}" class="text-xs font-medium text-sky-500 hover:text-sky-600 transition-colors">
                Manage all →
            </a>
        </div>

        @if($recentListings->isEmpty())
            <div class="px-6 py-12 text-center">
                <x-nav-icon name="layers" class="mx-auto h-8 w-8 text-slate-200" />
                <p class="mt-3 text-sm text-slate-500">No listings yet.</p>
                <a href="{{ route('vendor.listings.create') }}" class="mt-3 inline-block text-sm font-medium text-sky-500 hover:text-sky-600 transition-colors">
                    Create your first listing →
                </a>
            </div>
        @else
            <ul role="list" class="divide-y divide-slate-100">
                @foreach($recentListings as $listing)
                    <li class="px-6 py-4 flex items-center justify-between gap-4 hover:bg-slate-50 transition-colors">
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-slate-900 truncate">{{ $listing->name }}</p>
                            <p class="text-xs text-slate-400 mt-0.5">KSh {{ number_format($listing->price) }}</p>
                        </div>
                        <div class="flex items-center gap-3 shrink-0">
                            <x-trust-cert-badge :status="$listing->verification_status ?? 'unverified'" />
                            <a href="{{ route('vendor.listings.show', $listing) }}"
                               class="text-xs font-medium text-sky-500 hover:text-sky-600 transition-colors">View →</a>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

</x-layouts.app>
```

- [ ] **Step 2: Commit**

```bash
git -c user.name="Bahati" -c user.email="baha.dev@outlook.com" commit -m "feat: redesign vendor dashboard — editorial KPIs, sky accent, trust callout"
```

---

## Task 12: Buyer Dashboard Redesign

**Files:**
- Modify: `resources/views/pages/buyer/dashboard.blade.php`

> **Note for executor:** Use the `frontend-design` skill while implementing this view.

- [ ] **Step 1: Replace buyer dashboard view**

Replace the entire contents of `resources/views/pages/buyer/dashboard.blade.php`:
```blade
<x-layouts.app>
    <x-slot:title>Dashboard</x-slot:title>

    <div class="mb-8">
        <h2 class="text-2xl font-bold text-slate-900">
            Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }},
            {{ explode(' ', $user->name)[0] }}
        </h2>
        <p class="mt-1 text-sm text-slate-500">{{ now()->format('l, j F Y') }}</p>
    </div>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        <x-card-stat-card label="Active orders"        :value="(string) $stats['active_orders']"    icon="box"    icon-color="blue" />
        <x-card-stat-card label="Total purchases"      :value="(string) $stats['total_orders']"     icon="layers" icon-color="emerald" />
        <x-card-stat-card label="Wishlist"             :value="(string) $stats['wishlist_count']"   icon="search" icon-color="purple" />
        <x-card-stat-card label="Verified devices"     :value="(string) $stats['devices_verified']" icon="shield" icon-color="emerald" />
    </div>

    {{-- Trust callout --}}
    <div class="mb-8 rounded-2xl border border-emerald-200 bg-emerald-50 p-5 flex items-start gap-4">
        <x-trust-verified-pill size="lg" class="shrink-0 mt-0.5" />
        <div>
            <p class="text-sm font-semibold text-emerald-900">Every device on Shoppa is physically inspected</p>
            <p class="mt-0.5 text-sm text-emerald-700">
                Our verification team checks IMEI legitimacy, hardware authenticity, and condition grade before any listing goes live.
                <a href="{{ route('buyer.browse') }}" class="font-semibold underline underline-offset-2 hover:no-underline">Browse verified devices →</a>
            </p>
        </div>
    </div>

    {{-- Quick actions --}}
    <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2">
        <a href="{{ route('buyer.browse') }}"
           class="group flex items-center gap-4 rounded-2xl bg-white ring-1 ring-slate-900/5 shadow-sm p-5 hover:ring-sky-400 transition-all duration-150">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-sky-50 text-sky-600">
                <x-nav-icon name="search" class="h-5 w-5" />
            </span>
            <div class="min-w-0">
                <p class="text-sm font-semibold text-slate-900">Browse devices</p>
                <p class="text-xs text-slate-500 mt-0.5">Find verified electronics near you</p>
            </div>
            <x-nav-icon name="chevron-r" class="h-4 w-4 text-slate-300 group-hover:text-sky-500 ml-auto transition-colors" />
        </a>

        <a href="{{ route('buyer.orders.index') }}"
           class="group flex items-center gap-4 rounded-2xl bg-white ring-1 ring-slate-900/5 shadow-sm p-5 hover:ring-sky-400 transition-all duration-150">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                <x-nav-icon name="box" class="h-5 w-5" />
            </span>
            <div class="min-w-0">
                <p class="text-sm font-semibold text-slate-900">Track orders</p>
                <p class="text-xs text-slate-500 mt-0.5">View status and delivery updates</p>
            </div>
            <x-nav-icon name="chevron-r" class="h-4 w-4 text-slate-300 group-hover:text-sky-500 ml-auto transition-colors" />
        </a>
    </div>

    {{-- Recent orders --}}
    <div class="rounded-2xl bg-white ring-1 ring-slate-900/5 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-slate-900">Recent orders</h3>
            <a href="{{ route('buyer.orders.index') }}" class="text-xs font-medium text-sky-500 hover:text-sky-600 transition-colors">View all →</a>
        </div>

        @if($recentOrders->isEmpty())
            <div class="px-6 py-12 text-center">
                <x-nav-icon name="box" class="mx-auto h-8 w-8 text-slate-200" />
                <p class="mt-3 text-sm text-slate-500">No orders yet.</p>
                <a href="{{ route('buyer.browse') }}" class="mt-3 inline-block text-sm font-medium text-sky-500 hover:text-sky-600 transition-colors">
                    Browse devices →
                </a>
            </div>
        @else
            <ul role="list" class="divide-y divide-slate-100">
                @foreach($recentOrders as $order)
                    <li class="px-6 py-4 flex items-center justify-between gap-4 hover:bg-slate-50 transition-colors">
                        <div>
                            <p class="text-sm font-semibold text-slate-900">Order #{{ $order->id }}</p>
                            <p class="text-xs text-slate-400 mt-0.5">{{ $order->created_at->diffForHumans() }}</p>
                        </div>
                        <div class="flex items-center gap-3 shrink-0">
                            <x-ui-badge color="stone">{{ $order->status?->name ?? '—' }}</x-ui-badge>
                            <a href="{{ route('buyer.orders.show', $order) }}"
                               class="text-xs font-medium text-sky-500 hover:text-sky-600 transition-colors">View →</a>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

</x-layouts.app>
```

- [ ] **Step 2: Commit**

```bash
git -c user.name="Bahati" -c user.email="baha.dev@outlook.com" commit -m "feat: redesign buyer dashboard — editorial KPIs, trust callout, sky accents"
```

---

## Task 13: TestCase Base + UserFactory States

**Files:**
- Modify: `tests/TestCase.php`
- Modify: `database/factories/UserFactory.php`

- [ ] **Step 1: Update TestCase with RefreshDatabase + seedRoles()**

Replace `tests/TestCase.php`:
```php
<?php

namespace Tests;

use Database\Seeders\PermissionSeeder;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected function seedRoles(): void
    {
        $this->seed([
            RoleSeeder::class,
            PermissionSeeder::class,
            RolePermissionSeeder::class,
        ]);
    }
}
```

- [ ] **Step 2: Add role states to UserFactory**

Replace `database/factories/UserFactory.php` (keep existing definition, add new states):
```php
<?php

namespace Database\Factories;

use App\Enums\RoleName;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name'               => fake()->name(),
            'email'              => fake()->unique()->safeEmail(),
            'email_verified_at'  => now(),
            'password'           => static::$password ??= Hash::make('password'),
            'remember_token'     => Str::random(10),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    private function withRole(RoleName $role): static
    {
        return $this->state(fn (array $attributes) => [
            'role_id' => Role::where('name', $role->value)->value('id'),
        ]);
    }

    public function asSuperAdmin(): static  { return $this->withRole(RoleName::SuperAdmin); }
    public function asAdmin(): static       { return $this->withRole(RoleName::Admin); }
    public function asVendorManager(): static { return $this->withRole(RoleName::VendorManager); }
    public function asVerifier(): static    { return $this->withRole(RoleName::Verifier); }
    public function asCustomerService(): static { return $this->withRole(RoleName::CustomerService); }
    public function asContentManager(): static  { return $this->withRole(RoleName::ContentManager); }
    public function asVendor(): static      { return $this->withRole(RoleName::Vendor); }
    public function asBuyer(): static       { return $this->withRole(RoleName::User); }

    // Legacy aliases kept for compatibility
    public function vendor(): static        { return $this->asVendor(); }
    public function user(): static          { return $this->asBuyer(); }
    public function superAdmin(): static    { return $this->asSuperAdmin(); }
}
```

- [ ] **Step 3: Run existing test suite to confirm base setup works**

```bash
php artisan test
```
Expected: `2 tests, 2 assertions` (the two placeholder tests). No failures.

- [ ] **Step 4: Commit**

```bash
git -c user.name="Bahati" -c user.email="baha.dev@outlook.com" commit -m "test: add RefreshDatabase + seedRoles to TestCase, add role states to UserFactory"
```

---

## Task 14: Login + Register Tests

**Files:**
- Create: `tests/Feature/Auth/LoginTest.php`
- Create: `tests/Feature/Auth/RegisterTest.php`

- [ ] **Step 1: Write LoginTest**

Create `tests/Feature/Auth/LoginTest.php`:
```php
<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class LoginTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRoles();
    }

    public function test_login_page_renders(): void
    {
        $this->get('/login')->assertOk();
    }

    public function test_valid_credentials_log_user_in(): void
    {
        $user = User::factory()->asBuyer()->create();

        $this->post('/login', [
            'email'    => $user->email,
            'password' => 'password',
        ])->assertRedirect();

        $this->assertAuthenticatedAs($user);
    }

    public function test_invalid_password_returns_error(): void
    {
        $user = User::factory()->asBuyer()->create();

        $this->post('/login', [
            'email'    => $user->email,
            'password' => 'wrong-password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_non_existent_email_returns_error(): void
    {
        $this->post('/login', [
            'email'    => 'nobody@example.com',
            'password' => 'password',
        ])->assertSessionHasErrors('email');
    }

    public function test_admin_login_redirects_to_admin_dashboard(): void
    {
        $user = User::factory()->asAdmin()->create();

        $this->post('/login', ['email' => $user->email, 'password' => 'password'])
             ->assertRedirect(route('admin.dashboard'));
    }

    public function test_vendor_login_redirects_to_vendor_dashboard(): void
    {
        $user = User::factory()->asVendor()->create();

        $this->post('/login', ['email' => $user->email, 'password' => 'password'])
             ->assertRedirect(route('vendor.dashboard'));
    }

    public function test_buyer_login_redirects_to_buyer_dashboard(): void
    {
        $user = User::factory()->asBuyer()->create();

        $this->post('/login', ['email' => $user->email, 'password' => 'password'])
             ->assertRedirect(route('buyer.dashboard'));
    }

    public function test_remember_me_sets_cookie(): void
    {
        $user = User::factory()->asBuyer()->create();

        $response = $this->post('/login', [
            'email'    => $user->email,
            'password' => 'password',
            'remember' => true,
        ]);

        $response->assertCookie(auth()->guard()->getRecallerName());
    }
}
```

- [ ] **Step 2: Run LoginTest to verify it passes**

```bash
php artisan test tests/Feature/Auth/LoginTest.php --verbose
```
Expected: all tests pass.

- [ ] **Step 3: Write RegisterTest**

Create `tests/Feature/Auth/RegisterTest.php`:
```php
<?php

namespace Tests\Feature\Auth;

use App\Enums\RoleName;
use App\Models\User;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRoles();
    }

    public function test_register_page_renders(): void
    {
        $this->get('/register')->assertOk();
    }

    public function test_valid_registration_creates_user_with_buyer_role(): void
    {
        $this->post('/register', [
            'name'                  => 'Jane Wanjiru',
            'email'                 => 'jane@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $user = User::where('email', 'jane@example.com')->firstOrFail();
        $this->assertTrue($user->hasRole(RoleName::User));
    }

    public function test_registration_redirects_to_email_verification(): void
    {
        $this->post('/register', [
            'name'                  => 'Jane Wanjiru',
            'email'                 => 'jane@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect(route('verification.notice'));
    }

    public function test_name_is_required(): void
    {
        $this->post('/register', [
            'name'                  => '',
            'email'                 => 'test@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ])->assertSessionHasErrors('name');
    }

    public function test_email_must_be_valid(): void
    {
        $this->post('/register', [
            'name'                  => 'Jane',
            'email'                 => 'not-an-email',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ])->assertSessionHasErrors('email');
    }

    public function test_email_must_be_unique(): void
    {
        $existing = User::factory()->asBuyer()->create(['email' => 'taken@example.com']);

        $this->post('/register', [
            'name'                  => 'Jane',
            'email'                 => 'taken@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ])->assertSessionHasErrors('email');
    }

    public function test_password_minimum_8_characters(): void
    {
        $this->post('/register', [
            'name'                  => 'Jane',
            'email'                 => 'jane@example.com',
            'password'              => 'short',
            'password_confirmation' => 'short',
        ])->assertSessionHasErrors('password');
    }

    public function test_password_confirmation_must_match(): void
    {
        $this->post('/register', [
            'name'                  => 'Jane',
            'email'                 => 'jane@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'differentpassword',
        ])->assertSessionHasErrors('password');
    }

    public function test_phone_is_optional(): void
    {
        $this->post('/register', [
            'name'                  => 'Jane',
            'email'                 => 'jane@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ])->assertSessionDoesntHaveErrors('phone');
    }
}
```

- [ ] **Step 4: Run RegisterTest**

```bash
php artisan test tests/Feature/Auth/RegisterTest.php --verbose
```
Expected: all tests pass.

- [ ] **Step 5: Commit**

```bash
git -c user.name="Bahati" -c user.email="baha.dev@outlook.com" commit -m "test: add LoginTest and RegisterTest"
```

---

## Task 15: Password Reset + Email Verification Tests

**Files:**
- Create: `tests/Feature/Auth/PasswordResetTest.php`
- Create: `tests/Feature/Auth/EmailVerificationTest.php`

- [ ] **Step 1: Write PasswordResetTest**

Create `tests/Feature/Auth/PasswordResetTest.php`:
```php
<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRoles();
    }

    public function test_forgot_password_page_renders(): void
    {
        $this->get('/forgot-password')->assertOk();
    }

    public function test_reset_link_sent_for_valid_email(): void
    {
        Notification::fake();
        $user = User::factory()->asBuyer()->create();

        $this->post('/forgot-password', ['email' => $user->email])
             ->assertSessionHas('status');

        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_no_user_enumeration_for_unknown_email(): void
    {
        $this->post('/forgot-password', ['email' => 'nobody@example.com'])
             ->assertSessionHas('status');
    }

    public function test_reset_password_with_valid_token(): void
    {
        Notification::fake();
        $user = User::factory()->asBuyer()->create();

        $this->post('/forgot-password', ['email' => $user->email]);

        $token = null;
        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use (&$token) {
            $token = $notification->token;
            return true;
        });

        $this->post('/reset-password', [
            'token'                 => $token,
            'email'                 => $user->email,
            'password'              => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ])->assertRedirect(route('login'));
    }

    public function test_reset_with_invalid_token_fails(): void
    {
        $user = User::factory()->asBuyer()->create();

        $this->post('/reset-password', [
            'token'                 => 'invalid-token',
            'email'                 => $user->email,
            'password'              => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ])->assertSessionHasErrors('email');
    }
}
```

- [ ] **Step 2: Write EmailVerificationTest**

Create `tests/Feature/Auth/EmailVerificationTest.php`:
```php
<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRoles();
    }

    public function test_unverified_user_redirected_to_verify_notice(): void
    {
        $user = User::factory()->asBuyer()->unverified()->create();

        $this->actingAs($user)
             ->get(route('buyer.dashboard'))
             ->assertRedirect(route('verification.notice'));
    }

    public function test_verified_user_can_access_dashboard(): void
    {
        $user = User::factory()->asBuyer()->create();

        $this->actingAs($user)
             ->get(route('buyer.dashboard'))
             ->assertOk();
    }

    public function test_verify_email_link_marks_user_as_verified(): void
    {
        Event::fake();
        $user = User::factory()->asBuyer()->unverified()->create();

        $url = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $this->actingAs($user)->get($url)->assertRedirect();

        $this->assertTrue($user->fresh()->hasVerifiedEmail());
        Event::assertDispatched(Verified::class);
    }
}
```

- [ ] **Step 3: Run both tests**

```bash
php artisan test tests/Feature/Auth/PasswordResetTest.php tests/Feature/Auth/EmailVerificationTest.php --verbose
```
Expected: all tests pass.

- [ ] **Step 4: Commit**

```bash
git -c user.name="Bahati" -c user.email="baha.dev@outlook.com" commit -m "test: add PasswordResetTest and EmailVerificationTest"
```

---

## Task 16: Role Redirect Tests

**Files:**
- Create: `tests/Feature/Auth/RoleRedirectTest.php`

- [ ] **Step 1: Write RoleRedirectTest**

Create `tests/Feature/Auth/RoleRedirectTest.php`:
```php
<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Tests\TestCase;

class RoleRedirectTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRoles();
    }

    private function loginAs(User $user): \Illuminate\Testing\TestResponse
    {
        return $this->post('/login', [
            'email'    => $user->email,
            'password' => 'password',
        ]);
    }

    public function test_super_admin_redirects_to_admin_dashboard(): void
    {
        $this->loginAs(User::factory()->asSuperAdmin()->create())
             ->assertRedirect(route('admin.dashboard'));
    }

    public function test_admin_redirects_to_admin_dashboard(): void
    {
        $this->loginAs(User::factory()->asAdmin()->create())
             ->assertRedirect(route('admin.dashboard'));
    }

    public function test_vendor_manager_redirects_to_vendor_manager_dashboard(): void
    {
        $this->loginAs(User::factory()->asVendorManager()->create())
             ->assertRedirect(route('admin.vendor-manager.dashboard'));
    }

    public function test_verifier_redirects_to_verifier_dashboard(): void
    {
        $this->loginAs(User::factory()->asVerifier()->create())
             ->assertRedirect(route('verifier.dashboard'));
    }

    public function test_customer_service_redirects_to_cs_dashboard(): void
    {
        $this->loginAs(User::factory()->asCustomerService()->create())
             ->assertRedirect(route('admin.cs.dashboard'));
    }

    public function test_content_manager_redirects_to_content_dashboard(): void
    {
        $this->loginAs(User::factory()->asContentManager()->create())
             ->assertRedirect(route('admin.content.dashboard'));
    }

    public function test_vendor_redirects_to_vendor_dashboard(): void
    {
        $this->loginAs(User::factory()->asVendor()->create())
             ->assertRedirect(route('vendor.dashboard'));
    }

    public function test_buyer_redirects_to_buyer_dashboard(): void
    {
        $this->loginAs(User::factory()->asBuyer()->create())
             ->assertRedirect(route('buyer.dashboard'));
    }
}
```

- [ ] **Step 2: Run RoleRedirectTest**

```bash
php artisan test tests/Feature/Auth/RoleRedirectTest.php --verbose
```
Expected: 8 tests pass.

- [ ] **Step 3: Commit**

```bash
git -c user.name="Bahati" -c user.email="baha.dev@outlook.com" commit -m "test: add RoleRedirectTest — all 8 roles verified"
```

---

## Task 17: Dashboard Access Tests

**Files:**
- Create: `tests/Feature/Dashboard/AdminDashboardTest.php`
- Create: `tests/Feature/Dashboard/VendorDashboardTest.php`
- Create: `tests/Feature/Dashboard/BuyerDashboardTest.php`
- Create: `tests/Feature/Dashboard/StaffDashboardTest.php`

- [ ] **Step 1: Write AdminDashboardTest**

Create `tests/Feature/Dashboard/AdminDashboardTest.php`:
```php
<?php

namespace Tests\Feature\Dashboard;

use App\Models\User;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRoles();
    }

    public function test_admin_can_access_dashboard(): void
    {
        $this->actingAs(User::factory()->asAdmin()->create())
             ->get(route('admin.dashboard'))
             ->assertOk();
    }

    public function test_super_admin_can_access_dashboard(): void
    {
        $this->actingAs(User::factory()->asSuperAdmin()->create())
             ->get(route('admin.dashboard'))
             ->assertOk();
    }

    public function test_vendor_cannot_access_admin_dashboard(): void
    {
        $this->actingAs(User::factory()->asVendor()->create())
             ->get(route('admin.dashboard'))
             ->assertForbidden();
    }

    public function test_buyer_cannot_access_admin_dashboard(): void
    {
        $this->actingAs(User::factory()->asBuyer()->create())
             ->get(route('admin.dashboard'))
             ->assertForbidden();
    }

    public function test_unauthenticated_redirected_to_login(): void
    {
        $this->get(route('admin.dashboard'))
             ->assertRedirect(route('login'));
    }
}
```

- [ ] **Step 2: Write VendorDashboardTest**

Create `tests/Feature/Dashboard/VendorDashboardTest.php`:
```php
<?php

namespace Tests\Feature\Dashboard;

use App\Models\User;
use Tests\TestCase;

class VendorDashboardTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRoles();
    }

    public function test_vendor_can_access_dashboard(): void
    {
        $this->actingAs(User::factory()->asVendor()->create())
             ->get(route('vendor.dashboard'))
             ->assertOk();
    }

    public function test_buyer_cannot_access_vendor_dashboard(): void
    {
        $this->actingAs(User::factory()->asBuyer()->create())
             ->get(route('vendor.dashboard'))
             ->assertForbidden();
    }

    public function test_admin_cannot_access_vendor_dashboard(): void
    {
        $this->actingAs(User::factory()->asAdmin()->create())
             ->get(route('vendor.dashboard'))
             ->assertForbidden();
    }

    public function test_unauthenticated_redirected_to_login(): void
    {
        $this->get(route('vendor.dashboard'))
             ->assertRedirect(route('login'));
    }
}
```

- [ ] **Step 3: Write BuyerDashboardTest**

Create `tests/Feature/Dashboard/BuyerDashboardTest.php`:
```php
<?php

namespace Tests\Feature\Dashboard;

use App\Models\User;
use Tests\TestCase;

class BuyerDashboardTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRoles();
    }

    public function test_buyer_can_access_dashboard(): void
    {
        $this->actingAs(User::factory()->asBuyer()->create())
             ->get(route('buyer.dashboard'))
             ->assertOk();
    }

    public function test_unauthenticated_redirected_to_login(): void
    {
        $this->get(route('buyer.dashboard'))
             ->assertRedirect(route('login'));
    }
}
```

- [ ] **Step 4: Write StaffDashboardTest**

Create `tests/Feature/Dashboard/StaffDashboardTest.php`:
```php
<?php

namespace Tests\Feature\Dashboard;

use App\Models\User;
use Tests\TestCase;

class StaffDashboardTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRoles();
    }

    public function test_vendor_manager_can_access_own_dashboard(): void
    {
        $this->actingAs(User::factory()->asVendorManager()->create())
             ->get(route('admin.vendor-manager.dashboard'))
             ->assertOk();
    }

    public function test_verifier_can_access_own_dashboard(): void
    {
        $this->actingAs(User::factory()->asVerifier()->create())
             ->get(route('verifier.dashboard'))
             ->assertOk();
    }

    public function test_customer_service_can_access_own_dashboard(): void
    {
        $this->actingAs(User::factory()->asCustomerService()->create())
             ->get(route('admin.cs.dashboard'))
             ->assertOk();
    }

    public function test_content_manager_can_access_own_dashboard(): void
    {
        $this->actingAs(User::factory()->asContentManager()->create())
             ->get(route('admin.content.dashboard'))
             ->assertOk();
    }

    public function test_buyer_cannot_access_vendor_manager_dashboard(): void
    {
        $this->actingAs(User::factory()->asBuyer()->create())
             ->get(route('admin.vendor-manager.dashboard'))
             ->assertForbidden();
    }

    public function test_buyer_cannot_access_verifier_dashboard(): void
    {
        $this->actingAs(User::factory()->asBuyer()->create())
             ->get(route('verifier.dashboard'))
             ->assertForbidden();
    }

    public function test_buyer_cannot_access_cs_dashboard(): void
    {
        $this->actingAs(User::factory()->asBuyer()->create())
             ->get(route('admin.cs.dashboard'))
             ->assertForbidden();
    }

    public function test_buyer_cannot_access_content_dashboard(): void
    {
        $this->actingAs(User::factory()->asBuyer()->create())
             ->get(route('admin.content.dashboard'))
             ->assertForbidden();
    }
}
```

- [ ] **Step 5: Run all dashboard tests**

```bash
php artisan test tests/Feature/Dashboard/ --verbose
```
Expected: all tests pass.

- [ ] **Step 6: Commit**

```bash
git -c user.name="Bahati" -c user.email="baha.dev@outlook.com" commit -m "test: add dashboard access tests for all roles"
```

---

## Task 18: Admin User CRUD Tests

**Files:**
- Create: `tests/Feature/Admin/UserCrudTest.php`

- [ ] **Step 1: Write UserCrudTest**

Create `tests/Feature/Admin/UserCrudTest.php`:
```php
<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Tests\TestCase;

class UserCrudTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRoles();
    }

    private function admin(): User
    {
        return User::factory()->asAdmin()->create();
    }

    public function test_admin_can_list_users(): void
    {
        $this->actingAs($this->admin())
             ->get(route('admin.users.index'))
             ->assertOk();
    }

    public function test_admin_can_view_create_form(): void
    {
        $this->actingAs($this->admin())
             ->get(route('admin.users.create'))
             ->assertOk();
    }

    public function test_admin_can_create_user(): void
    {
        $this->actingAs($this->admin())
             ->post(route('admin.users.store'), [
                 'name'     => 'New User',
                 'email'    => 'newuser@example.com',
                 'password' => 'password123',
                 'role_id'  => \App\Models\Role::where('name', 'User')->value('id'),
             ])->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseHas('users', ['email' => 'newuser@example.com']);
    }

    public function test_admin_create_validates_required_fields(): void
    {
        $this->actingAs($this->admin())
             ->post(route('admin.users.store'), [])
             ->assertSessionHasErrors(['name', 'email', 'password']);
    }

    public function test_admin_can_view_edit_form(): void
    {
        $user = User::factory()->asBuyer()->create();

        $this->actingAs($this->admin())
             ->get(route('admin.users.edit', $user))
             ->assertOk();
    }

    public function test_admin_can_update_user(): void
    {
        $user = User::factory()->asBuyer()->create();

        $this->actingAs($this->admin())
             ->put(route('admin.users.update', $user), [
                 'name'  => 'Updated Name',
                 'email' => $user->email,
             ])->assertRedirect();

        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'Updated Name']);
    }

    public function test_admin_can_soft_delete_user(): void
    {
        $user = User::factory()->asBuyer()->create();

        $this->actingAs($this->admin())
             ->delete(route('admin.users.destroy', $user))
             ->assertRedirect();

        $this->assertSoftDeleted('users', ['id' => $user->id]);
    }

    public function test_vendor_cannot_access_user_list(): void
    {
        $this->actingAs(User::factory()->asVendor()->create())
             ->get(route('admin.users.index'))
             ->assertForbidden();
    }

    public function test_buyer_cannot_create_users(): void
    {
        $this->actingAs(User::factory()->asBuyer()->create())
             ->post(route('admin.users.store'), [
                 'name'     => 'Attacker',
                 'email'    => 'attacker@example.com',
                 'password' => 'password123',
             ])->assertForbidden();
    }
}
```

- [ ] **Step 2: Run UserCrudTest**

```bash
php artisan test tests/Feature/Admin/UserCrudTest.php --verbose
```
Expected: all tests pass.

- [ ] **Step 3: Run the full suite**

```bash
php artisan test --verbose
```
Expected: all tests pass. Note the final count.

- [ ] **Step 4: Commit**

```bash
git -c user.name="Bahati" -c user.email="baha.dev@outlook.com" commit -m "test: add UserCrudTest — admin CRUD and role-gate coverage"
```

---

## Task 19: Dockerfile

**Files:**
- Create: `Dockerfile`
- Create: `docker/php/php.ini`

- [ ] **Step 1: Create php.ini override**

```bash
mkdir -p docker/php
```

Create `docker/php/php.ini`:
```ini
upload_max_filesize = 50M
post_max_size = 50M
memory_limit = 256M
max_execution_time = 60
opcache.enable = 1
opcache.memory_consumption = 128
opcache.max_accelerated_files = 10000
opcache.revalidate_freq = 0
```

- [ ] **Step 2: Create Dockerfile**

Create `Dockerfile`:
```dockerfile
# Stage 1 — Node/Vite build
FROM node:20-alpine AS frontend
WORKDIR /app
COPY package.json pnpm-lock.yaml ./
RUN npm install -g pnpm && pnpm install --frozen-lockfile
COPY vite.config.js tailwind.config.js ./
COPY resources/ resources/
COPY public/ public/
RUN pnpm run build

# Stage 2 — Composer
FROM composer:2.7 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader

# Stage 3 — Production PHP-FPM
FROM php:8.2-fpm-alpine AS app

RUN apk add --no-cache \
    libpq-dev \
    libzip-dev \
    && docker-php-ext-install \
        pdo_pgsql \
        zip \
        opcache \
        pcntl \
    && pecl install redis \
    && docker-php-ext-enable redis

COPY docker/php/php.ini /usr/local/etc/php/conf.d/shoppa.ini

WORKDIR /var/www/html

COPY . .
COPY --from=vendor /app/vendor ./vendor
COPY --from=frontend /app/public/build ./public/build

RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

USER www-data

EXPOSE 9000
CMD ["php-fpm"]
```

- [ ] **Step 3: Verify Dockerfile syntax**

```bash
docker build --no-cache --target vendor -t shoppa-vendor-check . 2>&1 | tail -5
```
Expected: no errors in Composer stage.

- [ ] **Step 4: Commit**

```bash
git -c user.name="Bahati" -c user.email="baha.dev@outlook.com" commit -m "feat: add multi-stage Dockerfile (frontend → vendor → app)"
```

---

## Task 20: Docker Compose Files

**Files:**
- Create: `docker-compose.yml`
- Create: `docker-compose.staging.yml`
- Create: `docker-compose.prod.yml`

- [ ] **Step 1: Create base docker-compose.yml**

Create `docker-compose.yml`:
```yaml
services:
  app:
    build:
      context: .
      dockerfile: Dockerfile
      target: app
    restart: unless-stopped
    volumes:
      - storage_public:/var/www/html/storage/app/public
    depends_on:
      - postgres
      - redis

  nginx:
    image: nginx:stable-alpine
    restart: unless-stopped
    volumes:
      - /etc/letsencrypt:/etc/letsencrypt:ro
      - storage_public:/var/www/html/storage/app/public:ro
    depends_on:
      - app

  postgres:
    image: postgres:16-alpine
    restart: unless-stopped
    environment:
      POSTGRES_DB: ${DB_DATABASE}
      POSTGRES_USER: ${DB_USERNAME}
      POSTGRES_PASSWORD: ${DB_PASSWORD}

  redis:
    image: redis:7-alpine
    restart: unless-stopped
    command: redis-server --save 60 1 --loglevel warning

  queue:
    build:
      context: .
      dockerfile: Dockerfile
      target: app
    restart: unless-stopped
    command: php artisan queue:work --sleep=3 --tries=3 --max-time=3600
    depends_on:
      - app
      - redis

volumes:
  storage_public:
```

- [ ] **Step 2: Create docker-compose.staging.yml**

Create `docker-compose.staging.yml`:
```yaml
services:
  app:
    container_name: shoppa_staging_app
    env_file: .env.staging
    volumes:
      - shoppa_staging_storage:/var/www/html/storage/app/public

  nginx:
    container_name: shoppa_staging_nginx
    ports:
      - "8080:80"
      - "8443:443"
    volumes:
      - ./docker/nginx/staging.conf:/etc/nginx/conf.d/default.conf:ro
      - /etc/letsencrypt:/etc/letsencrypt:ro
      - shoppa_staging_storage:/var/www/html/storage/app/public:ro

  postgres:
    container_name: shoppa_staging_db
    volumes:
      - shoppa_staging_postgres:/var/lib/postgresql/data

  redis:
    container_name: shoppa_staging_redis
    volumes:
      - shoppa_staging_redis:/data

  queue:
    container_name: shoppa_staging_queue
    env_file: .env.staging
    volumes:
      - shoppa_staging_storage:/var/www/html/storage/app/public

volumes:
  shoppa_staging_storage:
  shoppa_staging_postgres:
  shoppa_staging_redis:
```

- [ ] **Step 3: Create docker-compose.prod.yml**

Create `docker-compose.prod.yml`:
```yaml
services:
  app:
    container_name: shoppa_prod_app
    env_file: .env.prod
    volumes:
      - shoppa_prod_storage:/var/www/html/storage/app/public

  nginx:
    container_name: shoppa_prod_nginx
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./docker/nginx/prod.conf:/etc/nginx/conf.d/default.conf:ro
      - /etc/letsencrypt:/etc/letsencrypt:ro
      - shoppa_prod_storage:/var/www/html/storage/app/public:ro

  postgres:
    container_name: shoppa_prod_db
    volumes:
      - shoppa_prod_postgres:/var/lib/postgresql/data

  redis:
    container_name: shoppa_prod_redis
    volumes:
      - shoppa_prod_redis:/data

  queue:
    container_name: shoppa_prod_queue
    env_file: .env.prod
    volumes:
      - shoppa_prod_storage:/var/www/html/storage/app/public

volumes:
  shoppa_prod_storage:
  shoppa_prod_postgres:
  shoppa_prod_redis:
```

- [ ] **Step 4: Commit**

```bash
git -c user.name="Bahati" -c user.email="baha.dev@outlook.com" commit -m "feat: add Docker Compose base, staging, and prod override files"
```

---

## Task 21: Nginx Config Files

**Files:**
- Create: `docker/nginx/staging.conf`
- Create: `docker/nginx/prod.conf`

- [ ] **Step 1: Create Nginx staging config**

```bash
mkdir -p docker/nginx
```

Create `docker/nginx/staging.conf`:
```nginx
server {
    listen 80;
    server_name dev.shoppa.yourdomain.com;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    server_name dev.shoppa.yourdomain.com;

    ssl_certificate     /etc/letsencrypt/live/dev.shoppa.yourdomain.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/dev.shoppa.yourdomain.com/privkey.pem;
    ssl_protocols       TLSv1.2 TLSv1.3;
    ssl_ciphers         HIGH:!aNULL:!MD5;

    root  /var/www/html/public;
    index index.php;

    client_max_body_size 50M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass            app:9000;
        fastcgi_index           index.php;
        fastcgi_param           SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include                 fastcgi_params;
        fastcgi_read_timeout    120;
    }

    location ~* \.(css|js|png|jpg|jpeg|gif|ico|svg|woff2?)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        try_files $uri =404;
    }

    location ~ /\.ht { deny all; }
}
```

- [ ] **Step 2: Create Nginx prod config**

Create `docker/nginx/prod.conf` (same structure, different domain):
```nginx
server {
    listen 80;
    server_name app.shoppa.yourdomain.com;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    server_name app.shoppa.yourdomain.com;

    ssl_certificate     /etc/letsencrypt/live/app.shoppa.yourdomain.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/app.shoppa.yourdomain.com/privkey.pem;
    ssl_protocols       TLSv1.2 TLSv1.3;
    ssl_ciphers         HIGH:!aNULL:!MD5;

    root  /var/www/html/public;
    index index.php;

    client_max_body_size 50M;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header Referrer-Policy "strict-origin-when-cross-origin";

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass            app:9000;
        fastcgi_index           index.php;
        fastcgi_param           SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include                 fastcgi_params;
        fastcgi_read_timeout    120;
    }

    location ~* \.(css|js|png|jpg|jpeg|gif|ico|svg|woff2?)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        try_files $uri =404;
    }

    location ~ /\.ht { deny all; }
}
```

> **After deployment:** replace `yourdomain.com` with the actual domain in both configs, or parameterise via `envsubst` in the deploy script.

- [ ] **Step 3: Commit**

```bash
git -c user.name="Bahati" -c user.email="baha.dev@outlook.com" commit -m "feat: add Nginx staging and prod vhost configs"
```

---

## Task 22: GitHub Actions Workflows + .env.example

**Files:**
- Create: `.github/workflows/ci.yml`
- Create: `.github/workflows/deploy-staging.yml`
- Create: `.github/workflows/deploy-prod.yml`
- Modify: `.env.example`

- [ ] **Step 1: Create CI workflow**

```bash
mkdir -p .github/workflows
```

Create `.github/workflows/ci.yml`:
```yaml
name: CI

on:
  push:
    branches: ['**']
  pull_request:
    branches: ['**']
  workflow_call:

jobs:
  test:
    runs-on: ubuntu-latest

    steps:
      - uses: actions/checkout@v4

      - name: Setup PHP 8.2
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.2'
          extensions: pdo, pdo_sqlite, pdo_pgsql, redis, zip, opcache, pcntl
          coverage: none

      - name: Cache Composer packages
        uses: actions/cache@v4
        with:
          path: vendor
          key: ${{ runner.os }}-composer-${{ hashFiles('**/composer.lock') }}
          restore-keys: ${{ runner.os }}-composer-

      - name: Install Composer dependencies
        run: composer install --no-interaction --prefer-dist --optimize-autoloader

      - name: Copy .env.testing
        run: cp .env.testing .env

      - name: Generate app key
        run: php artisan key:generate

      - name: Run test suite
        run: php artisan test --parallel

      - name: Setup Node 20
        uses: actions/setup-node@v4
        with:
          node-version: '20'
          cache: 'npm'

      - name: Install NPM dependencies
        run: npm ci

      - name: Build assets
        run: npm run build
```

- [ ] **Step 2: Create staging deploy workflow**

Create `.github/workflows/deploy-staging.yml`:
```yaml
name: Deploy Staging

on:
  push:
    branches: [develop]

jobs:
  test:
    uses: ./.github/workflows/ci.yml

  deploy:
    runs-on: ubuntu-latest
    needs: test
    environment: staging

    steps:
      - name: Deploy to staging
        uses: appleboy/ssh-action@v1.0.3
        with:
          host: ${{ secrets.SSH_HOST }}
          username: ${{ secrets.SSH_USER }}
          key: ${{ secrets.SSH_KEY }}
          script: |
            set -e
            cd /var/www/shoppa-staging
            git fetch origin
            git checkout develop
            git pull origin develop
            docker compose -f docker-compose.staging.yml build --no-cache app queue
            docker compose -f docker-compose.staging.yml up -d
            docker compose -f docker-compose.staging.yml exec -T app php artisan migrate --force
            docker compose -f docker-compose.staging.yml exec -T app php artisan config:cache
            docker compose -f docker-compose.staging.yml exec -T app php artisan route:cache
            docker compose -f docker-compose.staging.yml exec -T app php artisan view:cache
            echo "Staging deploy complete"

      - name: Health check
        uses: appleboy/ssh-action@v1.0.3
        with:
          host: ${{ secrets.SSH_HOST }}
          username: ${{ secrets.SSH_USER }}
          key: ${{ secrets.SSH_KEY }}
          script: |
            sleep 10
            curl --fail --retry 3 --retry-delay 5 http://localhost:8080/health
```

- [ ] **Step 3: Create prod deploy workflow**

Create `.github/workflows/deploy-prod.yml`:
```yaml
name: Deploy Production

on:
  push:
    branches: [main]

jobs:
  test:
    uses: ./.github/workflows/ci.yml

  deploy:
    runs-on: ubuntu-latest
    needs: test
    environment: production

    steps:
      - name: Deploy to production
        uses: appleboy/ssh-action@v1.0.3
        with:
          host: ${{ secrets.SSH_HOST }}
          username: ${{ secrets.SSH_USER }}
          key: ${{ secrets.SSH_KEY }}
          script: |
            set -e
            cd /var/www/shoppa-prod
            git fetch origin
            git checkout main
            git pull origin main
            docker compose -f docker-compose.prod.yml build --no-cache app queue
            docker compose -f docker-compose.prod.yml up -d
            docker compose -f docker-compose.prod.yml exec -T app php artisan migrate --force
            docker compose -f docker-compose.prod.yml exec -T app php artisan config:cache
            docker compose -f docker-compose.prod.yml exec -T app php artisan route:cache
            docker compose -f docker-compose.prod.yml exec -T app php artisan view:cache
            echo "Production deploy complete"

      - name: Health check
        uses: appleboy/ssh-action@v1.0.3
        with:
          host: ${{ secrets.SSH_HOST }}
          username: ${{ secrets.SSH_USER }}
          key: ${{ secrets.SSH_KEY }}
          script: |
            sleep 10
            curl --fail --retry 3 --retry-delay 5 http://localhost/health
```

- [ ] **Step 4: Update .env.example with all required vars**

Add to the bottom of `.env.example`:
```env
# Redis
REDIS_CLIENT=phpredis
REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379

# Queue / Cache
QUEUE_CONNECTION=database
CACHE_STORE=redis
SESSION_DRIVER=database

# Docker deploy notes
# .env.staging and .env.prod are created on the server — never committed.
# Copy this file, set DB_HOST=postgres, REDIS_HOST=redis inside Docker.
# GitHub Secrets required: SSH_HOST, SSH_USER, SSH_KEY
# GitHub Environments: "staging" and "production" (production requires manual approval)
```

- [ ] **Step 5: Add .env.testing if not present**

Verify `tests/` references — `phpunit.xml` already sets `DB_CONNECTION=sqlite` and `DB_DATABASE=:memory:`. Confirm `.env.testing` is not needed (phpunit.xml sets env directly):

```bash
grep -n "DB_CONNECTION" phpunit.xml
```
Expected: `<env name="DB_CONNECTION" value="sqlite"/>` — no `.env.testing` file needed.

- [ ] **Step 6: Final full test run**

```bash
php artisan test
```
Expected: all tests pass with no failures.

- [ ] **Step 7: Commit**

```bash
git -c user.name="Bahati" -c user.email="baha.dev@outlook.com" commit -m "feat: add GitHub Actions CI + staging/prod deploy workflows, update .env.example"
```

---

## Post-Implementation Checklist

After all tasks are complete, verify the following manually or via `php artisan test`:

- [ ] `php artisan route:list` — no errors, all new routes present
- [ ] `php artisan test` — full suite green
- [ ] Login as Admin → lands on `/admin/dashboard` with navy sidebar
- [ ] Login as VendorManager → lands on `/admin/vendor-manager/dashboard`
- [ ] Login as Verifier → lands on `/verifier/dashboard`
- [ ] Login as CustomerService → lands on `/admin/cs/dashboard`
- [ ] Login as ContentManager → lands on `/admin/content/dashboard`
- [ ] Login as Vendor → lands on `/vendor/dashboard`
- [ ] Login as Buyer → lands on `/dashboard`
- [ ] `GET /health` returns `{"status":"ok"}` with 200
- [ ] Docker Compose staging: `docker compose -f docker-compose.staging.yml config` — no YAML errors
- [ ] Docker Compose prod: `docker compose -f docker-compose.prod.yml config` — no YAML errors
