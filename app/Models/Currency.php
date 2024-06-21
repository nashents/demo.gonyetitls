<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Currency extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'user_id',
        'name',
        'fullname',
        'symbol',
    ];
    public function rate_cards(){
        return $this->hasMany('App\Models\RateCard');
    }
    public function workshop_services(){
        return $this->hasMany('App\Models\WorkshopService');
    }
    public function expenses(){
        return $this->hasMany('App\Models\Expense');
    }
    public function incident_damages(){
        return $this->hasMany('App\Models\IncidentDamage');
    }
    public function incident_others(){
        return $this->hasMany('App\Models\IncidentOther');
    }
    public function requisitions(){
        return $this->hasMany('App\Models\Requisition');
    }
    public function ticket_expenses(){
        return $this->hasMany('App\Models\TicketExpense');
    }
    public function rates(){
        return $this->hasMany('App\Models\Rate');
    }
    public function ticket_inventories(){
        return $this->hasMany('App\Models\TicketInventory');
    }
    public function customers(){
        return $this->hasMany('App\Models\Customers');
    }
    public function vendors(){
        return $this->hasMany('App\Models\Vendors');
    }
    public function accounts(){
        return $this->hasMany('App\Models\Account');
    }
    public function loans(){
        return $this->hasMany('App\Models\Loan');
    }
    public function company(){
        return $this->hasOne('App\Models\Company');
    }
    public function credit_notes(){
        return $this->hasMany('App\Models\CreditNote');
    }
    public function bills(){
        return $this->hasMany('App\Models\Bill');
    }
    public function bill_expenses(){
        return $this->hasMany('App\Models\BillExpense');
    }
    public function recoveries(){
        return $this->hasMany('App\Models\Recovery');
    }
    public function bank_accounts(){
        return $this->hasMany('App\Models\BankAccount');
    }
    public function driver_allowances(){
        return $this->hasMany('App\Models\AllowanceDriver');
    }
    public function retreads(){
        return $this->hasMany('App\Models\Retread');
    }
    public function payments(){
        return $this->hasMany('App\Models\Payment');
    }
    public function assets(){
        return $this->hasMany('App\Models\Asset');
    }
    public function fuels(){
        return $this->hasMany('App\Models\Fuel');
    }
    public function inventories(){
        return $this->hasMany('App\Models\Inventory');
    }
    public function employees(){
        return $this->hasMany('App\Models\Employee');
    }
    public function purchases(){
        return $this->hasMany('App\Models\Purchase');
    }
    public function containers(){
        return $this->hasMany('App\Models\Container');
    }
    public function top_ups(){
        return $this->hasMany('App\Models\TopUp');
    }
    public function receipts(){
        return $this->hasMany('App\Models\Receipt');
    }

    public function trip_expenses(){
        return $this->hasMany('App\Models\TripExpense');
    }
    public function quotations(){
        return $this->hasMany('App\Models\Quotation');
    }
    public function invoices(){
        return $this->hasMany('App\Models\Invoice');
    }
    public function payroll_salaries(){
        return $this->hasMany('App\Models\PayrollSalary');
    }
    public function cashflows(){
        return $this->hasMany('App\Models\Cashflow');
    }
    public function tyres(){
        return $this->hasMany('App\Models\Tyre');
    }
    public function trips(){
        return $this->hasMany('App\Models\Trip');
    }
    public function trip_returns(){
        return $this->hasMany('App\Models\TripReturn');
    }
}
