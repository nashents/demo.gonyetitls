<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Trailer extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    public function trailer_documents(){
        return $this->hasMany('App\Models\TrailerDocument');
    }
    public function incidents(){
        return $this->hasMany('App\Models\Incident');
    }
    public function workshop_services(){
        return $this->hasMany('App\Models\WorkshopService');
    }
    public function brokers(){
        return $this->belongsToMany('App\Models\Broker');
    }
    public function trailer_assignments(){
        return $this->hasMany('App\Models\TrailerAssignment');
    }
    public function breakdowns(){
        return $this->belongsToMany('App\Models\Breakdown');
    }
    public function checklists(){
        return $this->hasMany('App\Models\Checklist');
    }
    public function gate_passes(){
        return $this->belongsToMany('App\Models\GatePass');
    }
    public function breakdown_assignments(){
        return $this->belongsToMany('App\Models\BreakdownAssignment');
    }
   
    public function inventory_assignments(){
        return $this->hasMany('App\Models\InventoryAssignment');
    }
    public function inventory_requisitions(){
        return $this->hasMany('App\Models\InventoryRequisition');
    }
    public function inventory_dispatches(){
        return $this->hasMany('App\Models\InventoryDispatch');
    }
    public function ticket_inventories(){
        return $this->hasMany('App\Models\TicketInventory');
    }
    public function tyre_assignments(){
        return $this->hasMany('App\Models\TyreAssignment');
    }
    public function tyre_dispatches(){
        return $this->hasMany('App\Models\TyreDispatch');
    }
    public function user(){
        return $this->belongsTo('App\Models\User');
    }
    public function bookings(){
        return $this->hasMany('App\Models\Booking');
    }
    public function inspections(){
        return $this->hasMany('App\Models\Inspection');
    }

    public function trailer_images(){
        return $this->hasMany('App\Models\TrailerImage');
    }
    public function trailer_type(){
        return $this->belongsTo('App\Models\TrailerType');
    }
    public function trailer_transporters(){
        return $this->belongsToMany('App\Models\Transporter');
    }
    public function transporter(){
        return $this->belongsTo('App\Models\Transporter');
    }
    public function fitnesses(){
        return $this->hasMany('App\Models\Fitness');
    }
    public function trips(){
        return $this->belongsToMany('App\Models\Trip');
    }
    public function trip_returns(){
        return $this->hasMany('App\Models\TripReturn');
    }
    public function trailer_fitnesses(){
        return $this->hasMany('App\Models\TrailerFitness');
    }
    public function assignment(){
        return $this->hasMany('App\Models\Assignment');
    }
    public function tickets(){
        return $this->hasMany('App\Models\Ticket');
    }
    public function services(){
        return $this->hasMany('App\Models\Service');
    }
    public function cash_flows(){
        return $this->hasMany('App\Models\CashFlow');
    }

    protected $fillable = [
        'user_id',
        'trailer_type_id',
        'transporter_id',
        'registration_number',
        'trailer_number',
        'fleet_number',
        'make',
        'model',
        'color',
        'year',
        'condition',
        'manufacturer',
        'country_of_origin',
        'no_of_wheels',
        'status',
    ];
}
