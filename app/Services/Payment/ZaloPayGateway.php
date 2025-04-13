<?php

namespace App\Services\Payment;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ZaloPayGateway implements PaymentGateway
{
    public function createPayment($model, $amount, $type): string
    {
        $appId = config('zalopay.app_id');
        $key1 = config('zalopay.key1');
        $endpoint = config('zalopay.endpoint');
        $callbackUrl = config('zalopay.callback_url');
        $redirectUrl = config('zalopay.redirect_url');

        if (!$appId || !$key1 || !$endpoint || !$callbackUrl) {
            throw new \Exception('Cấu hình ZaloPay không đầy đủ.');
        }

        $id = $this->getModelId($model, $type);
        $orderId = $this->generateTxnRef($type, $id);
        $orderInfo = $this->generateOrderInfo($type, $id);
        $appTime = round(microtime(true) * 1000);
        $embeddata = json_encode(['redirecturl' => $redirectUrl]);

        $items = '[]';

        $order = [
            'amount' => (int) $amount,
            'app_id' => $appId,
            'app_time' => $appTime,
            'app_trans_id' => date('ymd') . '_' . $orderId,
            'app_user' => 'Student',
            'item' => $items,
            'embed_data' => $embeddata,
            'description' => $orderInfo,
            'bank_code' => '',
            'callback_url' => $callbackUrl,
        ];

        $data = $order['app_id'] . '|' . $order['app_trans_id'] . '|' . $order['app_user'] . '|' . $order['amount'] . '|' . $order['app_time'] . '|' . $order['embed_data'] . '|' . $order['item'];
        $order['mac'] = hash_hmac('sha256', $data, $key1);

        Log::info('ZaloPay createPayment request', ['order' => $order]);

        $response = Http::asForm()->post($endpoint, $order);
        $result = $response->json();

        Log::info('ZaloPay createPayment response', ['status' => $response->status(), 'result' => $result]);

        if (!$response->successful() || !is_array($result) || !isset($result['return_code']) || $result['return_code'] !== 1 || empty($result['order_url'])) {
            $errorMessage = isset($result['return_message']) ? $result['return_message'] : 'Lỗi không xác định';
            if (isset($result['sub_return_message'])) {
                $errorMessage .= ' - ' . $result['sub_return_message'];
            }
            Log::error('ZaloPay createPayment failed', [
                'status' => $response->status(),
                'response' => $result,
            ]);
            throw new \Exception("Không thể tạo URL thanh toán ZaloPay: $errorMessage");
        }

        return $result['order_url'];
    }

    public function verifyPayment(Request $request): bool
    {
        $key2 = config('zalopay.key2');
        if (!$key2) {
            throw new \Exception('Thiếu key2 trong cấu hình ZaloPay.');
        }

        // Ghi log toàn bộ request để debug
        Log::info('ZaloPay verifyPayment received', $request->all());

        // Kiểm tra redirect (GET) hoặc callback (POST)
        if ($request->isMethod('get')) {
            /// Xử lý redirect từ redirecturl
            $status = $request->input('status');
            $appTransId = $request->input('apptransid');
            $checksum = $request->input('checksum');
            $appid = $request->input('appid');
            $amount = $request->input('amount');
            $pmcid = $request->input('pmcid');
            $bankcode = $request->input('bankcode', ''); // bankcode có thể rỗng
            $discountamount = $request->input('discountamount');

            if (
                !$request->has('status') || !$request->has('apptransid') || !$request->has('checksum') ||
                !$request->has('appid') || !$request->has('amount') || !$request->has('pmcid') ||
                !$request->has('discountamount')
            ) {
                Log::warning('ZaloPay redirect: Thiếu tham số cần thiết', $request->all());
                return false;
            }

            // Tạo checksum theo tài liệu ZaloPay
            $dataToHash = $appid . '|' . $appTransId . '|' . $pmcid . '|' . $bankcode . '|' . $amount . '|' . $discountamount . '|' . $status;
            $computedChecksum = hash_hmac('sha256', $dataToHash, $key2);

            if ($computedChecksum === $checksum && ($status === "1" || $status === 1)) {
                Log::info('ZaloPay redirect verified', ['app_trans_id' => $appTransId]);
                return true;
            }

            Log::warning('ZaloPay redirect: Checksum không hợp lệ', [
                'receivedChecksum' => $checksum,
                'computedChecksum' => $computedChecksum,
                'dataToHash' => $dataToHash,
            ]);
            return false;
        }

        // Xử lý callback POST
        $data = $request->input('data');
        $mac = $request->input('mac');

        if (!$data || !$mac) {
            Log::warning('ZaloPay verifyPayment: Thiếu data hoặc mac', $request->all());
            return false;
        }

        $computedMac = hash_hmac('sha256', $data, $key2);
        $isValid = $computedMac === $mac;

        Log::warning('Data result', [
            'data' => $data,
            'mac' => $mac,
            'computedMac' => $computedMac,
            'isValid' => $isValid,
        ]);

        if ($isValid) {
            $dataDecoded = json_decode($data, true);
            $isSuccess = $dataDecoded['return_code'] === 1;
            Log::info('ZaloPay verifyPayment result', [
                'apptransid' => $dataDecoded['app_trans_id'],
                'isValid' => $isValid,
                'isSuccess' => $isSuccess,
            ]);
            return $isSuccess;
        }

        Log::warning('ZaloPay verifyPayment: Chữ ký không hợp lệ', [
            'receivedMac' => $mac,
            'computedMac' => $computedMac,
        ]);
        return false;
    }

    public function getPaymentMethod(): string
    {
        return 'ZALOPAY';
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
