<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inventory extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    public function store(){
        return $this->belongsTo('App\Models\Store');
    }
    public function horse_model(){
        return $this->belongsTo('App\Models\HorseModel');
    }
    public function horse_make(){
        return $this->belongsTo('App\Models\HorseMake');
    }
    public function vehicle_make(){
        return $this->belongsTo('App\Models\VehicleMake');
    }
    public function vehicle_model(){
        return $this->belongsTo('App\Models\VehicleModel');
    }
    public function inventory_documents(){
        return $this->hasMany('App\Models\InventoryDocument');
    }

    public function inventory_details(){
        return $this->hasMany('App\Models\InventoryDetail');
    }

    public function product(){
        return $this->belongsTo('App\Models\Product');
    }
    public function vendor(){
        return $this->belongsTo('App\Models\Vendor');
    }
    public function currency(){
        return $this->belongsTo('App\Models\Currency');
    }

    public function inventory_assignments(){
        return $this->hasMany('App\Models\InventoryAssignment');
    }
    public function ticket_inventories(){
        return $this->hasMany('App\Models\TicketInventory');
    }
    public function inventory_requisitions(){
        return $this->hasMany('App\Models\InventoryRequisition');
    }
}
