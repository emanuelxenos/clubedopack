<?php

namespace App\Http\Controllers;

use App\Models\Pack;
use App\Models\User;
use App\Models\Purchase;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Contracts\PaymentGatewayInterface;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    protected PaymentGatewayInterface $paymentGateway;

    public function __construct(PaymentGatewayInterface $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }

    public function purchasePack(Request $request, Pack $pack)
    {
        $user = auth()->user();

        // Check if already purchased
        if ($user->hasPurchased($pack)) {
            return back()->with('info', 'Você já comprou este pack!');
        }

        // Check if subscribed to creator
        if ($user->isSubscribedTo($pack->user)) {
            return back()->with('info', 'Você já tem acesso a este pack pela sua assinatura!');
        }

        $request->validate([
            'payment_method' => 'required|string|in:pix,credit_card',
            'card_number' => 'required_if:payment_method,credit_card|string',
            'card_name' => 'required_if:payment_method,credit_card|string',
            'card_expiry' => 'required_if:payment_method,credit_card|string',
            'card_cvv' => 'required_if:payment_method,credit_card|string|max:4',
            'holder_cpf' => 'required_if:payment_method,credit_card|string',
            'holder_phone' => 'required_if:payment_method,credit_card|string',
            'holder_zip' => 'required_if:payment_method,credit_card|string',
            'holder_address_num' => 'required_if:payment_method,credit_card|string',
        ]);

        $platformFeePercent = config('app.platform_fee_percent', 15);
        $platformFee = $pack->price * ($platformFeePercent / 100);
        $creatorAmount = $pack->price - $platformFee;

        // Create purchase draft
        $purchase = Purchase::create([
            'user_id' => $user->id,
            'pack_id' => $pack->id,
            'amount_paid' => $pack->price,
            'status' => 'pending',
            'payment_method' => $request->payment_method,
        ]);

        $expiryMonth = '';
        $expiryYear = '';
        if ($request->payment_method === 'credit_card' && $request->has('card_expiry')) {
            $parts = explode('/', $request->card_expiry);
            $expiryMonth = trim($parts[0] ?? '');
            $expiryYear = trim($parts[1] ?? '');
            if (strlen($expiryYear) === 2) {
                $expiryYear = '20' . $expiryYear;
            }
        }

        $paymentData = [
            'amount' => $pack->price,
            'payment_method' => $request->payment_method,
            'description' => "Venda do pack: {$pack->title}",
            'external_reference' => 'purchase_' . $purchase->id,
            'customer' => [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => preg_replace('/\D/', '', $request->holder_phone ?? ''),
            ],
        ];

        if ($pack->user->split_account_id) {
            $paymentData['split_wallet_id'] = $pack->user->split_account_id;
            $paymentData['split_value'] = $creatorAmount;
        }

        if ($request->payment_method === 'credit_card') {
            $paymentData['credit_card'] = [
                'holderName' => $request->card_name,
                'number' => preg_replace('/\D/', '', $request->card_number),
                'expiryMonth' => $expiryMonth,
                'expiryYear' => $expiryYear,
                'ccv' => $request->card_cvv,
            ];
            $paymentData['credit_card_holder'] = [
                'name' => $request->card_name,
                'email' => $user->email,
                'cpfCnpj' => preg_replace('/\D/', '', $request->holder_cpf),
                'postalCode' => preg_replace('/\D/', '', $request->holder_zip),
                'addressNumber' => $request->holder_address_num,
                'phone' => preg_replace('/\D/', '', $request->holder_phone),
            ];
        }

        $response = $this->paymentGateway->createOneTimePayment($paymentData);

        if (!$response['success']) {
            $purchase->update(['status' => 'failed']);
            return back()->with('error', 'Falha no processamento do pagamento: ' . ($response['error'] ?? 'Erro desconhecido.'));
        }

        $purchase->update([
            'gateway_id' => $response['payment_id'] ?? null,
            'pix_qr_code' => $response['pix_qr_code'] ?? null,
            'pix_qr_code_base64' => $response['pix_qr_code_base64'] ?? null,
            'payment_status' => $response['status'] ?? 'pending',
        ]);

        if ($request->payment_method === 'credit_card') {
            if (in_array($response['status'], ['confirmed', 'received'])) {
                $purchase->update(['status' => 'confirmed']);

                Transaction::create([
                    'user_id' => $pack->user_id,
                    'type' => 'purchase',
                    'amount' => $pack->price,
                    'platform_fee' => $platformFee,
                    'creator_amount' => $creatorAmount,
                    'status' => 'completed',
                    'description' => "Venda do pack: {$pack->title} (Cartão)",
                    'transactionable_type' => Purchase::class,
                    'transactionable_id' => $purchase->id,
                ]);

                $pack->user->increment('balance_pending', $creatorAmount);
                $pack->increment('downloads_count');

                return back()->with('success', 'Pagamento em cartão aprovado com sucesso! Pack liberado.');
            } else {
                return back()->with('info', 'Seu pagamento com cartão está em análise. O pack será liberado assim que for confirmado.');
            }
        } else {
            return back()->with([
                'success' => 'Cobrança Pix gerada com sucesso!',
                'show_pix_modal' => true,
                'pix_copy_paste' => $response['pix_qr_code'],
                'pix_qr_base64' => $response['pix_qr_code_base64'],
                'purchase_id' => $purchase->id,
                'pack_title' => $pack->title,
                'pack_price' => $pack->price,
            ]);
        }
    }

    public function subscribe(Request $request, User $creator)
    {
        $user = auth()->user();

        if (!$creator->isCreator()) {
            abort(404);
        }

        if (!$creator->subscription_price || $creator->subscription_price <= 0) {
            return back()->with('error', 'Este criador não oferece assinatura.');
        }

        // Check if already subscribed
        if ($user->isSubscribedTo($creator)) {
            return back()->with('info', 'Você já é assinante deste criador!');
        }

        $request->validate([
            'payment_method' => 'required|string|in:pix,credit_card',
            'card_number' => 'required_if:payment_method,credit_card|string',
            'card_name' => 'required_if:payment_method,credit_card|string',
            'card_expiry' => 'required_if:payment_method,credit_card|string',
            'card_cvv' => 'required_if:payment_method,credit_card|string|max:4',
            'holder_cpf' => 'required_if:payment_method,credit_card|string',
            'holder_phone' => 'required_if:payment_method,credit_card|string',
            'holder_zip' => 'required_if:payment_method,credit_card|string',
            'holder_address_num' => 'required_if:payment_method,credit_card|string',
        ]);

        $platformFeePercent = config('app.platform_fee_percent', 15);
        $platformFee = $creator->subscription_price * ($platformFeePercent / 100);
        $creatorAmount = $creator->subscription_price - $platformFee;

        // Create subscription draft
        $subscription = Subscription::create([
            'subscriber_id' => $user->id,
            'creator_id' => $creator->id,
            'status' => 'pending',
            'amount' => $creator->subscription_price,
            'starts_at' => now(),
            'expires_at' => now()->addMonth(),
            'payment_method' => $request->payment_method,
        ]);

        $expiryMonth = '';
        $expiryYear = '';
        if ($request->payment_method === 'credit_card' && $request->has('card_expiry')) {
            $parts = explode('/', $request->card_expiry);
            $expiryMonth = trim($parts[0] ?? '');
            $expiryYear = trim($parts[1] ?? '');
            if (strlen($expiryYear) === 2) {
                $expiryYear = '20' . $expiryYear;
            }
        }

        $paymentData = [
            'amount' => $creator->subscription_price,
            'payment_method' => $request->payment_method,
            'description' => "Assinatura mensal de {$user->name} para {$creator->name}",
            'external_reference' => 'subscription_' . $subscription->id,
            'customer' => [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => preg_replace('/\D/', '', $request->holder_phone ?? ''),
            ],
        ];

        if ($creator->split_account_id) {
            $paymentData['split_wallet_id'] = $creator->split_account_id;
            $paymentData['split_value'] = $creatorAmount;
        }

        if ($request->payment_method === 'credit_card') {
            $paymentData['credit_card'] = [
                'holderName' => $request->card_name,
                'number' => preg_replace('/\D/', '', $request->card_number),
                'expiryMonth' => $expiryMonth,
                'expiryYear' => $expiryYear,
                'ccv' => $request->card_cvv,
            ];
            $paymentData['credit_card_holder'] = [
                'name' => $request->card_name,
                'email' => $user->email,
                'cpfCnpj' => preg_replace('/\D/', '', $request->holder_cpf),
                'postalCode' => preg_replace('/\D/', '', $request->holder_zip),
                'addressNumber' => $request->holder_address_num,
                'phone' => preg_replace('/\D/', '', $request->holder_phone),
            ];
        }

        $response = $this->paymentGateway->createSubscription($paymentData);

        if (!$response['success']) {
            $subscription->update(['status' => 'inactive']);
            return back()->with('error', 'Falha ao processar assinatura: ' . ($response['error'] ?? 'Erro desconhecido.'));
        }

        $subscription->update([
            'gateway_id' => $response['subscription_id'] ?? ($response['payment_id'] ?? null),
            'pix_qr_code' => $response['pix_qr_code'] ?? null,
            'pix_qr_code_base64' => $response['pix_qr_code_base64'] ?? null,
            'payment_status' => $response['status'] ?? 'pending',
        ]);

        if ($request->payment_method === 'credit_card') {
            if (in_array($response['status'], ['active', 'confirmed', 'received'])) {
                $subscription->update(['status' => 'active']);

                Transaction::create([
                    'user_id' => $creator->id,
                    'type' => 'subscription',
                    'amount' => $creator->subscription_price,
                    'platform_fee' => $platformFee,
                    'creator_amount' => $creatorAmount,
                    'status' => 'completed',
                    'description' => "Assinatura de {$user->name} (Cartão)",
                    'transactionable_type' => Subscription::class,
                    'transactionable_id' => $subscription->id,
                ]);

                $creator->increment('balance_pending', $creatorAmount);

                return back()->with('success', 'Assinatura ativada com sucesso! Acesso liberado.');
            } else {
                return back()->with('info', 'Sua assinatura com cartão está em análise. O acesso será liberado assim que for confirmado.');
            }
        } else {
            return back()->with([
                'success' => 'Cobrança Pix de Assinatura gerada!',
                'show_pix_modal' => true,
                'pix_copy_paste' => $response['pix_qr_code'],
                'pix_qr_base64' => $response['pix_qr_code_base64'],
                'subscription_id' => $subscription->id,
                'pack_title' => "Assinatura: {$creator->name}",
                'pack_price' => $creator->subscription_price,
            ]);
        }
    }

    public function checkPurchaseStatus(Purchase $purchase)
    {
        if ($purchase->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json([
            'status' => $purchase->status,
        ]);
    }

    public function checkSubscriptionStatus(Subscription $subscription)
    {
        if ($subscription->subscriber_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json([
            'status' => $subscription->status,
        ]);
    }

    public function library()
    {
        $user = auth()->user();

        $purchases = $user->purchases()
            ->where('status', 'confirmed')
            ->with(['pack' => fn($q) => $q->with('user')])
            ->latest()
            ->paginate(12);

        $subscriptions = $user->subscriptionsAsSubscriber()
            ->with('creator')
            ->where('status', 'active')
            ->latest()
            ->get();

        return view('library', compact('purchases', 'subscriptions'));
    }
}
