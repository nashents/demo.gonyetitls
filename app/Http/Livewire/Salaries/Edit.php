<?php

namespace App\Http\Livewire\Salaries;

use App\Models\Loan;
use App\Models\Salary;
use Livewire\Component;
use App\Models\Currency;
use App\Models\Employee;
use App\Models\SalaryItem;
use App\Models\SalaryDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Edit extends Component
{

    public $salary_number;
    public $employees;
    public $currencies;
    public $currency_id;
    public $loans;
    public $loan_id;
    public $loan_amount;
    public $deductions;
    public $deduction_amount;
    public $deduction_id;
    public $earnings;
    public $earning_amount;
    public $earning_id;
    public $employee_id;
    public $salary_items;
    public $salary_item_id;
    public $net;
    public $total_earnings;
    public $total_deductions;
    public $payment_per_month;
    public $gross;
    public $basic;
    public $salary;
    public $salary_id;

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
    
    public $loans_inputs = [];
    public $j = 1;
    public $k = 1;
    
    public function loansAdd($j)
    {
        $j = $j + 1;
        $this->j = $j;
        array_push($this->loans_inputs ,$j);
    }
    
    public function loansRemove($j)
    {
        unset($this->loans_inputs[$j]);
    }
    
    public function salaryNumber(){

        if (isset(Auth::user()->company)) {
            $str = Auth::user()->company->name;
            $words = explode(' ', $str);
            if (isset($words[1][0])) {
                $initials = $words[0][0].$words[1][0];
            }else {
                $initials = $words[0][0];
            }
        }elseif (isset(Auth::user()->employee->company)) {
            $str = Auth::user()->employee->company->name;
            $words = explode(' ', $str);
            if (isset($words[1][0])) {
                $initials = $words[0][0].$words[1][0];
            }else {
                $initials = $words[0][0];
            }
        }
 
        $salary = Salary::orderBy('id','desc')->first();

        if (!$salary) {
            $salary_number =  $initials .'S'. str_pad(1, 5, "0", STR_PAD_LEFT);
        }else {
            $number = $salary->id + 1;
            $salary_number =  $initials .'S'. str_pad($number, 5, "0", STR_PAD_LEFT);
        }

        return  $salary_number;

    }

    public function mount($id){
        $salary = Salary::find($id);
        $this->salary_id = $salary->id;
        $this->basic = $salary->basic;
        $this->net = $salary->net;
        $this->gross = $salary->gross;
        $this->salary_number = $salary->salary_number;
        $this->currency_id = $salary->currency_id;
        $this->employee_id = $salary->employee_id;

        $this->employees = Employee::orderBy('name','asc')->get();
        $this->currencies = Currency::orderBy('name','asc')->get();
        $this->earnings = SalaryItem::where('type','Earnings')->get();
        $this->loans = Loan::where('balance','>','0')->get();
        $this->deductions = SalaryItem::where('type','Deductions')->get();
        $this->salary_number = $this->salaryNumber();
    }

    public function update(){
        $salary = Salary::find($this->salary_id);
        $salary->user_id = Auth::user()->id;
        $salary->salary_number = $this->salary_number;
        $salary->employee_id = $this->employee_id;
        $salary->currency_id = $this->currency_id;
        $salary->basic = $this->basic;
        $salary->update();

        Session::flash('success','Salary Updated Successfully!!');
        return redirect()->route('salaries.index');
        

    }
    public function render()
    {
        return view('livewire.salaries.edit');
    }
}
