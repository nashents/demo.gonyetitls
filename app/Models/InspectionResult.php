<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InspectionResult extends Model
{
    use HasFactory, SoftDeletes;

    public function inspection(){
        return $this->belongsTo('App\Models\Inspection');
    }
    public function inspection_type(){
        return $this->belongsTo('App\Models\InspectionType');
    }
}
