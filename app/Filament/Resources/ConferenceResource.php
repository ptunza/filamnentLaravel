<?php

namespace App\Filament\Resources;

use App\Enums\Region;
use App\Filament\Resources\ConferenceResource\Pages;
use App\Filament\Resources\ConferenceResource\RelationManagers\AttendeesRelationManager;
use App\Filament\Resources\ConferenceResource\RelationManagers\SpeakersRelationManager;
use App\Filament\Resources\ConferenceResource\RelationManagers\TalksRelationManager;
use App\Models\Attendee;
use App\Models\Conference;
use App\Models\Speaker;
use Filament\Forms\Form;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ConferenceResource extends Resource
{
    protected static ?string $model = Conference::class;

    protected static ?string $navigationGroup = 'First Group';
    public static function getNavigationBadge(): ?string
    {
        return count(Conference::all());
    }
    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'success';
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema(Conference::getForm());
    }
    protected static ?string $recordTitleAttribute = 'name';
    public static function table(Table $table): Table
    {
        return $table
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
            ])
            ->filters([
                SelectFilter::make('Region')
                ->multiple()
                ->options(Region::class),
                SelectFilter::make('Venue')
                ->multiple()
                ->preload()
                ->relationship('venue','name')

            ])
            ->actions([
                Tables\Actions\ViewAction::make()->slideOver(),
                ActionGroup::make([
                Tables\Actions\EditAction::make()->slideOver(),
                Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    RestoreBulkAction::make()
                ]),
            ]);
    }
    public static function infolist(Infolist $infolist): Infolist
    {
    return $infolist
        ->schema([

            Section::make('Conference Details')
            ->schema([
                TextEntry::make('name')->columns(2),
                TextEntry::make('status')->columns(2),
                TextEntry::make('description')->columnSpanFull(),
            ])->columns(2),
            Fieldset::make('Date & Location')
            ->schema([
                TextEntry::make('venue.name'),
                TextEntry::make('region'),
                TextEntry::make('start_date'),
                TextEntry::make('end_date'),
            ])


        ]);
    }
    public static function getRelations(): array
    {
        return [
            AttendeesRelationManager::class,
            SpeakersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListConferences::route('/'),
            'create' => Pages\CreateConference::route('/create'),
            'view' => Pages\ViewConference::route('/{record}'),
            'edit' => Pages\EditConference::route('/{record}/edit'),
        ];
    }
}
