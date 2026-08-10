<?php

declare(strict_types=1);

namespace App\Enums\Pipeline;

use App\Contracts\Pipeline\PipelineSubStage;

enum DealSubStage: string implements PipelineSubStage
{
    case UNCONTACTED = 'uncontacted';
    case CONTACT_ATTEMPTED = 'contact_attempted';
    case CONNECTED = 'connected';
    case MEETING_REQUESTED = 'meeting_requested';
    case MEETING_SCHEDULED = 'meeting_scheduled';
    case NO_RESPONSE = 'no_response';
    case REQUIREMENT_GATHERING = 'requirement_gathering';
    case TECHNICAL_DISCOVERY = 'technical_discovery';
    case BUDGET_DISCOVERY = 'budget_discovery';
    case TIMELINE_DISCOVERY = 'timeline_discovery';
    case QUALIFIED = 'qualified';
    case DECISION_MAKER_IDENTIFIED = 'decision_maker_identified';
    case BUDGET_CONFIRMED = 'budget_confirmed';
    case TIMELINE_CONFIRMED = 'timeline_confirmed';
    case PROPOSAL_DRAFTING = 'proposal_drafting';
    case PROPOSAL_SENT = 'proposal_sent';
    case PROPOSAL_VIEWED = 'proposal_viewed';
    case PROPOSAL_DISCUSSION = 'proposal_discussion';
    case PRICING_NEGOTIATION = 'pricing_negotiation';
    case SCOPE_NEGOTIATION = 'scope_negotiation';
    case TIMELINE_NEGOTIATION = 'timeline_negotiation';
    case TERMS_AND_CONDITIONS = 'terms_and_conditions';
    case CLIENT_APPROVED = 'client_approved';
    case AWAITING_PO_AGREEMENT = 'awaiting_po_agreement';
    case AWAITING_ADVANCE = 'awaiting_advance';
    case ADVANCE_PAID = 'advance_paid';
    case PROJECT_STARTED = 'project_started';
    case LOST_PRICE = 'lost_price';
    case LOST_COMPETITOR = 'lost_competitor';
    case LOST_NO_BUDGET = 'lost_no_budget';
    case LOST_NO_RESPONSE = 'lost_no_response';
    case LOST_NOT_A_FIT = 'lost_not_a_fit';
    case LOST_DELAYED = 'lost_delayed';
    case LOST_CANCELLED = 'lost_cancelled';

    public function getLabel(): string
    {
        return __('pipelines.deal.sub_stages.'.$this->value);
    }

    public function stage(): DealStage
    {
        return match ($this) {
            self::UNCONTACTED, self::CONTACT_ATTEMPTED => DealStage::NEW_LEAD,
            self::CONNECTED, self::MEETING_REQUESTED, self::MEETING_SCHEDULED,
            self::NO_RESPONSE => DealStage::CONTACTED,
            self::REQUIREMENT_GATHERING, self::TECHNICAL_DISCOVERY,
            self::BUDGET_DISCOVERY, self::TIMELINE_DISCOVERY => DealStage::DISCOVERY,
            self::QUALIFIED, self::DECISION_MAKER_IDENTIFIED,
            self::BUDGET_CONFIRMED, self::TIMELINE_CONFIRMED => DealStage::QUALIFIED,
            self::PROPOSAL_DRAFTING, self::PROPOSAL_SENT,
            self::PROPOSAL_VIEWED, self::PROPOSAL_DISCUSSION => DealStage::PROPOSAL,
            self::PRICING_NEGOTIATION, self::SCOPE_NEGOTIATION,
            self::TIMELINE_NEGOTIATION, self::TERMS_AND_CONDITIONS => DealStage::NEGOTIATION,
            self::CLIENT_APPROVED, self::AWAITING_PO_AGREEMENT,
            self::AWAITING_ADVANCE => DealStage::VERBAL_CONFIRMATION,
            self::ADVANCE_PAID, self::PROJECT_STARTED => DealStage::CLOSED_WON,
            self::LOST_PRICE, self::LOST_COMPETITOR, self::LOST_NO_BUDGET,
            self::LOST_NO_RESPONSE, self::LOST_NOT_A_FIT,
            self::LOST_DELAYED, self::LOST_CANCELLED => DealStage::CLOSED_LOST,
        };
    }
}
