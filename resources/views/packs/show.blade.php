@extends('layouts.app')

@section('title', $pack->title)

@section('content')
<div class="page-content">
    <div class="container">
        {{-- ── Breadcrumb ── --}}
        <div style="margin-bottom: var(--space-xl); font-size: 0.9rem; color: var(--text-tertiary);">
            <a href="/">Início</a> /
            <a href="/{{ $pack->user->username }}">{{ $pack->user->name }}</a> /
            <span style="color: var(--text-secondary);">{{ $pack->title }}</span>
        </div>

        <div class="pack-detail">
            {{-- ── Gallery / Content ── --}}
            <div>
                <h1 style="font-size: 1.75rem; margin-bottom: var(--space-lg);">{{ $pack->title }}</h1>

                @if($pack->description)
                    <p style="color: var(--text-secondary); line-height: 1.7; margin-bottom: var(--space-xl);">{{ $pack->description }}</p>
                @endif

                {{-- Media Gallery --}}
                <div class="pack-gallery">
                    @if($pack->media->count())
                        @foreach($pack->media as $media)
                            <div class="gallery-item">
                                @if($hasAccess)
                                    @if($media->isImage())
                                        <img src="{{ $media->url }}" alt="Pack media" loading="lazy">
                                    @else
                                        <div class="placeholder-image" style="flex-direction:column;gap:8px;">
                                            <span>🎬</span>
                                            <small style="font-size:0.75rem;">Vídeo</small>
                                        </div>
                                    @endif
                                @else
                                    <div class="lock-overlay">
                                        <span class="lock-icon">🔒</span>
                                        <span>Conteúdo bloqueado</span>
                                    </div>
                                    <div class="placeholder-image" style="filter: blur(20px);">📸</div>
                                @endif
                            </div>
                        @endforeach
                    @else
                        {{-- Show placeholder items --}}
                        @for($i = 0; $i < min($pack->media_count, 9); $i++)
                            <div class="gallery-item">
                                @if($hasAccess)
                                    <div class="placeholder-image">📸</div>
                                @else
                                    <div class="lock-overlay">
                                        <span class="lock-icon">🔒</span>
                                    </div>
                                    <div class="placeholder-image" style="filter: blur(20px);">📸</div>
                                @endif
                            </div>
                        @endfor
                    @endif
                </div>
            </div>

            {{-- ── Sidebar ── --}}
            <div class="pack-sidebar">
                <div class="sidebar-card">
                    <div class="pack-price-main">{{ $pack->formatted_price }}</div>

                    <ul class="pack-details-list">
                        <li>
                            <span>Criador</span>
                            <strong>
                                <a href="/{{ $pack->user->username }}">{{ $pack->user->name }}</a>
                            </strong>
                        </li>
                        <li>
                            <span>Categoria</span>
                            <strong>{{ $pack->category ? $pack->category->name : 'Geral' }}</strong>
                        </li>
                        <li>
                            <span>Itens no Pack</span>
                            <strong>{{ $pack->media_count }} arquivos</strong>
                        </li>
                        <li>
                            <span>Visualizações</span>
                            <strong>{{ $pack->views_count }}</strong>
                        </li>
                        <li>
                            <span>Publicado em</span>
                            <strong>{{ $pack->created_at->format('d/m/Y') }}</strong>
                        </li>
                    </ul>

                    @auth
                        @if($hasAccess)
                            <div class="btn btn-success btn-block btn-lg" style="cursor:default;">
                                ✓ Você tem acesso a este pack
                            </div>
                        @else
                            <form action="{{ route('pack.purchase', $pack) }}" method="POST" style="margin-bottom: var(--space-md);">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-block btn-lg">
                                    🛒 Comprar Pack — {{ $pack->formatted_price }}
                                </button>
                            </form>

                            @if($pack->user->subscription_price > 0)
                                <div style="text-align: center; margin-bottom: var(--space-md); color: var(--text-tertiary); font-size: 0.85rem;">ou</div>
                                <form action="{{ route('creator.subscribe', $pack->user) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-outline btn-block">
                                        ✨ Assinar {{ $pack->user->name }} — R$ {{ number_format($pack->user->subscription_price, 2, ',', '.') }}/mês
                                    </button>
                                </form>
                                <p style="font-size: 0.8rem; color: var(--text-tertiary); margin-top: var(--space-sm); text-align: center;">
                                    Assinando, você terá acesso a todos os packs deste criador.
                                </p>
                            @endif
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary btn-block btn-lg">
                            Entrar para Comprar
                        </a>
                    @endauth
                </div>

                {{-- Creator Mini Card --}}
                <div class="sidebar-card mt-lg" style="padding: var(--space-lg);">
                    <a href="/{{ $pack->user->username }}" style="display:flex;align-items:center;gap:var(--space-md);text-decoration:none;">
                        <img src="{{ $pack->user->avatar_url }}" alt="{{ $pack->user->name }}"
                             style="width:50px;height:50px;border-radius:var(--radius-full);object-fit:cover;">
                        <div>
                            <div style="font-weight:600;color:var(--text-primary);">{{ $pack->user->name }}</div>
                            <div style="font-size:0.85rem;color:var(--text-tertiary);">@{{ $pack->user->username }}</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        {{-- ── Related Packs ── --}}
        @if($relatedPacks->count())
            <section class="mt-2xl">
                <div class="section-header">
                    <h2 class="section-title">📦 Packs Relacionados</h2>
                </div>
                <div class="grid-packs">
                    @foreach($relatedPacks as $related)
                        <a href="{{ route('pack.show', $related->slug) }}" class="card pack-card" style="text-decoration:none;">
                            <div class="pack-image">
                                @if($related->cover_image_path)
                                    <img src="{{ $related->cover_url }}" alt="{{ $related->title }}" loading="lazy">
                                @else
                                    <div class="placeholder-image">📸</div>
                                @endif
                                <span class="pack-price-badge">{{ $related->formatted_price }}</span>
                            </div>
                            <div class="pack-info">
                                <div class="pack-title">{{ $related->title }}</div>
                                <div class="pack-creator">
                                    <img src="{{ $related->user->avatar_url }}" alt="{{ $related->user->name }}">
                                    <span>{{ $related->user->name }}</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</div>
@endsection
