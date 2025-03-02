<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UtilityRate extends Model
{
    protected $primaryKey = 'rate_id';
    protected $fillable = ['electricity_rate', 'water_rate', 'effective_date'];
}
