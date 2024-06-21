<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ImmediateCause extends Model
{
    use HasFactory, SoftDeletes;

    public function incident(){
        return $this->belongsTo('App\Models\Incident');
    }
    public function loss(){
        return $this->belongsTo('App\Models\Loss');
    }
}
