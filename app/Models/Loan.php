<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Loan extends Model
{
    use HasFactory, SoftDeletes;

    public function user(){
        return $this->belongsTo('App\Models\User');
    }
    public function employee(){
        return $this->belongsTo('App\Models\Employee');
    }
    public function currency(){
        return $this->belongsTo('App\Models\Currency');
    }
    public function loan_type(){
        return $this->belongsTo('App\Models\LoanType');
    }
    public function payroll_salary(){
        return $this->hasMany('App\Models\PayrollSalary');
    }
    public function salary_details(){
        return $this->hasMany('App\Models\SalaryDetail');
    }
    public function payroll_salary_details(){
        return $this->hasOne('App\Models\PayrollSalaryDetail');
    }
}
