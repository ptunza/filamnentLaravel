<?php

namespace App\Models;

use App\Enums\TalkLength;
use App\Enums\TalkStatus;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Talk extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'abstract',
        'new_talk',
        'talk_length',
        'talk_status',
        'speaker_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'talk_length' => TalkLength::class,
        'talk_status' => TalkStatus::class,
        'speaker_id' => 'integer',
    ];

    public function speaker(): BelongsTo
    {
        return $this->belongsTo(Speaker::class);
    }

    public function conferences(): BelongsToMany
    {
        return $this->belongsToMany(Conference::class);
    }

    public static function getForm($speakerId = null){
        return [
            TextInput::make('title')
                ->required()
                ->maxLength(255),
            RichEditor::make('abstract')
                ->required()
                ->columnSpanFull(),
            Select::make('talk_length')
                ->options(TalkLength::class)
                ->required(),
            Select::make('talk_status')
                ->options(TalkStatus::class)
                ->required(),
            Select::make('speaker_id')
                ->hidden(function()use($speakerId){
                    return $speakerId !== null;
                })
                ->relationship('speaker', 'name')
                ->required(),
            Toggle::make('new_talk')
                ->columnSpanFull()
                ->required(),
        ];
    }

    public static function getInfoLists($speakerId = null){
        return [
            Section::make('Speaker')
                ->hidden(function()use($speakerId){
                    return $speakerId !== null;
                })
                ->columns(3)
                ->schema([
                ImageEntry::make('speaker.image')
                    ->hiddenLabel()
                    ->circular()
                    ->defaultImageUrl(function($record){
                        return 'https://ui-avatars.com/api/?background=random&name=' . urlencode($record->speaker->name);
                        }),
                Group::make()
                    ->columns(2)
                    ->columnSpan(2)
                    ->schema([
                        TextEntry::make('speaker.name')
                            ->label('Name'),
                        TextEntry::make('speaker.email')
                            ->icon('heroicon-o-envelope')
                            ->label('Email'),
                        TextEntry::make('speaker.twitter_handle')
                            ->icon('heroicon-o-at-symbol')
                            ->label('Twitter'),
                        TextEntry::make('speaker.qualifications')
                            ->bulleted()
                            ->label('qualifications'),
                        ])

            ]),

            Fieldset::make('Talk Informations')
                ->schema([
                TextEntry::make('title'),
                TextEntry::make('abstract')->html(),
                TextEntry::make('talk_status')
                ->badge()
                ->color(
                    function($state){
                        return match($state->value){
                            'Approved' => 'success',
                            'Rejected' => 'danger',
                            'Submitted' => 'primary',
                        };
                    }
                ),
                TextEntry::make('talk_length')
                ->badge(),
                IconEntry::make('new_talk')
                ->icon(
                    function($state){
                        if($state){
                            return 'heroicon-o-check-circle';
                        }else{
                            return 'heroicon-o-x-circle';
                        };
                    }
                )
                ->color(
                    function($state){
                        if($state){
                            return 'success';
                        }else{
                            return 'danger';
                        };
                    }
                )
        ])
                ];
    }
}
