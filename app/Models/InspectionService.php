<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InspectionService extends Model
{
    use HasFactory, SoftDeletes;

    public function inspection_type(){
        return $this->belongsTo('App\Models\InspectionType');
    }
    
    public function inspection_group(){
        return $this->belongsTo('App\Models\InspectionGroup');
    }
    public function service_type(){
        return $this->belongsTo('App\Models\ServiceType');
    }
}
