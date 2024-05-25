<?php

namespace App\Filament\Resources\ConferenceResource\Pages;

use App\Filament\Resources\ConferenceResource;
use App\Filament\Resources\ConferenceResource\Widgets\ConferenceChart;
use App\Filament\Resources\ConferenceResource\Widgets\ConferenceOverview;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use PhpParser\Node\Expr\FuncCall;

class ListConferences extends ListRecords
{
    protected static string $resource = ConferenceResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            ConferenceOverview::class,
            ConferenceChart::class
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
