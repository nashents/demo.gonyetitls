<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Address extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'address_address',
        'address_latitude',
        'address_longitude',
    ];
}
