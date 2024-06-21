<?php

namespace App\Http\Livewire\Horses;

use App\Models\Horse;
use Livewire\Component;

class Show extends Component
{
    public $horse;
    public $horses;
    public $horse_id;
    public $trips;
    public $usages;
    public $total_usage;
    public $tyre_assignments;
    public $cashflows;
    public $documents;
    public $fuel_balance;
    public $fuel_tank_capacity;
    public $mileage;
    public $images;
    public $fitnesses;
    public $next_service;


    public function mount($id){
        $this->horse = Horse::find($id);
        $this->horse_id = $id;
        $this->tyre_assignments = $this->horse->tyre_assignments;
        $this->trips = $this->horse->trips;
        $this->usages = $this->horse->fuels;
        if (isset($this->horse->trips)) {
            $this->total_usage = $this->horse->trips->where('trip_fuel','!=',null)->where('deleted_at',Null)->where('trip_status',"Offloaded")->where('trip_fuel','!=',"")->sum('trip_fuel');
        }
      
        $this->cashflows = $this->horse->cash_flows;
        $this->documents = $this->horse->horse_documents;
        $this->fuel_balance = $this->horse->fuel_balance;
        $this->fuel_tank_capacity = $this->horse->fuel_tank_capacity;
        $this->mileage = $this->horse->mileage;
        $this->next_service = $this->horse->next_service;
        $this->images = $this->horse->horse_images;
        $this->fitnesses = $this->horse->fitnesses;
    }

    public function odometer($id){
        $this->horse_id = $id;
        $this->horse = Horse::find($id);
        $this->mileage = $this->horse->mileage;
        $this->dispatchBrowserEvent('show-odometerModal');
    }
    public function nextService($id){
        $this->horse_id = $id;
        $this->horse = Horse::find($id);
        $this->next_service = $this->horse->next_service;
        $this->dispatchBrowserEvent('show-nextServiceModal');
    }


    public function updateOdometer(){
        $horse = Horse::find($this->horse_id);
        $horse->mileage = $this->mileage;
        $horse->update();
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Horse Mileage Updated Successfully!!"
        ]);
        $this->dispatchBrowserEvent('hide-odometerModal');
        return redirect(request()->header('Referer'));
    }

    public function updateNextService(){
        $horse = Horse::find($this->horse_id);
        $horse->next_service = $this->next_service;
        $horse->update();
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Horse Next Service Mileage Updated Successfully!!"
        ]);
        $this->dispatchBrowserEvent('hide-nextServiceModal');
        return redirect(request()->header('Referer'));
    }


    public function fuelTank($id){
        $this->horse_id = $id;
        $this->horse = Horse::find($id);
        $this->fuel_balance = $this->horse->fuel_balance;
        $this->dispatchBrowserEvent('show-fuelTankModal');
    }

    public function updateFuelTank(){
        $horse = Horse::find($this->horse_id);
        $horse->fuel_balance = $this->fuel_balance;
        $horse->update();
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Fuel Level Updated Successfully!!"
        ]);
        $this->dispatchBrowserEvent('hide-fuelTankModal');
        return redirect(request()->header('Referer'));
    }

    public function fuelTankCapacity($id){
        $this->horse_id = $id;
        $this->horse = Horse::find($id);
        $this->fuel_tank_capacity = $this->horse->fuel_tank_capacity;
        $this->dispatchBrowserEvent('show-fuelTankCapacityModal');
    }

    public function updateFuelTankCapacity(){
        $horse = Horse::find($this->horse_id);
        $horse->fuel_tank_capacity = $this->fuel_tank_capacity;
        $horse->update();
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Fuel Tank Capacity Updated Successfully!!"
        ]);
        $this->dispatchBrowserEvent('hide-fuelTankCapacityModal');
        return redirect(request()->header('Referer'));
    }

    public function render()
    {
        $this->fuel_balance ;
        $this->mileage ;

        return view('livewire.horses.show',[
            'fuel_balance' => $this->fuel_balance,
            'mileage' => $this->mileage
        ]);
    }
}
