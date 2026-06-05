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
            <div class="stat-icon">🏦</div>
            <div class="stat-value" style="color: var(--accent-primary);">R$ {{ number_format($stats['platform_fees'], 2, ',', '.') }}</div>
            <div class="stat-label">Minha Receita Líquida (Acumulada)</div>
        </div>
        <div class="stat-card" style="border-color: var(--accent-primary); background: linear-gradient(135deg, rgba(233, 30, 140, 0.05), transparent); display: flex; flex-direction: column; justify-content: space-between;">
            <div>
                <div class="stat-icon" style="color: var(--accent-primary); background: var(--accent-soft);">📈</div>
                <div class="stat-value" style="color: var(--accent-primary);">R$ {{ number_format($stats['monthly_earnings'], 2, ',', '.') }}</div>
                <div class="stat-label">Minha Receita Líquida (Este Mês)</div>
            </div>
            <a href="{{ route('admin.earnings') }}" class="btn btn-primary btn-sm" style="margin-top: var(--space-md); width: 100%; text-align: center; text-decoration: none;">
                💸 Ir para Sacar Lucro
            </a>
        </div>
        <div class="stat-card">
            <div class="stat-icon">💸</div>
            <div class="stat-value">R$ {{ number_format($stats['monthly_withdrawals'], 2, ',', '.') }}</div>
            <div class="stat-label">Saques Concluídos (Este Mês)</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">⌛</div>
            <div class="stat-value" style="color: var(--warning);">R$ {{ number_format($stats['total_pending_withdrawals'], 2, ',', '.') }}</div>
            <div class="stat-label">Saques Pendentes na Fila</div>
        </div>
    </div>

    {{-- ── Graphic Analysis ── --}}
    <div class="card mt-2xl" style="padding: var(--space-xl); background: var(--bg-card); border: 1px solid var(--border-primary); border-radius: var(--radius-lg);">
        <h3 style="margin-bottom: var(--space-lg); color: var(--text-primary); display: flex; align-items: center; gap: 8px;">📊 Desempenho Financeiro (Últimos 30 Dias)</h3>
        <div style="position: relative; height: 320px; width: 100%;">
            <canvas id="adminChart"></canvas>
        </div>
    </div>

    @push('styles')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endpush

    @push('styles')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('adminChart').getContext('2d');
            
            // Extract dark/light mode configurations if needed
            const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
            const gridColor = isDark ? 'rgba(255, 255, 255, 0.08)' : 'rgba(0, 0, 0, 0.06)';
            const textColor = isDark ? 'rgba(255, 255, 255, 0.7)' : 'rgba(0, 0, 0, 0.7)';

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! $stats['chart_labels'] !!},
                    datasets: [
                        {
                            label: 'Meu Lucro Líquido (R$)',
                            data: {!! $stats['chart_revenue'] !!},
                            borderColor: '#e91e8c',
                            backgroundColor: 'rgba(233, 30, 140, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.3,
                        },
                        {
                            label: 'Volume de Saques Criadores (R$)',
                            data: {!! $stats['chart_withdrawals'] !!},
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.05)',
                            borderWidth: 2,
                            fill: false,
                            tension: 0.3,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                color: textColor,
                                font: {
                                    family: 'Inter, system-ui, sans-serif',
                                    weight: 'bold'
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                color: gridColor
                            },
                            ticks: {
                                color: textColor
                            }
                        },
                        y: {
                            grid: {
                                color: gridColor
                            },
                            ticks: {
                                color: textColor,
                                callback: function(value) {
                                    return 'R$ ' + value;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
    @endpush

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
