<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransporterCargo extends Model
{
    use HasFactory, SoftDeletes;

    public function transporter(){
        return $this->belongsTo('App\Models\Transporter');
    }

    public function cargo(){
        return $this->belongsTo('App\Models\Cargo');
    }
}
