<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TicketInventory extends Model
{
    use HasFactory, SoftDeletes;

    public function inventory(){
        return $this->belongsTo('App\Models\Inventory');
    }
    public function inventory_requisition(){
        return $this->hasOne('App\Models\InventoryRequisition');
    }
    public function inventory_dispatch(){
        return $this->hasOne('App\Models\InventoryDispatch');
    }
    public function ticket(){
        return $this->belongsTo('App\Models\Ticket');
    }

    public function currency(){
        return $this->belongsTo('App\Models\Currency');
    }
}
