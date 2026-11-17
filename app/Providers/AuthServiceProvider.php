<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('administrar-usuarios', fn (User $user) => $user->isSalesAdministrator());
        Gate::define('administrar-catalogo', fn (User $user) =>
            $user->isSalesAdministrator() || $user->hasRole(User::SUBADMIN_ROLE, 'web')
        );
    }
}
