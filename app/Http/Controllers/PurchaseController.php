<?php

namespace App\Http\Controllers;

use App\Models\Pack;
use App\Models\User;
use App\Models\Purchase;
use App\Models\Subscription;
use App\Models\Transaction;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
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

        $platformFeePercent = config('app.platform_fee_percent', 15);
        $platformFee = $pack->price * ($platformFeePercent / 100);
        $creatorAmount = $pack->price - $platformFee;

        // Create purchase (in production, this would go through the payment gateway)
        $purchase = Purchase::create([
            'user_id' => $user->id,
            'pack_id' => $pack->id,
            'amount_paid' => $pack->price,
            'status' => 'confirmed', // Mock: auto-confirm
        ]);

        // Simula o método de pagamento selecionado (50% de chance de ser Pix ou Cartão para fins de teste local do fluxo)
        $paymentMethod = rand(0, 1) === 0 ? 'pix' : 'card';

        // Create transaction record
        Transaction::create([
            'user_id' => $pack->user_id,
            'type' => 'purchase',
            'amount' => $pack->price,
            'platform_fee' => $platformFee,
            'creator_amount' => $creatorAmount,
            'status' => 'completed',
            'description' => "Venda do pack: {$pack->title} (" . ($paymentMethod === 'pix' ? 'Pix' : 'Cartão') . ")",
            'transactionable_type' => Purchase::class,
            'transactionable_id' => $purchase->id,
        ]);

        // Atualiza a carteira interna do Criador
        $creator = $pack->user;
        if ($paymentMethod === 'pix') {
            $creator->increment('balance_available', $creatorAmount);
        } else {
            $creator->increment('balance_pending', $creatorAmount);
        }

        $pack->increment('downloads_count');

        return back()->with('success', 'Pack comprado com sucesso! Agora você tem acesso ao conteúdo.');
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

        $platformFeePercent = config('app.platform_fee_percent', 15);
        $platformFee = $creator->subscription_price * ($platformFeePercent / 100);
        $creatorAmount = $creator->subscription_price - $platformFee;

        // Create subscription (mock: auto-activate)
        $subscription = Subscription::create([
            'subscriber_id' => $user->id,
            'creator_id' => $creator->id,
            'status' => 'active',
            'amount' => $creator->subscription_price,
            'starts_at' => now(),
            'expires_at' => now()->addMonth(),
        ]);

        // Simula o método de pagamento selecionado (50% de chance de ser Pix ou Cartão para fins de teste local do fluxo)
        $paymentMethod = rand(0, 1) === 0 ? 'pix' : 'card';

        // Create transaction record
        Transaction::create([
            'user_id' => $creator->id,
            'type' => 'subscription',
            'amount' => $creator->subscription_price,
            'platform_fee' => $platformFee,
            'creator_amount' => $creatorAmount,
            'status' => 'completed',
            'description' => "Assinatura de {$user->name} (" . ($paymentMethod === 'pix' ? 'Pix' : 'Cartão') . ")",
            'transactionable_type' => Subscription::class,
            'transactionable_id' => $subscription->id,
        ]);

        // Atualiza a carteira interna do Criador
        if ($paymentMethod === 'pix') {
            $creator->increment('balance_available', $creatorAmount);
        } else {
            $creator->increment('balance_pending', $creatorAmount);
        }

        return back()->with('success', 'Assinatura ativada com sucesso! Agora você tem acesso a todos os packs deste criador.');
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
            ->latest()
            ->get();

        return view('library', compact('purchases', 'subscriptions'));
    }
}
