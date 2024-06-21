<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    public function invoice_products(){
        return $this->hasMany('App\Models\InvoiceProduct');
    }
    public function invoice_items(){
        return $this->hasMany('App\Models\InvoiceItem');
    }
    public function bills(){
        return $this->hasMany('App\Models\Bill');
    }
    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public function receipts(){
        return $this->hasMany('App\Models\Receipt');
    }
    public function payments(){
        return $this->hasMany('App\Models\Payment');
    }
    public function invoice_payments(){
        return $this->hasMany('App\Models\InvoicePayment');
    }
    public function company(){
        return $this->belongsTo('App\Models\Company');
    }

    public function customer(){
        return $this->belongsTo('App\Models\Customer');
    }
    public function currency(){
        return $this->belongsTo('App\Models\Currency');
    }
    public function bank_account(){
        return $this->belongsTo('App\Models\BankAccount');
    }
    public function trip(){
        return $this->belongsTo('App\Models\Trip');
    }
    public function credit_notes(){
        return $this->hasMany('App\Models\CreditNote');
    }
    public function invoice_trips(){
        return $this->hasMany('App\Models\InvoiceTrip');
    }

    protected $fillable =[
        'user_id',
        'customer_id',
        'currency_id',
        'invoice_number',
        'trip_id',
        'vat',
        'total',
        'subtotal',
        'date',
        'expiry',
        'memo',
        'footer',
        'subheading',
    ];
}
