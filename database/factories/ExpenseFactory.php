<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Pipeline\ExpenseStage;
use App\Enums\Pipeline\ExpenseSubStage;
use App\Models\Expense;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Expense> */
final class ExpenseFactory extends Factory
{
    protected $model = Expense::class;

    public function definition(): array
    {
        return [
            'team_id' => Team::factory(),
            'description' => fake()->sentence(4),
            'category' => fake()->randomElement(['Salaries', 'Cloud', 'SaaS', 'Marketing', 'Office']),
            'amount' => fake()->randomFloat(2, 100, 250000),
            'expense_date' => fake()->dateTimeBetween('-1 month', '+1 month'),
            'stage' => ExpenseStage::EXPENSE_PLANNED,
            'sub_stage' => ExpenseSubStage::OPERATIONS,
        ];
    }
}
