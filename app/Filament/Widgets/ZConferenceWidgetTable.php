<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ConferenceResource;
use App\Models\Conference;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class ZConferenceWidgetTable extends BaseWidget
{
    protected static ?string $heading = 'Conference';
    protected int | string | array $columnSpan = 'full';
    public function table(Table $table): Table
    {
        return $table
            ->query(ConferenceResource::getEloquentQuery())
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->description(fn (Conference $record): string => substr($record->description,0,30) . " ...")
                    ->searchable(),

                Tables\Columns\TextColumn::make('start_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('region')
                ->badge()
                ->color(function($state){
                    return match($state){
                        'Eu' => 'gray',
                        'Au' => 'warning',
                        'Us' => 'success',
                        'online' => 'danger',
                    };
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('speakers_count')->counts('speakers')
                ->wrap()
                ->numeric(),
                Tables\Columns\TextColumn::make('venue.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ]);
    }
}
