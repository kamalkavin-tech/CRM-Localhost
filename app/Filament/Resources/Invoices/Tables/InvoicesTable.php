<?php

declare(strict_types=1);

namespace App\Filament\Resources\Invoices\Tables;

use App\Enums\Pipeline\InvoiceStage;
use App\Models\Invoice;
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

final class InvoicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('invoice_number')->label(__('Invoice Number'))->searchable()->sortable(),
                TextColumn::make('company.name')->label(__('Client'))->searchable()->sortable(),
                TextColumn::make('project.name')->label(__('Project'))->searchable(),
                TextColumn::make('stage')->label(__('Stage'))->badge()
                    ->color(fn (Invoice $record): array => Color::hex($record->stage->getColor())),
                TextColumn::make('sub_stage')->label(__('Sub-stage'))->placeholder(__('—')),
                TextColumn::make('net_receivable')->label(__('Net Receivable'))->money('INR')->sortable(),
                TextColumn::make('amount_paid')->label(__('Amount Paid'))->money('INR')->sortable(),
                TextColumn::make('balance')->label(__('Balance'))->money('INR'),
                TextColumn::make('due_date')->label(__('Due Date'))->date()->sortable(),
                TextColumn::make('days_overdue')->label(__('Days Overdue')),
            ])
            ->defaultSort('due_date')
            ->filters([
                SelectFilter::make('stage')->label(__('Stage'))->options(InvoiceStage::class),
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
