<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Measurement extends Model
{
    use HasFactory, SoftDeletes;

    public function trips(){
        return $this->hasMany('App\Models\Trip');
    }
    public function trip_destinations(){
        return $this->hasMany('App\Models\TripDestination');
    }
    public function incidents(){
        return $this->hasMany('App\Models\Incident');
    }
}
