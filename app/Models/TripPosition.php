<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TripPosition extends Model
{
    use HasFactory, SoftDeletes;

    public function trip(){
        return $this->belongsTo('App\Models\Trip');
    }
    public function user(){
        return $this->belongsTo('App\Models\User');
    }
}
