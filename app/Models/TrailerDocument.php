<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TrailerDocument extends Model
{
    use HasFactory, SoftDeletes;

    public function trailer(){
        return $this->belongsTo('App\Models\Trailer');
    }
}
