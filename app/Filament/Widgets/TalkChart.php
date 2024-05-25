<?php

namespace App\Filament\Widgets;

use App\Models\Speaker;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Carbon;

class TalkChart extends ChartWidget
{
    use InteractsWithPageFilters;
    protected static ?string $heading = 'Talk';

    protected function getData(): array
    {
        $start = $this->filters['startDate'];
        $end = $this->filters['endDate'];
        $data = Trend::model(Speaker::class)
        ->between(
            start: Carbon::parse($start),
            end: Carbon::parse($end),
        )
        ->perMonth()
        ->count();

    return [
        'datasets' => [
            [
                'label' => 'Talk',
                'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
            ],
        ],
        'labels' => $data->map(fn (TrendValue $value) => $value->date),
    ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
