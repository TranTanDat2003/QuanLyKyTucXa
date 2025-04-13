<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UtilityBill extends Model
{
    protected $table = 'utility_bills';
    protected $primaryKey = 'utility_bill_id';

    protected $fillable = [
        'share_amount',
        'amount_paid',
        'due_date',
        'paid_at',
        'is_paid',
        'utility_id',
        'contract_id',
    ];

    protected $casts = [
        'due_date' => 'date',
        'paid_at' => 'datetime',
    ];

    public function utility()
    {
        return $this->belongsTo(Utility::class, 'utility_id');
    }

    public function contract()
    {
        return $this->belongsTo(Contract::class, 'contract_id');
    }

    // Lấy hóa đơn điện/nước của sinh viên
    public static function getStudentUtilityBills($studentId)
    {
        return self::whereHas('contract', function ($query) use ($studentId) {
            $query->where('student_id', $studentId);
        })
            ->with(['utility.room', 'contract.semester'])
            ->get();
    }

    // Thanh toán hóa đơn
    public function updatePayment($amount)
    {
        return DB::transaction(function () use ($amount) {
            $this->amount_paid += $amount;
            if ($this->amount_paid >= $this->share_amount) {
                $this->is_paid = true;
                $this->paid_at = now();
            }
            $this->save();

            return $this;
        });
    }

    // Lấy tiền đã thu theo utility_id
    public static function getShareCollected($utilityId)
    {
        return self::where('utility_id', $utilityId)
            ->where('is_paid', true)
            ->sum('share_amount');
    }

    // Tạo hoặc cập nhật utility_bills cho một utility
    public static function createOrUpdateForUtility(Utility $utility): void
    {
        // Lấy học kỳ hiện tại
        $currentSemester = Semester::getCurrentSemester();
        if (!$currentSemester) {
            return;
        }

        // Lấy danh sách hợp đồng đang ở trong phòng
        $activeContracts = Contract::where('room_id', $utility->room_id)
            ->where('status', 'Đang ở')
            ->where('semester_id', $currentSemester->semester_id)
            ->get();

        if ($activeContracts->isEmpty()) {
            return;
        }

        // Tính share_amount một lần
        $shareAmount = self::calculateShareAmountForUtility($utility, $activeContracts->count());
        $dueDate = Carbon::now()->addWeeks(2);

        foreach ($activeContracts as $contract) {
            // Tìm hoặc tạo utility_bill
            $utilityBill = self::firstOrNew([
                'utility_id' => $utility->utility_id,
                'contract_id' => $contract->contract_id,
            ]);

            // Cập nhật thông tin
            $utilityBill->share_amount = $shareAmount;
            $utilityBill->due_date = $dueDate;
            $utilityBill->is_paid = false; // Đặt mặc định chưa thanh toán
            $utilityBill->amount_paid = $utilityBill->amount_paid ?? 0; // Giữ nguyên nếu đã có thanh toán
            $utilityBill->paid_at = null; // Reset nếu chưa thanh toán hoàn toàn
            $utilityBill->save();
        }
    }

    // Tính toán share_amount cho utility
    protected static function calculateShareAmountForUtility(Utility $utility, int $contractCount): float
    {
        return $contractCount > 0 ? $utility->utility_cost / $contractCount : 0;
    }

    // Tính share_amount chia đều dựa trên số hợp đồng trong phòng
    public function calculateShareAmount()
    {
        $roomId = $this->utility->room_id;
        $currentSemester = Semester::getCurrentSemester();

        if (!$currentSemester) {
            $this->share_amount = 0;
            $this->save();
            return;
        }

        $activeContractsCount = Contract::where('room_id', $roomId)
            ->where('status', 'Đang ở')
            ->where('semester_id', $currentSemester->semester_id)
            ->count();

        $this->share_amount = self::calculateShareAmountForUtility($this->utility, $activeContractsCount);
        $this->save();
    }
}
