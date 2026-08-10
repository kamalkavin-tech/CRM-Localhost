<?php

declare(strict_types=1);

namespace App\Filament\Resources\Expenses\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

final class ExpenseInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('description')->label(__('Description')),
                TextEntry::make('category')->label(__('Category')),
                TextEntry::make('vendor')->label(__('Employee / Vendor'))->placeholder(__('—')),
                TextEntry::make('stage')->label(__('Stage'))->badge(),
                TextEntry::make('sub_stage')->label(__('Sub-stage'))->placeholder(__('—')),
                TextEntry::make('amount')->label(__('Amount'))->money('INR'),
                TextEntry::make('expense_date')->label(__('Expense Date'))->date(),
                TextEntry::make('scheduled_payment_date')->label(__('Scheduled Payment Date'))->date()->placeholder(__('—')),
                TextEntry::make('payment_method')->label(__('Payment Method'))->placeholder(__('—')),
                TextEntry::make('payment_reference')->label(__('Payment Reference'))->placeholder(__('—')),
            ]);
    }
}
