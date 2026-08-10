<?php

declare(strict_types=1);

namespace App\Enums\Pipeline;

use App\Contracts\Pipeline\PipelineSubStage;

enum OrderSubStage: string implements PipelineSubStage
{
    case ADVANCE_CONFIRMED = 'advance_confirmed';
    case CONTRACT_SIGNED = 'contract_signed';
    case TEAM_ASSIGNED = 'team_assigned';
    case KICKOFF_MEETING = 'kickoff_meeting';
    case REQUIREMENTS_GATHERING = 'requirements_gathering';
    case REQUIREMENTS_APPROVED = 'requirements_approved';
    case SCOPE_LOCKED = 'scope_locked';
    case TECH_STACK = 'tech_stack';
    case ARCHITECTURE = 'architecture';
    case WIREFRAMES = 'wireframes';
    case PROJECT_PLAN = 'project_plan';
    case SPRINT_PLANNING = 'sprint_planning';
    case DEVELOPMENT_STARTED = 'development_started';
    case SPRINT_1 = 'sprint_1';
    case SPRINT_2 = 'sprint_2';
    case SPRINT_3 = 'sprint_3';
    case FEATURE_DEVELOPMENT = 'feature_development';
    case TESTING = 'testing';
    case BUG_FIXING = 'bug_fixing';
    case REGRESSION_TESTING = 'regression_testing';
    case INTERNAL_APPROVAL = 'internal_approval';
    case DEMO_SCHEDULED = 'demo_scheduled';
    case CLIENT_TESTING = 'client_testing';
    case FEEDBACK_RECEIVED = 'feedback_received';
    case UAT = 'uat';
    case CHANGES_REQUESTED = 'changes_requested';
    case CHANGES_IN_PROGRESS = 'changes_in_progress';
    case CHANGES_COMPLETED = 'changes_completed';
    case STAGING = 'staging';
    case PRODUCTION_DEPLOYMENT = 'production_deployment';
    case DOMAIN_SERVER_SETUP = 'domain_server_setup';
    case PRODUCTION_VERIFICATION = 'production_verification';
    case DOCUMENTATION = 'documentation';
    case TRAINING = 'training';
    case CREDENTIALS_HANDOVER = 'credentials_handover';
    case SOURCE_CODE_HANDOVER = 'source_code_handover';
    case FINAL_APPROVAL = 'final_approval';
    case FINAL_INVOICE = 'final_invoice';
    case PROJECT_CLOSED = 'project_closed';
    case WARRANTY = 'warranty';
    case AMC = 'amc';
    case SUPPORT = 'support';
    case UPSELL_RENEWAL = 'upsell_renewal';

    public function getLabel(): string
    {
        return __('pipelines.order.sub_stages.'.$this->value);
    }

    public function stage(): OrderStage
    {
        return match ($this) {
            self::ADVANCE_CONFIRMED, self::CONTRACT_SIGNED,
            self::TEAM_ASSIGNED, self::KICKOFF_MEETING => OrderStage::PROJECT_KICKOFF,
            self::REQUIREMENTS_GATHERING, self::REQUIREMENTS_APPROVED,
            self::SCOPE_LOCKED => OrderStage::REQUIREMENT_FINALIZATION,
            self::TECH_STACK, self::ARCHITECTURE, self::WIREFRAMES,
            self::PROJECT_PLAN, self::SPRINT_PLANNING => OrderStage::PLANNING_ARCHITECTURE,
            self::DEVELOPMENT_STARTED, self::SPRINT_1, self::SPRINT_2,
            self::SPRINT_3, self::FEATURE_DEVELOPMENT => OrderStage::DEVELOPMENT,
            self::TESTING, self::BUG_FIXING, self::REGRESSION_TESTING,
            self::INTERNAL_APPROVAL => OrderStage::INTERNAL_QA,
            self::DEMO_SCHEDULED, self::CLIENT_TESTING,
            self::FEEDBACK_RECEIVED, self::UAT => OrderStage::CLIENT_REVIEW_UAT,
            self::CHANGES_REQUESTED, self::CHANGES_IN_PROGRESS,
            self::CHANGES_COMPLETED => OrderStage::REVISIONS,
            self::STAGING, self::PRODUCTION_DEPLOYMENT, self::DOMAIN_SERVER_SETUP,
            self::PRODUCTION_VERIFICATION => OrderStage::DEPLOYMENT,
            self::DOCUMENTATION, self::TRAINING, self::CREDENTIALS_HANDOVER,
            self::SOURCE_CODE_HANDOVER => OrderStage::HANDOVER,
            self::FINAL_APPROVAL, self::FINAL_INVOICE,
            self::PROJECT_CLOSED => OrderStage::PROJECT_COMPLETED,
            self::WARRANTY, self::AMC, self::SUPPORT,
            self::UPSELL_RENEWAL => OrderStage::POST_PROJECT,
        };
    }
}
