<?php

namespace App\Http\Livewire\Destinations;

use App\Models\Country;
use Livewire\Component;
use App\Models\Destination;
use Maatwebsite\Excel\Excel;
use App\Exports\DestinationsExport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Index extends Component
{

    public $destinations;
    public $country_id;
    public $city;
    public $long;
    public $lat;
    public $countries;
    public $description;

    public $updateMode = false;
    public $deleteMode = false;

    public $destination_id;
    public $user_id;

    public function mount(){
        $this->destinations = Destination::all();
        $this->countries = Country::all();
    }
    private function resetInputFields(){
        $this->country_id = '';
        $this->city = '';
        $this->description = '';
        $this->long = '';
        $this->lat = '';
    }

    public function exportDestinationsCSV(Excel $excel){

        return $excel->download(new DestinationsExport, 'destinations.csv', Excel::CSV);
    }
    public function exportDestinationsPDF(Excel $excel){

        return $excel->download(new DestinationsExport, 'destinations.pdf', Excel::DOMPDF);
    }
    public function exportDestinationsExcel(Excel $excel){

        return $excel->download(new DestinationsExport, 'destinations.xlsx');
    }
    public function updated($value){
        $this->validateOnly($value);
    }
    protected $rules = [
        'country_id' => 'required',
        'city' => 'required|unique:destinations,city,NULL,id,deleted_at,NULL',
        'long' => 'required',
        'lat' => 'required',
        'description' => 'required',
    ];

    public function store(){
        try{
        $destination = new Destination;
        $destination->user_id = Auth::user()->id;
        $destination->country_id = $this->country_id;
        $destination->city = $this->city;
        $destination->long = $this->long;
        $destination->lat = $this->lat;
        $destination->description = $this->description;
        $destination->save();

        $this->dispatchBrowserEvent('hide-destinationModal');
        $this->resetInputFields();
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Destination Created Successfully!!"
        ]);

    }
    catch(\Exception $e){
    // Set Flash Message
    $this->dispatchBrowserEvent('alert',[
        'type'=>'error',
        'message'=>"Something goes wrong while creating destination!!"
    ]);
     }
    }

    public function edit($id){
    $destination = Destination::find($id);
    $this->updateMode = true;
    $this->user_id = $destination->user_id;
    $this->country_id = $destination->country_id;
    $this->city = $destination->city;
    $this->long = $destination->long;
    $this->lat = $destination->lat;
    $this->description = $destination->description;
    $this->destination_id = $destination->id;
    $this->dispatchBrowserEvent('show-destinationEditModal');

    }



    public function update()
    {
        if ($this->destination_id) {
            try{
            $destination = Destination::find($this->destination_id);
            $destination->update([
                'user_id' => Auth::user()->id,
                'country_id' => $this->country_id,
                'city' => $this->city,
                'long' => $this->long,
                'lat' => $this->lat,
                'description' => $this->description,
            ]);
            $this->dispatchBrowserEvent('hide-destinationEditModal');
            $this->resetInputFields();
            $this->dispatchBrowserEvent('alert',[
                'type'=>'success',
                'message'=>"Destination Updated Successfully!!"
            ]);

        }
        catch(\Exception $e){
        // Set Flash Message
        $this->dispatchBrowserEvent('alert',[
            'type'=>'error',
            'message'=>"Something goes wrong while creating destination!!"
        ]);
         }

        }
    }

    public function render()
    {
        $this->destinations = Destination::latest()->get();
        return view('livewire.destinations.index',[
            'destinations' => $this->destinations
        ]);
    }
}
