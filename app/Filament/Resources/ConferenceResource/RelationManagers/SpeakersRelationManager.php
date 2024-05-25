<?php

namespace App\Filament\Resources\ConferenceResource\RelationManagers;

use App\Models\Speaker;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SpeakersRelationManager extends RelationManager
{
    protected static string $relationship = 'speakers';
    public function isReadOnly(): bool
    {
        return false;
    }
    public function form(Form $form): Form
    {
        return $form
            ->schema(Speaker::getForm());
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema(Speaker::getInfoList());
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\ImageColumn::make('avatar')
                    ->defaultImageUrl(function($record){
                        return 'https://ui-avatars.com/api/?background=random&name=' . urlencode($record->name) ;
                    })
                    ->circular(),
                Tables\Columns\TextColumn::make('name')
                ->description(function($record){
                    return $record->email;
                })->searchable()->sortable(),
                TextColumn::make('qualifications')->searchable()

            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->slideOver(),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make()->slideOver(),
                    Tables\Actions\EditAction::make()->slideOver(),
                    Tables\Actions\DeleteAction::make(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
