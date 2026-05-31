@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('dashboard-content')
    <h1 style="margin-bottom: var(--space-sm);">Olá, {{ auth()->user()->name }} 👋</h1>
    <p style="color: var(--text-secondary); margin-bottom: var(--space-2xl);">Aqui está um resumo da sua conta.</p>

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
        <div class="stat-card">
            <div class="stat-icon">💰</div>
            <div class="stat-value">R$ {{ number_format($totalRevenue, 2, ',', '.') }}</div>
            <div class="stat-label">Receita Total</div>
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
