<?php

namespace App\Models;

use App\Enums\Region;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Livewire;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Conference extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'status',
        'region',
        'venue_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'venue_id' => 'integer',
    ];

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    public function speakers(): BelongsToMany
    {
        return $this->belongsToMany(Speaker::class);
    }
    public function attendees(){
        return $this->hasMany(Attendee::class);
    }
    public function talks(): BelongsToMany
    {
        return $this->belongsToMany(Talk::class);
    }

    public static function getForm()
    {
        return  [

            Tabs::make('Tabs')
            ->columnSpanFull()
            ->tabs([
            Tabs\Tab::make('Conference Details')
            ->schema([
                Section::make('Conference Info')
                ->description('Prevent abuse by limiting the number of requests per period')
                ->icon('heroicon-m-exclamation-circle')
                ->schema([
                    TextInput::make('name')
                        ->required()
                        ->columnSpanFull()
                        ->maxLength(255),
                    MarkdownEditor::make('description')
                        ->required()
                        ->columnSpanFull()
                        ->maxLength(255),
                    TextInput::make('status')
                        ->columnSpanFull()
                        ->required(),
                    DateTimePicker::make('start_date')
                        ->required(),
                    DateTimePicker::make('end_date')
                        ->required(),
            ])->columns(2),
            ]),
            Tabs\Tab::make('Location')
            ->schema([
                Fieldset::make('Location')
                    ->schema([
                Select::make('region')
                    ->live()
                    ->options(Region::class)
                    ->required(),
                Select::make('venue_id')
                    ->searchable()
                    ->preload()
                    ->relationship('venue', 'name', modifyQueryUsing: function (EloquentBuilder $query, Get $get) {
                        return $query->where('region', $get('region'));
                    }),
                ]),
            ]),
            Tabs\Tab::make('Speakers')
            ->schema([
                CheckboxList::make('speaker')
                ->columns(3)
                ->columnSpanFull()
                ->searchable()
                ->relationship('speakers', 'name'),
            ]),
        ]),
        Actions::make([
            Action::make('Fill with Factory')
                ->icon('heroicon-m-star')
                ->requiresConfirmation()
                ->action(function ($livewire) {
                    $data = Conference::factory()->make()->toArray();
                    $livewire->form->fill($data);
                }),
        ]),
        ];
    }
}
