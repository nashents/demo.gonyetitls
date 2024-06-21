<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contract extends Model
{
    use HasFactory, SoftDeletes;

    public function customer(){
        return $this->belongsTo('App\Models\Customer');
    }
    public function vendor(){
        return $this->belongsTo('App\Models\Vendor');
    }
}
