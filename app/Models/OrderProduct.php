<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderProduct extends Model
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    public function order(){
        return $this->belongsTo('App\Models\Order');
    }
    public function product(){
        return $this->belongsTo('App\Models\Product');
    }
}
