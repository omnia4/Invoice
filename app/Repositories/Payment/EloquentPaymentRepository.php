<?php

namespace App\Repositories\Payment;

use App\Models\Payment;
use App\Repositories\Payment\PaymentRepositoryInterface;

class EloquentPaymentRepository implements PaymentRepositoryInterface
{
    public function create(array $data)
    {
        return Payment::create($data);
    }
}