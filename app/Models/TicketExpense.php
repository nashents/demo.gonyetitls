<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TicketExpense extends Model
{
    use HasFactory, SoftDeletes;

    public function ticket(){
        return $this->belongsTo('App\Models\Ticket');
    }
    public function currency(){
        return $this->belongsTo('App\Models\Currency');
    }

    public function bill(){
        return $this->hasOne('App\Models\Bill');
    }

    public function expense(){
        return $this->belongsTo('App\Models\Expense');
    }
    public function account(){
        return $this->belongsTo('App\Models\Account');
    }

}
