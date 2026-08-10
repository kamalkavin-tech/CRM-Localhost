<?php

declare(strict_types=1);

namespace App\Filament\Resources\Expenses\Tables;

use App\Enums\Pipeline\ExpenseStage;
use App\Filament\Resources\Expenses\Schemas\ExpenseForm;
use App\Models\Expense;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Support\Colors\Color;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

final class ExpensesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('description')->label(__('Description'))->searchable()->sortable(),
                TextColumn::make('category')->label(__('Category'))->searchable()->sortable(),
                TextColumn::make('vendor')->label(__('Employee / Vendor'))->searchable(),
                TextColumn::make('stage')->label(__('Stage'))->badge()
                    ->color(fn (Expense $record): array => Color::hex($record->stage->getColor())),
                TextColumn::make('sub_stage')->label(__('Sub-stage'))->placeholder(__('—')),
                TextColumn::make('amount')->label(__('Amount'))->money('INR')->sortable(),
                TextColumn::make('expense_date')->label(__('Expense Date'))->date()->sortable(),
                TextColumn::make('scheduled_payment_date')->label(__('Payment Date'))->date()->sortable(),
            ])
            ->defaultSort('expense_date', 'desc')
            ->filters([
                SelectFilter::make('stage')->label(__('Stage'))->options(ExpenseStage::class),
                SelectFilter::make('category')->label(__('Category'))->options(fn (): array => ExpenseForm::categories()),
                TrashedFilter::make(),
            ])
            ->recordActions([ViewAction::make(), EditAction::make()])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
