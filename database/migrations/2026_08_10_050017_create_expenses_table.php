<?php

declare(strict_types=1);

use App\Enums\Pipeline\ExpenseStage;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignUlid('team_id')->constrained('teams')->cascadeOnDelete();
            $table->foreignUlid('creator_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('description');
            $table->string('category')->index();
            $table->string('vendor')->nullable();
            $table->decimal('amount', 15, 2);
            $table->date('expense_date');
            $table->date('scheduled_payment_date')->nullable()->index();
            $table->timestamp('paid_at')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_reference')->nullable();
            $table->string('stage')->default(ExpenseStage::EXPENSE_PLANNED->value)->index();
            $table->string('sub_stage')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['team_id', 'stage']);
        });
    }
};
