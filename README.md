# Shoppa

**Trust-as-a-Service electronics marketplace for Kenya and East Africa.**

Shoppa does not sell devices. It verifies them.

Every device listed on Shoppa is physically inspected by a licensed Verifier before going live. A cryptographically signed Trust Certificate — queryable by IMEI or serial number — is issued per device. Buyers pay into escrow; funds release to the seller only after the buyer confirms receipt. The result: a marketplace where a counterfeit iPhone 11 dressed as an iPhone 17 Pro Max cannot survive.

---

## The problem

Nairobi's second-hand electronics market is broken. Counterfeit hardware modified to present as premium models, stolen devices laundered through informal sellers, and opaque pricing with no recourse. Shoppa is the infrastructure layer that eliminates this — for buyers, sellers, and eventually the whole East African market.

---

## How it works

```
Vendor submits listing → Verifier inspects device → Trust Certificate issued
                                                   ↓
                         Buyer places order → Escrow holds funds
                                           → Buyer confirms receipt
                                           → Funds released to vendor
```

Every step is logged to an immutable audit trail. Every status transition is role-gated. Every certificate is UUID-signed and publicly verifiable.

---

## Tech stack

| Layer | Choice |
|---|---|
| Framework | Laravel 11 (PHP 8.2+) |
| Auth | Laravel Fortify with custom Blade views |
| Database | PostgreSQL |
| Frontend | Blade + Alpine.js + Tailwind CSS |
| Build | Vite |
| File storage | Spatie MediaLibrary + S3 |
| Activity logging | Spatie ActivityLog |
| Queue | Laravel Queue (database in dev, Redis in prod) |
| Cache | Redis |

No React. No Vue. No SPA. All state is server-side.

---

## Getting started

```bash
# 1. Install dependencies
composer install
npm install

# 2. Environment
cp .env.example .env
php artisan key:generate
# Set DB_CONNECTION=pgsql and configure DB_* vars

# 3. Migrate and seed
php artisan migrate
php artisan db:seed

# 4. Create a Super Admin
php artisan tinker
>>> $role = App\Models\Role::where('name', 'Super Admin')->first();
>>> App\Models\User::create([
...     'name'     => 'Admin',
...     'email'    => 'admin@shoppa.co.ke',
...     'password' => bcrypt('password'),
...     'role_id'  => $role->id,
... ])->markEmailAsVerified();

# 5. Start the dev server
npm run dev
php artisan serve
```

---

## Roles

| Role | Access |
|---|---|
| Super Admin | Bypasses all gates — full system access |
| Admin | Full panel minus device certification |
| Vendor Manager | Vendor approvals, listings, orders |
| Verifier | **Issues Trust Certificates** — the only role that can certify a device |
| Customer Service | Disputes, refunds, support tickets |
| Content Manager | Product catalog, categories |
| Vendor | Own listings and orders |
| User (Buyer) | Browse, purchase, escrow |
| Guest | Browse only |

Public registration assigns the `User` (Buyer) role. Vendors apply and are approved by Admin / Vendor Manager.

---

## The Trust Engine

The verification pipeline is the core product. Devices move through a one-way state machine:

```
pending → in_review → verified
                   ↘ rejected
```

Rules:
- An IMEI must be unique across all active listings — duplicates are blocked at the DB constraint level.
- Only the `Verifier` role can call `certify` or `reject`. Cert UUIDs are server-generated; they cannot be set by a vendor.
- Every status transition is logged via Spatie ActivityLog with the acting user's ID. This is a legal audit requirement.
- Devices flagged in `theft_reports` are blocked from listing (Sprint 4).
- Trust Certificates expire after 90 days — configurable via `config('shoppa.trust_cert.valid_days')`.

---

## Routes

| Prefix | Audience |
|---|---|
| `/` | Buyers (dashboard, browse, orders) |
| `/vendor` | Vendors (apply, listings, earnings) |
| `/admin` | Staff panel (Admin, Verifier, CS, Content Manager) |
| `/verifier` | Inspection queue and device certification |
| `/verify/{identifier}` | **Public** — anyone can look up a Trust Certificate by IMEI or serial number |

---

## Sprint roadmap

| Sprint | Focus | Status |
|---|---|---|
| S1 | IAM, roles, permissions, auth pages, layouts, base components | ✅ Complete |
| S2 | Vendor lifecycle, KYC uploads, trust scoring | Next |
| S3 | Product catalog, device PIM, search | Planned |
| S4 | Trust Engine — IMEI validation, inspection workflow, cert generation, theft registry | Planned |
| S5 | Buyer journey — cart, orders, wishlist, escrow confirm | Planned |
| S6 | Payments (M-Pesa Daraja), escrow state machine, disputes, vendor payouts | Planned |

---

## Development commands

```bash
# Run tests
php artisan test

# Re-seed cleanly (drops all tables)
php artisan migrate:fresh --seed

# Process queued jobs
php artisan queue:work

# Inspect registered routes
php artisan route:list --columns=method,uri,name,middleware
```

---

## Configuration

Business rules live in `config/shoppa.php` — not hardcoded in application logic:

```php
'escrow'       => ['release_after_days' => 3]
'verification' => ['fee_min_ksh' => 700, 'fee_max_ksh' => 1000]
'commission'   => ['default_percent' => 5]
'trust_cert'   => ['valid_days' => 90]
```
