<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Pipeline\InvoiceStage;
use App\Enums\Pipeline\InvoiceSubStage;
use App\Models\Invoice;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Invoice> */
final class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition(): array
    {
        $invoiceDate = fake()->dateTimeBetween('-2 months', 'now');

        return [
            'team_id' => Team::factory(),
            'invoice_number' => 'INV-'.fake()->unique()->numerify('######'),
            'invoice_date' => $invoiceDate,
            'due_date' => fake()->dateTimeBetween($invoiceDate, '+45 days'),
            'amount' => fake()->randomFloat(2, 1000, 500000),
            'tax_amount' => fake()->randomFloat(2, 0, 50000),
            'stage' => InvoiceStage::BILLING_DUE,
            'sub_stage' => InvoiceSubStage::MILESTONE_DUE,
        ];
    }
}
