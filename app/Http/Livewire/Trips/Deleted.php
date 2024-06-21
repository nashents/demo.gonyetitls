<?php

namespace App\Http\Livewire\Trips;

use App\Models\Trip;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Deleted extends Component
{

    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    private $trips;
    public $trip_id;

    public function mount(){

      
        
    }

    public function restore($id){
        $this->trip_id = $id;
        $this->dispatchBrowserEvent('show-tripRestoreModal');
    }
    public function update(){

        Trip::withTrashed()->find($this->trip_id)->restore();

        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Trip Restored Successfully!!"
        ]);
        $this->dispatchBrowserEvent('hide-tripRestoreModal');
       
    }
    public function render()
    {
       
        return view('livewire.trips.deleted',[
            'trips' => Trip::onlyTrashed()->latest()->paginate(10),
        ]);
    }
}
