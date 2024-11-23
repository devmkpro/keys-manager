<?php

namespace App\Filament\Resources\CredentialResource\Pages;

use App\Filament\Resources\CredentialResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageCredentials extends ManageRecords
{
    protected static string $resource = CredentialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label(__('Create Key'))
        ];
    }
}
