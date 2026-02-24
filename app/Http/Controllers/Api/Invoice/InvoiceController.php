<?php

namespace App\Http\Controllers\Api\Invoice;

use App\DTOs\CreateInvoiceDTO;
use App\DTOs\RecordPaymentDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Invoice\StoreInvoiceRequest;
use App\Http\Requests\Payment\StorePaymentRequest;
use App\Http\Resources\Api\Contract\ContractSummaryResource;
use App\Http\Resources\Api\Invoice\InvoiceResource;
use App\Http\Resources\Api\Payment\PaymentResource;
use App\Models\Contract;
use App\Models\Invoice;
use App\Services\InvoiceService;


class InvoiceController extends Controller
{
    public function __construct(
        private InvoiceService $invoiceService
    ) {}

    public function store(StoreInvoiceRequest $request, Contract $contract)
    {
//         dd([
//     'auth_user' => auth()->user(),
//     'user_tenant' => auth()->user()?->tenant_id,
//     'contract_tenant' => $contract->tenant_id,
// ]);
        $this->authorize('create', [Invoice::class, $contract]);

       $dto = CreateInvoiceDTO::fromRequest($request, $contract);
        $invoice = $this->invoiceService->createInvoice($dto);

        return InvoiceResource::make($invoice)
            ->response()
            ->setStatusCode(201);
    }

public function index(Contract $contract)
{
    $this->authorize('view', $contract);

    $filters = [
        'status' => request('status'),
        'from' => request('from'),
        'to' => request('to'),
        'min_total' => request('min_total'),
        'max_total' => request('max_total'),
    ];

    $invoices = $this->invoiceService
                     ->getInvoicesForContract($contract->id, $filters);

    return InvoiceResource::collection($invoices);
}

    public function show(Invoice $invoice)
    {
        $this->authorize('view', $invoice);

        return InvoiceResource::make(
            $invoice->load(['contract', 'payments'])
        );
    }

    public function recordPayment(
        StorePaymentRequest $request,
        Invoice $invoice
    ) {
        $this->authorize('recordPayment', $invoice);

            $dto = RecordPaymentDTO::fromRequest(
        $request,
        $invoice->id
    );
        $payment = $this->invoiceService->recordPayment($dto);

        return PaymentResource::make($payment)
            ->response()
            ->setStatusCode(201);
    }

    public function summary(Contract $contract)
    {
        $this->authorize('view', $contract);

        $summary = $this->invoiceService
            ->getContractSummary($contract->id);

        return new ContractSummaryResource($summary);
    }
}
