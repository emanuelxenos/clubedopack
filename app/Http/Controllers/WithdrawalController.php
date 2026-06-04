<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Withdrawal;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class WithdrawalController extends Controller
{
    public function store(Request $request)
    {
        $user = auth()->user();

        if (!$user->isCreator()) {
            abort(403);
        }

        if (empty($user->pix_key) || empty($user->pix_key_type)) {
            return back()->with('error', 'Por favor, configure sua chave PIX no seu perfil antes de solicitar um saque.');
        }

        $request->validate([
            'amount' => 'required|numeric|min:5.00', // Mínimo de R$ 5,00 para saque
        ]);

        $amount = $request->amount;

        try {
            DB::transaction(function () use ($user, $amount) {
                // Lock the user row to prevent concurrency issues
                $lockedUser = User::where('id', $user->id)->lockForUpdate()->first();

                if ($lockedUser->balance_available < $amount) {
                    throw ValidationException::withMessages([
                        'amount' => ['Saldo disponível insuficiente para realizar este saque.'],
                    ]);
                }

                // Deduct balance
                $lockedUser->decrement('balance_available', $amount);

                // Create withdrawal record
                $withdrawal = Withdrawal::create([
                    'user_id' => $lockedUser->id,
                    'amount' => $amount,
                    'pix_key_type' => $lockedUser->pix_key_type,
                    'pix_key' => $lockedUser->pix_key,
                    'status' => 'pending',
                ]);

                // Dispatch the automatic queue job to send PIX instantly
                \App\Jobs\ProcessWithdrawalJob::dispatch($withdrawal);
            });

            return back()->with('success', 'Solicitação de saque recebida! O PIX está sendo transferido para sua conta agora mesmo.');
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return back()->with('error', 'Falha ao processar solicitação de saque: ' . $e->getMessage());
        }
    }

    // Admin Methods
    public function adminIndex()
    {
        $withdrawals = Withdrawal::with('user')->latest()->paginate(15);
        return view('admin.withdrawals', compact('withdrawals'));
    }

    public function adminApprove(Request $request, Withdrawal $withdrawal)
    {
        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Este saque já foi processado.');
        }

        // If the gateway is Asaas, trigger the transfer automatically via API
        if (config('services.payments.gateway') === 'asaas' && config('app.env') !== 'testing') {
            $asaas = new \App\Services\Payments\AsaasGateway();
            $transferResponse = $asaas->transfer(
                $withdrawal->amount,
                $withdrawal->pix_key_type,
                $withdrawal->pix_key,
                "Saque Clube do Pack #{$withdrawal->id}"
            );

            if (!$transferResponse['success']) {
                return back()->with('error', 'Falha ao processar transferência via Asaas: ' . ($transferResponse['error'] ?? 'Erro desconhecido.'));
            }
        }

        DB::transaction(function () use ($withdrawal) {
            $withdrawal->update(['status' => 'completed']);

            // Record transaction
            Transaction::create([
                'user_id' => $withdrawal->user_id,
                'type' => 'payout',
                'amount' => $withdrawal->amount,
                'platform_fee' => 0,
                'creator_amount' => -$withdrawal->amount, // negative debit
                'status' => 'completed',
                'description' => "Saque concluído via PIX ({$withdrawal->pix_key_type}: {$withdrawal->pix_key})",
                'transactionable_type' => Withdrawal::class,
                'transactionable_id' => $withdrawal->id,
            ]);
        });

        return back()->with('success', 'Saque processado e transferido via PIX com sucesso!');
    }

    public function adminReject(Request $request, Withdrawal $withdrawal)
    {
        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Este saque já foi processado.');
        }

        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        DB::transaction(function () use ($withdrawal, $request) {
            $withdrawal->update([
                'status' => 'failed',
                'status_message' => $request->reason,
            ]);

            // Devolve the funds to availability
            $creator = $withdrawal->user;
            $creator->increment('balance_available', $withdrawal->amount);
        });

        return back()->with('success', 'Saque rejeitado e saldo estornado para o criador.');
    }
}
