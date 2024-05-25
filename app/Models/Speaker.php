<?php

namespace App\Models;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section as ComponentsSection;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Speaker extends Model
{
    use HasFactory;

    const QUALIFICATIONS = [
        'business-leader' => 'Business Leader',
        'charisma' => 'Charismatic Speaker',
        'first-time' => 'First Time Speaker',
        'hometown-hero' => 'Hometown Hero',
        'humanitarian' => 'Works in Humanitarian Field',
        'laracasts-contributor' => 'Laracasts Contributor',
        'twitter-influencer' => 'Large Twitter Following',
        'youtube-influencer' => 'Large YouTube Following',
        'open-source' => 'Open Source Creator / Maintainer',
        'unique-perspective' => 'Unique Perspective'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'avatar',
        'email',
        'bio',
        'qualifications',
        'twitter_handle',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'avatar' => 'string'
    ];

    public static function getForm(){
        return [
            Section::make('Speaker Infos')
                ->description('Prevent abuse by limiting the number of requests per period')
                ->schema([
                    FileUpload::make('avatar')
                        ->storeFiles()
                        ->image()
                        ->maxSize(1024)
                        ->avatar(),
                    TextInput::make('name')
                        ->columns(2)
                        ->required()
                        ->maxLength(255),
                    TextInput::make('email')
                        ->columns(2)
                        ->email()
                        ->required()
                        ->maxLength(255),
                    MarkdownEditor::make('bio')
                        ->columnSpanFull()
                        ->required()
                        ->columnSpanFull(),
                    TextInput::make('twitter_handle')
                        ->columns(2)
                        ->required()
                        ->maxLength(255),
                    Select::make('qualifications')
                        ->columns(2)
                        ->options([
                            self::QUALIFICATIONS
                        ]),
            ])->columns(2),
        ];
    }

    public static function getInfoList(){
        return [
            ComponentsSection::make('Personal Informations')
                ->columns(3)
                ->schema([
                    ImageEntry::make('avatar')
                        ->circular()
                        ->defaultImageUrl(function ($record) {
                            return 'https://ui-avatars.com/api/?background=random&name=' . urlencode($record->name);
                        }),
                    Group::make()
                        ->schema(
                            [
                                TextEntry::make('name')
                                    ->columnSpan(2),
                                TextEntry::make('email')->columnSpan(2),
                                TextEntry::make('twitter_handle')
                                    ->columnSpan(2)
                                    ->label('Twitter')
                                    ->icon('heroicon-o-at-symbol')
                            ]
                        )
                ]),
            ComponentsSection::make('Other Informations')
                ->schema([
                    TextEntry::make('bio')
                        ->columnSpanFull()
                        ->markdown(),

                    TextEntry::make('qualifications')
                        ->bulleted()
                ])

                ];
    }


    public function talks(){
        return $this->hasMany(Talk::class);
    }


    public function conferences(): BelongsToMany
    {
        return $this->belongsToMany(Conference::class);
    }
}
