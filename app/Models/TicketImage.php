<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TicketImage extends Model
{
    use HasFactory, SoftDeletes;
   
    public function ticket(){
        return $this->belongsTo('App\Models\Ticket');
    }
}
