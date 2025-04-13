<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceBillItem extends Model
{
    protected $table = 'service_bill_items';
    protected $primaryKey = 'service_bill_item_id';

    protected $fillable = [
        'service_bill_id',
        'service_id',
        'service_price',
        'total_amount',
        'start_date',
        'end_date',
        'bike_plate',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function serviceBill()
    {
        return $this->belongsTo(ServiceBill::class, 'service_bill_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    // Truy xuất student_id thông qua service_bill
    public function getStudentIdAttribute()
    {
        return $this->serviceBill ? $this->serviceBill->student_id : null;
    }
}
