<?php

namespace App\Services\Payments;

use App\Contracts\PaymentGatewayInterface;
use Illuminate\Support\Str;

class MockGateway implements PaymentGatewayInterface
{
    public function createOneTimePayment(array $data): array
    {
        return [
            'success' => true,
            'payment_id' => 'mock_pay_' . Str::random(16),
            'status' => 'confirmed',
            'amount' => $data['amount'],
            'checkout_url' => null,
        ];
    }

    public function createSubscription(array $data): array
    {
        return [
            'success' => true,
            'subscription_id' => 'mock_sub_' . Str::random(16),
            'status' => 'active',
            'amount' => $data['amount'],
            'checkout_url' => null,
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
