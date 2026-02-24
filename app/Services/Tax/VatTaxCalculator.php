<?php

namespace App\Services\Tax;

class VatTaxCalculator implements TaxCalculatorInterface
{
    public function calculate(float $amount): float
    {
        return $amount * 0.15;
    }
}