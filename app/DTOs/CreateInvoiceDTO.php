<?php

namespace App\DTOs;

use App\Http\Requests\Invoice\StoreInvoiceRequest;
use App\Models\Contract;


final class CreateInvoiceDTO
{
    public function __construct(
        public readonly int $contract_id,
        public readonly string $due_date,
        public readonly int $tenant_id,
    ) {}

public static function fromRequest(
    StoreInvoiceRequest $request,
    Contract $contract
): self {
    return new self(
        contract_id: $contract->id,
        due_date: $request->validated('due_date'),
        tenant_id: $request->user()->tenant_id,
    );
}

}