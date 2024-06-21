<?php

namespace App\Http\Livewire\Horses;

use App\Models\Horse;
use Livewire\Component;
use Illuminate\Support\Facades\Session;

class Archived extends Component
{

    public $horses;
    public $horse_id;
  
    public function mount(){
        $this->horses = Horse::where('archive','1')
                                    ->orderBy('registration_number', 'desc')->get();
      }

      public function restore($id){
        $this->horse_id = $id;
        $this->dispatchBrowserEvent('show-horseRestoreModal');
    }
    public function update(){
        $horse =  Horse::withTrashed()->find($this->horse_id);
        $horse->archive = 0;
        $horse->update();
        Session::flash('success','Horse Restored Successfully!!');
        $this->dispatchBrowserEvent('hide-horseRestoreModal');
        return redirect()->route('horses.index');
    }


    public function render()
    {
        return view('livewire.horses.archived');
    }
}
