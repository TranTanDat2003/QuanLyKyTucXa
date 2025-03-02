<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomBill extends Model
{
    protected $primaryKey = 'room_bill_id';
    protected $fillable = ['contract_id', 'student_id', 'semester_id', 'room_cost', 'issue_date', 'due_date', 'status'];

    public function contract()
    {
        return $this->belongsTo(Contract::class, 'contract_id');
    }
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
    public function semester()
    {
        return $this->belongsTo(Semester::class, 'semester_id');
    }

    // Lấy hóa đơn tiền phòng của sinh viên
    public static function getStudentRoomBills($studentId)
    {
        return self::where('student_id', $studentId)
            ->with(['semester', 'contract'])
            ->get();
    }

    // Thanh toán hóa đơn
    public static function payRoomBill($roomBillId)
    {
        $roomBill = self::findOrFail($roomBillId);
        if ($roomBill->status === 'Đã thanh toán') {
            throw new \Exception('Hóa đơn đã được thanh toán!');
        }
        $roomBill->update(['status' => 'Đã thanh toán']);
        $allPaid = self::where('contract_id', $roomBill->contract_id)
            ->where('status', 'Chưa thanh toán')
            ->doesntExist();
        if ($allPaid) {
            $roomBill->contract->update(['is_paid' => true]);
        }
        return $roomBill;
    }
}
