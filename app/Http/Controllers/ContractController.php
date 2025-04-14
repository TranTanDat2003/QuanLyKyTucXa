<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContractRequest;
use App\Models\Contract;
use App\Models\RoomType;
use App\Models\Semester;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ContractController extends Controller
{
    protected $paymentController;

    public function __construct(PaymentController $paymentController)
    {
        $this->paymentController = $paymentController;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $contracts = Contract::with(['student', 'room', 'semester', 'staff'])->get();
            return response()->json([
                'success' => true,
                'contracts' => $contracts
            ], 200);
        }

        return view('admin.contracts.index');
    }

    public function store(ContractRequest $request)
    {
        try {
            $studentId = $request->student_id;
            $semesterId = $request->semester_id;
            $roomTypeId = $request->room_type_id;

            $semester = Semester::findOrFail($semesterId);
            $roomType = RoomType::findOrFail($roomTypeId);
            $gender = Student::getGenderById($studentId);

            if ($gender === null) {
                return response()->json(['success' => false, 'message' => 'Không tìm thấy sinh viên'], 404);
            }

            $gender = $gender == 0 ? 'Nam' : 'Nữ';

            // Kiểm tra xem loại phòng có chỗ trống cho giới tính này không
            if (!$roomType->hasAvailableSlotsByGender($gender)) {
                return response()->json(['success' => false, 'message' => 'Không còn phòng trống cho loại phòng này'], 400);
            }

            $contract = DB::transaction(function () use ($studentId, $semesterId, $roomTypeId, $semester) {
                return Contract::create([
                    'student_id' => $studentId,
                    'semester_id' => $semesterId,
                    'room_type_id' => $roomTypeId,
                    'contract_cost' => 0,
                    'contract_start_date' => $semester->start_date,
                    'contract_end_date' => $semester->end_date,
                    'status' => 'Chờ duyệt',
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Tạo hợp đồng thành công',
                'contract' => $contract
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating contract:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Tạo hợp đồng thất bại: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($contractId)
    {
        $contract = Contract::with(['student', 'room', 'semester', 'staff'])->findOrFail($contractId);
        return response()->json([
            'success' => true,
            'contract' => $contract
        ], 200);
    }

    public function approve($contractId)
    {
        try {
            $contract = Contract::findOrFail($contractId);
            $contract->approve();

            return response()->json(['success' => true, 'message' => 'Duyệt hợp đồng thành công'], 200);
        } catch (\Exception $e) {
            Log::error('Error approving contract:', ['message' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Duyệt hợp đồng thất bại: ' . $e->getMessage()], 500);
        }
    }

    public function cancel($contractId)
    {
        try {
            $contract = Contract::findOrFail($contractId);

            // Ngăn hủy nếu hợp đồng đã bị hủy hoặc hết hạn
            if (in_array($contract->status, ['Hủy', 'Hết hạn'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hợp đồng đã được hủy hoặc hết hạn'
                ], 400);
            }

            $contract->cancel();

            return response()->json(['success' => true, 'message' => 'Hủy hợp đồng thành công'], 200);
        } catch (\Exception $e) {
            Log::error('Error canceling contract:', ['message' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Hủy hợp đồng thất bại: ' . $e->getMessage()], 500);
        }
    }

    public function checkout($contractId)
    {
        try {
            $contract = Contract::findOrFail($contractId);
            $contract->checkout();

            return response()->json(['success' => true, 'message' => 'Trả phòng thành công'], 200);
        } catch (\Exception $e) {
            Log::error('Error checking out contract:', ['message' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Trả phòng thất bại: ' . $e->getMessage()], 500);
        }
    }

    public function initiatePayment(Request $request, $contractId)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:VNPAY,MOMO,ZALOPAY'
        ]);

        try {
            $contract = Contract::findOrFail($contractId);

            if ($contract->is_paid) {
                return response()->json(['success' => false, 'message' => 'Hợp đồng đã được thanh toán'], 400);
            }

            $remainingAmount = $contract->contract_cost - $contract->paid_amount;
            if ($request->amount > $remainingAmount) {
                return response()->json(['success' => false, 'message' => 'Số tiền thanh toán vượt quá số tiền còn lại'], 400);
            }

            $paymentUrl = $this->paymentController->createPayment($contract, $request->amount, 'contract', $request->payment_method);

            return response()->json([
                'success' => true,
                'message' => 'Yêu cầu thanh toán đã được tạo',
                'redirect' => $paymentUrl
            ], 200);
        } catch (\Exception $e) {
            Log::error('Lỗi khi tạo thanh toán cho hợp đồng: ' . $e->getMessage(), [
                'contract_id' => $contractId,
                'amount' => $request->amount ?? null,
                'payment_method' => $request->payment_method ?? null
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Thanh toán thất bại: ' . $e->getMessage()
            ], 500);
        }
    }
}
