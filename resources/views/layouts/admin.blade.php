@extends('layouts.app')

@section('content')
<div class="page-content" style="padding-top: 72px;">
    <div class="dashboard-layout">
        {{-- ── Admin Sidebar ── --}}
        <aside class="dashboard-sidebar">
            <div style="margin-bottom: var(--space-xl);">
                <div style="font-weight: 700; font-size: 1.1rem; color: var(--text-primary);">Admin</div>
                <div style="font-size: 0.85rem; color: var(--text-tertiary);">Painel de Controle</div>
            </div>

            <nav>
                <a href="{{ route('admin') }}" class="{{ request()->routeIs('admin') && !request()->routeIs('admin.*') ? 'active' : '' }}">
                    📊 Dashboard
                </a>
                <a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                    👥 Usuários
                </a>
                <a href="{{ route('admin.transactions') }}" class="{{ request()->routeIs('admin.transactions') ? 'active' : '' }}">
                    💳 Transações
                </a>
                <a href="{{ route('admin.categories') }}" class="{{ request()->routeIs('admin.categories') ? 'active' : '' }}">
                    🏷️ Categorias
                </a>
                <a href="{{ route('admin.settings') }}" class="{{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                    ⚙️ Configurações
                </a>
                <div style="height:1px;background:var(--border-primary);margin:var(--space-md) 0;"></div>
                <a href="/">🏠 Voltar ao Marketplace</a>
            </nav>
        </aside>

        <div class="dashboard-main">
            @yield('admin-content')
        </div>
    </div>
</div>
@endsection
