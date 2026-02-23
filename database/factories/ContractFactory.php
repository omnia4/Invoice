<?php

namespace Database\Factories;

use App\Enums\ContractStatus;
use App\Models\Contract;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contract>
 */
class ContractFactory extends Factory
{
    protected $model = Contract::class;

    public function definition()
    {
        return [
            'tenant_id' => 1,
            'unit_name' => $this->faker->word,
            'customer_name' => $this->faker->name,
            'rent_amount' => 1000,
            'start_date' => now(),
            'end_date' => now()->addYear(),
            'status' => ContractStatus::Active,
        ];
    }
}
