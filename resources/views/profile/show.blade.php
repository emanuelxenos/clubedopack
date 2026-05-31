@extends('layouts.app')

@section('title', $creator->name)

@section('content')
{{-- ── Banner ── --}}
<div class="profile-banner">
    @if($creator->banner_url)
        <img src="{{ $creator->banner_url }}" alt="Banner de {{ $creator->name }}">
    @else
        <div style="width:100%;height:100%;background:var(--accent-gradient);"></div>
    @endif
    <div class="banner-overlay"></div>
</div>

{{-- ── Profile Header ── --}}
<div class="profile-header">
    <div class="profile-header-inner">
        <img src="{{ $creator->avatar_url }}" alt="{{ $creator->name }}" class="profile-avatar">

        <div class="profile-info">
            <h1 class="profile-name">{{ $creator->name }}</h1>
            <div class="profile-username">@{{ $creator->username }}</div>
            @if($creator->bio)
                <p class="profile-bio">{{ $creator->bio }}</p>
            @endif
        </div>

        <div class="profile-stats">
            <div class="profile-stat">
                <span class="stat-value">{{ $packsCount }}</span>
                <span class="stat-label">Packs</span>
            </div>
            <div class="profile-stat">
                <span class="stat-value">{{ $subscribersCount }}</span>
                <span class="stat-label">Assinantes</span>
            </div>
        </div>

        <div class="profile-actions">
            @auth
                @if(!$isSubscribed && $creator->subscription_price > 0 && auth()->id() !== $creator->id)
                    <form action="{{ route('creator.subscribe', $creator) }}" method="POST" onsubmit="return confirm('⚠️ Assinatura de Conteúdo\n\nPor se tratar de conteúdo digital com entrega imediata, esta assinatura NÃO é elegível para reembolsos fracionados ou estornos. Deseja prosseguir com a assinatura?')">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-lg">
                            ✨ Assinar — R$ {{ number_format($creator->subscription_price, 2, ',', '.') }}/mês
                        </button>
                    </form>
                @elseif($isSubscribed)
                    <span class="btn btn-success btn-lg" style="cursor:default;">✓ Assinante</span>
                @endif
            @else
                @if($creator->subscription_price > 0)
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
                        ✨ Assinar — R$ {{ number_format($creator->subscription_price, 2, ',', '.') }}/mês
                    </a>
                @endif
            @endauth
        </div>
    </div>
</div>

{{-- ── Packs Grid ── --}}
<div class="container">
    <div class="section-header">
        <h2 class="section-title">📦 Packs de {{ $creator->name }}</h2>
        <span class="text-muted text-small">{{ $packs->total() }} packs</span>
    </div>

    <div class="grid-packs">
        @forelse($packs as $pack)
            <a href="{{ route('pack.show', $pack->slug) }}" class="card pack-card" style="text-decoration:none;">
                <div class="pack-image">
                    @if($pack->cover_image_path)
                        <img src="{{ $pack->cover_url }}" alt="{{ $pack->title }}" loading="lazy">
                    @else
                        <div class="placeholder-image">📸</div>
                    @endif
                    <div class="pack-overlay">
                        <span class="btn btn-primary btn-sm">Ver Pack</span>
                    </div>
                    <span class="pack-price-badge">{{ $pack->formatted_price }}</span>

                    @auth
                        @if(auth()->user()->hasAccessToPack($pack))
                            <span class="pack-badge" style="background:var(--success);">✓ Desbloqueado</span>
                        @endif
                    @endauth
                </div>
                <div class="pack-info">
                    <div class="pack-title">{{ $pack->title }}</div>
                    @if($pack->category)
                        <div style="margin-bottom:var(--space-sm);">
                            <span class="badge badge-accent">{{ $pack->category->icon }} {{ $pack->category->name }}</span>
                        </div>
                    @endif
                    <div class="pack-meta">
                        <div class="pack-stats">
                            <span>👁️ {{ $pack->views_count }}</span>
                            <span>📦 {{ $pack->media_count }}</span>
                        </div>
                        <span class="pack-price">{{ $pack->formatted_price }}</span>
                    </div>
                </div>
            </a>
        @empty
            <div class="empty-state" style="grid-column: 1 / -1;">
                <div class="empty-icon">📦</div>
                <h3>Nenhum pack ainda</h3>
                <p>Este criador ainda não publicou nenhum pack.</p>
            </div>
        @endforelse
    </div>

    @if($packs->hasPages())
        <div class="pagination">
            {{ $packs->links('pagination.custom') }}
        </div>
    @endif
</div>
@endsection
