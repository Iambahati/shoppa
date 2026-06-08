<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Enums\RoleName;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Fortify\Fortify;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // \App\Models\Product::class => \App\Policies\ProductPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        //  Gates from permission strings 
        Gate::before(function (User $user, string $ability) {
            if ($user->hasRole(RoleName::SuperAdmin)) {
                return true;  // Super admin always passes
            }
        });

        Gate::define('is-staff', fn(User $user) => $user->isStaff());
    }
}
