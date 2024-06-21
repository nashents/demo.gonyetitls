<?php

namespace App\Http\Livewire\Salaries;

use App\Models\Salary;
use Livewire\Component;

class Index extends Component
{
    public $salaries;
    public $salary_id;


    public function mount(){
        $this->salaries = Salary::latest()->get();
    } 

    public function render()
    {
        return view('livewire.salaries.index');
    }
}
