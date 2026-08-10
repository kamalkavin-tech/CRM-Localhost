<?php

declare(strict_types=1);

use App\Actions\Expense\AdvanceApprovedExpense;
use App\Actions\Invoice\MarkProjectFinanciallyClosed;
use App\Enums\Pipeline\ExpenseStage;
use App\Enums\Pipeline\ExpenseSubStage;
use App\Enums\Pipeline\InvoiceStage;
use App\Enums\Pipeline\InvoiceSubStage;
use App\Filament\Resources\Expenses\Pages\CreateExpense;
use App\Filament\Resources\Invoices\Pages\CreateInvoice;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\User;
use Filament\Facades\Filament;

mutates(
    AdvanceApprovedExpense::class,
    MarkProjectFinanciallyClosed::class,
    Expense::class,
    Invoice::class,
);

beforeEach(function (): void {
    $this->user = User::factory()->withTeam()->create();
    $this->team = $this->user->currentTeam;
    $this->actingAs($this->user);
    Filament::setTenant($this->team);
});

it('creates a paid invoice and financially closes its project', function (): void {
    $project = Order::factory()->recycle([$this->user, $this->team])->create();

    livewire(CreateInvoice::class)
        ->fillForm([
            'invoice_number' => 'INV-1001',
            'order_id' => $project->getKey(),
            'invoice_date' => today()->toDateString(),
            'due_date' => today()->addDays(7)->toDateString(),
            'amount' => 100000,
            'tax_amount' => 18000,
            'amount_paid' => 118000,
            'stage' => InvoiceStage::PAID->value,
            'sub_stage' => InvoiceSubStage::PAYMENT_VERIFIED->value,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $invoice = Invoice::query()->where('invoice_number', 'INV-1001')->firstOrFail();

    expect($invoice->net_receivable)->toBe(118000.0)
        ->and($invoice->balance)->toBe(0.0)
        ->and($project->fresh()->financially_closed_at)->not->toBeNull();
});

it('moves an approved expense into payment processing', function (): void {
    livewire(CreateExpense::class)
        ->fillForm([
            'description' => 'August cloud infrastructure',
            'category' => 'Cloud',
            'amount' => 25000,
            'expense_date' => today()->toDateString(),
            'stage' => ExpenseStage::APPROVED->value,
            'sub_stage' => ExpenseSubStage::APPROVED->value,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $expense = Expense::query()->where('description', 'August cloud infrastructure')->firstOrFail();

    expect($expense->stage)->toBe(ExpenseStage::PAYMENT_PROCESSING)
        ->and($expense->sub_stage)->toBe(ExpenseSubStage::PAYMENT_INITIATED);
});
