<?php

namespace App\Services;

use App\Models\Invoice;
use Carbon\Carbon;

class InvoiceNumberGenerator
{
    public static function generate(int $tenantId): string
    {
        $now = Carbon::now();
        $yearMonth = $now->format('Ym'); 

        $lastInvoice = Invoice::where('tenant_id', $tenantId)
            ->where('invoice_number', 'like', "INV-{$tenantId}-{$yearMonth}-%")
            ->orderByDesc('id')
            ->first();

        $sequence = 1;

        if ($lastInvoice) {
            $lastSequence = (int) substr($lastInvoice->invoice_number, -4);
            $sequence = $lastSequence + 1;
        }

        return sprintf(
            'INV-%03d-%s-%04d',
            $tenantId,
            $yearMonth,
            $sequence
        );
    }
}