# Invoice Management API - Real Estate Platform

This is an **Invoice Management API** module for a real estate management platform.  
It is built with **Laravel 10** and **PHP 8.1**, following clean architecture principles, OOP best practices, and a layered design.

---

## Table of Contents

- [Architecture Overview](#architecture-overview)
- [Domain Models & Database](#domain-models--database)
- [Data Transfer Objects (DTOs)](#data-transfer-objects-dtos)
- [Tax Calculation System](#tax-calculation-system)
- [Repository Layer](#repository-layer)
- [Invoice Service (Business Logic)](#invoice-service-business-logic)
- [Authorization Policies](#authorization-policies)
- [Controllers & API Endpoints](#controllers--api-endpoints)
- [API Resources](#api-resources)
- [Bonus Features](#bonus-features)
- [Postman API Documentation](#postman-api-documentation)

---

## Architecture Overview

The API follows a **layered architecture**:

- **Form Request:** Validation only, no business logic.
- **DTOs:** Immutable data objects carrying validated data from Form Request to Service.
- **Controller:** Thin; handles authorization and calls Service.
- **Policy:** Handles tenant-based authorization.
- **Service:** Contains all business logic, tax calculation, and transaction management.
- **Repository:** Handles all database access via Eloquent, Service never touches Eloquent directly.
- **API Resource:** Controls JSON output, computed fields, and conditional relationships.

---

## Domain Models & Database

### Models

1. **Contract**
   - Fields: `id, tenant_id, unit_name, customer_name, rent_amount, start_date, end_date, status`
   - Status Enum: `draft, active, expired, terminated`
   - Relationships:
     - `hasMany(Invoices)`

2. **Invoice**
   - Fields: `id, contract_id, invoice_number, subtotal, tax_amount, total, status, due_date, paid_at`
   - Status Enum: `pending, paid, partially_paid, overdue, cancelled`
   - Relationships:
     - `belongsTo(Contract)`
     - `hasMany(Payments)`

3. **Payment**
   - Fields: `id, invoice_id, amount, payment_method, reference_number, paid_at`
   - Payment Method Enum: `cash, bank_transfer, credit_card`
   - Relationships:
     - `belongsTo(Invoice)`

---

## Data Transfer Objects (DTOs)

- **CreateInvoiceDTO**
  - Carries `contract_id, due_date, tenant_id`
  - Created from `StoreInvoiceRequest`
  - Immutable, no business logic

- **RecordPaymentDTO**
  - Carries `invoice_id, amount, payment_method, reference_number`
  - Created from `StorePaymentRequest`
  - Immutable

---

## Tax Calculation System

- **Strategy Pattern** used to calculate taxes.
- **Interface:** `TaxCalculatorInterface` → `calculate(float $amount): float`
- **Concrete Tax Calculators:**
  - `VatTaxCalculator` → 15%
  - `MunicipalFeeCalculator` → 2.5%
- **TaxService:** Aggregates all tax calculators and returns total tax.
- **Extensible:** Add new tax types by creating a class + registering in `TaxServiceProvider`.

---

## Repository Layer

- Interfaces and Eloquent implementations:
  - `ContractRepositoryInterface` → `EloquentContractRepository`
  - `InvoiceRepositoryInterface` → `EloquentInvoiceRepository`
  - `PaymentRepositoryInterface` → `EloquentPaymentRepository`
- Service layer depends on Interfaces only.
- Methods example: `findById()`, `create()`, `getByContractId()`

---

## Invoice Service (Business Logic)

- Handles:
  - Creating invoices from contracts
    - Validate contract is active
    - Calculate subtotal & taxes
    - Generate invoice number: `INV-{TENANT_ID}-{YYYYMM}-{SEQUENCE}`
    - Persist invoice
  - Recording payments
    - Validate amount ≤ remaining balance
    - Update invoice status automatically (`paid` or `partially_paid`)
  - Financial summary
    - Total invoiced, total paid, outstanding balance
- All multi-step operations are wrapped in **database transactions**

---

## Authorization Policies

**InvoicePolicy**
- `create(User $user, Contract $contract)`
- `view(User $user, Invoice $invoice)`
- `recordPayment(User $user, Invoice $invoice)`

Rules:
- Users can only access resources belonging to their tenant
- Cancelled invoices cannot receive payments

---

## Controllers & API Endpoints

| Method | Endpoint | Description | Body (JSON) |
|--------|---------|------------|-------------|
| POST | /api/contracts/{contract_id}/invoices | Create invoice | `{ "due_date": "YYYY-MM-DD" }` |
| GET | /api/contracts/{contract_id}/invoices | List invoices | N/A |
| GET | /api/invoices/{invoice_id} | Get invoice details | N/A |
| POST | /api/invoices/{invoice_id}/payments | Record payment | `{ "amount": 500.00, "payment_method": "cash", "reference_number": "REF123" }` |
| GET | /api/contracts/{contract_id}/summary | Contract financial summary | N/A |

- Controllers are **thin**, handle only DTO creation, authorization, and delegate to Service.
- Responses use **API Resources**.

---

## API Resources

- **InvoiceResource**
  - Fields: `id, invoice_number, subtotal, tax_amount, total, status, due_date, paid_at, remaining_balance`
  - Includes `contract` & `payments` when loaded
- **PaymentResource**
  - Fields: `id, amount, payment_method, reference_number, paid_at`
- **ContractSummaryResource**
  - Fields: `contract_id, total_invoiced, total_paid, outstanding_balance, invoices_count, latest_invoice_date`

---

## Bonus Features

- Custom Exceptions:
  - `ContractNotActiveException`
  - `InsufficientBalanceException`
- Global Scope for tenant_id (multi-tenancy)
- Artisan command to mark overdue invoices
- Observer/Event for invoice paid notifications/logging

---

## Postman API Documentation

All API endpoints are documented in Postman:  

[Invoice Management API - Postman](https://documenter.getpostman.com/view/20110993/2sBXcGDebR)

You can **import this link into Postman** and test all endpoints with example requests.

---

## Installation & Setup

```bash
git clone <repo-url>
cd invoice-management-api
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve