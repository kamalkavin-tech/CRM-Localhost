<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Invoice;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Auth\Access\HandlesAuthorization;

final readonly class InvoicePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasVerifiedEmail() && $user->currentTeam !== null;
    }

    public function view(User $user, Invoice $invoice): bool
    {
        return $user->belongsToTeamId($invoice->team_id);
    }

    public function create(User $user): bool
    {
        return $user->hasVerifiedEmail() && $user->currentTeam !== null;
    }

    public function update(User $user, Invoice $invoice): bool
    {
        return $user->belongsToTeamId($invoice->team_id);
    }

    public function delete(User $user, Invoice $invoice): bool
    {
        return $user->belongsToTeamId($invoice->team_id);
    }

    public function deleteAny(User $user): bool
    {
        return $user->hasVerifiedEmail() && $user->currentTeam !== null;
    }

    public function restore(User $user, Invoice $invoice): bool
    {
        return $user->belongsToTeamId($invoice->team_id);
    }

    public function restoreAny(User $user): bool
    {
        return $user->hasVerifiedEmail() && $user->currentTeam !== null;
    }

    public function forceDelete(User $user): bool
    {
        return $user->hasTeamRole(Filament::getTenant(), 'admin');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->hasTeamRole(Filament::getTenant(), 'admin');
    }
}
