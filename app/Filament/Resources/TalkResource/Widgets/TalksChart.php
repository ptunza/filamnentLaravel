<?php

namespace App\Filament\Resources\TalkResource\Widgets;

use App\Filament\Resources\TalkResource\Pages\ListTalks;
use App\Models\Talk;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class TalksChart extends ChartWidget
{
    protected int | string | array $columnSpan = 'full';
    protected static ?string $maxHeight = '200px';
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


    protected function getData(): array
    {

        $activeFilter = $this->filter;
        if ($activeFilter == 'today') {
            $data = Trend::model(Talk::class)
            ->between(
            start: now()->subDay(),
            end: now(),
        )
        ->perHour()
        ->count();
        } else if($activeFilter == 'week'){
            $data = Trend::model(Talk::class)
            ->between(
            start: now()->subWeek(),
            end: now(),
        )
        ->perDay()
        ->count();
        } else if($activeFilter == 'month'){
            $data = Trend::model(Talk::class)
            ->between(
            start: now()->subMonth(),
            end: now(),
        )
        ->perDay()
        ->count();
        } else if($activeFilter == 'year'){
            $data = Trend::model(Talk::class)
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
