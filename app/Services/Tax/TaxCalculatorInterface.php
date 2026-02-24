<?php

namespace App\Services\Tax;

interface TaxCalculatorInterface
{
    public function calculate(float $amount): float;
}