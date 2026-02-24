<?php

namespace App\Http\Resources\Api\Payment;


use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'payment_method' => $this->payment_method->value,
            'reference_number' => $this->reference_number,
            'paid_at' => $this->paid_at,
        ];
    }
}

