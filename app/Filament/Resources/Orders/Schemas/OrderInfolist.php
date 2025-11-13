<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class OrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('customer_id')
                    ->numeric(),
                TextEntry::make('total_price')
                    ->money('IDR')
                    ->numeric(),
                TextEntry::make('order_date')
                    ->date(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->hidden(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
