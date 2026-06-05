<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Withdrawal;
use App\Models\Transaction;
use App\Services\Payments\AsaasGateway;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessWithdrawalJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Withdrawal $withdrawal;

    /**
     * Create a new job instance.
     */
    public function __construct(Withdrawal $withdrawal)
    {
        $this->withdrawal = $withdrawal;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Double check status before processing
        if ($this->withdrawal->status !== 'pending') {
            return;
        }

        $gateway = config('services.payments.gateway', 'mock');

        if ($gateway === 'asaas' && config('app.env') !== 'testing') {
            try {
                $asaas = new AsaasGateway();
                $transferResponse = $asaas->transfer(
                    $this->withdrawal->amount,
                    $this->withdrawal->pix_key_type,
                    $this->withdrawal->pix_key,
                    "Saque Automático Clube do Pack #{$this->withdrawal->id}"
                );

                if (!$transferResponse['success']) {
                    $this->failWithdrawal($transferResponse['error'] ?? 'Erro desconhecido na API da Asaas.');
                    return;
                }
            } catch (\Exception $e) {
                Log::error("Asaas automatic payout exception: " . $e->getMessage());
                $this->failWithdrawal($e->getMessage());
                return;
            }
        }

        // Successfully paid (or running mock in testing/dev)
        DB::transaction(function () {
            // Lock withdrawal to prevent updates
            $lockedWithdrawal = Withdrawal::where('id', $this->withdrawal->id)->lockForUpdate()->first();
            
            if ($lockedWithdrawal->status !== 'pending') {
                return;
            }

            $lockedWithdrawal->update(['status' => 'completed']);

            // Record transaction payout debit representation
            Transaction::create([
                'user_id' => $lockedWithdrawal->user_id,
                'type' => 'payout',
                'amount' => $lockedWithdrawal->amount,
                'platform_fee' => 0,
                'creator_amount' => -$lockedWithdrawal->amount, // negative debit
                'status' => 'completed',
                'description' => "Saque automático concluído via PIX ({$lockedWithdrawal->pix_key_type}: {$lockedWithdrawal->pix_key})",
                'transactionable_type' => Withdrawal::class,
                'transactionable_id' => $lockedWithdrawal->id,
            ]);
        });
    }

    /**
     * Fail the withdrawal and refund the creator.
     */
    protected function failWithdrawal(string $reason): void
    {
        DB::transaction(function () use ($reason) {
            $lockedWithdrawal = Withdrawal::where('id', $this->withdrawal->id)->lockForUpdate()->first();
            
            if ($lockedWithdrawal->status !== 'pending') {
                return;
            }

            $lockedWithdrawal->update([
                'status' => 'failed',
                'status_message' => "Falha no PIX automático: {$reason}"
            ]);

            // Safely refund the creator using lockForUpdate
            $creator = User::where('id', $lockedWithdrawal->user_id)->lockForUpdate()->first();
            if ($creator->isCreator()) {
                $creator->increment('balance_available', $lockedWithdrawal->amount);
            }
        });
    }
}
