<?php

declare(strict_types=1);

namespace App\Enums\Pipeline;

use App\Contracts\Pipeline\PipelineStage;
use App\Enums\Pipeline\Concerns\ProgressesThroughStages;

enum ExpenseStage: string implements PipelineStage
{
    use ProgressesThroughStages;

    case EXPENSE_PLANNED = 'expense_planned';
    case EXPENSE_SUBMITTED = 'expense_submitted';
    case VERIFICATION = 'verification';
    case APPROVED = 'approved';
    case PAYMENT_PROCESSING = 'payment_processing';
    case PAID = 'paid';
    case RECONCILIATION = 'reconciliation';
    case REJECTED_CANCELLED = 'rejected_cancelled';

    public function getLabel(): string
    {
        return __('pipelines.expense.stages.'.$this->value);
    }

    public function getColor(): string
    {
        return match ($this) {
            self::EXPENSE_PLANNED => '#a5b4fc',
            self::EXPENSE_SUBMITTED => '#818cf8',
            self::VERIFICATION => '#6366f1',
            self::APPROVED => '#0891b2',
            self::PAYMENT_PROCESSING => '#f59e0b',
            self::PAID => '#059669',
            self::RECONCILIATION => '#0d9488',
            self::REJECTED_CANCELLED => '#6b7280',
        };
    }

    /** @return list<ExpenseSubStage> */
    public function subStages(): array
    {
        return array_values(array_filter(
            ExpenseSubStage::cases(),
            fn (ExpenseSubStage $subStage): bool => $subStage->stage() === $this,
        ));
    }

    public function isWon(): bool
    {
        return $this === self::RECONCILIATION;
    }

    public function isLost(): bool
    {
        return $this === self::REJECTED_CANCELLED;
    }
}
