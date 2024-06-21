<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Requisition extends Model
{
    use HasFactory, SoftDeletes;

    public function user(){
        return $this->belongsTo('App\Models\User');
    }
    public function trip(){
        return $this->belongsTo('App\Models\Trip');
    }
    public function currency(){
        return $this->belongsTo('App\Models\Currency');
    }
    public function requisition_items(){
        return $this->hasMany('App\Models\RequisitionItem');
    }
    public function payments(){
        return $this->hasMany('App\Models\Payment');
    }
    public function department(){
        return $this->belongsTo('App\Models\Department');
    }
    public function employee(){
        return $this->belongsTo('App\Models\Employee');
    }
    public function expense(){
        return $this->belongsTo('App\Models\Expense');
    }
    public function account(){
        return $this->belongsTo('App\Models\Account');
    }
}
