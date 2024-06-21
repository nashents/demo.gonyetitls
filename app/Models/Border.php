<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Border extends Model
{
    use HasFactory, SoftDeletes;

    public function clearing_agents(){
        return $this->belongsToMany('App\Models\ClearingAgent');
    }
    public function routes(){
        return $this->belongsToMany('App\Models\Route');
    }
    
    public function trips(){
        return $this->belongsToMany('App\Models\Trip');
    }
 
    protected $fillable=[
        'user_id',
        'name',
        'country_a',
        'country_b',
    ];
}
