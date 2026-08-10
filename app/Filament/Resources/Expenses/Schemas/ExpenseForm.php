<?php

declare(strict_types=1);

namespace App\Filament\Resources\Expenses\Schemas;

use App\Enums\Pipeline\ExpenseStage;
use App\Filament\Forms\PipelineStageFields;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;

final class ExpenseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('description')->label(__('Description'))->required()->maxLength(255)->columnSpanFull(),
                Select::make('category')->label(__('Category'))->options(self::categories())->searchable()->required(),
                TextInput::make('vendor')->label(__('Employee / Vendor'))->maxLength(255),
                TextInput::make('amount')->label(__('Amount'))->numeric()->minValue(0)->required()->prefix('₹'),
                DatePicker::make('expense_date')->label(__('Expense Date'))->required()->default(today()),
                DatePicker::make('scheduled_payment_date')->label(__('Scheduled Payment Date')),
                DatePicker::make('paid_at')->label(__('Paid Date')),
                TextInput::make('payment_method')->label(__('Payment Method'))->maxLength(255),
                TextInput::make('payment_reference')->label(__('Payment Reference'))->maxLength(255),
                ...array_map(
                    fn (Component $component): Component => $component->columnSpan(1),
                    PipelineStageFields::make(ExpenseStage::class),
                ),
            ])
            ->columns(2);
    }

    /** @return array<string, string> */
    public static function categories(): array
    {
        $categories = [
            'Salaries', 'Freelancers', 'Consultants', 'Employee Reimbursements',
            'Incentives / Commissions', 'Cloud', 'Servers', 'Domains', 'SaaS',
            'APIs', 'Software Licenses', 'Developer Tools', 'Marketing',
            'Advertising', 'Travel', 'Events', 'Office', 'Internet / Utilities',
            'Legal', 'Accounting',
        ];

        $options = [];

        foreach ($categories as $category) {
            $options[$category] = __($category);
        }

        return $options;
    }
}
