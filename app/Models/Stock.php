<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stock extends Model
{
    use HasFactory, SoftDeletes;

    public function user(){
        return $this->belongsTo('App\Models\User');
    }
    public function opening_stocks(){
        return $this->hasMany('App\Models\OpeningStock');
    }
    public function closing_stocks(){
        return $this->hasMany('App\Models\ClosingStock');
    }
}
