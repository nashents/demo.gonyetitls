<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TripGroup extends Model
{
    use HasFactory, SoftDeletes;

    public function trips(){
        return $this->hasMany('App\Models\Trip');
    }
    public function customer(){
        return $this->belongsTo('App\Models\Customer');
    }
}
