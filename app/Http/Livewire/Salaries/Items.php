<?php

namespace App\Http\Livewire\Salaries;

use App\Models\Cargo;
use App\Models\Salary;
use Livewire\Component;
use App\Models\SalaryItem;
use App\Models\Destination;
use App\Models\LoadingPoint;
use App\Models\SalaryDetail;
use App\Models\OffloadingPoint;


class Items extends Component
{
    public $salary;
    public $salary_id;
    public $salary_items;
    public $salary_item_id;
    public $salary_details;
    public $salary_detail_id;
    public $currencies;
    public $currency_id;
    public $gross;
    public $net;
    public $basic;
    public $employees;
    public $employee_id;
    public $user_id;
    public $total_earnings;
    public $total_deductions;
    public $deductions;
    public $deduction_amount;
    public $deduction_id;
    public $earnings;
    public $earning_amount;
    public $earning_id;
    public $inputs = [];
    public $i = 1;
    public $n = 1;
    
    public function add($i)
    {
        $i = $i + 1;
        $this->i = $i;
        array_push($this->inputs ,$i);
    }
    
    public function remove($i)
    {
        unset($this->inputs[$i]);
    }

    public $deductions_inputs = [];
    public $l = 1;
    public $m = 1;
    
    public function deductionsAdd($l)
    {
        $l = $l + 1;
        $this->l = $l;
        array_push($this->deductions_inputs ,$l);
    }
    
    public function deductionsRemove($l)
    {
        unset($this->deductions_inputs[$l]);
    }
    
private function resetInputFields(){
    $this->from = "" ;
}

    public function mount($id){
        $this->salary_id = $id;
        $this->salary = Salary::find($id);
        $this->salary_details = $this->salary->salary_details;
        $this->earnings = SalaryItem::where('type','Earnings')->get();
        $this->deductions = SalaryItem::where('type','Deductions')->get();
        $this->salary_items = SalaryItem::latest()->get();
    }

public function store(){

    foreach ($this->salary_item_id as $key => $value) {
        $salary_detail = new SalaryDetail;
        $salary_detail->salary_id = $this->salary_id;
        if (isset($this->salary_item_id[$key])) {
          $salary_detail->salary_item_id = $this->salary_item_id[$key];
          $salary_detail->amount = $this->amount[$key];
          $salary_detail->save();
        }
    }

    $this->dispatchBrowserEvent('hide-salary_itemModal');
    $this->resetInputFields();
    $this->dispatchBrowserEvent('alert',[
        'type'=>'success',
        'message'=>"Salary Item(s) Added Successfully!!"
    ]);
   
}



    public function removeShow($salary_detail_id){
        $this->salary_detail = SalaryDetail::find($salary_detail_id);
        $this->vat = $this->salary->vat;
        $this->total = $this->salary->total;
        $this->subtotal = $this->salary->subtotal;
        $this->salary_details = $this->salary->salary_details;
        $this->dispatchBrowserEvent('show-removeModal');
    }

    public function removesalarydetail(){ 

        $salary = $this->salary_detail->salary;
        $this->deleted_detail_subtotal = $this->subtotal - $this->salary_detail->freight;

        if ($this->vat != "") {
            $this->total = $this->deleted_detail_subtotal + ( $this->deleted_detail_subtotal * ($this->vat/100));
            $salary =  Salary::find($this->salary->id);
            $salary->total = $this->total;
            $salary->subtotal = $this->deleted_detail_subtotal;
            $salary->vat = $this->vat;
            $salary->update();
        }else{
            $this->total = $this->deleted_detail_subtotal;
            $salary =  Salary::find($this->salary->id);
            $salary->total = $this->total;
            $salary->subtotal = $this->deleted_detail_subtotal;
            $salary->vat = $this->vat;
            $salary->update();
        }
        $this->salary_detail->delete();
        $this->dispatchBrowserEvent('hide-removeModal');
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"salary detail Deleted Successfully!!"
        ]);

   

    }

    public function edit($id){
     
        $this->salary_detail = SalaryDetail::find($id);
        $this->salary_detail_id = $id;
        $this->freight = $this->salary_detail->freight;
        $this->dispatchBrowserEvent('show-editsalary_detailModal');
    }

    public function update(){

      
        $salary_detail = SalaryDetail::find($this->salary_detail_id);
        $salary_detail->from = $this->from;
        $salary_detail->to = $this->to;
        $salary_detail->loading_point_id = $this->loading_point_id;
        $salary_detail->offloading_point_id = $this->offloading_point_id;
        $salary_detail->cargo_id = $this->cargo_id;
        $salary_detail->weight = $this->weight;
        $salary_detail->rate = $this->rate;
        $salary_detail->freight = $this->freight;
        $salary_detail->update();

        $salary = $salary_detail->salary;
        $this->edited_detail_subtotal = $salary->salary_details->sum('freight');

        $this->dispatchBrowserEvent('hide-editsalary_detailModal');
        $this->resetInputFields();
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Salary Detail Updated Successfully!!"
        ]);
    }

    public function render()
    {
        $this->salary_details = SalaryDetail::where('salary_id',$this->salary_id)->get();
        return view('livewire.salaries.items',[
            'salary_details' => $this->salary_details
        ]);
    }
}
