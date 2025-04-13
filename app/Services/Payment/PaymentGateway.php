<?php

namespace App\Services\Payment;

use Illuminate\Http\Request;

interface PaymentGateway
{
    public function createPayment($model, $amount, $type): string;
    public function verifyPayment(Request $request): bool;
    public function getPaymentMethod(): string;
}
