<?php

declare(strict_types=1);

use App\Enums\Pipeline\DealStage;
use App\Enums\Pipeline\DealSubStage;
use App\Filament\Forms\PipelineStageFields;
use App\Filament\Resources\DealResource\Pages\ListDeals;
use App\Models\Deal;
use App\Models\User;
use Filament\Actions\Testing\TestAction;
use Filament\Facades\Filament;

mutates(PipelineStageFields::class);

beforeEach(function (): void {
    $this->user = User::factory()->withTeam()->create();
    $this->actingAs($this->user);
    $this->team = $this->user->currentTeam;
    Filament::setTenant($this->team);
});

it('accepts every sub-stage of :dataset and rejects one from another stage', function (DealStage $stage): void {
    $foreign = collect(DealStage::cases())
        ->reject(fn (DealStage $other): bool => $other === $stage)
        ->flatMap(fn (DealStage $other): array => $other->subStages())
        ->reject(fn (DealSubStage $sub): bool => in_array($sub, $stage->subStages(), true))
        ->first();

    foreach ($stage->subStages() as $own) {
        $deal = Deal::factory()->recycle([$this->user, $this->team])->create();
        $deal->stage = $stage;
        $deal->sub_stage = $own;
        $deal->save();

        $expected = $stage->isWon() ? DealSubStage::PROJECT_STARTED : $own;

        expect($deal->fresh()->sub_stage)
            ->toBe($expected, "{$own->value} should be valid for {$stage->value}");
    }

    $deal = Deal::factory()->recycle([$this->user, $this->team])->create();
    $deal->stage = $stage;
    $deal->sub_stage = $foreign;
    $deal->save();

    // Asserts the foreign value specifically, not null: reaching a won stage
    // converts the record downstream, and that conversion stamps its own
    // sub-stage, so null is not guaranteed there.
    expect($deal->fresh()->sub_stage)
        ->not->toBe($foreign, "{$foreign->value} must not persist on {$stage->value}");
})->with(fn (): array => array_map(
    fn (DealStage $stage): array => [$stage],
    DealStage::cases(),
));

it('clears the sub-stage when the stage changes in the form', function (): void {
    $deal = Deal::factory()->recycle([$this->user, $this->team])->create([
        'stage' => DealStage::VERBAL_CONFIRMATION,
        'sub_stage' => DealSubStage::CLIENT_APPROVED,
    ]);

    livewire(ListDeals::class)
        ->mountAction(TestAction::make('edit')->table($deal))
        ->assertSchemaStateSet([
            'stage' => DealStage::VERBAL_CONFIRMATION->value,
            'sub_stage' => DealSubStage::CLIENT_APPROVED->value,
        ])
        ->fillForm(['stage' => DealStage::PROPOSAL->value])
        ->assertSchemaStateSet(['sub_stage' => null]);
});

it('persists a valid stage and sub-stage pair', function (): void {
    $deal = Deal::factory()->recycle([$this->user, $this->team])
        ->create(['stage' => DealStage::NEW_LEAD]);

    livewire(ListDeals::class)
        ->callAction(TestAction::make('edit')->table($deal), [
            'name' => $deal->name,
            'stage' => DealStage::NEGOTIATION->value,
            'sub_stage' => DealSubStage::PRICING_NEGOTIATION->value,
        ])
        ->assertHasNoActionErrors();

    $deal->refresh();

    expect($deal->stage)->toBe(DealStage::NEGOTIATION)
        ->and($deal->sub_stage)->toBe(DealSubStage::PRICING_NEGOTIATION);
});

it('refuses to persist a sub-stage from a different stage', function (): void {
    $deal = Deal::factory()->recycle([$this->user, $this->team])
        ->create(['stage' => DealStage::NEW_LEAD]);

    // QC belongs to the Order pipeline's Production stage, so it is not a valid
    // pairing for any deal stage and must not survive the write.
    $deal->stage = DealStage::PROPOSAL;
    $deal->sub_stage = DealSubStage::PRICING_NEGOTIATION;
    $deal->save();

    expect($deal->fresh()->sub_stage)->toBeNull();
});
