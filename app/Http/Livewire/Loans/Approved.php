<?php

namespace App\Http\Livewire\Loans;

use App\Models\Loan;
use Livewire\Component;
use App\Models\TransportOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Approved extends Component
{
    public $loans;
    public $authorize;
    public $comments;
    public $loan_id;


    public function mount(){
        $period = Auth::user()->employee->company->period;
        if (isset( $period)) {
            if ($period != "all") {
                $this->loans = Loan::where('authorization', 'approved')->whereYear('created_at',$period)->latest()->get();
            }else {
                $this->loans = Loan::where('authorization', 'approved')->latest()->get();
            }
        }
      }

      public function authorize($id){
        $loan = Loan::find($id);
        $this->loan_id = $loan->id;
        $this->dispatchBrowserEvent('show-authorizationModal');
      }

      public function update(){
        
        $loan = Loan::find($this->loan_id);
        $loan->authorized_by_id = Auth::user()->id;
        $loan->authorization = $this->authorize;
        $loan->reason = $this->comments;
        $loan->update();
        Session::flash('success','Authorization decision effected successfully');
        $this->dispatchBrowserEvent('hide-authorizationModal');
        if ($this->authorize == 'approved') {
            return redirect()->route('loans.approved');
        }else {
            return redirect()->route('loans.rejected');
        }
      }
    public function render()
    {
        return view('livewire.loans.approved');
    }
}
