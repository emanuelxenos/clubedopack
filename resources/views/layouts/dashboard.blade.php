@extends('layouts.app')

@section('content')
<div class="page-content" style="padding-top: 72px; padding-bottom: 0;">
    <div class="dashboard-layout">
        {{-- ── Sidebar ── --}}
        <aside class="dashboard-sidebar">
            <div class="dashboard-sidebar-sticky">
                <div style="margin-bottom: var(--space-xl);">
                    <div style="font-weight: 700; font-size: 1.1rem; color: var(--text-primary);">Dashboard</div>
                    <div style="font-size: 0.85rem; color: var(--text-tertiary);">Criador</div>
                </div>

                <nav>
                    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') && !request()->routeIs('dashboard.*') ? 'active' : '' }}">
                        📊 Visão Geral
                    </a>
                    <a href="{{ route('dashboard.packs') }}" class="{{ request()->routeIs('dashboard.packs*') ? 'active' : '' }}">
                        📦 Meus Packs
                    </a>
                    <a href="{{ route('dashboard.earnings') }}" class="{{ request()->routeIs('dashboard.earnings') ? 'active' : '' }}">
                        💰 Ganhos
                    </a>
                    <a href="{{ route('dashboard.profile') }}" class="{{ request()->routeIs('dashboard.profile*') ? 'active' : '' }}">
                        ⚙️ Meu Perfil
                    </a>
                    <a href="{{ route('dashboard.verify') }}" class="{{ request()->routeIs('dashboard.verify') ? 'active' : '' }}">
                        🛡️ Verificação de Idade
                        @if(auth()->user()->verification_status === 'verified')
                            <span style="color:#00ff88;font-size:0.8rem;margin-left:4px;">✓</span>
                        @else
                            <span style="color:#ff3b30;font-size:0.8rem;margin-left:4px;">⚠</span>
                        @endif
                    </a>
                    <div style="height:1px;background:var(--border-primary);margin:var(--space-md) 0;"></div>
                    <a href="/{{ auth()->user()->username }}">
                        👤 Ver Minha Página
                    </a>
                    <a href="/">
                        🏠 Voltar ao Marketplace
                    </a>
                </nav>
            </div>
        </aside>

        {{-- ── Main Content ── --}}
        <div class="dashboard-main">
            @yield('dashboard-content')
        </div>
    </div>
</div>
@endsection
