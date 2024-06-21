<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    public function trip(){
        return $this->belongsTo('App\Models\Trip');
    }
    public function account(){
        return $this->belongsTo('App\Models\Account');
    }
    public function driver(){
        return $this->belongsTo('App\Models\Driver');
    }
    public function container(){
        return $this->belongsTo('App\Models\Container');
    }
    public function requisition(){
        return $this->belongsTo('App\Models\Requisition');
    }
    public function top_up(){
        return $this->belongsTo('App\Models\TopUp');
    }
    public function invoice(){
        return $this->belongsTo('App\Models\Invoice');
    }
    public function invoice_payment(){
        return $this->hasOne('App\Models\InvoicePayment');
    }
    public function bill(){
        return $this->belongsTo('App\Models\Bill');
    }
    public function cash_flow(){
        return $this->hasOne('App\Models\CashFlow');
    }
    public function recovery(){
        return $this->belongsTo('App\Models\Recovery');
    }
    public function vendor(){
        return $this->belongsTo('App\Models\Vendor');
    }
    public function transporter(){
        return $this->belongsTo('App\Models\Transporter');
    }
  
    public function customer(){
        return $this->belongsTo('App\Models\Customer');
    }
    public function currency(){
        return $this->belongsTo('App\Models\Currency');
    }
    public function user(){
        return $this->belongsTo('App\Models\User');
    }
    public function denominations(){
        return $this->hasMany('App\Models\Denomination');
    }
    public function receipt(){
        return $this->hasOne('App\Models\Receipt');
    }
    public function bank_account(){
        return $this->belongsTo('App\Models\BankAccount');
    }
}
