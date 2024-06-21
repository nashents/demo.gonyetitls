<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HorseModel extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    public function horse_make(){
        return $this->belongsTo('App\Models\HorseMake');
    }
    public function invetories(){
        return $this->hasMany('App\Models\Inventory');
    }
    public function horses(){
        return $this->hasMany('App\Models\Horse');
    }
    protected $fillable = [
        'vehicle_make_id',
        'name',
        'user_id',
    ];
}
