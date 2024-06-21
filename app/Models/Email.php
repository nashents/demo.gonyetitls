<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Email extends Model implements Auditable
{
    use HasFactory,SoftDeletes;

    use \OwenIt\Auditing\Auditable;

    public function user(){
        return $this->belongsTo('App\Models\User');
    }
    public function employee(){
        return $this->belongsTo('App\Models\Employee');
    }
    public function driver(){
        return $this->belongsTo('App\Models\Driver');
    }
    public function department(){
        return $this->belongsTo('App\Models\Department');
    }
    public function branch(){
        return $this->belongsTo('App\Models\Branch');
    }

    public function attachments(){
        return $this->hasMany('App\Models\Attachment');
    }
}
