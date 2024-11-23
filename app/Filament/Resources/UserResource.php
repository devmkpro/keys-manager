<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationGroup  = 'Administrativo';

    public static function getModelLabel(): string
    {
        return __('Usuário');
    }

    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('Name'))
                    ->placeholder(__('Name'))
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('email')
                    ->label(__('Email'))
                    ->placeholder(__('Email'))
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true, table: 'users', column: 'email')
                    ->maxLength(255),

                Forms\Components\TextInput::make('password')
                    ->label(__('Password'))
                    ->placeholder(__('Password'))
                    ->password()
                    ->required(fn(string $operation): bool => $operation === 'create')
                    ->dehydrated(fn(?string $state) => filled($state))
                    ->maxLength(255)
                    ->confirmed(),

                Forms\Components\TextInput::make('password_confirmation')
                    ->label(__('Confirm Password'))
                    ->placeholder(__('Confirm Password'))
                    ->password()
                    ->requiredWith('password')
                    ->dehydrated(false)
                    ->maxLength(255),

                Select::make('roles')->multiple()->relationship('roles', 'name')
                    ->label(__('Roles'))
                    ->options(
                        fn() => \App\Models\Role::where('name', '!=', 'owner')->get()->pluck('name', 'id')->take(5)
                    )
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->sortable()
                    ->limit(10)
                    ->label(__('Code'))
                    ->copyable(),




                Tables\Columns\TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable(),


                Tables\Columns\TextColumn::make('roles')
                    ->label(__('Roles'))
                    ->getStateUsing(function ($record) {
                        return __($record->roles->pluck('name')->join(', '));
                    })
                    ->searchable(),

                    
                    Tables\Columns\TextColumn::make('email')
                        ->searchable()
                        ->getStateUsing(function ($record) {
                            return str_repeat('*', strlen($record->email));
                        })
                        ->extraAttributes(function ($record) {
                            return [
                                'x-data' => '{ email: "'. $record->email .'", hidden: true }',
                                'x-on:click' => 'hidden = !hidden',
                                'x-text' => 'hidden ? "'. str_repeat('*', strlen($record->email)) .'" : email',
                                'style' => 'cursor: pointer; font-size: inherit; width: 150px;',
                                'x-on:dblclick' => 'navigator.clipboard.writeText(email)',
                            ];
                        }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Created'))
                    ->dateTime(format: 'd/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->hidden(
                        function ($record) {
                            return $record->roles->contains('name', 'owner');
                        }
                    ),
                Tables\Actions\DeleteAction::make()
                    ->hidden(
                        function ($record) {
                            return $record->isOwner();
                        }
                    )
                    ->action(
                        function ($record) {
                            if ($record->isOwner()) {
                                Notification::make()
                                    ->title(__('User not deleted'))
                                    ->body(__('This user is protected and cannot be deleted.'))
                                    ->icon('heroicon-o-exclamation-triangle')
                                    ->color('warning')
                                    ->send();

                                return;
                            } else if ($record->propertys->count() > 0) {
                                Notification::make()
                                    ->title(__('User not deleted'))
                                    ->body(__('This user has properties associated with it.'))
                                    ->icon('heroicon-o-exclamation-triangle')
                                    ->color('danger')
                                    ->send();

                                return;
                            }

                            $record->delete();

                            Notification::make()
                                ->title(__('User deleted'))
                                ->icon('heroicon-o-check-circle')
                                ->color('success')
                                ->send();
                        }
                    ),
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
            'index' => Pages\ManageUsers::route('/'),
        ];
    }
}
