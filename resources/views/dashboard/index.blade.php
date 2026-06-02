@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('dashboard-content')
    <h1 style="margin-bottom: var(--space-sm);">Olá, {{ auth()->user()->name }} 👋</h1>
    
    @if(auth()->user()->verification_status !== 'verified')
        <div style="background: linear-gradient(135deg, rgba(233, 30, 140, 0.15), rgba(233, 30, 140, 0.05)); border: 1px solid rgba(233, 30, 140, 0.3); border-radius: var(--radius-md); padding: var(--space-md) var(--space-lg); margin-bottom: var(--space-xl); display: flex; align-items: center; justify-content: space-between; gap: var(--space-md); flex-wrap: wrap;">
            <div style="display: flex; align-items: center; gap: var(--space-sm);">
                <span style="font-size: 1.5rem;">🛡️</span>
                <div>
                    <h4 style="margin: 0; color: var(--text-primary); font-weight: 600;">Sua conta precisa de verificação de idade</h4>
                    <p style="margin: 2px 0 0 0; font-size: 0.85rem; color: var(--text-secondary);">Para publicar packs e liberar vendas, confirme sua maioridade e biometria facial.</p>
                </div>
            </div>
            <a href="{{ route('dashboard.verify') }}" class="btn btn-primary btn-sm">Verificar Agora</a>
        </div>
    @endif

    <p style="color: var(--text-secondary); margin-bottom: var(--space-lg);">Aqui está um resumo da sua conta.</p>

    {{-- ── Stats Grid ── --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">📦</div>
            <div class="stat-value">{{ $totalPacks }}</div>
            <div class="stat-label">Packs Publicados</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">👁️</div>
            <div class="stat-value">{{ number_format($totalViews) }}</div>
            <div class="stat-label">Visualizações</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">🛒</div>
            <div class="stat-value">{{ $totalSales }}</div>
            <div class="stat-label">Vendas</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">✨</div>
            <div class="stat-value">{{ $subscribersCount }}</div>
            <div class="stat-label">Assinantes</div>
        </div>
        <div class="stat-card" style="border: 1px solid rgba(0, 255, 136, 0.2); background: rgba(0, 255, 136, 0.02);">
            <div class="stat-icon" style="color: #00ff88;">💸</div>
            <div class="stat-value" style="color: #00ff88;">R$ {{ number_format(auth()->user()->balance_available, 2, ',', '.') }}</div>
            <div class="stat-label">Saldo Disponível (Pix)</div>
        </div>
        <div class="stat-card" style="border: 1px solid rgba(233, 30, 140, 0.2); background: rgba(233, 30, 140, 0.02);">
            <div class="stat-icon" style="color: #e91e8c;">🕒</div>
            <div class="stat-value" style="color: #e91e8c;">R$ {{ number_format(auth()->user()->balance_pending, 2, ',', '.') }}</div>
            <div class="stat-label">Saldo Pendente (A liberar)</div>
        </div>
    </div>

    {{-- ── Recent Packs ── --}}
    <div class="section-header">
        <h2 class="section-title">📦 Packs Recentes</h2>
        <a href="{{ route('dashboard.packs.create') }}" class="btn btn-primary btn-sm">+ Novo Pack</a>
    </div>

    @if($recentPacks->count())
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Pack</th>
                        <th>Categoria</th>
                        <th>Preço</th>
                        <th>Views</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentPacks as $pack)
                        <tr>
                            <td>
                                <a href="{{ route('dashboard.packs.edit', $pack) }}" style="font-weight:600;color:var(--text-primary);">
                                    {{ $pack->title }}
                                </a>
                            </td>
                            <td>{{ $pack->category ? $pack->category->name : '-' }}</td>
                            <td>{{ $pack->formatted_price }}</td>
                            <td>{{ $pack->views_count }}</td>
                            <td>
                                @if($pack->is_active)
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
    @else
        <div class="empty-state">
            <div class="empty-icon">📦</div>
            <h3>Nenhum pack ainda</h3>
            <p>Crie seu primeiro pack e comece a vender!</p>
            <a href="{{ route('dashboard.packs.create') }}" class="btn btn-primary">Criar Primeiro Pack</a>
        </div>
    @endif
@endsection
