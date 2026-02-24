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

  public function getByContractId(int $contractId, array $filters = [])
{
    $query = Invoice::where('contract_id', $contractId);

    if (!empty($filters['status'])) {
        $query->where('status', $filters['status']);
    }

    if (!empty($filters['from'])) {
        $query->whereDate('created_at', '>=', $filters['from']);
    }

    if (!empty($filters['to'])) {
        $query->whereDate('created_at', '<=', $filters['to']);
    }

    if (!empty($filters['min_total'])) {
        $query->where('total', '>=', $filters['min_total']);
    }

    if (!empty($filters['max_total'])) {
        $query->where('total', '<=', $filters['max_total']);
    }

    return $query->with('payments')->get();
}
}
