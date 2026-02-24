<?php

namespace App\Services;

use App\Models\Invoice;
use Carbon\Carbon;

class InvoiceNumberGenerator
{
    public static function generate(int $tenantId): string
    {
        $month = now()->format('Ym');

  
        $lastInvoice = Invoice::where('tenant_id', $tenantId)
            ->where('invoice_number', 'like', "INV-{$tenantId}-{$month}-%")
            ->orderBy('invoice_number', 'desc')
            ->first();

        if ($lastInvoice) {
            $lastSeq = (int) substr($lastInvoice->invoice_number, -4);
            $nextSeq = str_pad($lastSeq + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $nextSeq = '0001';
        }

        return "INV-{$tenantId}-{$month}-{$nextSeq}";
    }
}