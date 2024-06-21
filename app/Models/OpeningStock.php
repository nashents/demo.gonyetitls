<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpeningStock extends Model
{
    use HasFactory, SoftDeletes;

    public function stock(){
        return $this->belongsTo('App\Models\Stock');
    }
    public function inventory(){
        return $this->belongsTo('App\Models\Inventory');
    }
    public function asset(){
        return $this->belongsTo('App\Models\Asset');
    }
    public function closing_stocks(){
        return $this->hasMany('App\Models\ClosingStock');
    }
}
