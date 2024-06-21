<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Account extends Model
{
    use HasFactory, SoftDeletes;

    public function account_type(){
        return $this->belongsTo('App\Models\AccountType');
    }
    public function payments(){
        return $this->hasMany('App\Models\Payment');
    }
    public function bill_expenses(){
        return $this->hasMany('App\Models\BillExpense');
    }
    public function workshop_services(){
        return $this->hasMany('App\Models\WorkshopService');
    }
    public function purchases(){
        return $this->hasMany('App\Models\Purchase');
    }
    public function expenses(){
        return $this->hasMany('App\Models\Expense');
    }
    public function invoice_items(){
        return $this->hasMany('App\Models\InvoiceItem');
    }
    public function currency(){
        return $this->belongsTo('App\Models\Currency');
    }
    public function user(){
        return $this->belongsTo('App\Models\User');
    }
    public function bank_account(){
        return $this->belongsTo('App\Models\BankAccount');
    }
    public function customer(){
        return $this->belongsTo('App\Models\Customer');
    }
    public function cash_flows(){
        return $this->hasMany('App\Models\CashFlow');
    }
    public function ticket_expenses(){
        return $this->hasMany('App\Models\TicketExpense');
    }
    public function requisitions(){
        return $this->hasMany('App\Models\Requisition');
    }

    protected $fillable=[
        'bank_account_id',
        'account_type_id',
        'currency_id',
        'account_number',
        'name',
        'description',
    ];
}
