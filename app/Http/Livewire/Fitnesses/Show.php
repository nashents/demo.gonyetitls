<?php

namespace App\Http\Livewire\Fitnesses;

use Carbon\Carbon;
use App\Models\Fitness;
use Livewire\Component;

class Show extends Component
{

    public $fitness;

    public function mount($id){
        $this->fitness = Fitness::find($id); 
    }



    public function close($id){
      
        $fitness = Fitness::find($id);

        $fitness->first_reminder_at_status = True;
        $fitness->second_reminder_at_status = True;
        $fitness->third_reminder_at_status = True;
        $fitness->closed = 1;
        $fitness->update();

        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Reminder Closed Successfully!!"
        ]);

        return redirect(request()->header('Referer'));

    }

    public function snooze($id){
      
        $fitness = Fitness::find($id);

        if ($fitness->first_reminder_at <=  Carbon::today() ) {
            $fitness->first_reminder_at_status = True;
        }
        if ($fitness->second_reminder_at <=  Carbon::today() ) {
            $fitness->second_reminder_at_status = True;
        }
        if ($fitness->third_reminder_at <=  Carbon::today() ) {
            $fitness->third_reminder_at_status = True;
        }
        $fitness->update();

        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Reminder Viewed & Snoozed Successfully!!"
        ]);

        return redirect(request()->header('Referer'));

    }

    public function render()
    {
        return view('livewire.fitnesses.show');
    }
}
