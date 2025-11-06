<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')->label('Name')->required()->maxLength(255), //Buat Field Name dengan text input di form
                TextInput::make('email')->label('Email')->required()->email()->maxLength(255), //Buat Field Email dengan text input di form
                TextInput::make('password') //Buat Field Password dengan text input di form dengan catatan ketika form di gunakan untuk edit user maka password tidak wajib di isi
                    ->password()
                    ->dehydrateStateUsing(fn(string $state): string => Hash::make($state))
                    ->dehydrated(fn(?string $state): bool => filled($state))
                    ->required(fn(string $operation): bool => $operation === 'create')
            ]);
    }
}
