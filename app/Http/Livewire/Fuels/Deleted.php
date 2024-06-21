<?php

namespace App\Http\Livewire\Fuels;

use App\Models\Fuel;
use Livewire\Component;
use Illuminate\Support\Facades\Session;

class Deleted extends Component
{
    public $fuels;
    public $fuel_id;

    public function mount(){
        $period = Auth::user()->employee->company->period;
        if (isset( $period)) {
            if ($period != "all") {
                $this->fuels = Fuel::onlyTrashed()->whereYear('created_at',$period)->latest()->get();
            }else {
                $this->fuels = Fuel::onlyTrashed()->latest()->get();
            }
        }

    }

    public function restore($id){
        $this->fuel_id = $id;
        $this->dispatchBrowserEvent('show-fuelRestoreModal',['message',"Fuel Order successfully restored"]);
    }
    public function update(){
        Fuel::withTrashed()->find($this->fuel_id)->restore();
        Session::flash('success','Fuel Order restored successfully');
        $this->dispatchBrowserEvent('hide-fuelRestoreModal',['message',"Fuel Order successfully restored"]);
        return redirect()->route('fuels.deleted');
    }
    public function render()
    {
        return view('livewire.fuels.deleted');
    }
}
