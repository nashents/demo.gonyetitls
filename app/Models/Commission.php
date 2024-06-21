<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Commission extends Model
{
    use HasFactory, SoftDeletes;

    public function trip(){
        return $this->belongsTo('App\Models\Trip');
    }
    public function agent(){
        return $this->belongsTo('App\Models\Agent');
    }
}
