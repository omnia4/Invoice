<?php

namespace App\Http\Resources\Api\Contract;

use Illuminate\Http\Resources\Json\JsonResource;

class ContractResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'unit_name' => $this->unit_name,
            'customer_name' => $this->customer_name,
            'rent_amount' => $this->rent_amount,
            'status' => $this->status->value,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ];
    }
}