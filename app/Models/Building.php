<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    protected $primaryKey = 'building_id';
    protected $fillable = ['building_name', 'description'];

    public function rooms()
    {
        return $this->hasMany(Room::class, 'building_id');
    }
}
