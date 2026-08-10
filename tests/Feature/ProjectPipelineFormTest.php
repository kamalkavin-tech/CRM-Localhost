<?php

declare(strict_types=1);

use App\Enums\Pipeline\OrderStage;
use App\Filament\Forms\PipelineStageFields;
use App\Filament\Resources\OrderResource\Pages\ListOrders;
use App\Models\Order;
use App\Models\User;
use Filament\Actions\Testing\TestAction;
use Filament\Facades\Filament;

mutates(PipelineStageFields::class, Order::class);

beforeEach(function (): void {
    $this->user = User::factory()->withTeam()->create();
    $this->team = $this->user->currentTeam;
    $this->actingAs($this->user);
    Filament::setTenant($this->team);
});

it('persists every project delivery stage with its first sub-stage', function (OrderStage $stage): void {
    $project = Order::factory()->recycle([$this->user, $this->team])->create();
    $subStage = $stage->firstSubStage();

    livewire(ListOrders::class)
        ->callAction(TestAction::make('edit')->table($project), [
            'name' => $project->name,
            'stage' => $stage->value,
            'sub_stage' => $subStage?->value,
        ])
        ->assertHasNoActionErrors();

    expect($project->fresh()->stage)->toBe($stage)
        ->and($project->fresh()->sub_stage)->toBe($subStage);
})->with(fn (): array => array_map(
    fn (OrderStage $stage): array => [$stage],
    OrderStage::cases(),
));
