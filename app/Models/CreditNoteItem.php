<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CreditNoteItem extends Model
{
    use HasFactory, SoftDeletes;

    public function credit_note(){
        return $this->belongsTo('App\Models\CreditNote');
    }
    public function invoice_product(){
        return $this->belongsTo('App\Models\InvoiceProduct');
    }
}
