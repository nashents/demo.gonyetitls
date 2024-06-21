<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Corridor extends Model
{
    use HasFactory, SoftDeletes;

    public function transporters(){
        return $this->belongsToMany('App\Models\Transporter');
    }
}
