<?php

namespace App\Repositories\Contracts;

use App\Models\Contract;
use App\Repositories\Contracts\ContractRepositoryInterface;

class EloquentContractRepository implements ContractRepositoryInterface
{
    public function findById(int $id): Contract
    {
        return Contract::findOrFail($id);
    }
}