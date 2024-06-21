<?php

namespace App\Http\Livewire\Loans;

use App\Models\Loan;
use Livewire\Component;

class Show extends Component
{

    public $loan;

    public function mount($id){
        $this->loan = Loan::find($id);
    }

    public function render()
    {
        return view('livewire.loans.show');
    }
}
