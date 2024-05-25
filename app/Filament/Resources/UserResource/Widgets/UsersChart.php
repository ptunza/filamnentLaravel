<?php

namespace App\Filament\Resources\UserResource\Widgets;

use App\Filament\Resources\UserResource\Pages\ListUsers;
use App\Models\Attendee;
use App\Models\User;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class UsersChart extends ChartWidget
{
    use InteractsWithPageTable;
    protected static ?string $heading = 'Chart';
   protected int | string | array $columnSpan = 'full';

    protected function getTablePage(): string
    {
        return  ListUsers::class;
    }
   protected function getMaxHeight(): ?string
   {
    return '200px';
   }
    protected function getData(): array
    {
        $query = $this->getPageTableQuery();
        $query->getQuery()->orders = [];
        $data = Trend::query($query)
        ->between(
            start: now()->startOfYear(),
            end: now()->endOfYear(),
        )
        ->perMonth()
        ->count();

    return [
        'datasets' => [
            [
                'label' => 'Blog posts',
                'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
            ],
        ],
        'labels' => $data->map(fn (TrendValue $value) => $value->date),
    ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
