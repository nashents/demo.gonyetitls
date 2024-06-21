<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TyreDispatch extends Model
{
    use HasFactory, SoftDeletes;

    public function tyre_assignment(){
        return $this->belongsTo('App\Models\TyreAssignment');
    }
    public function tyre_detail(){
        return $this->belongsTo('App\Models\TyreDetail');
    }
    public function horse(){
        return $this->belongsTo('App\Models\Horse');
    }
    public function vehicle(){
        return $this->belongsTo('App\Models\Vehicle');
    }
    public function trailer(){
        return $this->belongsTo('App\Models\Trailer');
    }
}
