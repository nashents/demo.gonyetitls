<?php

namespace App\Http\Livewire\Horses;

use App\Models\Horse;
use Livewire\Component;
use App\Models\Currency;
use Maatwebsite\Excel\Excel;
use App\Exports\HorsesExport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Index extends Component
{


    public $horses;
    public $currencies;

    public function exportHorsesCSV(Excel $excel){

        return $excel->download(new HorsesExport, 'horses.csv', Excel::CSV);
    }
    public function exportHorsesPDF(Excel $excel){

        return $excel->download(new HorsesExport, 'horses.pdf', Excel::DOMPDF);
    }
    public function exportHorsesExcel(Excel $excel){
        return $excel->download(new HorsesExport, 'horses.xlsx');
    }

    public function mount(){

        $this->horses = Horse::with('transporter:id,name','horse_make:id,name','horse_model:id,name')->where('archive',0)->orderBy('registration_number','asc')->get();

        $this->currencies = Currency::all();
      }


    public function deactivate($id){
        $horse = Horse::find($id);
        $horse->status = 0 ;
        $horse->update();
        Session::flash('success','Horse successfully deactivated');
        return redirect(route('horses.index'));
    }

    public function activate($id){
        $horse = Horse::find($id);
        $horse->status = 1 ;
        $horse->update();
        Session::flash('success','Horse successfully deactivated');
        return redirect(route('horses.index'));
    }

    public function render()
    {
        $this->horses = Horse::with('transporter:id,name','horse_make:id,name','horse_model:id,name')->where('archive',0)->orderBy('registration_number','asc')->get();
        
        return view('livewire.horses.index',[
            'horses' => $this->horses
        ]);
    }
}
