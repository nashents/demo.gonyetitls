<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InvoiceItem extends Model
{
    use HasFactory, SoftDeletes;

    public function invoice(){
        return $this->belongsTo('App\Models\Invoice');
    }
    public function tax(){
        return $this->belongsTo('App\Models\Tax');
    }
    public function account(){
        return $this->belongsTo('App\Models\Account');
    }
    public function trip(){
        return $this->belongsTo('App\Models\Trip');
    }
    public function income_stream(){
        return $this->belongsTo('App\Models\IncomeStream');
    }
    public function product(){
        return $this->belongsTo('App\Models\Product');
    }
}
