<?php

namespace App\Models;

use App\Enums\InvoiceStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
     protected $fillable = [
        'tenant_id',
        'contract_id',
        'invoice_number',
        'subtotal',
        'tax_amount',
        'total',
        'status',
        'due_date',
        'paid_at',
    ];

    protected $casts = [
        'status' => InvoiceStatus::class,
        'due_date' => 'date',
        'paid_at' => 'datetime',
    ];

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
