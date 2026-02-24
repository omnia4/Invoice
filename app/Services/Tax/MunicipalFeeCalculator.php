<?php

namespace App\Services\Tax;

class MunicipalFeeCalculator implements TaxCalculatorInterface
{
    public function calculate(float $amount): float
    {
        return $amount * 0.025;
    }
}