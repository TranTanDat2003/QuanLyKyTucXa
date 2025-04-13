<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\UtilityBill;
use App\Models\ServiceBill;
use App\Services\Payment\VNPayGateway;
use App\Services\Payment\MoMoGateway;
use App\Services\Payment\ZaloPayGateway;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $gateways;

    public function __construct()
    {
        $this->gateways = [
            'VNPAY' => new VNPayGateway(),
            'MOMO' => new MoMoGateway(),
            'ZALOPAY' => new ZaloPayGateway(),
        ];
    }

    public function createPayment($model, $amount, $type, $method): string
    {
        if (!isset($this->gateways[$method])) {
            throw new \Exception("Phương thức thanh toán $method không được hỗ trợ.");
        }

        return $this->gateways[$method]->createPayment($model, $amount, $type);
    }

    public function paymentCallback(Request $request, string $method)
    {
        if (!isset($this->gateways[$method])) {
            Log::error("Invalid payment method in callback: $method");
            return redirect()->route('student.pay')->with('error', 'Phương thức thanh toán không hợp lệ.');
        }

        $gateway = $this->gateways[$method];

        if ($gateway->verifyPayment($request)) {
            $txnRef = $method === 'ZALOPAY'
                ? $request->input('apptransid', 'unknown_' . time())
                : $request->input('vnp_TxnRef', $request->input('orderId', 'unknown_' . time()));

            [$type, $id] = $this->parseTxnRef($txnRef, $method);

            // Xử lý amount dựa trên phương thức thanh toán
            $amount = $this->getAmountFromRequest($request, $method);

            switch ($type) {
                case 'C':
                    $contract = Contract::findOrFail($id);
                    $contract->updatePayment($amount);
                    break;
                case 'U':
                    $utilityBill = UtilityBill::findOrFail($id);
                    $utilityBill->updatePayment($amount);
                    break;
                case 'S':
                    $serviceBill = ServiceBill::findOrFail($id);
                    $serviceBill->updatePayment($amount);
                    break;
                default:
                    Log::error('Unknown payment type in callback: ' . $txnRef);
                    return redirect()->route('student.pay')->with('error', 'Không xác định được loại thanh toán.');
            }

            return redirect()->route('student.pay')->with('success', "Thanh toán thành công qua {$gateway->getPaymentMethod()}!");
        }

        return redirect()->route('student.pay')->with('error', 'Thanh toán thất bại hoặc bị hủy.');
    }

    private function getAmountFromRequest(Request $request, string $method): float
    {
        if ($method === 'VNPAY') {
            return $request->input('vnp_Amount') / 100;
        } elseif ($method === 'MOMO') {
            return (float) $request->input('amount');
        } elseif ($method === 'ZALOPAY') {
            if ($request->has('data')) {
                $data = json_decode($request->input('data'), true);
                return (float) $data['amount'];
            }
            return (float) $request->input('amount');
        }

        throw new \Exception("Không thể xác định số tiền cho phương thức thanh toán: $method");
    }

    private function parseTxnRef($txnRef, $method)
    {
        if ($method === 'VNPAY') {
            return $this->parseVnPayTxnRef($txnRef);
        } elseif ($method === 'MOMO') {
            return $this->parseMoMoTxnRef($txnRef);
        } elseif ($method === 'ZALOPAY') {
            return $this->parseZaloPayTxnRef($txnRef);
        }

        throw new \Exception("Không thể phân tích txnRef cho phương thức thanh toán: $method");
    }
    private function parseVnPayTxnRef($txnRef)
    {
        // Với VNPay, định dạng là: [prefix][id]_[timestamp]
        $parts = explode('_', $txnRef);
        if (count($parts) < 2) {
            throw new \Exception("Invalid txnRef format: $txnRef");
        }

        $prefixAndId = $parts[0];
        $type = substr($prefixAndId, 0, 1);
        $id = substr($prefixAndId, 1);
        if (!in_array($type, ['C', 'U', 'S'])) {
            throw new \Exception("Invalid payment type in txnRef: $txnRef");
        }
        return [$type, $id];
    }
    private function parseMoMoTxnRef($txnRef)
    {
        // Với MoMo, định dạng là: [prefix][id]_[timestamp]
        $parts = explode('_', $txnRef);
        if (count($parts) < 2) {
            throw new \Exception("Invalid txnRef format: $txnRef");
        }

        $prefixAndId = $parts[0];
        $type = substr($prefixAndId, 0, 1);
        $id = substr($prefixAndId, 1);
        if (!in_array($type, ['C', 'U', 'S'])) {
            throw new \Exception("Invalid payment type in txnRef: $txnRef");
        }
        return [$type, $id];
    }
    private function parseZaloPayTxnRef($txnRef)
    {
        $parts = explode('_', $txnRef);

        // Với ZaloPay, định dạng là: [date]_[prefix][id]_[timestamp]
        if (count($parts) < 3 || !preg_match('/^\d{6}$/', $parts[0])) {
            throw new \Exception("Invalid ZaloPay txnRef format: $txnRef");
        }
        $prefixAndId = $parts[1]; // Lấy "C7"
        $type = substr($prefixAndId, 0, 1); // Lấy "C"
        $id = substr($prefixAndId, 1); // Lấy "7"
        if (!in_array($type, ['C', 'U', 'S'])) {
            throw new \Exception("Invalid payment type in txnRef: $txnRef");
        }
        return [$type, $id];
    }
}
