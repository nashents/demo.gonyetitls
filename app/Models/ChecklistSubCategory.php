<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChecklistSubCategory extends Model
{
    use HasFactory, SoftDeletes;

    public function category_checklists(){
        return $this->hasMany('App\Models\CategoryChecklist');
    }
    public function checklist_items(){
        return $this->hasMany('App\Models\ChecklistItem');
    }
    
    protected $fillable = [
        'checklist_category_id',
        'name',
        'user_id',
    ];
}
