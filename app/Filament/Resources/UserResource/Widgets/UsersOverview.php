<?php

namespace App\Filament\Resources\UserResource\Widgets;

use App\Filament\Resources\UserResource\Pages\ListUsers;
use App\Models\User;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UsersOverview extends BaseWidget
{
    use InteractsWithPageTable;
    protected function getTablePage(): string
    {
        return ListUsers::class;
    }
    protected function getColumns(): int
    {
        return 2;
    }
    protected function getStats(): array
    {
            return [
                Stat::make('User Count', $this->getPageTableQuery()->count())
                ->color('success')
                ->chart([3,4,5,6])
                ->descriptionIcon('heroicon-m-arrow-trending-up'),

            ];


    }
}
