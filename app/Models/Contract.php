<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $primaryKey = 'contract_id';
    protected $fillable = ['student_id', 'room_id', 'semester_id', 'start_date', 'end_date', 'status', 'is_paid', 'approved_at'];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }
    public function semester()
    {
        return $this->belongsTo(Semester::class, 'semester_id');
    }
    public function roomBills()
    {
        return $this->hasMany(RoomBill::class, 'contract_id');
    }

    // Kiểm tra hợp đồng đã tồn tại trong học kỳ hiện tại
    public static function hasExistingContract($studentId, $semesterId)
    {
        return self::where('student_id', $studentId)
            ->where('semester_id', $semesterId)
            ->whereIn('status', ['Chờ duyệt', 'Đang thuê'])
            ->exists();
    }

    // Lấy danh sách hợp đồng chờ duyệt
    public static function getPendingContracts()
    {
        return self::where('status', 'Chờ duyệt')
            ->with(['student', 'room', 'semester'])
            ->get();
    }

    // Duyệt hợp đồng
    public static function approveContract($contractId)
    {
        $contract = self::findOrFail($contractId);
        if ($contract->status !== 'Chờ duyệt') {
            throw new \Exception('Hợp đồng không ở trạng thái chờ duyệt!');
        }
        $contract->update(['status' => 'Đang thuê', 'approved_at' => now()]);
        return $contract;
    }
}
