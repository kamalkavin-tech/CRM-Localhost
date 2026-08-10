<?php

declare(strict_types=1);

namespace App\Filament\Resources\Invoices\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

final class InvoiceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('invoice_number')->label(__('Invoice Number')),
                TextEntry::make('company.name')->label(__('Client'))->placeholder(__('—')),
                TextEntry::make('project.name')->label(__('Project'))->placeholder(__('—')),
                TextEntry::make('stage')->label(__('Stage'))->badge(),
                TextEntry::make('sub_stage')->label(__('Sub-stage'))->placeholder(__('—')),
                TextEntry::make('net_receivable')->label(__('Net Receivable'))->money('INR'),
                TextEntry::make('amount_paid')->label(__('Amount Paid'))->money('INR'),
                TextEntry::make('balance')->label(__('Balance'))->money('INR'),
                TextEntry::make('invoice_date')->label(__('Invoice Date'))->date(),
                TextEntry::make('due_date')->label(__('Due Date'))->date(),
            ]);
    }
}
