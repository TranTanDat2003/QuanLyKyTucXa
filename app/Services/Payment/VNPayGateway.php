<?php

namespace App\Services\Payment;

use Illuminate\Http\Request;

class VNPayGateway implements PaymentGateway
{
    public function createPayment($model, $amount, $type): string
    {
        $vnp_TmnCode = config('vnpay.vnp_TmnCode');
        $vnp_HashSecret = config('vnpay.vnp_HashSecret');
        $vnp_Url = config('vnpay.vnp_Url');
        $vnp_ReturnUrl = config('vnpay.vnp_ReturnUrl');

        $id = $this->getModelId($model, $type);
        $vnp_TxnRef = $this->generateTxnRef($type, $id);
        $vnp_OrderInfo = $this->generateOrderInfo($type, $id);
        $vnp_Amount = $amount * 100;

        $inputData = [
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => request()->ip(),
            "vnp_Locale" => "vn",
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => "billpayment",
            "vnp_ReturnUrl" => $vnp_ReturnUrl,
            "vnp_TxnRef" => $vnp_TxnRef,
        ];

        ksort($inputData);
        $query = http_build_query($inputData);
        $vnp_SecureHash = hash_hmac('sha512', $query, $vnp_HashSecret);
        return $vnp_Url . '?' . $query . '&vnp_SecureHash=' . $vnp_SecureHash;
    }

    public function verifyPayment(Request $request): bool
    {
        $vnp_HashSecret = config('vnpay.vnp_HashSecret');
        $vnp_SecureHash = $request->vnp_SecureHash;
        $inputData = $request->except('vnp_SecureHash');

        ksort($inputData);
        $hashdata = http_build_query($inputData);
        $hash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);

        return $hash === $vnp_SecureHash && $request->vnp_ResponseCode == '00';
    }

    public function getPaymentMethod(): string
    {
        return 'VNPAY';
    }

    private function getModelId($model, $type)
    {
        return match ($type) {
            'contract' => $model->contract_id,
            'utility' => $model->utility_bill_id,
            'service' => $model->service_bill_id,
            default => throw new \Exception('Invalid payment type'),
        };
    }

    private function generateTxnRef($type, $id)
    {
        $prefix = match ($type) {
            'contract' => 'C',
            'utility' => 'U',
            'service' => 'S',
            default => throw new \Exception('Invalid payment type'),
        };
        return $prefix . $id . '_' . time();
    }

    private function generateOrderInfo($type, $id)
    {
        return match ($type) {
            'contract' => "Thanh toan hop dong #$id",
            'utility' => "Thanh toan hoa don dien nuoc #$id",
            'service' => "Thanh toan hoa don dich vu #$id",
            default => throw new \Exception('Invalid payment type'),
        };
    }
}
