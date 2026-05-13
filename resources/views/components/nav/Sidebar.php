<?php

namespace App\View\Components\Nav;

use App\Enums\RoleName;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Sidebar extends Component
{
    /** @var array<array{label: string, route: string, icon: string, active: string}> */
    public array $navItems;

    public function __construct(public bool $staff = false)
    {
        $this->navItems = $this->buildNav();
    }

    private function buildNav(): array
    {
        $user = auth()->user();
        $role = $user?->roleName();

        return match(true) {
            $role === RoleName::SuperAdmin, $role === RoleName::Admin => $this->adminNav(),
            $role === RoleName::VendorManager                        => $this->vendorManagerNav(),
            $role === RoleName::Verifier                             => $this->verifierNav(),
            $role === RoleName::CustomerService                      => $this->customerServiceNav(),
            $role === RoleName::ContentManager                       => $this->contentManagerNav(),
            $role === RoleName::Vendor                               => $this->vendorNav(),
            default                                                  => $this->buyerNav(),
        };
    }

    // ── Nav sets ──────────────────────────────────────────────────────────────

    private function buyerNav(): array
    {
        return [
            ['label' => 'Dashboard',  'route' => 'buyer.dashboard',    'icon' => 'home',   'active' => 'buyer.dashboard'],
            ['label' => 'Browse',     'route' => 'buyer.browse',       'icon' => 'search', 'active' => 'buyer.browse'],
            ['label' => 'My orders',  'route' => 'buyer.orders.index', 'icon' => 'box',    'active' => 'buyer.orders.*'],
        ];
    }

    private function vendorNav(): array
    {
        return [
            ['label' => 'Dashboard',  'route' => 'vendor.dashboard',       'icon' => 'home',   'active' => 'vendor.dashboard'],
            ['label' => 'Listings',   'route' => 'vendor.listings.index',  'icon' => 'layers', 'active' => 'vendor.listings.*'],
        ];
    }

    private function adminNav(): array
    {
        return [
            ['label' => 'Dashboard', 'route' => 'admin.dashboard',       'icon' => 'home',    'active' => 'admin.dashboard'],
            ['label' => 'Users',     'route' => 'admin.users.index',     'icon' => 'users',   'active' => 'admin.users.*'],
            ['label' => 'Vendors',   'route' => 'admin.vendors.index',   'icon' => 'store',   'active' => 'admin.vendors.*'],
            ['label' => 'Products',  'route' => 'admin.products.index',  'icon' => 'package', 'active' => 'admin.products.*'],
            ['label' => 'Disputes',  'route' => 'admin.disputes.index',  'icon' => 'flag',    'active' => 'admin.disputes.*'],
        ];
    }

    private function vendorManagerNav(): array
    {
        return [
            ['label' => 'Dashboard', 'route' => 'admin.dashboard',      'icon' => 'home',  'active' => 'admin.dashboard'],
            ['label' => 'Vendors',   'route' => 'admin.vendors.index',  'icon' => 'store', 'active' => 'admin.vendors.*'],
        ];
    }

    private function verifierNav(): array
    {
        return [
            ['label' => 'Inspect queue', 'route' => 'verifier.queue',            'icon' => 'shield',  'active' => 'verifier.queue'],
            ['label' => 'Inspections',   'route' => 'verifier.inspections.show', 'icon' => 'cpu',     'active' => 'verifier.inspections.*'],
        ];
    }

    private function customerServiceNav(): array
    {
        return [
            ['label' => 'Dashboard', 'route' => 'admin.dashboard',      'icon' => 'home',       'active' => 'admin.dashboard'],
            ['label' => 'Disputes',  'route' => 'admin.disputes.index', 'icon' => 'message-sq', 'active' => 'admin.disputes.*'],
        ];
    }

    private function contentManagerNav(): array
    {
        return [
            ['label' => 'Dashboard', 'route' => 'admin.dashboard',      'icon' => 'home',    'active' => 'admin.dashboard'],
            ['label' => 'Products',  'route' => 'admin.products.index', 'icon' => 'package', 'active' => 'admin.products.*'],
        ];
    }

    public function render(): View|Closure|string
    {
        return view('components.nav.sidebar');
    }
}