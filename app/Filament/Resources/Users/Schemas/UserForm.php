<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama')
                    ->required()
                    ->maxLength(255),

                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true) // ✅ cegah email duplikat saat edit
                    ->maxLength(255),

                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->dehydrateStateUsing(fn($state) => filled($state) ? bcrypt($state) : null)
                    ->dehydrated(fn($state) => filled($state)) // ✅ skip kalau kosong saat edit
                    ->required(fn(string $operation): bool => $operation === 'create') // ✅ wajib saat create, opsional saat edit
                    ->maxLength(255)
                    ->hint('Kosongkan jika tidak ingin mengubah password'), // ✅ hint saat edit
            ]);
    }
}
