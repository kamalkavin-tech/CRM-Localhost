<?php

declare(strict_types=1);

use App\Enums\Pipeline\DealStage;
use App\Enums\Pipeline\DealSubStage;
use App\Filament\Resources\DealResource;
use App\Filament\Resources\DealResource\Pages\DealsBoard;
use App\Filament\Resources\DealResource\Pages\ListDeals;
use App\Models\Deal;
use App\Models\User;
use Filament\Facades\Filament;
use Relaticle\Flowforge\Board;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

mutates(DealsBoard::class);

beforeEach(function (): void {
    $this->user = User::factory()->withTeam()->create();
    $this->actingAs($this->user);

    $this->team = $this->user->currentTeam;
    Filament::setTenant($this->team);
});

function getDealBoard(): Board
{
    $component = livewire(DealsBoard::class);

    return $component->instance()->getBoard();
}

it('can render the board page', function (): void {
    livewire(DealsBoard::class)
        ->assertOk();
});

it('renders one column per deal stage, in pipeline order', function (): void {
    $columns = collect(getDealBoard()->getColumns())->map(fn ($column): string => $column->getName());

    expect($columns->all())->toBe(array_map(
        fn (DealStage $stage): string => $stage->value,
        DealStage::cases(),
    ));
});

it('displays deals in the column matching their stage', function (): void {
    $opportunity = Deal::factory()->recycle([$this->user, $this->team])
        ->create(['stage' => DealStage::NEW_LEAD]);

    $won = Deal::factory()->recycle([$this->user, $this->team])
        ->create(['stage' => DealStage::CLOSED_WON]);

    $board = getDealBoard();

    expect($board->getBoardRecords(DealStage::NEW_LEAD->value)->pluck('id'))
        ->toContain($opportunity->id)
        ->not->toContain($won->id)
        ->and($board->getBoardRecords(DealStage::CLOSED_WON->value)->pluck('id'))
        ->toContain($won->id)
        ->not->toContain($opportunity->id);
});

it('does not show deals from other teams', function (): void {
    $otherUser = User::factory()->withTeam()->create();
    $otherDeal = Deal::factory()->for($otherUser->currentTeam)->create();

    $board = getDealBoard();

    $allRecordIds = collect(DealStage::cases())
        ->flatMap(fn (DealStage $stage) => $board->getBoardRecords($stage->value))
        ->pluck('id');

    expect($allRecordIds)->not->toContain($otherDeal->id);
});

it('shows the view switcher linking list and board views', function (): void {
    livewire(ListDeals::class)
        ->assertSeeHtml(DealResource::getUrl('board'));

    livewire(DealsBoard::class)
        ->assertSeeHtml(DealResource::getUrl('index'));
});

it('redirects the legacy board url to the resource board page', function (): void {
    $this->get(route('filament.app.deals-board.redirect', ['tenant' => $this->team->slug]))
        ->assertRedirect(DealResource::getUrl('board'));
});

it('moves a card between columns via moveCard', function (): void {
    $deal = Deal::factory()->recycle([$this->user, $this->team])
        ->create(['stage' => DealStage::NEW_LEAD]);

    livewire(DealsBoard::class)
        ->call('moveCard', (string) $deal->id, DealStage::DISCOVERY->value)
        ->assertDispatched('kanban-card-moved');

    expect($deal->fresh()->stage)->toBe(DealStage::DISCOVERY);
});

it('clears a sub-stage that does not belong to the new stage', function (): void {
    $deal = Deal::factory()->recycle([$this->user, $this->team])->create([
        'stage' => DealStage::VERBAL_CONFIRMATION,
        'sub_stage' => DealSubStage::CLIENT_APPROVED,
    ]);

    livewire(DealsBoard::class)
        ->call('moveCard', (string) $deal->id, DealStage::PROPOSAL->value)
        ->assertDispatched('kanban-card-moved');

    $deal->refresh();

    expect($deal->stage)->toBe(DealStage::PROPOSAL)
        ->and($deal->sub_stage)->toBeNull();
});

it('records a non-final sub-stage without advancing the stage', function (): void {
    $deal = Deal::factory()->recycle([$this->user, $this->team])->create([
        'stage' => DealStage::DISCOVERY,
        'sub_stage' => null,
    ]);

    $firstSubStage = DealStage::DISCOVERY->subStages()[0];

    livewire(DealsBoard::class)
        ->call('setSubStage', (string) $deal->id, $firstSubStage->value)
        ->assertNotDispatched('kanban-card-moved');

    $deal->refresh();

    expect($deal->stage)->toBe(DealStage::DISCOVERY)
        ->and($deal->sub_stage)->toBe($firstSubStage);
});

it('advances a card to the next stage when its last sub-stage is chosen', function (): void {
    $deal = Deal::factory()->recycle([$this->user, $this->team])->create([
        'stage' => DealStage::NEW_LEAD,
        'sub_stage' => null,
    ]);

    $subStages = DealStage::NEW_LEAD->subStages();
    $lastSubStage = $subStages[array_key_last($subStages)];

    livewire(DealsBoard::class)
        ->call('setSubStage', (string) $deal->id, $lastSubStage->value)
        ->assertDispatched('kanban-card-moved');

    $deal->refresh();
    $nextStage = DealStage::NEW_LEAD->nextStage();

    expect($deal->stage)->toBe($nextStage)
        ->and($deal->sub_stage)->toBe($nextStage?->firstSubStage());
});

it('leaves a deal owned by another team untouched via setSubStage', function (): void {
    $otherUser = User::factory()->withTeam()->create();
    $otherDeal = Deal::factory()->for($otherUser->currentTeam)->create([
        'stage' => DealStage::NEW_LEAD,
        'sub_stage' => null,
    ]);

    try {
        // The tenant-scoped lookup finds nothing and aborts 404. Depending on the
        // Livewire version the abort either surfaces here or is swallowed into the
        // component response, so the security guarantee is asserted on the record.
        livewire(DealsBoard::class)
            ->call('setSubStage', (string) $otherDeal->id, DealSubStage::UNCONTACTED->value);
    } catch (NotFoundHttpException) {
    }

    $otherDeal->refresh();

    expect($otherDeal->stage)->toBe(DealStage::NEW_LEAD)
        ->and($otherDeal->sub_stage)->toBeNull();
});
