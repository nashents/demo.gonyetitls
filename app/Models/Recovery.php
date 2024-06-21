<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Recovery extends Model
{
    use HasFactory, SoftDeletes;

    public function deduction(){
        return $this->belongsTo('App\Models\Deduction');
    }
    public function currency(){
        return $this->belongsTo('App\Models\Currency');
    }
    public function trip(){
        return $this->belongsTo('App\Models\Trip');
    }
    public function receipts(){
        return $this->hasMany('App\Models\Recovery');
    }
    public function driver(){
        return $this->belongsTo('App\Models\Driver');
    }
    public function documents(){
        return $this->hasMany('App\Models\Document');
    }
    public function payments(){
        return $this->hasMany('App\Models\Payment');
    }
    public function destination(){
        return $this->belongsTo('App\Models\Destination');
    }
    public function user(){
        return $this->belongsTo('App\Models\User');
    }
}
