<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssetDetail extends Model
{
    use HasFactory, SoftDeletes;

    public function asset(){
        return $this->belongsTo('App\Models\Asset');
    }
    public function asset_dispatch(){
        return $this->belongsTo('App\Models\AssetDispatch');
    }


}
