<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LossCategory extends Model
{
    use HasFactory, SoftDeletes;

    public function user(){
        return $this->belongsTo('App\Models\User');
    }
    public function loss_groups(){
        return $this->hasMany('App\Models\LossGroup');
    }
    public function losses(){
        return $this->hasMany('App\Models\Loss');
    }
}
