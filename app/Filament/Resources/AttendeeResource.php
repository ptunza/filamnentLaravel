<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttendeeResource\Pages;
use App\Filament\Resources\AttendeeResource\RelationManagers;
use App\Models\Attendee;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use PhpParser\Node\Expr\FuncCall;

class AttendeeResource extends Resource
{
    protected static ?string $model = Attendee::class;
    protected static ?string $navigationGroup = 'First Group';
    protected static ?string $recordTitleAttribute = 'name';
    public static function form(Form $form): Form
    {
        return $form
            ->schema(Attendee::getForm());
    }
    public static function getNavigationBadge(): ?string
    {
        return count(Attendee::all());
    }
    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'success';
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('conference.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Attendee')
                    ->description(function($record){
                        return $record->email;
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('ticket_cost'),
                Tables\Columns\IconColumn::make('is_paid')
                ->color(function($state){
                    return $state ? 'success' : 'danger';
                })
                ->icon(function($state){
                    return $state ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle';
                }),
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
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                ActionGroup::make(
                    [
                        Tables\Actions\EditAction::make(),
                        Tables\Actions\DeleteAction::make()
                    ]
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
        ->schema([
            TextEntry::make('name'),
            TextEntry::make('email'),
            TextEntry::make('ticket_cost'),
            IconEntry::make('is_paid')
            ->color(function($state){
                if($state){
                    return 'success';
                }else{
                    return 'danger';
                };
            })
            ->icon(function($state){
                if($state){
                    return 'heroicon-o-check-circle';
                }else{
                    return 'heroicon-o-x-circle';
                };
            }),
            TextEntry::make('conference.name')
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAttendees::route('/'),
            'create' => Pages\CreateAttendee::route('/create'),
            'view' => Pages\ViewAttendee::route('/{record}'),
            'edit' => Pages\EditAttendee::route('/{record}/edit'),
        ];
    }
}
