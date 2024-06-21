<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contact extends Model
{
    use HasFactory, SoftDeletes;

    public function transporter(){
        return $this->belongsTo('App\Models\Transporter');
    }
    public function clearing_agent(){
        return $this->belongsTo('App\Models\ClearingAgent');
    }
    public function incident(){
        return $this->belongsTo('App\Models\Incident');
    }
    public function customer(){
        return $this->belongsTo('App\Models\Customer');
    }
    public function broker(){
        return $this->belongsTo('App\Models\Broker');
    }
    public function vendor(){
        return $this->belongsTo('App\Models\Vendor');
    }

    protected $fillable=[
        'transporter_id',
        'customer_id',
        'broker_id',
        'vendor_id',
        'category',
        'name',
        'surname',
        'email',
        'phonenumber',
    ];
}
