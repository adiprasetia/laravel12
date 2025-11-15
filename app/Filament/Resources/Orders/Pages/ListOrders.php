<?php

namespace App\Filament\Resources\Orders\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Widgets\OrderStats;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use App\Filament\Resources\Orders\OrderResource;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    //function header widgets
    protected function getHeaderWidgets(): array
    {
        return [
            OrderStats::class,
        ];
    }

    //funtcion to add table filters
    public function getTabs(): array
    {
        return [
            null => Tab::make('All')->label('All Orders'),
            'New' => Tab::make()->query(fn($query) => $query->where('status', 'new')),
            'Processing' => Tab::make()->query(fn($query) => $query->where('status', 'processing')),
            'Completed' => Tab::make()->query(fn($query) => $query->where('status', 'completed')),
            'Cancelled' => Tab::make()->query(fn($query) => $query->where('status', 'cancelled')),
        ];
    }
}
