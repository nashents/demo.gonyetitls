<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Asset extends Model implements Auditable
{
    use HasFactory, SoftDeletes;

    use \OwenIt\Auditing\Auditable;

    public function product(){
        return $this->belongsTo('App\Models\Product');
    }
    public function category(){
        return $this->belongsTo('App\Models\Category');
    }
    public function purchase(){
        return $this->belongsTo('App\Models\Purchase');
    }
    public function category_value(){
        return $this->belongsTo('App\Models\CategoryValue');
    }
    public function currency(){
        return $this->belongsTo('App\Models\Currency');
    }
    public function asset_details(){
        return $this->hasMany('App\Models\AssetDetail');
    }
    public function asset_dispatches(){
        return $this->hasMany('App\Models\AssetDispatch');
    }
    public function asset_assignments(){
        return $this->hasMany('App\Models\AssetAssignment');
    }
    public function fuels(){
        return $this->hasMany('App\Models\Fuel');
    }
    public function vendor(){
        return $this->belongsTo('App\Models\Vendor');
    }

    public function asset_documents(){
        return $this->hasMany('App\Models\AssetDocument');
    }
    public function opening_stock(){
        return $this->hasOne('App\Models\OpeningStock');
    }
    public function closing_stock(){
        return $this->hasOne('App\Models\ClosingStock');
    }

}
