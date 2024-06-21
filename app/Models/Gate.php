<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Gate extends Model
{
    use HasFactory, SoftDeletes;

    public function branch(){
        return $this->belongsTo('App\Models\Branch');
    }
    public function gate_passes(){
        return $this->hasMany('App\Models\GatePass');
    }
    
}
