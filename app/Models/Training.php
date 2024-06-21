<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Training extends Model
{
    use HasFactory, SoftDeletes;

    public function employee(){
        return $this->belongsTo('App\Models\Employee');
    }

    public function driver(){
        return $this->belongsTo('App\Models\Driver');
    }
  
    public function training_item(){
        return $this->belongsTo('App\Models\TrainingItem');
    }

}
