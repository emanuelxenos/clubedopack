<?php

namespace App\Services\Payments;

use App\Contracts\PaymentGatewayInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AsaasGateway implements PaymentGatewayInterface
{
    protected string $apiKey;
    protected string $apiUrl;

    public function __construct()
    {
        $this->apiKey = config('services.asaas.key', '');
        $this->apiUrl = config('services.asaas.url', 'https://sandbox.asaas.com/api/v3');
    }

    protected function request(string $method, string $endpoint, array $data = []): array
    {
        $url = rtrim($this->apiUrl, '/') . '/' . ltrim($endpoint, '/');
        
        $response = Http::withHeaders([
            'access_token' => $this->apiKey,
        ])->$method($url, $data);

        if ($response->failed()) {
            Log::error("Asaas API Request Failed", [
                'url' => $url,
                'method' => $method,
                'data' => $data,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            throw new \Exception("Erro na integração com gateway de pagamento: " . ($response->json('errors.0.description') ?? 'Erro inesperado.'));
        }

        return $response->json();
    }

    protected function getOrCreateCustomer(array $customerData): string
    {
        $email = $customerData['email'];
        
        try {
            // Search for existing customer
            $search = $this->request('get', 'customers', ['email' => $email]);
            if (!empty($search['data'])) {
                return $search['data'][0]['id'];
            }
        } catch (\Exception $e) {
            // Log and fall back to create
            Log::warning("Asaas Customer search failed, attempting to create: " . $e->getMessage());
        }

        // Create customer
        $create = $this->request('post', 'customers', [
            'name' => $customerData['name'],
            'email' => $email,
            'mobilePhone' => $customerData['phone'] ?? null,
        ]);

        return $create['id'];
    }

    public function createOneTimePayment(array $data): array
    {
        try {
            $customerId = $this->getOrCreateCustomer($data['customer']);

            $payload = [
                'customer' => $customerId,
                'billingType' => strtoupper($data['payment_method']), // PIX or CREDIT_CARD
                'value' => $data['amount'],
                'dueDate' => now()->addDays(1)->format('Y-m-d'), // Pix valid for 1 day
                'description' => $data['description'] ?? 'Compra de Conteúdo',
                'externalReference' => $data['external_reference'],
            ];

            // If split is configured
            if (!empty($data['split_wallet_id']) && !empty($data['split_value'])) {
                $payload['split'] = [
                    [
                        'walletId' => $data['split_wallet_id'],
                        'fixedValue' => $data['split_value']
                    ]
                ];
            }

            // If Credit Card details are provided
            if (strtoupper($data['payment_method']) === 'CREDIT_CARD' && !empty($data['credit_card'])) {
                $payload['creditCard'] = $data['credit_card'];
                $payload['creditCardHolderInfo'] = $data['credit_card_holder'];
                // For credit card, due date is today
                $payload['dueDate'] = now()->format('Y-m-d');
            }

            $response = $this->request('post', 'payments', $payload);

            $result = [
                'success' => true,
                'payment_id' => $response['id'],
                'status' => strtolower($response['status']), // confirmed, pending, etc.
                'amount' => $response['value'],
                'checkout_url' => $response['invoiceUrl'] ?? null,
                'pix_qr_code' => null,
                'pix_qr_code_base64' => null,
            ];

            // If PIX, fetch the QR Code details
            if (strtoupper($data['payment_method']) === 'PIX') {
                $pixResponse = $this->request('get', "payments/{$response['id']}/pixQrCode");
                $result['pix_qr_code'] = $pixResponse['payload'];
                $result['pix_qr_code_base64'] = $pixResponse['encodedImage'];
            }

            return $result;
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    public function createSubscription(array $data): array
    {
        try {
            $customerId = $this->getOrCreateCustomer($data['customer']);

            $payload = [
                'customer' => $customerId,
                'billingType' => strtoupper($data['payment_method']), // PIX or CREDIT_CARD
                'value' => $data['amount'],
                'nextDueDate' => now()->addDays(1)->format('Y-m-d'),
                'cycle' => 'MONTHLY',
                'description' => $data['description'] ?? 'Assinatura Mensal',
                'externalReference' => $data['external_reference'],
            ];

            if (!empty($data['split_wallet_id']) && !empty($data['split_value'])) {
                $payload['split'] = [
                    [
                        'walletId' => $data['split_wallet_id'],
                        'fixedValue' => $data['split_value']
                    ]
                ];
            }

            if (strtoupper($data['payment_method']) === 'CREDIT_CARD' && !empty($data['credit_card'])) {
                $payload['creditCard'] = $data['credit_card'];
                $payload['creditCardHolderInfo'] = $data['credit_card_holder'];
                $payload['nextDueDate'] = now()->format('Y-m-d');
            }

            $response = $this->request('post', 'subscriptions', $payload);

            $result = [
                'success' => true,
                'subscription_id' => $response['id'],
                'status' => strtolower($response['status']),
                'amount' => $response['value'],
                'checkout_url' => $response['invoiceUrl'] ?? null,
                'pix_qr_code' => null,
                'pix_qr_code_base64' => null,
            ];

            // If PIX, we need to get the QR code for the first pending invoice
            if (strtoupper($data['payment_method']) === 'PIX') {
                // Fetch subscription invoices
                $invoices = $this->request('get', "subscriptions/{$response['id']}/payments");
                if (!empty($invoices['data'])) {
                    $invoiceId = $invoices['data'][0]['id'];
                    $pixResponse = $this->request('get', "payments/{$invoiceId}/pixQrCode");
                    $result['pix_qr_code'] = $pixResponse['payload'];
                    $result['pix_qr_code_base64'] = $pixResponse['encodedImage'];
                    // Store the payment ID of the first invoice as reference
                    $result['payment_id'] = $invoiceId;
                }
            }

            return $result;
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    public function cancelSubscription(string $subscriptionId): bool
    {
        try {
            $this->request('delete', "subscriptions/{$subscriptionId}");
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to cancel subscription on Asaas", [
                'subscription_id' => $subscriptionId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    public function handleWebhook(array $payload): array
    {
        $event = $payload['event'] ?? 'unknown';
        
        return [
            'event' => $event,
            'processed' => true,
        ];
    }

    public function getPaymentStatus(string $paymentId): string
    {
        try {
            $response = $this->request('get', "payments/{$paymentId}");
            return strtolower($response['status']);
        } catch (\Exception $e) {
            return 'failed';
        }
    }

    public function transfer(float $amount, string $pixKeyType, string $pixKey, string $description = ''): array
    {
        // Map app types to Asaas API types: CPF, CNPJ, EMAIL, PHONE, RANDOM_KEY (EVP)
        $typeMap = [
            'cpf' => 'CPF',
            'cnpj' => 'CNPJ',
            'email' => 'EMAIL',
            'phone' => 'PHONE',
            'random' => 'EVP',
            'evp' => 'EVP',
        ];
        
        $mappedType = $typeMap[strtolower($pixKeyType)] ?? 'EVP';

        $payload = [
            'value' => $amount,
            'pixAddressKey' => $pixKey,
            'pixAddressKeyType' => $mappedType,
            'description' => $description ?: 'Saque Clube do Pack',
        ];

        try {
            $response = $this->request('post', 'transfers', $payload);
            return [
                'success' => true,
                'transfer_id' => $response['id'] ?? null,
                'status' => $response['status'] ?? 'pending',
            ];
        } catch (\Exception $e) {
            Log::error("Asaas transfer API failure: " . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    public function getWalletBalance(): float
    {
        try {
            $response = $this->request('get', 'finance/balance');
            return (float) ($response['balance'] ?? 0.00);
        } catch (\Exception $e) {
            Log::error("Failed to fetch wallet balance from Asaas: " . $e->getMessage());
            return 0.00;
        }
    }
}
