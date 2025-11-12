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
use Filament\Schemas\Components\Utilities\Get;

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
                        Repeater::make('orderDetails') //ambil dari function orderDetails di model Order.php
                            ->relationship() //relation to order_details table
                            ->schema([
                                Select::make('product_id')
                                    ->relationship('product', 'name') //relation to products table and display name column
                                    ->reactive()
                                    ->afterStateUpdated(function (Get $get, Set $set, $state) {

                                        $product = \App\Models\Product::find($state); // ambil data produk berdasarkan product_id yang dipilih
                                        $price = $product->price ?? 0; // ambil harga produk atau default 0 jika belum ada
                                        $set('price', $price ?? null); // set harga saat produk dipilih atau default null jika belum ada
                                        $qty = $get('quantity') ?? 1; // ambil quantity saat ini atau default 1
                                        $set('quantity', $qty); // set quantity jika belum ada
                                        $subtotal = $price * $qty;
                                        $set('subtotal', $subtotal); // hitung subtotal

                                        $items = $get('orderDetails') ?? []; // ambil semua item orderDetails
                                        $total = 0; // inisialisasi total
                                        foreach ($items as $item) { // loop setiap item
                                            $total += $item['subtotal'] ?? 0;
                                        }
                                        $set('total_price', $total);   // set total price di form utama

                                    }),
                                TextInput::make('price')
                                    ->prefix('IDR')
                                    ->numeric(),
                                TextInput::make('quantity')
                                    ->numeric()
                                    ->reactive()
                                    ->afterStateUpdated(function (Get $get, Set $set, $state) {
                                        $price = $get('price') ?? 0; // ambil harga saat ini atau default 0 jika belum ada
                                        $qty = $state ?? 1; // ambil quantity saat ini atau default 1
                                        $set('subtotal', $price * $qty);

                                        $items = $get('orderDetails') ?? []; // ambil semua item orderDetails
                                        $total = 0; // inisialisasi total
                                        foreach ($items as $item) { // loop setiap item
                                            $total += $item['subtotal'] ?? 0;
                                        }
                                        $set('total_price', $total);   // set total price di form utama
                                    }),
                                TextInput::make('subtotal')
                                    ->disabled()
                                    ->prefix('IDR')
                                    ->numeric(),
                            ])->columns(4),
                    ]),

                TextInput::make('total_price')
                    ->prefix('IDR')
                    ->numeric()
                    ->disabled()
                    


            ]);
    }
}
