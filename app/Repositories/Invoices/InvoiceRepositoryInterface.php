<?php

namespace App\Repositories\Invoices;


interface InvoiceRepositoryInterface
{
    public function create(array $data);
    public function findById(int $id);
    public function getByContractId(int $contractId);
}