<?php

namespace App\Filament\Resources\SpeakerResource\Widgets;

use App\Filament\Resources\SpeakerResource\Pages\ListSpeakers;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SpeakersOverview extends BaseWidget
{
    use InteractsWithPageTable;
    protected function getColumns(): int
    {
        return 2;
    }

    protected function getTablePage(): string
    {
        return ListSpeakers::class;
    }
    protected function getStats(): array
    {
        return [
            Stat::make('Speakers', $this->getPageTableQuery()->count() )
            ->chart([7, 2, 10, 3, 15, 4, 17])
            ->color('success'),
        ];
    }
}
