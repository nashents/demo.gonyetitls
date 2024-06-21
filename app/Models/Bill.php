<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bill extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory, SoftDeletes;

    public function bill_expenses(){
        return $this->hasMany('App\Models\BillExpense');
    }
    public function fuel(){
        return $this->belongsTo('App\Models\Fuel');
    }
    public function workshop_service(){
        return $this->belongsTo('App\Models\WorkshopService');
    }
    public function ticket_expense(){
        return $this->BelongsTo('App\Models\TicketExpense');
    }
    public function ticket(){
        return $this->BelongsTo('App\Models\Ticket');
    }
    
    public function top_up(){
        return $this->belongsTo('App\Models\TopUp');
    }
    public function container(){
        return $this->belongsTo('App\Models\Container');
    }
    public function trip(){
        return $this->belongsTo('App\Models\Trip');
    }
    public function purchase(){
        return $this->belongsTo('App\Models\Purchase');
    }
    public function trip_expense(){
        return $this->belongsTo('App\Models\TripExpense');
    }
    public function expense(){
        return $this->belongsTo('App\Models\Expense');
    }
    public function horse(){
        return $this->belongsTo('App\Models\Horse');
    }
    public function driver(){
        return $this->belongsTo('App\Models\Driver');
    }
    public function invoice(){
        return $this->belongsTo('App\Models\Invoice');
    }
    public function payments(){
        return $this->hasMany('App\Models\Payment');
    }
    public function vendor(){
        return $this->belongsTo('App\Models\Vendor');
    }
    public function currency(){
        return $this->belongsTo('App\Models\Currency');
    }
    public function transporter(){
        return $this->belongsTo('App\Models\Transporter');
    }
}
