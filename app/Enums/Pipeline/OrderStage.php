<?php

declare(strict_types=1);

namespace App\Enums\Pipeline;

use App\Contracts\Pipeline\PipelineStage;
use App\Enums\Pipeline\Concerns\ProgressesThroughStages;

enum OrderStage: string implements PipelineStage
{
    use ProgressesThroughStages;

    case PROJECT_KICKOFF = 'project_kickoff';
    case REQUIREMENT_FINALIZATION = 'requirement_finalization';
    case PLANNING_ARCHITECTURE = 'planning_architecture';
    case DEVELOPMENT = 'development';
    case INTERNAL_QA = 'internal_qa';
    case CLIENT_REVIEW_UAT = 'client_review_uat';
    case REVISIONS = 'revisions';
    case DEPLOYMENT = 'deployment';
    case HANDOVER = 'handover';
    case PROJECT_COMPLETED = 'project_completed';
    case POST_PROJECT = 'post_project';

    public function getLabel(): string
    {
        return __('pipelines.order.stages.'.$this->value);
    }

    public function getColor(): string
    {
        return match ($this) {
            self::PROJECT_KICKOFF => '#a5b4fc',
            self::REQUIREMENT_FINALIZATION => '#818cf8',
            self::PLANNING_ARCHITECTURE => '#6366f1',
            self::DEVELOPMENT => '#7c3aed',
            self::INTERNAL_QA => '#0891b2',
            self::CLIENT_REVIEW_UAT => '#0d9488',
            self::REVISIONS => '#f59e0b',
            self::DEPLOYMENT => '#f97316',
            self::HANDOVER => '#16a34a',
            self::PROJECT_COMPLETED => '#059669',
            self::POST_PROJECT => '#db2777',
        };
    }

    /** @return list<OrderSubStage> */
    public function subStages(): array
    {
        return array_values(array_filter(
            OrderSubStage::cases(),
            fn (OrderSubStage $subStage): bool => $subStage->stage() === $this,
        ));
    }

    public function isWon(): bool
    {
        return $this === self::PROJECT_COMPLETED;
    }

    public function isLost(): bool
    {
        return false;
    }
}
