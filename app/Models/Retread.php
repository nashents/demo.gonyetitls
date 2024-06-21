<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Retread extends Model
{
    use HasFactory, SoftDeletes;

    public function tyre(){
        return $this->belongsTo('App\Models\Tyre');
    }
    public function vendor(){
        return $this->belongsTo('App\Models\Vendor');
    }
    public function currency(){
        return $this->belongsTo('App\Models\Currency');
    }
    public function retread_tyre(){
        return $this->hasMany('App\Models\RetreadTyre');
    }
    public function retread_document(){
        return $this->hasMany('App\Models\RetreadDocument');
    }
}
