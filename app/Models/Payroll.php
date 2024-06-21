<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payroll extends Model
{
    use HasFactory, SoftDeletes;

    public function payroll_salaries(){
        return $this->hasMany('App\Models\PayrollSalary');
    }
    public function user(){
        return $this->belongsTo('App\Models\User');
    }
}
