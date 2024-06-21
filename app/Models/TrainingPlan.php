<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TrainingPlan extends Model
{
    use HasFactory, SoftDeletes;

    public function training_item(){
        return $this->belongsTo('App\Models\TrainingItem');
    }
}
