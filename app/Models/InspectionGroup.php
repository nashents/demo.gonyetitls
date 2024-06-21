<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InspectionGroup extends Model
{
    use HasFactory, SoftDeletes;

    public function service_type(){
        return $this->belongsTo('App\Models\ServiceType');
    }
    public function inspection_services(){
        return $this->hasMany('App\Models\InspectionService');
    }
    
    public function inspection_types(){
        return $this->hasMany('App\Models\InspectionType');
    }
}
