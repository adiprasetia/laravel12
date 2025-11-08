<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Forms\Set;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DateTimePicker::make('order_date')
                    ->default(now())
                    ->required()
                    ->hiddenLabel()
                    ->prefix('Order Date & Time')
                    ->disabled(),

                Section::make('')
                    ->description('Customer Information')
                    ->columnSpanFull()
                    ->schema([
                        Select::make('customer_id')
                            ->required()
                            ->relationship('customer', 'name') //relation to customers table and display name column
                            ->afterStateUpdated(function ($state, Set $set) {

                            }),
                        TextInput::make('phone')
                            ->disabled(),
                        TextInput::make('address')
                            ->disabled(),
                    ]),


                TextInput::make('total_price')
                    ->required()
                    ->prefix('IDR')
                    ->numeric(),

            ]);
    }
}
