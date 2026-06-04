<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Subscription;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handleAsaas(Request $request)
    {
        $payload = $request->all();
        $event = $payload['event'] ?? '';
        $payment = $payload['payment'] ?? [];
        $externalRef = $payment['externalReference'] ?? '';

        Log::info("Asaas Webhook Received", [
            'event' => $event,
            'externalReference' => $externalRef,
            'paymentId' => $payment['id'] ?? null
        ]);

        // Verify access token if configured
        $configuredToken = config('services.asaas.webhook_token');
        $receivedToken = $request->header('asaas-access-token');
        if ($configuredToken && $receivedToken !== $configuredToken) {
            Log::warning("Asaas Webhook rejected: Invalid webhook token.");
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // We only care about payments that are captured/received
        if (!in_array($event, ['PAYMENT_RECEIVED', 'PAYMENT_CONFIRMED'])) {
            return response()->json(['status' => 'ignored']);
        }

        if (empty($externalRef)) {
            return response()->json(['status' => 'ignored', 'message' => 'No external reference provided']);
        }

        try {
            if (str_starts_with($externalRef, 'purchase_')) {
                $purchaseId = str_replace('purchase_', '', $externalRef);
                $this->confirmPurchase($purchaseId, $payment);
            } elseif (str_starts_with($externalRef, 'subscription_')) {
                $subscriptionId = str_replace('subscription_', '', $externalRef);
                $this->confirmSubscription($subscriptionId, $payment);
            } else {
                Log::warning("Unknown external reference in webhook: {$externalRef}");
                return response()->json(['status' => 'ignored']);
            }

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error("Error processing Asaas webhook: " . $e->getMessage(), [
                'exception' => $e
            ]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    protected function confirmPurchase($purchaseId, array $paymentData)
    {
        DB::transaction(function () use ($purchaseId, $paymentData) {
            $purchase = Purchase::where('id', $purchaseId)->lockForUpdate()->first();

            if (!$purchase) {
                throw new \Exception("Purchase ID {$purchaseId} not found");
            }

            if ($purchase->status === 'confirmed') {
                return; // Already confirmed
            }

            $pack = $purchase->pack;
            $platformFeePercent = config('app.platform_fee_percent', 15);
            $platformFee = $purchase->amount_paid * ($platformFeePercent / 100);
            $creatorAmount = $purchase->amount_paid - $platformFee;

            $purchase->update([
                'status' => 'confirmed',
                'gateway_id' => $paymentData['id'] ?? $purchase->gateway_id,
                'payment_status' => 'received',
            ]);

            // Create Transaction record
            Transaction::create([
                'user_id' => $pack->user_id,
                'type' => 'purchase',
                'amount' => $purchase->amount_paid,
                'platform_fee' => $platformFee,
                'creator_amount' => $creatorAmount,
                'status' => 'completed',
                'description' => "Venda do pack: {$pack->title} (" . (strtoupper($paymentData['billingType'] ?? '') === 'PIX' ? 'Pix' : 'Cartão') . ")",
                'transactionable_type' => Purchase::class,
                'transactionable_id' => $purchase->id,
            ]);

            // Update creator balance
            $creator = $pack->user;
            $billingType = strtoupper($paymentData['billingType'] ?? '');
            
            if ($billingType === 'PIX') {
                $creator->increment('balance_available', $creatorAmount);
            } else {
                $creator->increment('balance_pending', $creatorAmount);
            }

            $pack->increment('downloads_count');
            
            Log::info("Purchase confirmed via Webhook", ['purchase_id' => $purchase->id]);
        });
    }

    protected function confirmSubscription($subscriptionId, array $paymentData)
    {
        DB::transaction(function () use ($subscriptionId, $paymentData) {
            $subscription = Subscription::where('id', $subscriptionId)->lockForUpdate()->first();

            if (!$subscription) {
                throw new \Exception("Subscription ID {$subscriptionId} not found");
            }

            if ($subscription->status === 'active') {
                return; // Already active
            }

            $creator = $subscription->creator;
            $subscriber = $subscription->subscriber;
            $platformFeePercent = config('app.platform_fee_percent', 15);
            $platformFee = $subscription->amount * ($platformFeePercent / 100);
            $creatorAmount = $subscription->amount - $platformFee;

            $subscription->update([
                'status' => 'active',
                'gateway_id' => $paymentData['subscription'] ?? ($paymentData['id'] ?? $subscription->gateway_id),
                'payment_status' => 'received',
            ]);

            // Create Transaction record
            Transaction::create([
                'user_id' => $creator->id,
                'type' => 'subscription',
                'amount' => $subscription->amount,
                'platform_fee' => $platformFee,
                'creator_amount' => $creatorAmount,
                'status' => 'completed',
                'description' => "Assinatura de {$subscriber->name} (" . (strtoupper($paymentData['billingType'] ?? '') === 'PIX' ? 'Pix' : 'Cartão') . ")",
                'transactionable_type' => Subscription::class,
                'transactionable_id' => $subscription->id,
            ]);

            // Update creator balance
            $billingType = strtoupper($paymentData['billingType'] ?? '');
            
            if ($billingType === 'PIX') {
                $creator->increment('balance_available', $creatorAmount);
            } else {
                $creator->increment('balance_pending', $creatorAmount);
            }

            Log::info("Subscription activated via Webhook", ['subscription_id' => $subscription->id]);
        });
    }
}
