<?php

namespace App\Contracts;

interface PaymentGatewayInterface
{
    /**
     * Create a one-time payment for a pack purchase.
     */
    public function createOneTimePayment(array $data): array;

    /**
     * Create a recurring subscription.
     */
    public function createSubscription(array $data): array;

    /**
     * Cancel an existing subscription.
     */
    public function cancelSubscription(string $subscriptionId): bool;

    /**
     * Handle incoming webhook from the payment gateway.
     */
    public function handleWebhook(array $payload): array;

    /**
     * Get payment status.
     */
    public function getPaymentStatus(string $paymentId): string;
}
