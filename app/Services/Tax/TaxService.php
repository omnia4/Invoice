<?php

namespace App\Services\Tax;

class TaxService
{
    /**
     * @param iterable<TaxCalculatorInterface> $calculators
     */
    public function __construct(
        private iterable $calculators
    ) {}

    public function calculateTotalTax(float $subtotal): float
    {
        $totalTax = 0;

        foreach ($this->calculators as $calculator) {
            $totalTax += $calculator->calculate($subtotal);
        }

        return $totalTax;
    }
}