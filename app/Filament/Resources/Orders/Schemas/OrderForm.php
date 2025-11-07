<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('customer_id')
                    ->required()
                    ->relationship('customer', 'name'),//relation to customers table and display name column
                DateTimePicker::make('order_date')
                ->default(now())
                ->required(),
                TextInput::make('total_price')
                    ->required()
                    ->prefix('IDR')
                    ->numeric(),

            ]);
    }
}
