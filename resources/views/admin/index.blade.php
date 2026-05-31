@extends('layouts.admin')

@section('title', 'Admin - Dashboard')

@section('admin-content')
    <h1 style="margin-bottom: var(--space-2xl);">🛡️ Painel Administrativo</h1>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">👥</div>
            <div class="stat-value">{{ $stats['total_users'] }}</div>
            <div class="stat-label">Usuários Totais</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">✨</div>
            <div class="stat-value">{{ $stats['total_creators'] }}</div>
            <div class="stat-label">Criadores</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">🛒</div>
            <div class="stat-value">{{ $stats['total_customers'] }}</div>
            <div class="stat-label">Clientes</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">📦</div>
            <div class="stat-value">{{ $stats['total_packs'] }}</div>
            <div class="stat-label">Packs</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">💰</div>
            <div class="stat-value">R$ {{ number_format($stats['total_revenue'], 2, ',', '.') }}</div>
            <div class="stat-label">Receita Total</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">🏦</div>
            <div class="stat-value">R$ {{ number_format($stats['platform_fees'], 2, ',', '.') }}</div>
            <div class="stat-label">Taxas da Plataforma</div>
        </div>
    </div>

    {{-- Recent Users --}}
    <div class="section-header mt-2xl">
        <h2 class="section-title">👥 Últimos Usuários</h2>
        <a href="{{ route('admin.users') }}" class="section-link">Ver Todos →</a>
    </div>
    <div class="table-wrapper mb-2xl">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Usuário</th>
                    <th>E-mail</th>
                    <th>Tipo</th>
                    <th>Data</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentUsers as $user)
                    <tr>
                        <td style="font-weight:600;">{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->role === 'admin')
                                <span class="badge badge-danger">Admin</span>
                            @elseif($user->role === 'creator')
                                <span class="badge badge-accent">Criador</span>
                            @else
                                <span class="badge badge-info">Cliente</span>
                            @endif
                        </td>
                        <td>{{ $user->created_at->format('d/m/Y') }}</td>
                        <td>
                            @if($user->is_active)
                                <span class="badge badge-success">Ativo</span>
                            @else
                                <span class="badge badge-danger">Inativo</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Recent Transactions --}}
    <div class="section-header">
        <h2 class="section-title">💳 Últimas Transações</h2>
        <a href="{{ route('admin.transactions') }}" class="section-link">Ver Todas →</a>
    </div>
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Usuário</th>
                    <th>Tipo</th>
                    <th>Valor</th>
                    <th>Taxa</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentTransactions as $transaction)
                    <tr>
                        <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $transaction->user->name ?? '-' }}</td>
                        <td>
                            @if($transaction->type === 'purchase')
                                <span class="badge badge-info">Compra</span>
                            @else
                                <span class="badge badge-accent">Assinatura</span>
                            @endif
                        </td>
                        <td>{{ $transaction->formatted_amount }}</td>
                        <td style="color:var(--success);">R$ {{ number_format($transaction->platform_fee, 2, ',', '.') }}</td>
                        <td>
                            <span class="badge badge-{{ $transaction->status === 'completed' ? 'success' : 'warning' }}">
                                {{ ucfirst($transaction->status) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" style="text-align:center;color:var(--text-tertiary);">Nenhuma transação ainda.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
