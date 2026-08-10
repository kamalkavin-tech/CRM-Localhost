<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Pipeline\InvoiceStage;
use App\Enums\Pipeline\InvoiceSubStage;
use App\Models\Concerns\BelongsToTeamCreator;
use App\Models\Concerns\HasTeam;
use Database\Factories\InvoiceFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property InvoiceStage $stage
 * @property InvoiceSubStage|null $sub_stage
 * @property Carbon $due_date
 * @property string $amount
 * @property string $tax_amount
 * @property string $amount_paid
 * @property-read float $net_receivable
 * @property-read float $balance
 * @property-read int $days_overdue
 * @property-read Order|null $project
 */
#[Fillable([
    'team_id', 'creator_id', 'company_id', 'order_id', 'invoice_number',
    'invoice_date', 'due_date', 'amount', 'tax_amount', 'amount_paid',
    'payment_date', 'payment_method', 'payment_reference', 'stage', 'sub_stage',
])]
final class Invoice extends Model
{
    use BelongsToTeamCreator;

    /** @use HasFactory<InvoiceFactory> */
    use HasFactory;

    use HasTeam;
    use HasUlids;
    use SoftDeletes;

    /** @var array<string, mixed> */
    protected $attributes = [
        'stage' => InvoiceStage::BILLING_DUE,
        'amount_paid' => 0,
        'tax_amount' => 0,
    ];

    /** @return array<string, string|class-string> */
    protected function casts(): array
    {
        return [
            'invoice_date' => 'date',
            'due_date' => 'date',
            'payment_date' => 'date',
            'amount' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'amount_paid' => 'decimal:2',
            'stage' => InvoiceStage::class,
            'sub_stage' => InvoiceSubStage::class,
        ];
    }

    /** @return BelongsTo<Company, $this> */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /** @return BelongsTo<Order, $this> */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    /** @return Attribute<float, never> */
    protected function netReceivable(): Attribute
    {
        return Attribute::get(fn (): float => (float) $this->amount + (float) $this->tax_amount);
    }

    /** @return Attribute<float, never> */
    protected function balance(): Attribute
    {
        return Attribute::get(fn (): float => max(0, $this->net_receivable - (float) $this->amount_paid));
    }

    /** @return Attribute<int, never> */
    protected function daysOverdue(): Attribute
    {
        return Attribute::get(fn (): int => $this->balance > 0 && $this->due_date->isPast()
            ? (int) $this->due_date->diffInDays(today())
            : 0);
    }
}
