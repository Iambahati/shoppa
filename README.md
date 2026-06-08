<p align="center">
  <strong>Shoppa</strong> is a feature-rich, role-aware e-commerce dashboard for vendors, buyers, and administrators built with Laravel 12.
</p>

## About Shoppa

Shoppa emulates a boutique marketplace: buyers place orders and request returns, vendors fulfill shipments, and an admin team manages disputes, products, and promotions. The app arrives with domain models (orders, refunds, shipments, vendors, coupon campaigns, etc.) plus role-driven navigation powered by policy-aware enums and seeders that provision the staff/user roles and relations needed for an admin dashboard and public storefront.

Key highlights:

- Multi-tenant user roles (Super Admin, Admin, Vendor, Customer Service, Buyer) with helper enums that drive dashboard redirects and permissions.
- Rich order lifecycle models (returns, refunds, shipments, commissions) and cascading tables to analyze vendor revenue and customer history.
- Seeders that prime every model needed to explore the domain (roles, permissions, users, addresses, vendor profiles, etc.).
- Laravel Fortify authentication with email verification, two-factor auth helpers, and policy-aware user authorization.

## Getting Started

1. Copy the environment file and generate keys:
   ```bash
   cp .env.example .env
   composer install
   php artisan key:generate
   ```
2. Configure your database/queue/MAIL settings in `.env`, then migrate:
   ```bash
   php artisan migrate
   ```
3. Seed the database (roles, permissions, users, vendors, addresses, etc.):
   ```bash
   php artisan db:seed
   ```
4. Build assets and serve the app:
   ```bash
   npm install
   npm run dev
   php artisan serve
   ```

## Seeding Models

The `DatabaseSeeder` orchestrates several sub-seeders defined in `database/seeders/`:

- `RoleSeeder`, `PermissionSeeder`, `RolePermissionSeeder` define the role/permission graph.
- `UserSeeder` and `UserAddressSeeder` create buyers, vendors, and a Super Admin with the required addresses.
- `VendorSeeder` links vendor users to vendor profiles and attaches demo media.

After running `php artisan db:seed`, you can browse the admin panel at `/admin`, the vendor workspace at `/vendor`, and the buyer experience at `/buyer` using the seeded accounts.

## Running & Testing

- Use `php artisan test` to execute the test suite.
- To replay jobs/queues: `php artisan queue:work`.
- Run `php artisan route:list` to inspect registered routes, grouped by middleware and guards.

Enjoy exploring Shoppa!
