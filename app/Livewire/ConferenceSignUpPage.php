<?php

namespace App\Livewire;

use App\Models\Attendee;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Illuminate\Support\HtmlString;
use Livewire\Component;

class ConferenceSignUpPage extends Component implements HasForms, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;
    protected $cost = 5000;
    protected $conferenceId = 1;


    public function editAction(): Action
{
    return Action::make('edit')
        ->slideOver()
        ->form([
            Placeholder::make('total cost')
            ->content(function(Get $get){
                return count($get('attendee')) * 5000;
            }),
            Repeater::make('attendee')
                ->schema(
                    Attendee::getForm(),
                ),
            ])
        ->action(function($data){
            collect($data['attendee'])->each(function($data){
                Attendee::create([
                'ticket_cost' => $this->cost,
                'name' => $data['name'],
                'email' => $data['email'],
                'is_paid' => $data['is_paid'],]);
            });
        })->after(
           function(){
            Notification::make()->success()->title('Success!')
            ->body(new HtmlString('You have successfully signed up for the conference.'))->send();
           }
        );

}

    public function render()
    {
        return view('livewire.conference-sign-up-page');
    }
}
