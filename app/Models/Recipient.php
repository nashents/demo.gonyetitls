<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Recipient extends Model
{
    use HasFactory, SoftDeletes;

    public function user(){
        return $this->belongsTo('App\Models\User');
    }
    public function fitnesses(){
        return $this->belongsToMany('App\Models\Fitness');
    }
    public function reminders(){
        return $this->belongsToMany('App\Models\Reminder');
    }
}
