<?php

namespace App\Filament\Resources\AttendeeResource\Widgets;

use App\Filament\Resources\AttendeeResource\Pages\ListAttendees;
use App\Models\Attendee;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\Concerns\InteractsWithTableQuery;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AttendeeOverview extends BaseWidget
{
    use InteractsWithPageTable;
    protected function getColumns(): int
    {
        return 2;
    }
    protected function getTablePage(): string
    {
        return ListAttendees::class;
    }
    protected function getStats(): array
    {
        return [
            Stat::make('Attendees',$this->getPageTableQuery()->count())
            ->color("success")
            ->chart([7, 10, 20, 3, 35, 4, 17])
            ->description('32k increase')
            ->descriptionIcon('heroicon-m-arrow-trending-up'),
            Stat::make('Revenue', $this->getPageTableQuery()->sum('ticket_cost'))
            ->description('32k increase')
            ->descriptionIcon('heroicon-m-arrow-trending-up'),
        ];
    }
}
