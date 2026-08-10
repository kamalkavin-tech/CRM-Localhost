<?php

declare(strict_types=1);

namespace App\Enums\Pipeline;

use App\Contracts\Pipeline\PipelineStage;
use App\Enums\Pipeline\Concerns\ProgressesThroughStages;

enum DealStage: string implements PipelineStage
{
    use ProgressesThroughStages;

    case NEW_LEAD = 'new_lead';
    case CONTACTED = 'contacted';
    case DISCOVERY = 'discovery';
    case QUALIFIED = 'qualified';
    case PROPOSAL = 'proposal';
    case NEGOTIATION = 'negotiation';
    case VERBAL_CONFIRMATION = 'verbal_confirmation';
    case CLOSED_WON = 'closed_won';
    case CLOSED_LOST = 'closed_lost';

    public function getLabel(): string
    {
        return __('pipelines.deal.stages.'.$this->value);
    }

    public function getColor(): string
    {
        return match ($this) {
            self::NEW_LEAD => '#a5b4fc',
            self::CONTACTED => '#818cf8',
            self::DISCOVERY => '#0d9488',
            self::QUALIFIED => '#0891b2',
            self::PROPOSAL => '#f59e0b',
            self::NEGOTIATION => '#f97316',
            self::VERBAL_CONFIRMATION => '#7c3aed',
            self::CLOSED_WON => '#059669',
            self::CLOSED_LOST => '#6b7280',
        };
    }

    /** @return list<DealSubStage> */
    public function subStages(): array
    {
        return array_values(array_filter(
            DealSubStage::cases(),
            fn (DealSubStage $subStage): bool => $subStage->stage() === $this,
        ));
    }

    public function isWon(): bool
    {
        return $this === self::CLOSED_WON;
    }

    public function isLost(): bool
    {
        return $this === self::CLOSED_LOST;
    }
}
