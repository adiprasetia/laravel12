<?php

namespace App\Filament\Resources\Orders\RelationManagers;

use App\Filament\Resources\Orders\OrderResource;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;

class OrderDetailRelationManager extends RelationManager
{
    protected static string $relationship = 'orderDetails';

    protected static ?string $relatedResource = OrderResource::class;

    protected static ?string $title = 'Order Details';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id') // Optional
            ->columns([
                ImageColumn::make('product.image')
                    ->label('Product Image'),
                TextColumn::make('product.name')
                    ->label('Product')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('quantity')
                    ->label('Quantity'),
                TextColumn::make('price')
                    ->label('Price')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('subtotal')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('discount')
                    ->label('Discount')
                    ->sortable(),
                TextColumn::make('total_payment')
                    ->label('Total Payment')
                    ->money('IDR')
                    ->sortable(),
            ])
            ->recordActions([]);
    }
}
