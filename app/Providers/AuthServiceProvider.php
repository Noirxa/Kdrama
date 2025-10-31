<?php

namespace App\Providers;

// BELANGRIJK: Importeer de base ServiceProvider
use Illuminate\Support\ServiceProvider;

// Dit was je vergeten, vandaar de 'Class not found' error
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as BaseAuthServiceProvider;
use Illuminate\Support\Facades\Gate;

// IMPORTEER JE MODEL EN POLICY
use App\Models\Kdrama;
use App\Policies\KdramaPolicy;

// Let op: 'extends BaseAuthServiceProvider'
class AuthServiceProvider extends BaseAuthServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',

        // HIER HOORT JE REGEL
        Kdrama::class => KdramaPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        //
    }
}
