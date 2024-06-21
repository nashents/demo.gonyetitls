<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LossGroup extends Model
{
    use HasFactory, SoftDeletes;

    public function user(){
        return $this->belongsTo('App\Models\User');
    }
    public function loss_category(){
        return $this->belongsTo('App\Models\LossCategory');
    }
    public function losses(){
        return $this->hasMany('App\Models\Loss');
    }
}
