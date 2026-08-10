<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Pipeline\ExpenseStage;
use App\Enums\Pipeline\ExpenseSubStage;
use App\Models\Concerns\BelongsToTeamCreator;
use App\Models\Concerns\HasTeam;
use Database\Factories\ExpenseFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property ExpenseStage $stage
 * @property ExpenseSubStage|null $sub_stage
 */
#[Fillable([
    'team_id', 'creator_id', 'description', 'category', 'vendor',
    'amount', 'expense_date', 'scheduled_payment_date', 'paid_at',
    'payment_method', 'payment_reference', 'stage', 'sub_stage',
])]
final class Expense extends Model
{
    use BelongsToTeamCreator;

    /** @use HasFactory<ExpenseFactory> */
    use HasFactory;

    use HasTeam;
    use HasUlids;
    use SoftDeletes;

    /** @var array<string, mixed> */
    protected $attributes = [
        'stage' => ExpenseStage::EXPENSE_PLANNED,
    ];

    /** @return array<string, string|class-string> */
    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'expense_date' => 'date',
            'scheduled_payment_date' => 'date',
            'paid_at' => 'datetime',
            'stage' => ExpenseStage::class,
            'sub_stage' => ExpenseSubStage::class,
        ];
    }
}
