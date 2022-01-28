<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
         'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Passport::routes();
        Passport::tokensCan([
            'client' => 'Client for guest access',
            'admin' => 'Access Admin Backend',
            'user' => 'Access User Backend',

            'place-orders' => 'Place orders',
            'check-balance' => 'Check balance status',
        ]);
        Passport::setDefaultScope([
            'admin',
        ]);

        Passport::cookie('tang_token');
        //
    }
}
