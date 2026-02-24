<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Contract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
            \App\Models\Invoice::class => \App\Policies\InvoicePolicy::class,
                    Contract::class => \App\Policies\ContractPolicy::class,

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
