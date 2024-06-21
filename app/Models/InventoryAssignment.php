<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InventoryAssignment extends Model
{
    use HasFactory, SoftDeletes;

    public function inventory(){
        return $this->belongsTo('App\Models\Inventory');
    }
    public function employee(){
        return $this->belongsTo('App\Models\Employee');
    }
    public function vehicle(){
        return $this->belongsTo('App\Models\Vehicle');
    }
    public function horse(){
        return $this->belongsTo('App\Models\Horse');
    }
    public function trailer(){
        return $this->belongsTo('App\Models\Trailer');
    }
}
