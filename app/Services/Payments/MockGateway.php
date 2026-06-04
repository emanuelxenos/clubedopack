<?php

namespace App\Services\Payments;

use App\Contracts\PaymentGatewayInterface;
use Illuminate\Support\Str;

class MockGateway implements PaymentGatewayInterface
{
    public function createOneTimePayment(array $data): array
    {
        $isPix = strtoupper($data['payment_method'] ?? '') === 'PIX';
        return [
            'success' => true,
            'payment_id' => 'mock_pay_' . Str::random(16),
            'status' => $isPix ? 'pending' : 'confirmed',
            'amount' => $data['amount'],
            'checkout_url' => null,
            'pix_qr_code' => $isPix ? '00020126580014br.gov.bcb.pix0136mockkey-1234-5678-90ab-cdef12345678520400005303986540510.005802BR5913Clube do Pack6009SAO PAULO62070503***6304abcd' : null,
            'pix_qr_code_base64' => $isPix ? 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==' : null,
        ];
    }

    public function createSubscription(array $data): array
    {
        $isPix = strtoupper($data['payment_method'] ?? '') === 'PIX';
        return [
            'success' => true,
            'subscription_id' => 'mock_sub_' . Str::random(16),
            'status' => $isPix ? 'pending' : 'active',
            'amount' => $data['amount'],
            'checkout_url' => null,
            'pix_qr_code' => $isPix ? '00020126580014br.gov.bcb.pix0136mockkey-1234-5678-90ab-cdef12345678520400005303986540510.005802BR5913Clube do Pack6009SAO PAULO62070503***6304abcd' : null,
            'pix_qr_code_base64' => $isPix ? 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==' : null,
        ];
    }

    public function cancelSubscription(string $subscriptionId): bool
    {
        return true;
    }

    public function handleWebhook(array $payload): array
    {
        return [
            'event' => $payload['event'] ?? 'unknown',
            'processed' => true,
        ];
    }

    public function getPaymentStatus(string $paymentId): string
    {
        return 'confirmed';
    }
}
