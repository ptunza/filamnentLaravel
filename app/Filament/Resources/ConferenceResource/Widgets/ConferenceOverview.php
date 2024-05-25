<?php

namespace App\Filament\Resources\ConferenceResource\Widgets;

use App\Filament\Resources\ConferenceResource\Pages\ListConferences;
use App\Models\Conference;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ConferenceOverview extends BaseWidget
{
    protected function getColumns(): int
    {
        return 2;
    }

    protected function getTablePage(): string
    {
        return ListConferences::class;
    }
    protected function getStats(): array
    {
        return [
            Stat::make('Conference Count', count(Conference::all()))
            ->color('success')
            ->chart([1,30])
            ->descriptionIcon('heroicon-m-arrow-trending-up'),
        ];
    }
}
