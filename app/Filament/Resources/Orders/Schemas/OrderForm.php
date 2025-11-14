<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Flex;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema

            ->schema([
                DateTimePicker::make('order_date') // Kolom tanggal dan waktu order
                    ->default(now())
                    ->required()
                    ->hiddenLabel()
                    ->prefix('Order Date & Time')
                    ->disabled()
                    ->dehydrated()
                    ->columnSpan(2),

                Group::make([
                    Section::make('')
                        ->description('Customer Information') //Tab Customer Information
                        //->columnSpanFull()
                        ->schema([
                            Select::make('customer_id') //Kolom customer_id
                                ->required()
                                ->relationship('customer', 'name') //relation to customers table and display name column
                                ->reactive()
                                ->live()
                                ->hiddenLabel()
                                ->prefix('Name')
                                ->afterStateUpdated(function (Set $set, $state) {
                                    $customer = \App\Models\Customer::find($state);
                                    $set('email', $customer->email ?? null);
                                    $set('phone', $customer->phone ?? null);
                                    $set('address', $customer->address ?? null);
                                }),
                            TextInput::make('email') //Kolom email
                                ->disabled()
                                ->hiddenLabel()
                                ->prefix('Email'),
                            TextInput::make('phone') //Kolom phone
                                ->disabled()
                                ->hiddenLabel()
                                ->prefix('Phone'),
                            TextInput::make('address') //Kolom address
                                ->disabled()
                                ->hiddenLabel()
                                ->prefix('Address'),
                        ])->columns(2),


                    Section::make('')
                        ->description('Order Details')
                        //->columnSpanFull()
                        ->schema([
                            Repeater::make('orderDetails') //Tab order details
                                ->relationship() //relasi ke order details
                                ->schema([
                                    Select::make('product_id') //Kolom product_id
                                        ->required()
                                        ->relationship('product', 'name')
                                        ->reactive()
                                        ->disableOptionsWhenSelectedInSiblingRepeaterItems() // Disable item yang sudah dipilih di kolom product supaya tidak bisa dipilih lagi
                                        ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                            $product = \App\Models\Product::find($state);
                                            $set('price', $product->price ?? 0); // set price berdasarkan product yang dipilih

                                            // Update subtotal when product changes
                                            $quantity = $get('quantity') ?? 1;
                                            $set('quantity', $quantity); // tampilkan nilai quantity, default 1
                                            $set('subtotal', ($product->price ?? 0) * $quantity); //tampilkan subtotal = price * quantity

                                            // Update total price of the order
                                            $items = $get('../../orderDetails') ?? []; // Masukkan jumlah order detail,tambah .. karena ada diluar repeater
                                            $total = collect($items)->sum(function ($item) {
                                                return ($item['price'] ?? 0) * ($item['quantity'] ?? 0); //ambil total price dan quantity, kemudian kalikan
                                            });

                                            $set('../../total_price', $total); // Tampilkan total_price, tambah .. karena ada diluar repeater

                                            //untuk membuat perhitungan discount dan total payment otomatis muncul tanpa mengisi discount
                                            $discount = $get('../../discount') ?? 0;
                                            $discountAmmount = ($total * $discount) / 100;
                                            $set('../../discount_ammount', $discountAmmount);
                                            $set('../../total_payment', $total - $discountAmmount);
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

                                            //Untuk update discount dan total payment otomatis saat quantity diubah
                                            $discount = $get('../../discount') ?? 0;
                                            $discountAmmount = ($total * $discount) / 100;
                                            $set('../../discount_ammount', $discountAmmount);
                                            $set('../../total_payment', $total - $discountAmmount);

                                        }),

                                    TextInput::make('subtotal') //Kolom subtotal
                                        ->numeric()
                                        ->prefix('IDR')
                                        ->disabled()
                                        ->dehydrated(),

                                ])->columns(4),
                        ]),

                ])
                ->columnSpan(2),



                Section::make('')
                    ->description('Payment Information') //Tab Timestamps
                    ->schema([
                        Select::make('status') //Kolom status
                            ->options([
                                'new' => 'New',
                                'processing' => 'Processing',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                            ])
                            ->required()
                            ->default('new')
                            ->live()
                            ->columnSpanFull(),
                        TextInput::make('total_price') //Tab kolom total_price
                            ->prefix('IDR')
                            ->numeric()
                            ->disabled()
                            ->dehydrated() //supaya disimpan ke database meskipun disabled
                            ->live()
                            ->columnSpanFull(),
                        TextInput::make('discount') //Kolom discount
                            ->numeric()
                            ->label('Disc. (%)')
                            ->live()
                            ->columnSpan('1')
                            //afterstateUpdated to calculate discount_ammount and total_payment
                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                $totalPrice = $get('total_price') ?? 0;
                                $discountAmmount = ($totalPrice * $state) / 100;
                                $set('discount_ammount', $discountAmmount);
                                $set('total_payment', $totalPrice - $discountAmmount);
                            }),
                        TextInput::make('discount_ammount') //Kolom discount_ammount
                            ->prefix('IDR')
                            ->numeric()
                            ->disabled()
                            ->dehydrated() //supaya disimpan ke database meskipun disabled
                            ->live()
                            ->columnSpan('3'),
                        TextInput::make('total_payment') //Kolom total_payment
                            ->prefix('IDR')
                            ->numeric()
                            ->disabled()
                            ->dehydrated() //supaya disimpan ke database meskipun disabled
                            ->live()
                            ->columnSpanFull(),
                    ])
                    ->columnSpan(1)
                    ->columns(4),
            ])->columns(3);
    }
}
