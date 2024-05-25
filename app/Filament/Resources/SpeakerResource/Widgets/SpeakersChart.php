<?php

namespace App\Filament\Resources\SpeakerResource\Widgets;

use App\Filament\Resources\SpeakerResource\Pages\ListSpeakers;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use PhpParser\Node\Expr\FuncCall;

class SpeakersChart extends ChartWidget
{
    use InteractsWithPageTable;
    protected static ?string $heading = 'Speakers';
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
    protected function getTablePage(): string
    {
        return ListSpeakers::class;
    }

    protected function getData(): array
    {
        $query = $this->getPageTableQuery();
        $query->getQuery()->orders = [];
        $activeFilter = $this->filter;
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
        ->perDay()
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
