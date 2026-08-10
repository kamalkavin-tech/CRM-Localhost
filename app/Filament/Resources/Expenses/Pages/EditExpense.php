<?php

declare(strict_types=1);

namespace App\Filament\Resources\Expenses\Pages;

use App\Actions\Expense\AdvanceApprovedExpense;
use App\Filament\Resources\Expenses\ExpenseResource;
use App\Models\Expense;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

final class EditExpense extends EditRecord
{
    protected static string $resource = ExpenseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        /** @var Expense $expense */
        $expense = $this->record;
        /** @var User $user */
        $user = auth()->user();

        resolve(AdvanceApprovedExpense::class)->execute($user, $expense);
    }
}
