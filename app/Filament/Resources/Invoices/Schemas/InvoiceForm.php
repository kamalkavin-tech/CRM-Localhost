<?php

declare(strict_types=1);

namespace App\Filament\Resources\Invoices\Schemas;

use App\Enums\Pipeline\InvoiceStage;
use App\Filament\Forms\PipelineStageFields;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;

final class InvoiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('invoice_number')->label(__('Invoice Number'))->required()->maxLength(255),
                Select::make('company_id')->label(__('Client'))->relationship('company', 'name')->searchable()->preload(),
                Select::make('order_id')->label(__('Project'))->relationship('project', 'name')->searchable()->preload(),
                DatePicker::make('invoice_date')->label(__('Invoice Date'))->required()->default(today()),
                DatePicker::make('due_date')->label(__('Due Date'))->required()->afterOrEqual('invoice_date'),
                TextInput::make('amount')->label(__('Invoice Amount'))->numeric()->minValue(0)->required()->prefix('₹'),
                TextInput::make('tax_amount')->label(__('Tax'))->numeric()->minValue(0)->required()->default(0)->prefix('₹'),
                TextInput::make('amount_paid')->label(__('Amount Paid'))->numeric()->minValue(0)->required()->default(0)->prefix('₹'),
                DatePicker::make('payment_date')->label(__('Payment Date')),
                TextInput::make('payment_method')->label(__('Payment Method'))->maxLength(255),
                TextInput::make('payment_reference')->label(__('Payment Reference'))->maxLength(255),
                ...array_map(
                    fn (Component $component): Component => $component->columnSpan(1),
                    PipelineStageFields::make(InvoiceStage::class),
                ),
            ])
            ->columns(2);
    }
}
