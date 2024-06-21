<?php

namespace App\Http\Livewire\Vehicles;

use App\Models\Vehicle;
use Livewire\Component;
use Illuminate\Support\Facades\Session;

class Archived extends Component
{

    public $vehicles;
    public $vehicle_id;
  
    public function mount(){
        $this->vehicles = Vehicle::where('archive','1')
                                    ->orderBy('registration_number', 'desc')->get();
      }

      public function restore($id){
        $this->vehicle_id = $id;
        $this->dispatchBrowserEvent('show-vehicleRestoreModal');
    }
    public function update(){
        $vehicle =  Vehicle::withTrashed()->find($this->vehicle_id);
        $vehicle->archive = 0;
        $vehicle->update();
        Session::flash('success','Vehicle Restored Successfully!!');
        $this->dispatchBrowserEvent('hide-vehicleRestoreModal');
        return redirect()->route('vehicles.index');
    }


    public function render()
    {
        return view('livewire.vehicles.archived');
    }
}
