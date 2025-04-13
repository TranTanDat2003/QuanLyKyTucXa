<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table = 'services';
    protected $primaryKey = 'service_id';

    protected $fillable = [
        'service_name',
        'price',
        'is_active',
        'service_img_path',
        'service_description',
    ];

    public function serviceBillItems()
    {
        return $this->hasMany(ServiceBillItem::class, 'service_id');
    }
}
