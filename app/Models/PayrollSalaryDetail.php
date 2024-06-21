<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PayrollSalaryDetail extends Model
{
    use HasFactory, SoftDeletes;

    public function payroll_salary(){
        return $this->belongsTo('App\Models\PayrollSalary');
    }
    public function salary_item(){
        return $this->belongsTo('App\Models\SalaryItem');
    }
    public function loan(){
        return $this->belongsTo('App\Models\Loan');
    }
}
