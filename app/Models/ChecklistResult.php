<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChecklistResult extends Model
{
    use HasFactory, SoftDeletes;

    public function checklist_item(){
        return $this->belongsTo('App\Models\ChecklistItem');
    }
    public function checklist(){
        return $this->belongsTo('App\Models\Checklist');
    }
}
