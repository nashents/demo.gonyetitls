<?php

namespace App\Http\Livewire\Trailers;

use App\Models\Trailer;
use Livewire\Component;

class Trips extends Component
{

    public $trips;
    public $trailer;

    public function mount($id){
        $this->trailer = Trailer::find($id);
        $this->trips = $this->trailer->trips;
    }

    public function render()
    {
        return view('livewire.trailers.trips');
    }
}
