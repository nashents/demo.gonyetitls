<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Group extends Model
{
    use HasFactory, SoftDeletes;

    public function visitors(){
        return $this->hasMany('App\Models\Visitor');
    }
    
    public function gate_passes(){
        return $this->hasMany('App\Models\GatePass');
    }
}
