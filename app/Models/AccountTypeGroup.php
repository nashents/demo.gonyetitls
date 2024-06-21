<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccountTypeGroup extends Model
{
    use HasFactory, SoftDeletes;

    public function account_types(){
        return $this->hasMany('App\Models\AccountType');
    }
}
