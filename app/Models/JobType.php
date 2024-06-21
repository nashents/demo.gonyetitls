<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JobType extends Model
{
    use HasFactory, SoftDeletes;

    public function tickets(){
        return $this->hasMany('App\Models\Ticket');
    }
}
