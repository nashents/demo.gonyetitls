<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tyre extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    public function driver(){
        return $this->belongsTo('App\Models\Driver');
    }
    public function currency(){
        return $this->belongsTo('App\Models\Currency');
    }
    public function vehicle(){
        return $this->belongsTo('App\Models\Vehicle');
    }
    public function tyre(){
        return $this->belongsTo('App\Models\Tyre');
    }
    public function tyre_assignment(){
        return $this->hasOne('App\Models\TyreAssignment');
    }
    public function vendor(){
        return $this->belongsTo('App\Models\Vendor');
    }
    public function tyre_details(){
        return $this->hasMany('App\Models\TyreDetail');
    }
    public function product(){
        return $this->belongsTo('App\Models\Product');
    }
    public function user(){
        return $this->belongsTo('App\Models\User');
    }

}
