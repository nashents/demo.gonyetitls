<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tax extends Model
{
    use HasFactory, SoftDeletes;

    public function invoice_items(){
        return $this->hasMany('App\Models\InvoiceItem');
    }
    public function user(){
        return $this->belongsTo('App\Models\User');
    }
}
