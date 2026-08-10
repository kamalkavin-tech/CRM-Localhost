<?php

declare(strict_types=1);

namespace App\Enums\Pipeline;

use App\Contracts\Pipeline\PipelineSubStage;

enum ExpenseSubStage: string implements PipelineSubStage
{
    case SALARY = 'salary';
    case SOFTWARE = 'software';
    case INFRASTRUCTURE = 'infrastructure';
    case MARKETING = 'marketing';
    case OPERATIONS = 'operations';
    case OFFICE = 'office';
    case EMPLOYEE_SUBMITTED = 'employee_submitted';
    case VENDOR_BILL_RECEIVED = 'vendor_bill_received';
    case RECURRING_EXPENSE = 'recurring_expense';
    case MANAGER_REVIEW = 'manager_review';
    case FINANCE_REVIEW = 'finance_review';
    case SUPPORTING_DOCUMENTS = 'supporting_documents';
    case APPROVED = 'approved';
    case SCHEDULED_FOR_PAYMENT = 'scheduled_for_payment';
    case PAYMENT_INITIATED = 'payment_initiated';
    case PAYMENT_PROCESSING = 'payment_processing';
    case PAID = 'paid';
    case PAYMENT_VERIFIED = 'payment_verified';
    case RECEIPT_RECORDED = 'receipt_recorded';
    case BANK_RECONCILED = 'bank_reconciled';
    case ACCOUNTING_RECORDED = 'accounting_recorded';
    case REJECTED = 'rejected';
    case DUPLICATE = 'duplicate';
    case INCORRECT_AMOUNT = 'incorrect_amount';
    case CANCELLED = 'cancelled';

    public function getLabel(): string
    {
        return __('pipelines.expense.sub_stages.'.$this->value);
    }

    public function stage(): ExpenseStage
    {
        return match ($this) {
            self::SALARY, self::SOFTWARE, self::INFRASTRUCTURE,
            self::MARKETING, self::OPERATIONS, self::OFFICE => ExpenseStage::EXPENSE_PLANNED,
            self::EMPLOYEE_SUBMITTED, self::VENDOR_BILL_RECEIVED,
            self::RECURRING_EXPENSE => ExpenseStage::EXPENSE_SUBMITTED,
            self::MANAGER_REVIEW, self::FINANCE_REVIEW,
            self::SUPPORTING_DOCUMENTS => ExpenseStage::VERIFICATION,
            self::APPROVED, self::SCHEDULED_FOR_PAYMENT => ExpenseStage::APPROVED,
            self::PAYMENT_INITIATED, self::PAYMENT_PROCESSING => ExpenseStage::PAYMENT_PROCESSING,
            self::PAID, self::PAYMENT_VERIFIED, self::RECEIPT_RECORDED => ExpenseStage::PAID,
            self::BANK_RECONCILED, self::ACCOUNTING_RECORDED => ExpenseStage::RECONCILIATION,
            self::REJECTED, self::DUPLICATE, self::INCORRECT_AMOUNT,
            self::CANCELLED => ExpenseStage::REJECTED_CANCELLED,
        };
    }
}
