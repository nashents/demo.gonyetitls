<?php

namespace App\Http\Livewire\Payrolls;

use App\Models\Salary;
use App\Models\Payroll;
use Livewire\Component;
use App\Models\PayrollSalary;
use App\Models\PayrollSalaryDetail;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    public $payroll;
    public $payrolls;
    public $payroll_id;
    public $payroll_number;
    public $month;
    public $year;

    public function mount(){
        $this->payrolls = Payroll::latest()->get();
        $this->salaries = Salary::where('status',1)->get();
    }
    public function payrollNumber(){
       
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

            $payroll = Payroll::orderBy('id', 'desc')->first();

        if (!$payroll) {
            $payroll_number =  $initials .'L'. str_pad(1, 5, "0", STR_PAD_LEFT);
        }else {
            $number = $payroll->id + 1;
            $payroll_number =  $initials .'L'. str_pad($number, 5, "0", STR_PAD_LEFT);
        }

        return  $payroll_number;


    }
    private function resetInputFields(){
        $this->payroll_number = '';
        $this->month = '';
        $this->year = '';
    }

    public function store(){
        $payroll = new Payroll;
        $payroll->user_id = Auth::user()->id;
        $payroll->payroll_number = $this->payrollNumber();
        $payroll->month = $this->month;
        $payroll->year = $this->year;
        $payroll->save();
        
        if (isset($this->salaries)) {
            if ($this->salaries->count()>0) {
                foreach ($this->salaries as $salary) {
                    $payroll_salary = new PayrollSalary;
                    $payroll_salary->payroll_id = $payroll->id;
                    $payroll_salary->salary_id = $salary->id;
                    $payroll_salary->currency_id = $salary->currency_id;
                    $payroll_salary->salary_number = $salary->salary_number;
                    $payroll_salary->basic = $salary->basic;
                    $payroll_salary->gross = $salary->gross;
                    $payroll_salary->net = $salary->net;
                    $payroll_salary->employee_id = $salary->employee_id;
                    $payroll_salary->save();
                    foreach ($salary->salary_details as $salary_detail) {
                        $payroll_salary_detail = new PayrollSalaryDetail;
                        $payroll_salary_detail->payroll_salary_id = $payroll_salary->id;
                        $payroll_salary_detail->salary_item_id = $salary_detail->salary_item_id;
                        $payroll_salary_detail->loan_id = $salary_detail->loan_id;
                        $payroll_salary_detail->amount = $salary_detail->amount;
                        $payroll_salary_detail->save();
                    }
                }
            }
        }

        $this->dispatchBrowserEvent('hide-payrollModal');
        $this->resetInputFields();
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Payroll & Salaries Created Successfully!!"
        ]);


    }

    public function edit($id){
        $payroll = Payroll::find($id);
        $this->payroll_id = $id;
        $this->month = $payroll->month;
        $this->year = $payroll->year;
        $this->dispatchBrowserEvent('show-payrollEditModal');
    }

    public function update(){
        $payroll = Payroll::find($this->payroll_id);
        $payroll->month = $this->month;
        $payroll->year = $this->year;
        $payroll->update();
    
        $this->dispatchBrowserEvent('hide-payrollEditModal');
        $this->resetInputFields();
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Payroll Updated Successfully!!"
        ]);


    }


    public function render()
    {
        $this->payrolls = Payroll::latest()->get();
        return view('livewire.payrolls.index',[
            'payrolls' => $this->payrolls
        ]);
    }
}
