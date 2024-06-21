<?php

namespace App\Http\Livewire\Vehicles;

use App\Models\Vehicle;
use Livewire\Component;

class Show extends Component
{
    
    public $fuel_balance;
    public $fuel_tank_capacity;
    public $mileage;
    public $next_service;

    public $vehicle;
    public $vehicles;
    public $vehicle_id;
    public $trips;
    public $usages;
    public $total_usage;
    public $tyre_assignments;
    public $cashflows;
    public $logs;
    public $documents;
    public $images;
    public $fitnesses;

    public function mount($id){

        $this->vehicle = Vehicle::find($id);
        $this->vehicle_id = $id;
        $this->tyre_assignments = $this->vehicle->tyre_assignments;
        $this->trips = $this->vehicle->trips;
        $this->usages = $this->vehicle->fuels;
        if (isset($this->vehicle->trips)) {
            $this->total_usage = $this->vehicle->trips->where('trip_fuel','!=',null)->where('trip_fuel','!=',"")->sum('trip_fuel');
        }
      
        $this->cashflows = $this->vehicle->cash_flows;
        $this->documents = $this->vehicle->vehicle_documents;
        $this->fuel_balance = $this->vehicle->fuel_balance;
        $this->fuel_tank_capacity = $this->vehicle->fuel_tank_capacity;
        $this->mileage = $this->vehicle->mileage;
        $this->next_service = $this->vehicle->next_service;
        $this->images = $this->vehicle->vehicle_images;
        $this->fitnesses = $this->vehicle->fitnesses;

        // $this->vehicle = Vehicle::find($id);
        // $this->tyre_assignments = $this->vehicle->tyre_assignments;
        // $this->trips = $this->vehicle->trips;
        // $this->usages = $this->vehicle->fuels;
        // $this->total_usage = $this->vehicle->fuels->sum('quantity');
        // $this->cashflows = $this->vehicle->cash_flows;
        // $this->logs = $this->vehicle->logs;
        // $this->documents = $this->vehicle->vehicle_documents;
        // $this->images = $this->vehicle->vehicle_images;
        // $this->fitnesses = $this->vehicle->fitnesses;


    }


    public function odometer($id){
        $this->vehicle_id = $id;
        $this->vehicle = Vehicle::find($id);
        $this->mileage = $this->vehicle->mileage;
        $this->dispatchBrowserEvent('show-odometerModal');
    }
    public function nextService($id){
        $this->vehicle_id = $id;
        $this->vehicle = Vehicle::find($id);
        $this->next_service = $this->vehicle->next_service;
        $this->dispatchBrowserEvent('show-nextServiceModal');
    }


    public function updateOdometer(){
        $vehicle = Vehicle::find($this->vehicle_id);
        $vehicle->mileage = $this->mileage;
        $vehicle->update();
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Vehicle Mileage Updated Successfully!!"
        ]);
        $this->dispatchBrowserEvent('hide-odometerModal');
        return redirect(request()->header('Referer'));
    }

    public function updateNextService(){
        $vehicle = Vehicle::find($this->vehicle_id);
        $vehicle->next_service = $this->next_service;
        $vehicle->update();
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Vehicle Next Service Mileage Updated Successfully!!"
        ]);
        $this->dispatchBrowserEvent('hide-nextServiceModal');
        return redirect(request()->header('Referer'));
    }


    public function fuelTank($id){
        $this->vehicle_id = $id;
        $this->vehicle = Vehicle::find($id);
        $this->fuel_balance = $this->vehicle->fuel_balance;
        $this->dispatchBrowserEvent('show-fuelTankModal');
    }

    public function updateFuelTank(){
        $vehicle = Vehicle::find($this->vehicle_id);
        $vehicle->fuel_balance = $this->fuel_balance;
        $vehicle->update();
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Fuel Level Updated Successfully!!"
        ]);
        $this->dispatchBrowserEvent('hide-fuelTankModal');
        return redirect(request()->header('Referer'));
    }

    public function fuelTankCapacity($id){
        $this->vehicle_id = $id;
        $this->vehicle = Vehicle::find($id);
        $this->fuel_tank_capacity = $this->vehicle->fuel_tank_capacity;
        $this->dispatchBrowserEvent('show-fuelTankCapacityModal');
    }

    public function updateFuelTankCapacity(){
        $vehicle = Vehicle::find($this->vehicle_id);
        $vehicle->fuel_tank_capacity = $this->fuel_tank_capacity;
        $vehicle->update();
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Fuel Tank Capacity Updated Successfully!!"
        ]);
        $this->dispatchBrowserEvent('hide-fuelTankCapacityModal');
        return redirect(request()->header('Referer'));
    }

    public function render()
    {
        return view('livewire.vehicles.show');
    }
}
