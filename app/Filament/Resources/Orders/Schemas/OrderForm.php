<?php

namespace App\Filament\Resources\Orders\Schemas;

use Dom\Text;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Utilities\Get;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DateTimePicker::make('order_date')// Kolom tanggal dan waktu order
                    ->default(now())
                    ->required()
                    ->hiddenLabel()
                    ->prefix('Order Date & Time')
                    ->disabled()
                    ->dehydrated(),

                Section::make('')
                    ->description('Customer Information') //Tab Customer Information
                    ->columnSpanFull()
                    ->schema([
                        Select::make('customer_id') //Kolom customer_id
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
                        TextInput::make('email') //Kolom email
                            ->disabled(),
                        TextInput::make('phone') //Kolom phone
                            ->disabled(),
                        TextInput::make('address') //Kolom address
                            ->disabled(),
                    ])->columns(3),

                Section::make('')
                    ->description('Order Details')
                    ->columnSpanFull()
                    ->schema([
                        Repeater::make('orderDetails') //Tab order details
                            ->relationship() //relasi ke order details
                            ->schema([
                                Select::make('product_id') //Kolom product_id
                                    ->required()
                                    ->relationship('product', 'name')
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                        $product = \App\Models\Product::find($state);
                                        $set('price', $product->price ?? 0); // set price berdasarkan product yang dipilih

                                        // Update subtotal when product changes
                                        $quantity = $get('quantity') ?? 1;
                                        $set ('quantity', $quantity); // tampilkan nilai quantity, default 1
                                        $set('subtotal', ($product->price ?? 0) * $quantity); //tampilkan subtotal = price * quantity

                                        // Update total price of the order
                                        $items=$get('../../orderDetails') ?? []; // Masukkan jumlah order detail,tambah .. karena ada diluar repeater
                                        $total=collect($items)->sum(function($item){
                                            return ($item['price'] ?? 0) * ($item['quantity'] ?? 0); //ambil total price dan quantity, kemudian kalikan
                                        });

                                        $set('../../total_price', $total); // Tampilkan total_price, tambah .. karena ada diluar repeater
                                    }),

                                TextInput::make('price') //Kolom price
                                    ->numeric()
                                    ->prefix('IDR')
                                    ->disabled()
                                    ->dehydrated(),

                                TextInput::make('quantity') //Kolom quantity
                                    ->numeric()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                        // Update subtotal when price changes
                                        $quantity = $get('quantity') ?? 1;
                                        $price = $get('price') ?? 0;
                                        $set('subtotal', $price * $quantity);

                                        // Update total price of the order
                                        $items = $get('../../orderDetails') ?? []; // tambah .. karena ada diluar repeater
                                        $total = collect($items)->sum(function ($item) {
                                            return ($item['price'] ?? 0) * ($item['quantity'] ?? 0);
                                        });
                                        $set('../../total_price', $total); // tambah .. karena ada diluar repeater
                                    }),

                                TextInput::make('subtotal') //Kolom subtotal
                                    ->numeric()
                                    ->prefix('IDR')
                                    ->disabled()
                                    ->dehydrated(),


                            ])
                            ->columns(4),
                        ]),


                TextInput::make('total_price') //Tab kolom total_price
                    ->prefix('IDR')
                    ->numeric()
                    ->disabled()
                    ->dehydrated() //supaya disimpan ke database meskipun disabled
                    ->live(),

            ]);
    }


}


