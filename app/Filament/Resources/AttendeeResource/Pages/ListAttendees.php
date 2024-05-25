<?php

namespace App\Filament\Resources\AttendeeResource\Pages;

use App\Filament\Resources\AttendeeResource;
use App\Filament\Resources\AttendeeResource\Widgets\AttendeeChart;
use App\Filament\Resources\AttendeeResource\Widgets\AttendeeOverview;
use Filament\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;

class ListAttendees extends ListRecords
{
   use ExposesTableToWidgets;
    protected static string $resource = AttendeeResource::class;
    public function getheaderWidgets():array{
        return [
            AttendeeOverview::class,
            AttendeeChart::class
        ];
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }



}
