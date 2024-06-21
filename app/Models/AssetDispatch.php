<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssetDispatch extends Model
{
    use HasFactory, SoftDeletes;

    public function asset(){
        return $this->belongsTo('App\Models\Asset');
    }
    public function branch(){
        return $this->belongsTo('App\Models\Branch');
    }
    public function asset_assignment(){
        return $this->belongsTo('App\Models\AssetAssignment');
    }
    public function asset_detail(){
        return $this->belongsTo('App\Models\AssetDetail');
    }

}
