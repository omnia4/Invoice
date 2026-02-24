<?php

namespace App\Repositories\Payment;


interface PaymentRepositoryInterface
{
    public function create(array $data);
}