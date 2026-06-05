@extends('layouts.admin')

@section('title', 'Admin - Meus Ganhos')

@section('admin-content')
    <h1 style="margin-bottom: var(--space-2xl);">💰 Meus Ganhos e Retiradas (Plataforma)</h1>

    {{-- Stats Grid --}}
    <div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: var(--space-lg); margin-bottom: var(--space-2xl);">
        <div class="stat-card" style="border: 1px solid rgba(233, 30, 140, 0.25); background: rgba(233, 30, 140, 0.02);">
            <div class="stat-icon" style="color: var(--accent-primary); background: var(--accent-soft);">🏦</div>
            <div class="stat-value" style="color: var(--accent-primary);">R$ {{ number_format($platformEarnings, 2, ',', '.') }}</div>
            <div class="stat-label">Lucro Líquido Acumulado</div>
        </div>
        <div class="stat-card" style="border: 1px solid rgba(46, 204, 113, 0.25); background: rgba(46, 204, 113, 0.02);">
            <div class="stat-icon" style="color: #2ecc71; background: rgba(46, 204, 113, 0.1);">✓</div>
            <div class="stat-value" style="color: #2ecc71;">R$ {{ number_format($availableBalance, 2, ',', '.') }}</div>
            <div class="stat-label">Disponível para Retirada (Meu Saldo)</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">💸</div>
            <div class="stat-value">R$ {{ number_format($totalWithdrawn, 2, ',', '.') }}</div>
            <div class="stat-label">Total Retirado/Sacado</div>
        </div>
    </div>

    {{-- Solicitação de Saque Admin --}}
    <div class="card" style="padding: var(--space-xl); margin-bottom: var(--space-2xl); border: 1px solid rgba(255,255,255,0.05); background: var(--bg-secondary);">
        <h3 style="margin-top: 0; margin-bottom: var(--space-md); font-size: 1.25rem; color: var(--text-primary);">💸 Solicitar Retirada de Lucro via PIX</h3>
        
        @if(empty(auth()->user()->pix_key) || empty(auth()->user()->pix_key_type))
            <div style="background: rgba(231, 76, 60, 0.1); border: 1px solid rgba(231, 76, 60, 0.2); padding: var(--space-xl); border-radius: var(--radius-md); margin-bottom: var(--space-xl);">
                <div style="display: flex; align-items: center; gap: 15px; margin-bottom: var(--space-lg);">
                    <span style="font-size: 1.5rem;">⚠️</span>
                    <div>
                        <h4 style="margin: 0; color: var(--danger); font-size: 1rem; font-weight: 700;">Chave PIX do Admin não configurada</h4>
                        <p style="margin: 4px 0 0 0; font-size: 0.85rem; color: var(--text-secondary);">
                            Cadastre a sua chave PIX abaixo agora para habilitar as retiradas automáticas de lucro.
                        </p>
                    </div>
                </div>
                
                <form action="{{ route('dashboard.profile.update') }}" method="POST" style="background: var(--bg-card); padding: var(--space-lg); border-radius: var(--radius-sm); border: 1px solid var(--border-primary);">
                    @csrf
                    @method('PUT')
                    
                    {{-- Hidden fields to preserve admin profile data --}}
                    <input type="hidden" name="name" value="{{ auth()->user()->name }}">
                    <input type="hidden" name="username" value="{{ auth()->user()->username }}">
                    
                    <div style="display: grid; grid-template-columns: 1fr 2fr 1fr; gap: var(--space-md); align-items: flex-end;">
                        <div>
                            <label class="form-label">Tipo de Chave</label>
                            <select name="pix_key_type" class="form-select" required>
                                <option value="cpf">CPF</option>
                                <option value="email">E-mail</option>
                                <option value="phone">Celular</option>
                                <option value="random">Chave Aleatória (EVP)</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Chave PIX</label>
                            <input type="text" name="pix_key" class="form-input" required placeholder="Insira sua chave...">
                        </div>
                        <button type="submit" class="btn btn-primary" style="height: 48px; width: 100%;">💾 Salvar PIX</button>
                    </div>
                </form>
            </div>
        @else
            <div style="display: grid; grid-template-columns: 1fr 1.5fr; gap: var(--space-xl); align-items: start;">
                <div>
                    <p style="margin-top: 0; font-size: 0.85rem; color: var(--text-secondary); line-height: 1.6;">
                        * O valor selecionado será enviado instantaneamente para a sua chave PIX configurada.<br>
                        * Chave cadastrada: <strong>{{ strtoupper(auth()->user()->pix_key_type) }}:</strong> <code style="background: rgba(255,255,255,0.05); padding: 2px 6px; border-radius: 4px; font-family: monospace;">{{ auth()->user()->pix_key }}</code>.
                    </p>
                </div>
                
                <form action="{{ route('dashboard.withdraw') }}" method="POST" style="display: flex; gap: var(--space-md); align-items: flex-end;">
                    @csrf
                    <div style="flex: 1;">
                        <label class="form-label" style="display: block; margin-bottom: 6px; font-size: 0.85rem;">Valor para Retirada (R$)</label>
                        <input type="number" name="amount" class="form-input" min="5" max="{{ $availableBalance }}" step="0.01" required placeholder="0,00" style="font-size: 1.1rem; font-weight: bold; color: #fff;">
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg" style="height: 48px; white-space: nowrap;">
                        Realizar Retirada
                    </button>
                </form>
            </div>
        @endif
    </div>

    {{-- Histórico de Retiradas --}}
    <h2 style="margin-bottom: var(--space-lg); color: var(--text-primary);">📋 Histórico de Retiradas do Administrador</h2>
    @if($withdrawals->count())
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Chave PIX Utilizada</th>
                        <th>Valor Retirado</th>
                        <th>Status</th>
                        <th>Detalhes</th>
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
                            <td style="font-weight:700; color: var(--danger);">R$ {{ number_format($withdrawal->amount, 2, ',', '.') }}</td>
                            <td>
                                @if($withdrawal->status === 'completed')
                                    <span class="badge badge-success">✓ Transferido</span>
                                @elseif($withdrawal->status === 'pending')
                                    <span class="badge badge-warning">⌛ Processando</span>
                                @else
                                    <span class="badge badge-danger">✕ Falhou</span>
                                @endif
                            </td>
                            <td style="font-size: 0.85rem; color: var(--text-tertiary);">{{ $withdrawal->status_message ?? 'Processamento automático' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="empty-state">
            <div class="empty-icon">💰</div>
            <h3>Nenhuma retirada registrada</h3>
            <p>Seus saques de lucros da plataforma serão listados aqui.</p>
        </div>
    @endif

    {{-- ── Graphic Analysis ── --}}
    <div class="card mt-2xl" style="padding: var(--space-xl); background: var(--bg-card); border: 1px solid var(--border-primary); border-radius: var(--radius-lg); margin-top: var(--space-2xl);">
        <h3 style="margin-bottom: var(--space-lg); color: var(--text-primary); display: flex; align-items: center; gap: 8px;">📊 Lucro Líquido vs Retiradas (Últimos 30 Dias)</h3>
        <div style="position: relative; height: 320px; width: 100%;">
            <canvas id="adminEarningsChart"></canvas>
        </div>
    </div>

    @push('styles')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endpush

    @push('styles')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('adminEarningsChart').getContext('2d');
            
            const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
            const gridColor = isDark ? 'rgba(255, 255, 255, 0.08)' : 'rgba(0, 0, 0, 0.06)';
            const textColor = isDark ? 'rgba(255, 255, 255, 0.7)' : 'rgba(0, 0, 0, 0.7)';

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! $chartData['labels'] !!},
                    datasets: [
                        {
                            label: 'Meu Lucro Líquido (R$)',
                            data: {!! $chartData['revenue'] !!},
                            borderColor: '#e91e8c',
                            backgroundColor: 'rgba(233, 30, 140, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.3,
                        },
                        {
                            label: 'Minhas Retiradas (R$)',
                            data: {!! $chartData['withdrawals'] !!},
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
@endsection
