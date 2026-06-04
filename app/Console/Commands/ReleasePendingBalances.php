<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReleasePendingBalances extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:release-pending-balances';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Libera saldos pendentes de compras no cartão de crédito após o período de custódia (30 dias)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Iniciando liberação de saldos pendentes...");

        // Query transactions older than 30 days that haven't been released yet
        $transactions = Transaction::where('status', 'completed')
            ->where('is_released', false)
            ->where('created_at', '<=', now()->subDays(30))
            ->whereIn('type', ['purchase', 'subscription'])
            ->with('transactionable')
            ->get();

        $count = 0;

        foreach ($transactions as $transaction) {
            $paymentMethod = null;
            if ($transaction->transactionable) {
                $paymentMethod = $transaction->transactionable->payment_method ?? null;
            }

            // Defensive check: If it was a Pix transaction, just mark it released without modifying balances
            if ($paymentMethod === 'pix') {
                $transaction->update(['is_released' => true]);
                continue;
            }

            try {
                DB::transaction(function () use ($transaction, &$count) {
                    $creator = User::where('id', $transaction->user_id)->lockForUpdate()->first();

                    if ($creator) {
                        $amount = $transaction->creator_amount;

                        // Ensure we don't decrement pending below zero
                        if ($creator->balance_pending >= $amount) {
                            $creator->decrement('balance_pending', $amount);
                        } else {
                            $creator->balance_pending = 0;
                        }

                        $creator->increment('balance_available', $amount);
                        $creator->save();
                    }

                    $transaction->update(['is_released' => true]);
                    $count++;

                    Log::info("Saldo liberado para o criador", [
                        'creator_id' => $transaction->user_id,
                        'transaction_id' => $transaction->id,
                        'amount' => $transaction->creator_amount
                    ]);
                });
            } catch (\Exception $e) {
                Log::error("Falha ao liberar transação ID {$transaction->id}: " . $e->getMessage());
                $this->error("Erro na transação ID {$transaction->id}: {$e->getMessage()}");
            }
        }

        $this->info("Finalizado. {$count} saldos foram liberados com sucesso.");
    }
}
