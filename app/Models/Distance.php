<?php

namespace App\Models;

use Malhal\Geographical\Geographical;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Distance extends Model
{
    use HasFactory;
    use Geographical;

    protected static $kilometers = true;
}
