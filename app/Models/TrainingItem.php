<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TrainingItem extends Model
{
    use HasFactory, SoftDeletes;

    public function training_plan(){
        return $this->hasOne('App\Models\TrainingPlan');
    }
    public function training_requirement(){
        return $this->hasOne('App\Models\TrainingRequirement');
    }
    public function trainings(){
        return $this->hasMany('App\Models\Training');
    }
}
