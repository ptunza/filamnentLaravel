<?php

namespace App\Filament\Resources\TalkResource\Pages;

use App\Enums\TalkStatus;
use App\Filament\Resources\TalkResource;
use App\Filament\Resources\TalkResource\Widgets\TalksChart;
use Filament\Actions;
use Filament\Resources\Components\Tab as ComponentsTab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class ListTalks extends ListRecords
{
    protected static string $resource = TalkResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            TalksChart::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
{
    return [
        'all' => ComponentsTab::make(),
        'approved' => ComponentsTab::make()
            ->modifyQueryUsing(fn (EloquentBuilder $query) => $query->where('talk_status', TalkStatus::APPROVED)),
        'rejected' => ComponentsTab::make()
            ->modifyQueryUsing(fn (EloquentBuilder $query) => $query->where('talk_status', TalkStatus::REJECTED )),
        'submitted' => ComponentsTab::make()
            ->modifyQueryUsing(fn (EloquentBuilder $query) => $query->where('talk_status', TalkStatus::SUBMITTED )),
    ];
}
}
