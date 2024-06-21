<?php

namespace App\Http\Livewire\Vehicles;

use App\Models\Vehicle;
use Livewire\Component;
use Maatwebsite\Excel\Excel;
use App\Exports\VehiclesExport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Index extends Component
{


    public $vehicles;

    public function exportVehiclesCSV(Excel $excel){

        return $excel->download(new VehiclesExport, 'vehicles.csv', Excel::CSV);
    }
    public function exportVehiclesPDF(Excel $excel){

        return $excel->download(new VehiclesExport, 'vehicles.pdf', Excel::DOMPDF);
    }
    public function exportVehiclesExcel(Excel $excel){
        return $excel->download(new VehiclesExport, 'vehicles.xlsx');
    }
  

    public function mount(){
        $this->vehicles = Vehicle::with('transporter:id,name')->where('archive',0)->orderBy('registration_number','asc')->get();
      }


    public function deactivate($id){
        $vehicle = Vehicle::find($id);
        $vehicle->status = 0 ;
        $vehicle->update();
        Session::flash('success','Vehicle successfully deactivated');
        return redirect(route('vehicles.index'));
    }

    public function activate($id){
        $vehicle = Vehicle::find($id);
        $vehicle->status = 1 ;
        $vehicle->update();
        Session::flash('success','Vehicle successfully deactivated');
        return redirect(route('vehicles.index'));
    }

    public function render()
    {
        return view('livewire.vehicles.index');
    }
}
