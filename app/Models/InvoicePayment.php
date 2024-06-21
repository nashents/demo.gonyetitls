<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InvoicePayment extends Model
{
    use HasFactory, SoftDeletes;

    public function invoice(){
        return $this->belongsTo('App\Models\Invoice');
    }
    
    public function payment(){
        return $this->belongsTo('App\Models\Payment');
    }
}
