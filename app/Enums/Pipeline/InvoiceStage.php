<?php

declare(strict_types=1);

namespace App\Enums\Pipeline;

use App\Contracts\Pipeline\PipelineStage;
use App\Enums\Pipeline\Concerns\ProgressesThroughStages;

enum InvoiceStage: string implements PipelineStage
{
    use ProgressesThroughStages;

    case BILLING_DUE = 'billing_due';
    case INVOICE_PREPARATION = 'invoice_preparation';
    case INVOICE_SENT = 'invoice_sent';
    case PAYMENT_DUE = 'payment_due';
    case PAYMENT_FOLLOW_UP = 'payment_follow_up';
    case PARTIALLY_PAID = 'partially_paid';
    case PAID = 'paid';
    case OVERDUE = 'overdue';
    case DISPUTED_ON_HOLD = 'disputed_on_hold';
    case WRITTEN_OFF = 'written_off';

    public function getLabel(): string
    {
        return __('pipelines.invoice.stages.'.$this->value);
    }

    public function getColor(): string
    {
        return match ($this) {
            self::BILLING_DUE => '#a5b4fc',
            self::INVOICE_PREPARATION => '#818cf8',
            self::INVOICE_SENT => '#6366f1',
            self::PAYMENT_DUE => '#eab308',
            self::PAYMENT_FOLLOW_UP => '#f59e0b',
            self::PARTIALLY_PAID => '#f97316',
            self::PAID => '#059669',
            self::OVERDUE => '#dc2626',
            self::DISPUTED_ON_HOLD => '#7c3aed',
            self::WRITTEN_OFF => '#6b7280',
        };
    }

    /** @return list<InvoiceSubStage> */
    public function subStages(): array
    {
        return array_values(array_filter(
            InvoiceSubStage::cases(),
            fn (InvoiceSubStage $subStage): bool => $subStage->stage() === $this,
        ));
    }

    public function isWon(): bool
    {
        return $this === self::PAID;
    }

    public function isLost(): bool
    {
        return $this === self::WRITTEN_OFF;
    }
}
