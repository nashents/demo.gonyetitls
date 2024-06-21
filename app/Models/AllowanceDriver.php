<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AllowanceDriver extends Model
{
    use HasFactory, SoftDeletes;

    public function allowance(){
        return $this->belongsTo('App\Models\Allowance');
    }
    public function driver(){
        return $this->belongsTo('App\Models\Driver');
    }
    public function currency(){
        return $this->belongsTo('App\Models\Currency');
    }
    public function trip(){
        return $this->belongsTo('App\Models\Trip');
    }
}
