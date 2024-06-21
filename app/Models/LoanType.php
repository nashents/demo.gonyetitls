<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LoanType extends Model
{
    use HasFactory, SoftDeletes;

    public function loans(){
        return $this->hasMany('App\Models\Loan');
    }
    public function user(){
        return $this->belongsTo('App\Models\User');
        
    }

    protected $fillable =[
        'user_id',
        'name'
    ];
}
