<?php

namespace App\Filament\Resources\Orders\RelationManagers;

use App\Filament\Resources\Orders\OrderResource;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\CreateAction;






class OrderDetailRelationManager extends RelationManager
{
    protected static string $relationship = 'orderDetails';

    protected static ?string $relatedResource = OrderResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('product.name')
                    ->label('Product')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('quantity')
                    ->label('Quantity')
                    ->sortable(),
                TextColumn::make('price')
                    ->label('Price')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('subtotal')
                    ->label('Total')
                    ->money('IDR'),
            ])
            ->headerActions([
                CreateAction::make()
            ]);
    }
}
