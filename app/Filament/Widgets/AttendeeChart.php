<?php

namespace App\Filament\Widgets;

use App\Models\Attendee;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Carbon as SupportCarbon;

class AttendeeChart extends ChartWidget
{
    use InteractsWithPageFilters;
    protected static ?string $heading = 'Attendee';

    protected function getData(): array
    {
        $start = $this->filters['startDate'];
        $end = $this->filters['endDate'];
        $data = Trend::model(Attendee::class)
        ->between(
            start:SupportCarbon::parse($start),
            end:SupportCarbon::parse($end)
        )
        ->perMonth()
        ->count();

    return [
        'datasets' => [
            [
                'label' => 'Attendee',
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
