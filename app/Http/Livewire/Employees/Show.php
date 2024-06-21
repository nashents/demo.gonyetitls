<?php

namespace App\Http\Livewire\Employees;

use Livewire\Component;
use App\Models\Employee;
use App\Models\Department;

class Show extends Component
{

    public $employee_id;
    public $employee;
    public $driver;
    public $all_departments;
    public $employee_departments;
    public $department_id;
    public $trips;
    public $cashflows;
    public $use_email_as_username;


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

    public function mount($id){
        $this->all_departments = Department::orderBy('name','asc')->get();
        $this->employee = Employee::find($id);
        $this->employee_id = $id;
        $this->use_email_as_username =  $this->employee->user->use_email_as_username;
        $this->driver = $this->employee->driver;
        if(isset($this->driver)){
            $this->trips = $this->driver->trips;
            $this->cashflows = $this->driver->cash_flows;
        }

        $this->employee_id = $this->employee->id;
     
    }

    public function setUsername(){
        if ($this->use_email_as_username == TRUE) {
            $user = $this->employee->user;
            $user->username = $this->employee->email;
            $user->email = $this->employee->email;
            $user->update();
            $this->dispatchBrowserEvent('alert',[
                'type'=>'success',
                'message'=>"Email set as username successfully!!"
            ]);
        }elseif ($this->use_email_as_username == FALSE) {
            $user = $this->employee->user;
            $user->username = $this->employee->phonenumber;
            $user->phonenumber = $this->employee->phonenumber;
            $user->update();
            $this->dispatchBrowserEvent('alert',[
                'type'=>'success',
                'message'=>"Phonenumber set as username successfully!!"
            ]);
        }
    }



 


    public function showRemove($id){
        $this->department_id = $id;
        $this->department = Department::find($id);
        $this->dispatchBrowserEvent('show-removeDepartmentModal');
    }
    public function removeDepartment(){
        $this->employee->departments()->detach($this->department_id);
        $this->dispatchBrowserEvent('hide-removeDepartmentModal');
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Department Removed Successfully!!"
        ]);
    }
    public function addDepartments(){
        $this->employee->departments()->attach($this->department_id);
        $this->dispatchBrowserEvent('hide-departmentModal');
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Department(s) Added Successfully!!"
        ]);
    }




    public function render()
    {
        $this->all_departments = Department::orderBy('name','asc')->get();
        $this->employee = Employee::find($this->employee_id);
        $this->employee_departments = $this->employee->departments;
        return view('livewire.employees.show',[
            'all_departments' => $this->all_departments,
            'employee_departments' =>  $this->employee->departments
        ]);
    }
}
