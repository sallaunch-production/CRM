<?php

namespace App\Providers;
use Illuminate\Support\Facades\Gate;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
        $this->registerPolicies();

        Gate::define('direktur-only', function () {
            if (Auth::user()->level == 'direktur'){
                return true;
            }
            return false;
        });
        Gate::define('operasional-only', function () {
            if (Auth::user()->level == 'operasional'){
                return true;
            }
            return false;
        });
        Gate::define('finance-only', function () {
            if (Auth::user()->level == 'finance'){
                return true;
            }
            return false;
        });

        Gate::define('compliance-only', function () {
            if (Auth::user()->level == 'compliance'){
                return true;
            }
            return false;
        });

        Gate::define('it-only', function () {
            if (Auth::user()->level == 'it'){
                return true;
            }
            return false;
        });
       
    }
}
