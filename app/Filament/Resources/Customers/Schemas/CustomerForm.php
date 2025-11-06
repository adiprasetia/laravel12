<?php

namespace App\Filament\Resources\Customers\Schemas;

use Dom\Text;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('address')
                    ->maxLength(255),
                TextInput::make('phone')
                    ->tel(),
            ]);
    }
}
