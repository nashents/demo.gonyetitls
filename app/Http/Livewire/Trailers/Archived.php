<?php

namespace App\Http\Livewire\Trailers;

use App\Models\Trailer;
use Livewire\Component;
use Illuminate\Support\Facades\Session;

class Archived extends Component
{

    public $trailers;
    public $trailer_id;
  
    public function mount(){
        $this->trailers = Trailer::where('archive','1')
                                    ->orderBy('registration_number', 'desc')->get();
      }

      public function restore($id){
        $this->trailer_id = $id;
        $this->dispatchBrowserEvent('show-trailerRestoreModal');
    }
    public function update(){
        $trailer =  Trailer::withTrashed()->find($this->trailer_id);
        $trailer->archive = 0;
        $trailer->update();
        Session::flash('success','Trailer Restored Successfully!!');
        $this->dispatchBrowserEvent('hide-trailerRestoreModal');
        return redirect()->route('trailers.index');
    }


    public function render()
    {
        return view('livewire.trailers.archived');
    }
}
