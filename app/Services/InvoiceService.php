<?php

namespace App\Services;

use App\DTOs\CreateInvoiceDTO;
use App\DTOs\RecordPaymentDTO;
use App\Enums\ContractStatus;
use App\Enums\InvoiceStatus;
use App\Repositories\Contracts\ContractRepositoryInterface;
use App\Repositories\Invoices\InvoiceRepositoryInterface;
use App\Repositories\Payment\PaymentRepositoryInterface;
use App\Services\InvoiceNumberGenerator;
use App\Services\Tax\TaxService;
use Illuminate\Support\Facades\DB;

class InvoiceService
{
    public function __construct(
        private ContractRepositoryInterface $contractRepo,
        private InvoiceRepositoryInterface $invoiceRepo,
        private PaymentRepositoryInterface $paymentRepo,
        private TaxService $taxService,
    ) {}

    public function createInvoice(CreateInvoiceDTO $dto)
    {
        return DB::transaction(function () use ($dto) {

            $contract = $this->contractRepo->findById($dto->contract_id);

            if ($contract->status !== ContractStatus::Active) {
                throw new \Exception('Contract is not active');
            }

            $subtotal = $contract->rent_amount;
            $tax = $this->taxService->calculateTotalTax($subtotal);

            return $this->invoiceRepo->create([
                'tenant_id' => $dto->tenant_id,
                'contract_id' => $contract->id,
                'invoice_number' => InvoiceNumberGenerator::generate($dto->tenant_id),
                'subtotal' => $subtotal,
                'tax_amount' => $tax,
                'total' => $subtotal + $tax,
                'status' => InvoiceStatus::Pending,
                'due_date' => $dto->due_date,
            ]);
        });
    }

    public function recordPayment(RecordPaymentDTO $dto)
    {
        return DB::transaction(function () use ($dto) {

            $invoice = $this->invoiceRepo->findById($dto->invoice_id);

            $paidSoFar = $invoice->payments->sum('amount');
            $remaining = $invoice->total - $paidSoFar;

            if ($dto->amount > $remaining) {
                throw new \Exception('Payment exceeds remaining balance');
            }

            $payment = $this->paymentRepo->create([
                'invoice_id' => $invoice->id,
                'amount' => $dto->amount,
                'payment_method' => $dto->payment_method,
                'reference_number' => $dto->reference_number,
                'paid_at' => now(),
            ]);

            $newTotalPaid = $paidSoFar + $dto->amount;

            $invoice->update([
                'status' => $newTotalPaid == $invoice->total
                    ? InvoiceStatus::Paid
                    : InvoiceStatus::PartiallyPaid,
                'paid_at' => $newTotalPaid == $invoice->total ? now() : null,
            ]);

            return $payment;
        });
    }

    public function getContractSummary(int $contractId): array
    {
        $invoices = $this->invoiceRepo->getByContractId($contractId);

        $totalInvoiced = $invoices->sum('total');
        $totalPaid = $invoices->flatMap->payments->sum('amount');

        return [
            'contract_id' => $contractId,
            'total_invoiced' => $totalInvoiced,
            'total_paid' => $totalPaid,
            'outstanding_balance' => $totalInvoiced - $totalPaid,
            'invoices_count' => $invoices->count(),
            'latest_invoice_date' => optional($invoices->max('created_at')),
        ];
    }
}