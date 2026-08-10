<?php

declare(strict_types=1);

namespace App\Enums\Pipeline;

use App\Contracts\Pipeline\PipelineSubStage;

enum InvoiceSubStage: string implements PipelineSubStage
{
    case ADVANCE_DUE = 'advance_due';
    case MILESTONE_DUE = 'milestone_due';
    case FINAL_PAYMENT_DUE = 'final_payment_due';
    case RECURRING_BILLING = 'recurring_billing';
    case INVOICE_DRAFTING = 'invoice_drafting';
    case INTERNAL_VERIFICATION = 'internal_verification';
    case READY_TO_SEND = 'ready_to_send';
    case SENT_TO_CLIENT = 'sent_to_client';
    case CLIENT_RECEIVED = 'client_received';
    case AWAITING_CONFIRMATION = 'awaiting_confirmation';
    case DUE_THIS_WEEK = 'due_this_week';
    case DUE_THIS_MONTH = 'due_this_month';
    case PAYMENT_REMINDER = 'payment_reminder';
    case FIRST_REMINDER = 'first_reminder';
    case SECOND_REMINDER = 'second_reminder';
    case ESCALATED = 'escalated';
    case CLIENT_COMMITMENT_RECEIVED = 'client_commitment_received';
    case PARTIAL_PAYMENT_RECEIVED = 'partial_payment_received';
    case BALANCE_PENDING = 'balance_pending';
    case PAYMENT_RECEIVED = 'payment_received';
    case PAYMENT_VERIFIED = 'payment_verified';
    case RECEIPT_ISSUED = 'receipt_issued';
    case OVERDUE_1_TO_7_DAYS = 'overdue_1_to_7_days';
    case OVERDUE_8_TO_30_DAYS = 'overdue_8_to_30_days';
    case OVERDUE_31_TO_60_DAYS = 'overdue_31_to_60_days';
    case OVERDUE_60_PLUS_DAYS = 'overdue_60_plus_days';
    case INVOICE_DISPUTE = 'invoice_dispute';
    case SCOPE_DISPUTE = 'scope_dispute';
    case PAYMENT_HOLD = 'payment_hold';
    case BAD_DEBT = 'bad_debt';
    case CANCELLED_INVOICE = 'cancelled_invoice';
    case CREDIT_NOTE = 'credit_note';

    public function getLabel(): string
    {
        return __('pipelines.invoice.sub_stages.'.$this->value);
    }

    public function stage(): InvoiceStage
    {
        return match ($this) {
            self::ADVANCE_DUE, self::MILESTONE_DUE, self::FINAL_PAYMENT_DUE,
            self::RECURRING_BILLING => InvoiceStage::BILLING_DUE,
            self::INVOICE_DRAFTING, self::INTERNAL_VERIFICATION,
            self::READY_TO_SEND => InvoiceStage::INVOICE_PREPARATION,
            self::SENT_TO_CLIENT, self::CLIENT_RECEIVED,
            self::AWAITING_CONFIRMATION => InvoiceStage::INVOICE_SENT,
            self::DUE_THIS_WEEK, self::DUE_THIS_MONTH,
            self::PAYMENT_REMINDER => InvoiceStage::PAYMENT_DUE,
            self::FIRST_REMINDER, self::SECOND_REMINDER, self::ESCALATED,
            self::CLIENT_COMMITMENT_RECEIVED => InvoiceStage::PAYMENT_FOLLOW_UP,
            self::PARTIAL_PAYMENT_RECEIVED, self::BALANCE_PENDING => InvoiceStage::PARTIALLY_PAID,
            self::PAYMENT_RECEIVED, self::PAYMENT_VERIFIED,
            self::RECEIPT_ISSUED => InvoiceStage::PAID,
            self::OVERDUE_1_TO_7_DAYS, self::OVERDUE_8_TO_30_DAYS,
            self::OVERDUE_31_TO_60_DAYS, self::OVERDUE_60_PLUS_DAYS => InvoiceStage::OVERDUE,
            self::INVOICE_DISPUTE, self::SCOPE_DISPUTE,
            self::PAYMENT_HOLD => InvoiceStage::DISPUTED_ON_HOLD,
            self::BAD_DEBT, self::CANCELLED_INVOICE,
            self::CREDIT_NOTE => InvoiceStage::WRITTEN_OFF,
        };
    }
}
