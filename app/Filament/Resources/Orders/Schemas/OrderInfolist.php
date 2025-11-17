<?php

namespace App\Filament\Resources\Orders\Schemas;

use Dom\Text;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;

class OrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Order Information')
                    ->icon(Heroicon::ShoppingBag)
                    ->schema([
                        TextEntry::make('customer.name'),
                        TextEntry::make('customer.address')
                            ->label('Alamat'),
                        TextEntry::make('payment_status')
                            ->label('Status Pembayaran')
                            ->badge()
                            ->color(fn(string $state): string => match ($state) {
                                'paid' => 'success',
                                'unpaid' => 'warning',
                                'failed' => 'danger',
                            }),
                        TextEntry::make('total_payment')
                            ->prefix('IDR ')
                            ->numeric(),
                        TextEntry::make('order_date')
                            ->date(),
                        TextEntry::make('created_at')
                            ->dateTime()
                            ->hidden(),
                        TextEntry::make('updated_at')
                            ->dateTime(),

                    ])
                    ->columns(2),
            ]);
    }
}
