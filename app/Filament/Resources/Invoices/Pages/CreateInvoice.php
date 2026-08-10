<?php

declare(strict_types=1);

namespace App\Filament\Resources\Invoices\Pages;

use App\Actions\Invoice\MarkProjectFinanciallyClosed;
use App\Filament\Resources\Invoices\InvoiceResource;
use App\Models\Invoice;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;

final class CreateInvoice extends CreateRecord
{
    protected static string $resource = InvoiceResource::class;

    protected function afterCreate(): void
    {
        /** @var Invoice $invoice */
        $invoice = $this->record;
        /** @var User $user */
        $user = auth()->user();

        resolve(MarkProjectFinanciallyClosed::class)->execute($user, $invoice);
    }
}
