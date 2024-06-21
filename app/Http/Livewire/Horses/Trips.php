<?php

namespace App\Http\Livewire\Horses;

use App\Models\Horse;
use Livewire\Component;

class Trips extends Component
{
    public $trips;
    public $horse;

    public function mount($id){
        $this->horse = Horse::find($id);
        $this->trips = $this->horse->trips;
    }
    public function render()
    {
        return view('livewire.horses.trips');
    }
}
