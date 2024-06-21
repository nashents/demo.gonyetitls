<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalaryItem extends Model
{
    use HasFactory, SoftDeletes;

  
    public function user(){
        return $this->belongsTo('App\Models\User');
    }
    public function payroll_salary_details(){
        return $this->hasOne('App\Models\PayrollSalaryDetail');
    }
    public function salary_details(){
        return $this->hasOne('App\Models\SalaryDetail');
    }

    protected $fillable = [
        'user_id',
        'name',
        'percentage',
        'type',
        'amount',
        'description',
    ];
   
}
