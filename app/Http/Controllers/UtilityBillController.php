<?php

namespace App\Http\Controllers;

use App\Models\UtilityBill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UtilityBillController extends Controller
{
    protected $paymentController;

    public function __construct(PaymentController $paymentController)
    {
        $this->paymentController = $paymentController;
    }

    public function initiatePayment(Request $request, $utilityBillId)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:VNPAY,MOMO,ZALOPAY'
        ]);

        try {
            $utilityBill = UtilityBill::findOrFail($utilityBillId);

            if ($utilityBill->is_paid) {
                return response()->json(['success' => false, 'message' => 'Hóa đơn đã được thanh toán'], 400);
            }

            $remainingAmount = $utilityBill->share_amount - $utilityBill->amount_paid;
            if ($request->amount > $remainingAmount) {
                return response()->json(['success' => false, 'message' => 'Số tiền thanh toán vượt quá số tiền còn lại'], 400);
            }

            Log::info('Tạo yêu cầu thanh toán cho hóa đơn tiện ích', [
                'utility_bill_id' => $utilityBillId,
                'amount' => $request->amount,
                'payment_method' => $request->payment_method
            ]);

            $paymentUrl = $this->paymentController->createPayment($utilityBill, $request->amount, 'utility', $request->payment_method);

            return response()->json([
                'success' => true,
                'message' => 'Yêu cầu thanh toán đã được tạo',
                'redirect' => $paymentUrl
            ], 200);
        } catch (\Exception $e) {
            Log::error('Lỗi khi tạo thanh toán cho hóa đơn tiện ích: ' . $e->getMessage(), [
                'utility_bill_id' => $utilityBillId,
                'amount' => $request->amount ?? null,
                'payment_method' => $request->payment_method ?? null
            ]);
            return response()->json(['success' => false, 'message' => 'Thanh toán thất bại: ' . $e->getMessage()], 500);
        }
    }
}
