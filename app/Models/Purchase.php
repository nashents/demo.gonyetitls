<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Purchase extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    public function purchase_products(){
        return $this->hasMany('App\Models\PurchaseProduct');
    }
    public function assets(){
        return $this->hasMany('App\Models\Asset');
    }
    public function purchase_documents(){
        return $this->hasMany('App\Models\PurchaseDocument');
    }
    public function bills(){
        return $this->hasMany('App\Models\Bill');
    }
    public function expense(){
        return $this->belongsTo('App\Models\Expense');
    }
    public function booking(){
        return $this->belongsTo('App\Models\Booking');
    }
    public function account(){
        return $this->belongsTo('App\Models\Account');
    }
    public function currency(){
        return $this->belongsTo('App\Models\Currency');
    }
    public function category(){
        return $this->belongsTo('App\Models\Category');
    }
    public function vendor(){
        return $this->belongsTo('App\Models\Vendor');
    }
}
