<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceBillRequest;
use App\Models\Service;
use App\Models\ServiceBill;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ServiceBillController extends Controller
{
    protected $paymentController;

    public function __construct(PaymentController $paymentController)
    {
        $this->paymentController = $paymentController;
    }

    public function store(ServiceBillRequest $request)
    {
        try {
            $studentId = Auth::user()->student->student_id;
            $semester = Semester::getCurrentSemester() ?? Semester::getNextSemester();
            if (!$semester) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không có học kỳ nào để đăng ký.'
                ], 400);
            }

            $service = Service::findOrFail($request->service_id);
            if (!$service->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dịch vụ này không khả dụng.'
                ], 400);
            }

            $existingBill = ServiceBill::findPendingBill($studentId, $semester->semester_id);

            if ($existingBill) {
                if ($existingBill->hasService($service->service_id)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Bạn đã đăng ký dịch vụ này trong học kỳ hiện tại.'
                    ], 400);
                }

                $existingBill->addServiceItem($service, $semester, $request->bike_plate);
                $message = 'Đăng ký dịch vụ thành công và được thêm vào hóa đơn hiện tại!';
            } else {
                ServiceBill::createWithService($studentId, $semester, $service, $request->bike_plate);
                $message = 'Đăng ký dịch vụ thành công và tạo hóa đơn mới!';
            }

            return response()->json([
                'success' => true,
                'message' => $message
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Đăng ký thất bại: ' . $e->getMessage()
            ], 500);
        }
    }

    public function initiatePayment(Request $request, $serviceBillId)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:VNPAY,MOMO,ZALOPAY'
        ]);

        try {
            $serviceBill = ServiceBill::findOrFail($serviceBillId);

            if ($serviceBill->status === 'paid') {
                return response()->json(['success' => false, 'message' => 'Hóa đơn đã được thanh toán'], 400);
            }

            $remainingAmount = $serviceBill->total_amount - $serviceBill->amount_paid;
            if ($request->amount > $remainingAmount) {
                return response()->json(['success' => false, 'message' => 'Số tiền thanh toán vượt quá số tiền còn lại'], 400);
            }

            Log::info('Tạo yêu cầu thanh toán cho hóa đơn dịch vụ', [
                'service_bill_id' => $serviceBillId,
                'amount' => $request->amount,
                'payment_method' => $request->payment_method
            ]);

            $paymentUrl = $this->paymentController->createPayment($serviceBill, $request->amount, 'service', $request->payment_method);

            return response()->json([
                'success' => true,
                'message' => 'Yêu cầu thanh toán đã được tạo',
                'redirect' => $paymentUrl
            ], 200);
        } catch (\Exception $e) {
            Log::error('Lỗi khi tạo thanh toán cho hóa đơn dịch vụ: ' . $e->getMessage(), [
                'service_bill_id' => $serviceBillId,
                'amount' => $request->amount ?? null,
                'payment_method' => $request->payment_method ?? null
            ]);
            return response()->json(['success' => false, 'message' => 'Thanh toán thất bại: ' . $e->getMessage()], 500);
        }
    }
}
