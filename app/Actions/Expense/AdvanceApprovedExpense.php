<?php

declare(strict_types=1);

namespace App\Actions\Expense;

use App\Enums\Pipeline\ExpenseStage;
use App\Enums\Pipeline\ExpenseSubStage;
use App\Models\Expense;
use App\Models\User;

final readonly class AdvanceApprovedExpense
{
    public function execute(User $user, Expense $expense): void
    {
        abort_unless($user->can('update', $expense), 403);

        if ($expense->stage !== ExpenseStage::APPROVED) {
            return;
        }

        $expense->update([
            'stage' => ExpenseStage::PAYMENT_PROCESSING,
            'sub_stage' => ExpenseSubStage::PAYMENT_INITIATED,
        ]);
    }
}
