@extends('layouts.dashboard')

@section('title', 'Ganhos e Carteira')

@section('dashboard-content')
    <h1 style="margin-bottom: var(--space-2xl);">💰 Meus Ganhos e Carteira</h1>

    {{-- Stats Grid 1: Revenue --}}
    <h3 style="margin-bottom: var(--space-md); font-size: 1.1rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em;">📈 Histórico de Receita</h3>
    <div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: var(--space-lg); margin-bottom: var(--space-xl);">
        <div class="stat-card">
            <div class="stat-icon">💰</div>
            <div class="stat-value">R$ {{ number_format($totalEarnings, 2, ',', '.') }}</div>
            <div class="stat-label">Receita Total</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">📅</div>
            <div class="stat-value">R$ {{ number_format($monthlyEarnings, 2, ',', '.') }}</div>
            <div class="stat-label">Este Mês</div>
        </div>
    </div>

    {{-- Stats Grid 2: Balances --}}
    <h3 style="margin-bottom: var(--space-md); font-size: 1.1rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em;">💳 Minha Carteira</h3>
    <div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: var(--space-lg); margin-bottom: var(--space-2xl);">
        <div class="stat-card" style="border: 1px solid rgba(46, 204, 113, 0.25); background: rgba(46, 204, 113, 0.02);">
            <div class="stat-icon" style="color: #2ecc71;">✓</div>
            <div class="stat-value" style="color: #2ecc71;">R$ {{ number_format(auth()->user()->balance_available, 2, ',', '.') }}</div>
            <div class="stat-label">Saldo Disponível (PIX)</div>
        </div>
        <div class="stat-card" style="border: 1px solid rgba(241, 196, 15, 0.25); background: rgba(241, 196, 15, 0.02);">
            <div class="stat-icon" style="color: #f1c40f;">⏳</div>
            <div class="stat-value" style="color: #f1c40f;">R$ {{ number_format(auth()->user()->balance_pending, 2, ',', '.') }}</div>
            <div class="stat-label">Saldo Pendente (Custódia Cartão)</div>
        </div>
    </div>

    {{-- Solicitação de Saque --}}
    <div class="card" style="padding: var(--space-xl); margin-bottom: var(--space-2xl); border: 1px solid rgba(255,255,255,0.05);">
        <h3 style="margin-top: 0; margin-bottom: var(--space-md); font-size: 1.25rem;">💸 Solicitar Saque via PIX</h3>
        
        @if(empty(auth()->user()->pix_key) || empty(auth()->user()->pix_key_type))
            <div style="background: rgba(231, 76, 60, 0.1); border: 1px solid rgba(231, 76, 60, 0.2); padding: var(--space-lg); border-radius: var(--radius-md); display: flex; align-items: center; gap: 15px;">
                <span style="font-size: 1.5rem;">⚠️</span>
                <div>
                    <h4 style="margin: 0; color: var(--danger); font-size: 0.95rem;">Chave PIX não cadastrada</h4>
                    <p style="margin: 4px 0 0 0; font-size: 0.85rem; color: var(--text-secondary);">
                        Você precisa configurar seus dados de PIX no seu <a href="{{ route('dashboard.profile') }}" style="color: var(--accent-primary); text-decoration: underline; font-weight: 600;">Perfil de Criador</a> antes de poder solicitar saques.
                    </p>
                </div>
            </div>
        @else
            <div style="display: grid; grid-template-columns: 1fr 1.5fr; gap: var(--space-xl); align-items: start;">
                <div>
                    <p style="margin-top: 0; font-size: 0.85rem; color: var(--text-secondary); line-height: 1.6;">
                        * O saque mínimo é de <strong>R$ {{ number_format(config('app.min_withdrawal_amount', 50.00), 2, ',', '.') }}</strong>.<br>
                        * O valor do saque será enviado para a sua chave cadastrada:<br>
                        <strong>{{ strtoupper(auth()->user()->pix_key_type) }}:</strong> <code style="background: rgba(255,255,255,0.05); padding: 2px 6px; border-radius: 4px; font-family: monospace;">{{ auth()->user()->pix_key }}</code>.
                    </p>
                </div>
                
                @if(auth()->user()->balance_available < config('app.min_withdrawal_amount', 50.00))
                    <div style="background: rgba(231, 76, 60, 0.08); border: 1px solid rgba(231, 76, 60, 0.15); padding: var(--space-lg); border-radius: var(--radius-md); width: 100%;">
                        <p style="margin: 0; font-size: 0.9rem; color: #ff6b6b; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                            <span>⚠️</span> Saldo insuficiente para saque. Você precisa de pelo menos R$ {{ number_format(config('app.min_withdrawal_amount', 50.00), 2, ',', '.') }} de saldo disponível (seu saldo atual: R$ {{ number_format(auth()->user()->balance_available, 2, ',', '.') }}).
                        </p>
                    </div>
                @else
                    <form action="{{ route('dashboard.withdraw') }}" method="POST" style="display: flex; gap: var(--space-md); align-items: flex-end; width: 100%;">
                        @csrf
                        <div style="flex: 1;">
                            <label class="form-label" style="display: block; margin-bottom: 6px; font-size: 0.85rem;">Valor para Saque (R$)</label>
                            <input type="number" name="amount" class="form-input" min="{{ config('app.min_withdrawal_amount', 50.00) }}" max="{{ auth()->user()->balance_available }}" step="0.01" required placeholder="0,00" style="font-size: 1.1rem; font-weight: bold; color: #fff;">
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg" style="height: 48px; white-space: nowrap;">
                            Solicitar Resgate
                        </button>
                    </form>
                @endif
            </div>
        @endif
    </div>

    {{-- Histórico de Saques --}}
    @if($withdrawals->count())
        <h2 style="margin-bottom: var(--space-lg);">📋 Histórico de Saques</h2>
        <div class="table-wrapper" style="margin-bottom: var(--space-2xl);">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Solicitado em</th>
                        <th>Chave PIX</th>
                        <th>Valor</th>
                        <th>Status</th>
                        <th>Observação</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($withdrawals as $withdrawal)
                        <tr>
                            <td>{{ $withdrawal->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <span class="badge" style="background: rgba(255,255,255,0.05); font-family: monospace;">
                                    {{ strtoupper($withdrawal->pix_key_type) }}: {{ $withdrawal->pix_key }}
                                </span>
                            </td>
                            <td style="font-weight:600;">R$ {{ number_format($withdrawal->amount, 2, ',', '.') }}</td>
                            <td>
                                @if($withdrawal->status === 'completed')
                                    <span class="badge badge-success">Pago</span>
                                @elseif($withdrawal->status === 'pending')
                                    <span class="badge badge-warning">Processando</span>
                                @else
                                    <span class="badge badge-danger">Rejeitado</span>
                                @endif
                            </td>
                            <td style="font-size:0.85rem; color:var(--text-secondary);">{{ $withdrawal->status_message ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    {{-- Histórico de Transações (Receitas) --}}
    <h2 style="margin-bottom: var(--space-lg);">📋 Histórico de Transações (Vendas)</h2>
    @if($transactions->count())
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Tipo</th>
                        <th>Descrição</th>
                        <th>Valor Total</th>
                        <th>Taxa Plataforma</th>
                        <th>Seu Ganho</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                @if($transaction->type === 'purchase')
                                    <span class="badge badge-info">Venda</span>
                                @elseif($transaction->type === 'subscription')
                                    <span class="badge badge-accent">Assinatura</span>
                                @elseif($transaction->type === 'withdrawal')
                                    <span class="badge" style="background: #e74c3c;">Saque</span>
                                @else
                                    <span class="badge badge-warning">{{ ucfirst($transaction->type) }}</span>
                                @endif
                            </td>
                            <td>{{ $transaction->description ?? '-' }}</td>
                            <td>
                                @if($transaction->type === 'withdrawal')
                                    <span style="color:var(--danger);">- {{ $transaction->formatted_amount }}</span>
                                @else
                                    {{ $transaction->formatted_amount }}
                                @endif
                            </td>
                            <td style="color:var(--danger);">
                                @if($transaction->type === 'withdrawal')
                                    -
                                @else
                                    - R$ {{ number_format($transaction->platform_fee, 2, ',', '.') }}
                                @endif
                            </td>
                            <td style="{{ $transaction->creator_amount < 0 ? 'color:var(--danger);' : 'color:var(--success);' }} font-weight:600;">
                                R$ {{ number_format($transaction->creator_amount, 2, ',', '.') }}
                            </td>
                            <td>
                                @if($transaction->status === 'completed')
                                    <span class="badge badge-success">Concluído</span>
                                @elseif($transaction->status === 'pending')
                                    <span class="badge badge-warning">Pendente</span>
                                @else
                                    <span class="badge badge-danger">{{ ucfirst($transaction->status) }}</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($transactions->hasPages())
            <div class="pagination">
                {{ $transactions->links('pagination.custom') }}
            </div>
        @endif
    @else
        <div class="empty-state">
            <div class="empty-icon">💰</div>
            <h3>Nenhuma transação ainda</h3>
            <p>Quando seus packs forem vendidos, as transações aparecerão aqui.</p>
        </div>
    @endif
@endsection
