<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SpeakerResource\Pages;
use App\Filament\Resources\SpeakerResource\RelationManagers\TalksRelationManager;
use App\Models\Speaker;
use Filament\Forms\Form;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SpeakerResource extends Resource
{
    protected static ?string $model = Speaker::class;

    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $navigationGroup = 'Second Group';

    public static function getNavigationBadge(): ?string
    {
        return count(Speaker::all());
    }
    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'success';
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema(Speaker::getForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('avatar')
                    ->defaultImageUrl(function ($record) {
                        return 'https://ui-avatars.com/api/?background=random&name=' . urlencode($record->name);
                    })
                    ->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('qualifications')
                    ->searchable(),
                Tables\Columns\TextColumn::make('twitter_handle')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make()->slideOver(),
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\DeleteAction::make()
                ])

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }


    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema(Speaker::getInfoList());
    }

    public static function getRelations(): array
    {
        return [
            TalksRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSpeakers::route('/'),
            'create' => Pages\CreateSpeaker::route('/create'),
            'edit' => Pages\EditSpeaker::route('/{record}/edit'),
            'view' => Pages\ViewSpeaker::route('/{record}'),
        ];
    }
}
