<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Consignee extends Model
{
    use HasFactory, SoftDeletes;
    
    public function user(){
        return $this->belongsTo('App\Models\User');
    }
    public function trips(){
        return $this->hasMany('App\Models\Trip');
    }
    public function offloading_points(){
        return $this->hasMany('App\Models\OffloadingPoint');
    }
    public function contacts(){
        return $this->hasMany('App\Models\Contact');
    }
}
