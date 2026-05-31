@extends('layouts.dashboard')

@section('title', 'Ganhos')

@section('dashboard-content')
    <h1 style="margin-bottom: var(--space-2xl);">💰 Meus Ganhos</h1>

    <div class="stats-grid" style="grid-template-columns: 1fr 1fr;">
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

    <h2 style="margin-bottom: var(--space-lg);">📋 Histórico de Transações</h2>

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
                                @else
                                    <span class="badge badge-warning">{{ ucfirst($transaction->type) }}</span>
                                @endif
                            </td>
                            <td>{{ $transaction->description ?? '-' }}</td>
                            <td>{{ $transaction->formatted_amount }}</td>
                            <td style="color:var(--danger);">- R$ {{ number_format($transaction->platform_fee, 2, ',', '.') }}</td>
                            <td style="color:var(--success);font-weight:600;">R$ {{ number_format($transaction->creator_amount, 2, ',', '.') }}</td>
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
