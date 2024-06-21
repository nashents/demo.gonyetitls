<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Denomination extends Model
{
    use HasFactory, SoftDeletes;

    public function payment(){
        return $this->belongsTo('App\Models\Payment');
    }
}
