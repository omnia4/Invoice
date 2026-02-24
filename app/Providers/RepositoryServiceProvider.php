<?php

namespace App\Providers;

use App\Repositories\Contracts\ContractRepositoryInterface;
use App\Repositories\Contracts\EloquentContractRepository;
use App\Repositories\Invoices\EloquentInvoiceRepository;
use App\Repositories\Invoices\InvoiceRepositoryInterface;
use App\Repositories\Payment\EloquentPaymentRepository;
use App\Repositories\Payment\PaymentRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
   public function register(): void
    {
        $this->app->bind(
            ContractRepositoryInterface::class,
            EloquentContractRepository::class
        );

        $this->app->bind(
            InvoiceRepositoryInterface::class,
            EloquentInvoiceRepository::class
        );

        $this->app->bind(
            PaymentRepositoryInterface::class,
            EloquentPaymentRepository::class
        );
    }
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
