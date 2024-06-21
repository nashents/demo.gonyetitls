<?php

namespace App\Http\Livewire\Retreads;

use App\Models\Retread;
use Livewire\Component;
use App\Models\RetreadTyre;

class Index extends Component
{

    public $retread_tyres;
    public $retread_tyre_id;

    public function mount(){
        $this->retread_tyres = RetreadTyre::latest()->get();
    }

    public function render()
    {
        return view('livewire.retreads.index');
    }
}
