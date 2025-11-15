<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrderStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            //stat new chart widget
            Stat::make('New Orders', Order::where('status', 'New')->count())
                ->description('Order menunggu konfirmasi')
                ->descriptionIcon('heroicon-m-clock')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('info'),

            //stat processing chart widget
            Stat::make('Processing Orders', Order::where('status', 'Processing')->count())
                ->description('Order sedang diproses')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->chart([3, 6, 4, 8, 5, 10, 7])
                ->color('warning'),
            // //stat completed chart widget
            Stat::make('Completed Orders', Order::where('status', 'Completed')->count())
                ->description('Order yang telah selesai')
                ->descriptionIcon('heroicon-m-check-circle')
                ->chart([1, 4, 2, 5, 3, 7, 4])
                ->color('success'),
            // //stat cancelled chart widget
            // Stat::make('Cancelled Orders', Order::where('status', 'Cancelled')->count())
            //     ->description('Order yang dibatalkan')
            //     ->descriptionIcon('heroicon-m-x-circle')
            //     ->chart([2, 3, 1, 4, 2, 5, 3])
            //     ->color('danger'),
            //total orders chart widget
            Stat::make('Total Revenue', 'IDR '.number_format(Order::where('status', 'Completed')->sum('total_payment'), 0))
                ->description('Total pendapatan')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->chart([10, 20, 15, 30, 25, 40, 50])
                ->color('danger'),
        ];
    }
}
