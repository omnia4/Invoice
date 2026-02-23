<?php

namespace App\Models;

use App\Enums\ContractStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;
     protected $fillable = [
        'tenant_id',
        'unit_name',
        'customer_name',
        'rent_amount',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'status' => ContractStatus::class,
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
