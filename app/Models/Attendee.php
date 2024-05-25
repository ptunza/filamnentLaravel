<?php

namespace App\Models;

use App\Enums\Region;
use Awcodes\Shout\Components\Shout;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Expr\FuncCall;

class Attendee extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'ticket_cost',
        'is_paid'
    ];
    public static function getForm(){
        return [
            Shout::make('so-important')
            ->visible(function(Get $get){
                return $get('ticket_cost') > 5000;
            })
            ->columnSpanFull()
            ->type('warning')
            ->content(function(Get $get){
                return '$'. $get('ticket_cost') . ' is expensive for a ticket';
            }),
            Section::make()
            ->columns(2)
            ->schema([
                TextInput::make('name')
                ->required()
                ->maxLength(255),
            TextInput::make('email')
                ->email()
                ->required()
                ->maxLength(255),
            TextInput::make('ticket_cost')
                ->live()
                ->hiddenLabel()
                ->default(5000),
            Checkbox::make('is_paid')
        ]),
        ];
    }

    public function conference(){
        return $this->belongsTo(Conference::class);
    }

}
