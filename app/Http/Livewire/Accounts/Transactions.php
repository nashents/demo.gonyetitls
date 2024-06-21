<?php

namespace App\Http\Livewire\Accounts;
use Livewire\Component;
use App\Models\CashFlow;

class Transactions extends Component
{
    public $cashflows;

    public function mount($id){
        $this->cashflows = CashFlow::where('account_id',$id)->latest()->get();
    }

    public function render()
    {
        return view('livewire.accounts.transactions');
    }
}
