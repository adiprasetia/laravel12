<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make([
                    Section::make([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        TextInput::make('price')
                            ->required()
                            ->numeric(),
                        TextInput::make('stock')
                            ->required()
                            ->numeric(),
                            Toggle::make('is_active')
                            ->required(),
                            Toggle::make('is_stock')
                            ->required(),
                            FileUpload::make('image')
                                ->image()
                                ->maxSize(1024),

                    ])->columns(2)
                    ->description('Product Details'),
                ])->columnSpan(2),

                Section::make([
                    TextInput::make('brand_id')
                        ->required()
                        ->numeric(),
                    TextInput::make('category_id')
                        ->required()
                        ->numeric(),
                    TextInput::make('subcategory_id')
                        ->required()
                        ->numeric(),

                ])->description('Associations'),
            ])->columns(3);
    }
}
