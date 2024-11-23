<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CredentialResource\Pages;
use App\Models\Credential;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Crypt;
use Rawilk\FilamentPasswordInput\Password;

class CredentialResource extends Resource
{
    protected static ?string $model = Credential::class;

    protected static ?string $navigationIcon = 'heroicon-o-key';

    protected static ?string $navigationGroup  = 'Serviços';

    public static function getNavigationLabel(): string
    {
        return __('Key');
    }

    public static function getModelLabel(): string
    {
        return __('Keys');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('service_name')

                    ->label(__('Service Name'))
                    ->placeholder(__('AWS, DigitalOcean, etc.')),

                Forms\Components\TextInput::make('username')
                    ->label(__('Username or Email'))
                    ->placeholder(__('Insert your username or email'))
                    ->required(),

                Password::make('encrypted_password')
                    ->placeholder(__('Insert your password'))
                    ->label(__('Password'))
                    ->copyable(color: 'success')
                    ->copyMessage(__('Password copied to clipboard'))
                    ->afterStateHydrated(function ($state, callable $set) {
                        $set('encrypted_password', Crypt::decryptString($state));
                    }),

                Forms\Components\TextInput::make('site_url')
                    ->placeholder(__('example.com'))
                    ->url()
                    ->label(__('Site URL'))
                    ->default('https://')
                    ->afterStateHydrated(function ($state, callable $set) {
                        if (!str_starts_with($state, 'https://')) {
                            $set('site_url', 'https://' . ltrim($state, 'https://'));
                        }
                    }),

                Forms\Components\Hidden::make('user_id')
                    ->default(auth()->id()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(fn() => Credential::query()->where('user_id', auth()->id()))
            ->columns([
                Tables\Columns\TextColumn::make('service_name')
                    ->label(__('Service Name'))
                    ->getStateUsing(fn($record) => $record->service_name ?? __('Not Informed'))

                    ->searchable(),


                Tables\Columns\TextColumn::make('site_url')
                    ->label(__('Site URL'))
                    ->copyable()
                    ->searchable()
                    ->url(fn(Credential $record) => $record->site_url ? $record->site_url : null, '_blank')
                    ->color(fn(Credential $record) => $record->site_url ? 'primary' : null),

                Tables\Columns\TextColumn::make('username')
                    ->label(__('Username'))
                    ->copyable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label(__('Created'))
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->label(__('Updated at'))
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageCredentials::route('/'),
        ];
    }
}
