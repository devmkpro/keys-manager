<?php

namespace App\Filament\Pages\Auth;
 
use Coderflex\FilamentTurnstile\Forms\Components\Turnstile;
use Filament\Forms\Form;
use Filament\Pages\Auth\Login as AuthLogin;

class Login extends AuthLogin
{
    /**
     * @return array<int|string, string|Form>
     */
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getEmailFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getRememberFormComponent(),
                        Turnstile::make('captcha')
                            ->required()
                            ->language('pt_BR')
                            ->theme('auto'),

                       
                    ])
                    ->statePath('data'),
            ),
        ];
    }
 
    // if you want to reset the captcha in case of validation error
    protected function throwFailureValidationException(): never
    {
        $this->dispatch('reset-captcha');
 
        parent::throwFailureValidationException();
    }
}