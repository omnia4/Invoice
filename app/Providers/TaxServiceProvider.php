<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Tax\TaxService;
use App\Services\Tax\VatTaxCalculator;
use App\Services\Tax\MunicipalFeeCalculator;

class TaxServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(TaxService::class, function () {
            return new TaxService([
                new VatTaxCalculator(),
                new MunicipalFeeCalculator(),
            ]);
        });
    }
}