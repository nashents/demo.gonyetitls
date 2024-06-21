<?php

namespace App\Http\Livewire\Quotations;

use App\Models\Cargo;
use App\Models\Company;
use Livewire\Component;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\Quotation;
use App\Models\Destination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Index extends Component
{

    public $quotations;
    public $quotation_products;
    public $quotation;
    public $quotation_id;
    public $customers;

    public function mount(){
        $departments = Auth::user()->employee->departments;
        foreach($departments as $department){
            $department_names[] = $department->name;
        }
        $roles = Auth::user()->roles;
        foreach($roles as $role){
            $role_names[] = $role->name;
        }
        $ranks = Auth::user()->employee->ranks;
        foreach($ranks as $rank){
            $rank_names[] = $rank->name;
        }
        if (in_array('Admin', $role_names) || in_array('Super Admin', $role_names)) {
            $this->quotations = Quotation::latest()->get();
        } else {
            $this->quotations = Quotation::where('user_id',Auth::user()->id)->latest()->get();
        }

        $this->customers = Customer::all();

    }


    public function render()
    {
        return view('livewire.quotations.index');
    }
}
