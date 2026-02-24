<?php

namespace App\Http\Resources\Api\Invoice;

use App\Http\Resources\Api\Contract\ContractResource;
use App\Http\Resources\Api\Payment\PaymentResource;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
  public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'invoice_number' => $this->invoice_number,
            'subtotal' => $this->subtotal,
            'tax_amount' => $this->tax_amount,
            'total' => $this->total,
            'status' => $this->status->value,
            'due_date' => $this->due_date,
            'paid_at' => $this->paid_at,

            'remaining_balance' =>
                $this->total - $this->payments->sum('amount'),

            'contract' => ContractResource::make(
                $this->whenLoaded('contract')
            ),

            'payments' => PaymentResource::collection(
                $this->whenLoaded('payments')
            ),
        ];
    }
}
