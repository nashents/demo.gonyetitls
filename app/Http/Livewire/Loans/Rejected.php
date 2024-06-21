<?php

namespace App\Http\Livewire\Loans;

use App\Models\User;
use App\Models\Loan;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class Rejected extends Component
{
    public $loans;
    public $loan_id;
    public $trip_id;
    public $authorize;
    public $comments;
    public $loan;

    public function mount(){
        $period = Auth::user()->employee->company->period;
        if (isset( $period)) {
            if ($period != "all") {
                $this->loans = Loan::where('authorization', 'rejected')->whereYear('created_at',$period)->latest()->get();
            }else {
                $this->loans = Loan::where('authorization', 'rejected')->latest()->get();
            }
        }

    }
    public function authorize($id){
        $loan = Loan::find($id);
        $this->loan_id = $loan->id;
        $this->loan = $loan;
        $this->dispatchBrowserEvent('show-authorizationModal');
      }

      public function update(){
      try{
            $loan = Loan::find($this->loan_id);
            $loan->authorized_by_id = Auth::user()->id;
            $loan->authorization = $this->authorize;
            $loan->reason = $this->comments;
            $loan->update();

        if ($this->authorize == "approved") {
            $this->dispatchBrowserEvent('hide-authorizationModal');
            $this->dispatchBrowserEvent('alert',[
                'type'=>'success',
                'message'=>"loan Approved Successfully"
            ]);
            return redirect()->route('loans.approved');
        }else {
            $this->dispatchBrowserEvent('hide-authorizationModal');
            $this->dispatchBrowserEvent('alert',[
                'type'=>'success',
                'message'=>"loan Rejected Successfully"
            ]);
            return redirect()->route('loans.rejected');
        }
}
catch(\Exception $e){
    $this->dispatchBrowserEvent('hide-authorizationModal');
    $this->dispatchBrowserEvent('alert',[
        'type'=>'error',
        'message'=>"Something went wrong while trying to authorize loan!!"
    ]);
    }

      }
    public function render()
    {
        $this->loans = Loan::where('authorization', 'rejected')->latest()->get();
        return view('livewire.loans.rejected',[
            'loans' => $this->loans
        ]);
    }
}
