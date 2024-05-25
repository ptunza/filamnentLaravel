<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Filament\Resources\UserResource\Widgets\UsersChart;
use App\Filament\Resources\UserResource\Widgets\UsersOverview;
use Filament\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = UserResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            UsersOverview::class,
            UsersChart::class
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
