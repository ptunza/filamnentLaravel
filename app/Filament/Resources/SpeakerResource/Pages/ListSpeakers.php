<?php

namespace App\Filament\Resources\SpeakerResource\Pages;

use App\Filament\Resources\SpeakerResource;
use App\Filament\Resources\SpeakerResource\Widgets\SpeakersChart;
use App\Filament\Resources\SpeakerResource\Widgets\SpeakersOverview;
use App\Filament\Resources\UserResource\Widgets\UsersOverview;
use Filament\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;

class ListSpeakers extends ListRecords
{
    use ExposesTableToWidgets;
    protected static string $resource = SpeakerResource::class;
    protected function getHeaderWidgets(): array
    {
        return[
            SpeakersOverview::class,
            SpeakersChart::class
        ];
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
