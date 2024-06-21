<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Visitor extends Model
{
    use HasFactory, SoftDeletes;

    public function gate_passes(){
        return $this->hasMany('App\Models\GatePass');
    }
    public function group(){
        return $this->belongsTo('App\Models\Group');
    }
}
