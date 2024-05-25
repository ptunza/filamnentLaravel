<?php

namespace App\Filament\Resources\AttendeeResource\Widgets;

use App\Filament\Resources\AttendeeResource\Pages\ListAttendees;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class AttendeeChart extends ChartWidget
{
    use InteractsWithPageTable;
    protected static ?string $heading = 'Attendee';

    protected int | string | array $columnSpan = 'full';
    protected static ?string $maxHeight = '200px';
    protected function getTablePage(): string
    {
        return ListAttendees::class;
    }
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
        $query = $this->getPageTableQuery();
        $query->getQuery()->orders = [];
        if ($activeFilter == 'today') {
            $data = Trend::query($query)
            ->between(
            start: now()->subDay(),
            end: now(),
        )
        ->perHour()
        ->count();
        } else if($activeFilter == 'week'){
            $data = Trend::query($query)
            ->between(
            start: now()->subWeek(),
            end: now(),
        )
        ->perDay()
        ->count();
        } else if($activeFilter == 'month'){
            $data = Trend::query($query)
            ->between(
            start: now()->subMonth(),
            end: now(),
        )
        ->perMonth()
        ->count();
        } else if($activeFilter == 'year'){
            $data = Trend::query($query)
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
                'label' => 'Attendee',
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
