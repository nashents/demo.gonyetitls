<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TruckStop extends Model
{
    use HasFactory, SoftDeletes;

    public function trips(){
        return $this->belongsToMany('App\Models\Trip');
    }
    public function route(){
        return $this->belongsTo('App\Models\Route');
    }

    protected $fillable=[
        'route_id',
        'name',
        'status',
    ];
}
