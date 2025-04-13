<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Contract extends Model
{
    protected $table = 'contracts';
    protected $primaryKey = 'contract_id';

    protected $fillable = [
        'contract_start_date',
        'contract_end_date',
        'actual_end_date',
        'approve_at',
        'status',
        'contract_cost',
        'paid_amount',
        'is_paid',
        'student_id',
        'room_id',
        'room_type_id',
        'semester_id',
        'staff_id',
    ];

    protected $casts = [
        'contract_start_date' => 'date',
        'contract_end_date' => 'date',
        'actual_end_date' => 'date',
        'approve_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function roomType()
    {
        return $this->belongsTo(RoomType::class, 'room_type_id');
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class, 'semester_id');
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function utilityBills()
    {
        return $this->hasMany(UtilityBill::class, 'contract_id');
    }

    public static function getPendingContracts()
    {
        return self::where('status', 'Chờ duyệt')->with(['student', 'room', 'semester'])->get();
    }

    public static function getContractWithStudentAndSemester($studentId, $semesterId)
    {
        return self::where('student_id', $studentId)
            ->where('semester_id', $semesterId)
            ->first();
    }

    // Tính contract_cost dựa trên room_type_price và số tháng
    public function calculateContractCost($startDate = null, $endDate = null)
    {
        $start = $startDate ? Carbon::parse($startDate) : Carbon::parse($this->contract_start_date);
        $end = $endDate ? Carbon::parse($endDate) : Carbon::parse($this->contract_end_date ?? $this->actual_end_date);

        $months = $start->diffInMonths($end) ?: 1; // Ít nhất 1 tháng
        $roomTypePrice = $this->roomType->room_type_price;

        return round($roomTypePrice * $months);
    }

    // Duyệt hợp đồng
    public function approve()
    {
        return DB::transaction(function () {
            $gender = Student::getGenderById($this->student_id);
            $gender = $gender == 0 ? 'Nam' : 'Nữ';

            $room = Room::findAvailableRoomByFillRatio($this->room_type_id, $gender);

            if (!$room) {
                throw new \Exception('Không còn phòng trống phù hợp để phân.');
            }

            $this->status = 'Đã duyệt';
            $this->approve_at = now();
            $this->contract_cost = $this->calculateContractCost();
            $this->staff_id = Auth::user()->staff->staff_id;
            $this->room_id = $room->room_id;
            $this->save();

            $room->updateAvailableSlots();

            return $this;
        });
    }

    // Hủy hợp đồng
    public function cancel()
    {
        return DB::transaction(function () {
            $this->status = 'Hủy';
            $this->save();

            $room = $this->room;
            $room->updateAvailableSlots();

            return $this;
        });
    }

    // Trả phòng
    public function checkout()
    {
        return DB::transaction(function () {
            $this->actual_end_date = now();
            $this->contract_cost = $this->calculateContractCost($this->contract_start_date, $this->actual_end_date);
            $this->status = 'Hết hạn';
            $this->save();

            $room = $this->room;
            $room->updateAvailableSlots();

            return $this;
        });
    }

    // Cập nhật trạng thái khi thanh toán
    public function updatePayment($amount)
    {
        return DB::transaction(function () use ($amount) {
            $this->paid_amount += $amount;
            if ($this->paid_amount >= $this->contract_cost) {
                $this->is_paid = true;
                $this->status = 'Đang ở';
            }
            $this->save();

            return $this;
        });
    }

    // Kiểm tra hợp đồng đã tồn tại
    public static function hasExistingContract($studentId, $semesterId)
    {
        return self::where('student_id', $studentId)
            ->where('semester_id', $semesterId)
            ->whereIn('status', ['Chờ duyệt', 'Đã duyệt', 'Đang ở'])
            ->exists();
    }

    public static function approveContract($contractId)
    {
        return DB::transaction(function () use ($contractId) {
            $contract = self::findOrFail($contractId);
            $contract->status = 'Đang ở';
            $contract->approve_at = now();
            $contract->approved_by = Auth::id();
            $contract->save();
            return $contract;
        });
    }

    // Kiểm tra và xóa hợp đồng chưa thanh toán khi đến ngày bắt đầu học kỳ
    public static function checkAndDeleteUnpaidContracts()
    {
        $contracts = self::where('status', 'Đã duyệt')
            ->where('is_paid', false)
            ->where('contract_start_date', '<=', now())
            ->get();

        foreach ($contracts as $contract) {
            $contract->cancel();
        }
    }

    // Kiểm tra hợp đồng hết hạn
    public static function checkExpiredContracts()
    {
        $contracts = self::where('status', 'Đang ở')
            ->where(function ($query) {
                $query->where('contract_end_date', '<=', now())
                    ->orWhereNotNull('actual_end_date');
            })
            ->get();

        foreach ($contracts as $contract) {
            $contract->status = 'Hết hạn';
            $contract->save();

            $room = $contract->room;
            $room->updateAvailableSlots();
        }
    }

    public static function getMonthlyRevenue(): array
    {
        return self::select(
            DB::raw("DATE_FORMAT(approve_at, '%Y-%m') as month"),
            DB::raw('SUM(contract_cost) as contract_revenue')
        )
            ->whereNotNull('approve_at')
            ->groupBy(DB::raw("DATE_FORMAT(approve_at, '%Y-%m')"))
            ->orderBy('month')
            ->get()
            ->keyBy('month')
            ->toArray();
    }
}
