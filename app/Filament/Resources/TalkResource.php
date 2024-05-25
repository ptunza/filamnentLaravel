<?php

namespace App\Filament\Resources;

use App\Enums\TalkLength;
use App\Enums\TalkStatus;
use App\Filament\Resources\TalkResource\Pages;
use App\Models\Talk;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action as ActionsAction;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class TalkResource extends Resource
{
    protected static ?string $model = Talk::class;

    protected static ?string $navigationGroup = 'Second Group';
    public static function getNavigationBadge(): ?string
    {
        return count(Talk::all());
    }
    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'success';
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema(Talk::getForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->filtersTriggerAction(function($action){
                return $action->button()->label('Filters');
            })
            ->persistFiltersInSession()
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),

                IconColumn::make('talk_length')
                    ->icon(function($state){
                        return match($state->value){
                            'Lightning - 15 Minutes' => 'heroicon-o-bolt',
                            'Normal - 30 Minutes' => 'heroicon-o-megaphone',
                            'Keynote' => 'heroicon-o-key',
                        };
                    }),

                Tables\Columns\TextColumn::make('talk_status')
                    ->badge()
                    ->color(
                        function($state){
                            return match($state->value){
                                'Approved' => 'success',
                                'Rejected' => 'danger',
                                'Submitted' => 'primary',
                            };
                        })
                    ->searchable(),

                IconColumn::make('new_talk')
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
                Tables\Columns\TextColumn::make('speaker.name')
                    ->numeric()
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
                SelectFilter::make('talk_status')
                    ->options(TalkStatus::class),
                SelectFilter::make('talk_length')
                    ->options(TalkLength::class),
                TernaryFilter::make('new_talk')

            ])
            ->actions([
                Tables\Actions\EditAction::make()->slideOver(),
                Tables\Actions\ViewAction::make()->slideOver(),
                ActionGroup::make([
                    ActionsAction::make('approve')
                        ->visible(function($record){
                            return $record->talk_status->value !== 'Approved';
                        })
                        ->icon('heroicon-o-plus-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(fn (Talk $record) => $record->update(['talk_status' => TalkStatus::APPROVED]))->after(
                           function(){
                            Notification::make()->success()->title('success')->send();
                           }
                        ),
                    ActionsAction::make('reject')
                        ->visible(function($record){
                        return $record->talk_status->value !== 'Rejected';
                        })
                        ->icon('heroicon-o-plus-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(fn (Talk $record) => $record->update(['talk_status' => TalkStatus::REJECTED]))->after(
                            function(){
                             Notification::make()->danger()->title('rejected')->send();
                            }
                         ),
                ]),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
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
        ->schema(Talk::getInfoLists());
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTalks::route('/'),
            'create' => Pages\CreateTalk::route('/create'),
            'edit' => Pages\EditTalk::route('/{record}/edit'),
        ];
    }
}
