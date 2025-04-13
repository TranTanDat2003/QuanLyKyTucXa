<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ServiceBill extends Model
{
    protected $table = 'service_bills';
    protected $primaryKey = 'service_bill_id';

    protected $fillable = [
        'total_amount',
        'amount_paid',
        'paid_at',
        'issued_date',
        'due_date',
        'status',
        'student_id',
        'semester_id',
    ];

    protected $casts = [
        'issued_date' => 'date',
        'due_date' => 'date',
        'status' => 'string',
        'paid_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class, 'semester_id');
    }

    public function items()
    {
        return $this->hasMany(ServiceBillItem::class, 'service_bill_id');
    }

    // Tìm hóa đơn pending cho student và semester.
    public static function findPendingBill($studentId, $semesterId)
    {
        return self::where('student_id', $studentId)
            ->where('semester_id', $semesterId)
            ->where('status', 'pending')
            ->first();
    }

    // Kiểm tra xem dịch vụ đã tồn tại trong hóa đơn chưa.
    public function hasService($serviceId)
    {
        return $this->items()->where('service_id', $serviceId)->exists();
    }

    // Thêm dịch vụ vào hóa đơn và cập nhật total_amount.
    public function addServiceItem($service, $semester, $bikePlate = null)
    {
        $item = ServiceBillItem::create([
            'service_bill_id' => $this->service_bill_id,
            'service_id' => $service->service_id,
            'service_price' => $service->price,
            'total_amount' => $service->price,
            'start_date' => $semester->start_date,
            'end_date' => $semester->end_date,
            'bike_plate' => $bikePlate,
        ]);

        $this->total_amount += $service->price;
        $this->save();

        return $item;
    }

    // Tạo hóa đơn mới với dịch vụ.
    public static function createWithService($studentId, $semester, $service, $bikePlate = null)
    {
        $bill = self::create([
            'total_amount' => $service->price,
            'amount_paid' => 0,
            'issued_date' => now(),
            'due_date' => $semester->start_date, // Due_date là ngày bắt đầu học kỳ
            'status' => 'pending',
            'student_id' => $studentId,
            'semester_id' => $semester->semester_id,
        ]);

        $item = ServiceBillItem::create([
            'service_bill_id' => $bill->service_bill_id,
            'service_id' => $service->service_id,
            'service_price' => $service->price,
            'total_amount' => $service->price,
            'start_date' => $semester->start_date,
            'end_date' => $semester->end_date,
            'bike_plate' => $bikePlate,
        ]);

        return $bill;
    }

    public function updatePayment($amount)
    {
        return DB::transaction(function () use ($amount) {
            $this->amount_paid += $amount;
            if ($this->amount_paid >= $this->total_amount) {
                $this->status = 'paid';
                $this->paid_at = now();
            }
            $this->save();

            return $this;
        });
    }

    public static function getMonthlyRevenue(): array
    {
        return self::select(
            DB::raw("DATE_FORMAT(paid_at, '%Y-%m') as month"),
            DB::raw('SUM(total_amount) as service_revenue')
        )
            ->where('status', 'paid')
            ->whereNotNull('paid_at')
            ->groupBy(DB::raw("DATE_FORMAT(paid_at, '%Y-%m')"))
            ->orderBy('month')
            ->get()
            ->keyBy('month')
            ->toArray();
    }
}
