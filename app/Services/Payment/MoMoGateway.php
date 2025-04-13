<?php

namespace App\Services\Payment;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MoMoGateway implements PaymentGateway
{
    public function createPayment($model, $amount, $type): string
    {
        $endpoint = config('momo.endpoint');
        $partnerCode = config('momo.partner_code');
        $accessKey = config('momo.access_key');
        $secretKey = config('momo.secret_key');
        $ipnUrl = config('momo.ipn_Url');
        $redirectUrl = config('momo.redirect_url');

        // Kiểm tra cấu hình
        if (!$endpoint || !$partnerCode || !$accessKey || !$secretKey || !$redirectUrl || !$ipnUrl) {
            throw new \Exception('Cấu hình MoMo không đầy đủ.');
        }

        $id = $this->getModelId($model, $type);
        $orderId = $this->generateTxnRef($type, $id);
        $orderInfo = $this->generateOrderInfo($type, $id);
        $requestId = time() . '';
        $requestType = "captureWallet";
        $extraData = base64_encode(json_encode(['type' => $type, 'id' => $id]));

        $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
        $signature = hash_hmac('sha256', $rawHash, $secretKey);

        $response = Http::post($endpoint, [
            'partnerCode' => $partnerCode,
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'requestType' => $requestType,
            'lang' => 'vi',
            'extraData' => $extraData,
            'signature' => $signature,
        ]);

        $result = $response->json();
        if (!$response->successful() || empty($result['payUrl'])) {
            throw new \Exception('Không thể tạo URL thanh toán MoMo: ' . ($result['message'] ?? 'Lỗi không xác định'));
        }

        return $result['payUrl'];
    }

    public function verifyPayment(Request $request): bool
    {
        $secretKey = config('momo.secret_key');
        $rawHash = "accessKey=" . config('momo.access_key') . "&amount={$request->amount}&orderId={$request->orderId}&partnerCode=" . config('momo.partner_code') . "&requestId={$request->requestId}&transId={$request->transId}";
        $signature = hash_hmac('sha256', $rawHash, $secretKey);

        return $signature === $request->signature && $request->resultCode == 0;
    }

    public function getPaymentMethod(): string
    {
        return 'MOMO';
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
