<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $primaryKey = 'service_id';
    protected $fillable = ['service_name', 'price', 'is_active'];

    public function studentServices()
    {
        return $this->hasMany(StudentService::class, 'service_id');
    }
}
