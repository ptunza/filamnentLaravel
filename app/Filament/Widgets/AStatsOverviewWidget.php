<?php

namespace App\Filament\Widgets;

use App\Models\Attendee;
use App\Models\Conference;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AStatsOverviewWidget extends BaseWidget
{
    use InteractsWithPageFilters;

    protected function getStats(): array
    {
        $start = $this->filters['startDate'];
        $end = $this->filters['endDate'];
            return [
                Stat::make('Attendee', count(Attendee::all()))
                ->description('Attendee Count')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
                Stat::make('Revenue', Attendee::sum('ticket_cost') )
                ->description('Revenue From Conferences')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
                Stat::make('Conference', count(Conference::all()))
                ->description('Conference Count')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            ];

    }
}
