<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Forms\Components\Repeater;

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
                            ->reactive()
                            ->label('Name')
                            ->afterStateUpdated(function (Set $set, $state) {
                                $customer = \App\Models\Customer::find($state);
                                $set('email', $customer->email ?? null);
                                $set('phone', $customer->phone ?? null);
                                $set('address', $customer->address ?? null);
                            }),
                        TextInput::make('email')
                            ->disabled(),
                        TextInput::make('phone')
                            ->disabled(),
                        TextInput::make('address')
                            ->disabled(),
                    ])->columns(3),

                Section::make('')
                    ->description('Order Details')
                    ->columnSpanFull()
                    ->schema([
                        Repeater::make('order_details')
                            ->relationship() //relation to order_details table
                            ->schema([
                                Select::make('product_id')
                                    ->relationship('product', 'name'), //relation to products table and display name column
                                // ->reactive()
                                // ->afterStateUpdated(function (TextInput $priceInput, $state) {
                                //     $product = \App\Models\Product::find($state);
                                //     $priceInput->state($product->price ?? null);
                                TextInput::make('quantity'),
                                TextInput::make('sutotal'),
                            ])->columns(3),

                    ]),

                TextInput::make('total_price')
                    ->required()
                    ->prefix('IDR')
                    ->numeric(),

            ]);
    }
}
