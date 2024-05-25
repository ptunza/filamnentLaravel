<?php

namespace App\Filament\Resources\ConferenceResource\Widgets;

use App\Filament\Resources\ConferenceResource\Pages\ListConferences;
use App\Models\Conference;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class ConferenceChart extends ChartWidget
{
    protected static ?string $heading = 'Conference Details';
    protected int | string | array $columnSpan = 'full';

    public ?string $filter = 'today';
    protected function getFilters(): ?array
{
    return [
        'today' => 'Today',
        'week' => 'Last week',
        'month' => 'Last month',
        'year' => 'This year',
    ];
}

    protected function getMaxHeight(): ?string
    {
        return '200px';
    }
    protected function getData(): array
    {
        $activeFilter = $this->filter;

        if ($activeFilter == 'today') {
            $data = Trend::model(Conference::class)
            ->between(
            start: now()->subDay(),
            end: now(),
        )
        ->perHour()
        ->count();
        } else if($activeFilter == 'week'){
            $data = Trend::model(Conference::class)
            ->between(
            start: now()->subWeek(),
            end: now(),
        )
        ->perDay()
        ->count();
        } else if($activeFilter == 'month'){
            $data = Trend::model(Conference::class)
            ->between(
            start: now()->subMonth(),
            end: now(),
        )
        ->perMonth()
        ->count();
        } else if($activeFilter == 'year'){
            $data = Trend::model(Conference::class)
            ->between(
            start: now()->startOfYear(),
            end: now(),
        )
        ->perMonth()
        ->count();
        }

    return [
        'datasets' => [
            [
                'label' => 'Conference',
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
