<?php

namespace App\Filament\Pages;

use Filament\Auth\Pages\Login;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;
use Illuminate\Validation\ValidationException;
use SensitiveParameter;

class LoginAdmin extends Login
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getLoginFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getRememberFormComponent(),
            ]);
    }

    protected function getLoginFormComponent(): Component
    {
        return TextInput::make('login')
            ->label(__('Nama / Email'))
            ->required()
            ->autocomplete()
            ->autofocus()
            ->extraInputAttributes(['tabindex' => 1]);
    }

    protected function getCredentialsFromFormData(#[SensitiveParameter] array $data): array
    {
        $login_type = filter_var($data['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'name';
        return [
            $login_type => $data['login'],
            'password' => $data['password'],
        ];
    }

    protected function throwFailureValidationException(): never
    {
        throw ValidationException::withMessages([
            'data.login' => __('filament-panels::auth/pages/login.messages.failed'),
        ]);
    }

    public function getHeading(): string
    {
        return __('Sistem UPT - Login Admin');
    }
}
