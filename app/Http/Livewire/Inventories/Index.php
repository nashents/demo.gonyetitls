<?php

namespace App\Http\Livewire\Inventories;

use App\Models\Inventory;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{

    public $inventories;
    public $inventory_id;

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
            $this->inventories = Inventory::where('status',1)->latest()->get();
        } else {
            $this->inventories = Inventory::where('user_id',Auth::user()->id)->where('status',1)->get();
        }
    }

    public function render()
    {
        return view('livewire.inventories.index',[
            'inventories' => $this->inventories
        ]);
    }
}
