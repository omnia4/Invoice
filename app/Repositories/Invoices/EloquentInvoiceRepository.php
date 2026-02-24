<?php

namespace App\Repositories\Invoices;

use App\Models\Invoice;
use App\Repositories\Invoices\InvoiceRepositoryInterface;

class EloquentInvoiceRepository implements InvoiceRepositoryInterface
{
    public function create(array $data)
    {
        return Invoice::create($data);
    }

    public function findById(int $id)
    {
        return Invoice::with('payments')->findOrFail($id);
    }

    public function getByContractId(int $contractId)
    {
        return Invoice::where('contract_id', $contractId)->get();
    }
}
