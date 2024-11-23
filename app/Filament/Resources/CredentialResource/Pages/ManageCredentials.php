<?php

namespace App\Filament\Resources\CredentialResource\Pages;

use App\Filament\Resources\CredentialResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class ManageCredentials extends ManageRecords
{
    protected static string $resource = CredentialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label(__('Create Key'))
            ->action(
                function ($data) {
                    DB::transaction(
                        function () use ($data) {
                            $existingCredential = \App\Models\Credential::where('site_url', $data['site_url'])
                                ->where('username', $data['username'])
                                ->first();

                            if ($existingCredential) {
                                Notification::make()
                                    ->warning()
                                    ->title(__('Credential already exists'))
                                    ->send();
                                return;
                            }

                            $data['encrypted_password'] = Crypt::encryptString($data['encrypted_password']);
                            \App\Models\Credential::create([
                                'service_name' => $data['service_name'],
                                'username' => $data['username'],
                                'encrypted_password' => $data['encrypted_password'],
                                'site_url' => $data['site_url'],
                            ]);

                            Notification::make()
                                ->success()
                                ->title(__('Credential created'))
                                ->send();
                        }
                    );
                }
            )->after(function () {
                return Redirect::to('/admin/credentials');
            }),
        ];

    }
}
