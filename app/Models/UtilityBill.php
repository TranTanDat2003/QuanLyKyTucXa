<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UtilityBill extends Model
{
    protected $primaryKey = 'utility_bill_id';
    protected $fillable = ['utility_id', 'student_id', 'electricity_cost', 'water_cost', 'service_cost', 'total_amount', 'issue_date', 'due_date', 'status', 'paid_at'];

    public function utility()
    {
        return $this->belongsTo(Utility::class, 'utility_id');
    }
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    // Lấy hóa đơn điện/nước của sinh viên
    public static function getStudentUtilityBills($studentId)
    {
        return self::where('student_id', $studentId)
            ->with(['utility.room', 'utility.contracts.semester'])
            ->get();
    }

    // Thanh toán hóa đơn
    public static function payUtilityBill($utilityBillId)
    {
        $utilityBill = self::findOrFail($utilityBillId);
        if ($utilityBill->status === 'Đã thanh toán') {
            throw new \Exception('Hóa đơn đã được thanh toán!');
        }
        $utilityBill->update([
            'status' => 'Đã thanh toán',
            'paid_at' => now(),
        ]);
        return $utilityBill;
    }

    // Lấy tổng tiền đã thu theo utility_id
    public static function getTotalCollected($utilityId)
    {
        return self::where('utility_id', $utilityId)
            ->where('status', 'Đã thanh toán')
            ->sum('total_amount');
    }
}
