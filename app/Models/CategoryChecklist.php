<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CategoryChecklist extends Model
{
    use HasFactory, SoftDeletes;

    public function checklist_item(){
        return $this->belongsTo('App\Models\ChecklistItem');
    }
    public function checklist_category(){
        return $this->belongsTo('App\Models\ChecklistCategory');
    }
    public function checklist_sub_category(){
        return $this->belongsTo('App\Models\ChecklistSubCategory');
    }
}
