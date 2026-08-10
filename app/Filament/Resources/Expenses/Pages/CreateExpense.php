<?php

declare(strict_types=1);

namespace App\Filament\Resources\Expenses\Pages;

use App\Actions\Expense\AdvanceApprovedExpense;
use App\Filament\Resources\Expenses\ExpenseResource;
use App\Models\Expense;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;

final class CreateExpense extends CreateRecord
{
    protected static string $resource = ExpenseResource::class;

    protected function afterCreate(): void
    {
        /** @var Expense $expense */
        $expense = $this->record;
        /** @var User $user */
        $user = auth()->user();

        resolve(AdvanceApprovedExpense::class)->execute($user, $expense);
    }
}
