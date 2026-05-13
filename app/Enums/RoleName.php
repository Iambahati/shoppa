<?php

namespace App\Enums;

enum RoleName: string
{
    case SuperAdmin     = 'Super Admin';
    case Admin          = 'Admin';
    case VendorManager  = 'Vendor Manager';
    case Verifier       = 'Verifier';
    case CustomerService = 'Customer Service';
    case ContentManager = 'Content Manager';
    case Vendor         = 'Vendor';
    case User           = 'User';
    case Guest          = 'Guest';

    public function label(): string
    {
        return match ($this) {
            self::SuperAdmin      => 'Super Admin',
            self::Admin           => 'Admin',
            self::VendorManager   => 'Vendor Manager',
            self::Verifier        => 'Verifier',
            self::CustomerService => 'Customer Service',
            self::ContentManager  => 'Content Manager',
            self::Vendor          => 'Vendor',
            self::User            => 'Buyer',
            self::Guest           => 'Guest',
        };
    }

    /**
     * Which roles can access the admin panel at all.
     */
    public static function staffRoles(): array
    {
        return [
            self::SuperAdmin,
            self::Admin,
            self::VendorManager,
            self::Verifier,
            self::CustomerService,
            self::ContentManager,
        ];
    }

    public function isStaff(): bool
    {
        return in_array($this, self::staffRoles(), strict: true);
    }

    /**
     * Where to redirect after login, keyed by role.
     */
    public function dashboardRoute(): string
    {
        return match ($this) {
            self::SuperAdmin, self::Admin  => 'admin.dashboard',
            self::VendorManager           => 'admin.vendors.index',
            self::Verifier                => 'verifier.queue',
            self::CustomerService         => 'admin.disputes.index',
            self::ContentManager          => 'admin.products.index',
            self::Vendor                  => 'vendor.dashboard',
            self::User, self::Guest       => 'buyer.dashboard',
        };
    }
}
