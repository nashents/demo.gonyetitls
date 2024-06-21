<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PayrollSalary extends Model
{
    use HasFactory, SoftDeletes;

    public function payroll(){
        return $this->belongsTo('App\Models\Payroll');
    }
    public function payroll_salary_details(){
        return $this->hasMany('App\Models\PayrollSalaryDetail');
    }
    public function salary(){
        return $this->belongsTo('App\Models\Salary');
    }
    public function salary_item(){
        return $this->belongsTo('App\Models\SalaryItem');
    }
    public function loan(){
        return $this->belongsTo('App\Models\Loan');
    }
    public function employee(){
        return $this->belongsTo('App\Models\Employee');
    }
    public function currency(){
        return $this->belongsTo('App\Models\Currency');
    }
}
