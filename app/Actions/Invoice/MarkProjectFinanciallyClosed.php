<?php

declare(strict_types=1);

namespace App\Actions\Invoice;

use App\Enums\Pipeline\InvoiceStage;
use App\Models\Invoice;
use App\Models\User;

final readonly class MarkProjectFinanciallyClosed
{
    public function execute(User $user, Invoice $invoice): void
    {
        abort_unless($user->can('update', $invoice), 403);

        if ($invoice->stage !== InvoiceStage::PAID || $invoice->balance > 0 || $invoice->project === null) {
            return;
        }

        abort_unless($user->can('update', $invoice->project), 403);

        $invoice->project->update([
            'financially_closed_at' => now(),
        ]);
    }
}
