@extends('layouts.app')

@section('title', 'Marketplace')

@section('content')
<div class="page-content">
    {{-- ── Hero Section ── --}}
    @unless(request('search') || request('category'))
    <section class="hero-section">
        <div class="container">
            <h1 class="hero-title">
                Conteúdo <span class="text-gradient">Exclusivo</span><br>
                dos Melhores Criadores
            </h1>
            <p class="hero-subtitle">
                Descubra packs incríveis de fotos e vídeos. Assine seus criadores favoritos ou compre packs avulsos.
            </p>
            <div style="display: flex; gap: var(--space-md); justify-content: center; flex-wrap: wrap;">
                <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Comece Agora</a>
                <a href="#packs" class="btn btn-secondary btn-lg">Explorar Packs</a>
            </div>
        </div>
    </section>
    @endunless

    <div class="container">
        {{-- ── Top Creators ── --}}
        @if($topCreators->count() && !request('search'))
        <section class="mb-2xl">
            <div class="section-header">
                <h2 class="section-title">🌟 Criadores em Destaque</h2>
            </div>
            <div class="creators-slider">
                @foreach($topCreators as $creator)
                    <a href="/{{ $creator->username }}" class="creator-card-mini">
                        <img src="{{ $creator->avatar_url }}" alt="{{ $creator->name }}">
                        <div class="creator-name">{{ $creator->name }}</div>
                        <div class="creator-packs">{{ $creator->packs_count }} packs</div>
                    </a>
                @endforeach
            </div>
        </section>
        @endif

        {{-- ── Featured Packs ── --}}
        @if($featuredPacks->count() && !request('search') && !request('category'))
        <section class="featured-section">
            <div class="section-header">
                <h2 class="section-title">🔥 Packs em Destaque</h2>
            </div>
            <div class="featured-grid">
                @foreach($featuredPacks as $pack)
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
                            <span class="pack-badge">⭐ Destaque</span>
                            <span class="pack-price-badge">{{ $pack->formatted_price }}</span>
                        </div>
                        <div class="pack-info">
                            <div class="pack-title">{{ $pack->title }}</div>
                            <div class="pack-creator">
                                <img src="{{ $pack->user->avatar_url }}" alt="{{ $pack->user->name }}">
                                <span>{{ $pack->user->name }}</span>
                            </div>
                            <div class="pack-meta">
                                <div class="pack-stats">
                                    <span>👁️ {{ $pack->views_count }}</span>
                                    <span>📦 {{ $pack->media_count }} itens</span>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
        @endif

        {{-- ── Category Pills ── --}}
        <section id="packs">
            <div class="category-pills">
                <a href="/" class="category-pill {{ !request('category') ? 'active' : '' }}">🏠 Todos</a>
                @foreach($categories as $category)
                    <a href="/?category={{ $category->slug }}" class="category-pill {{ request('category') == $category->slug ? 'active' : '' }}">
                        {{ $category->icon }} {{ $category->name }}
                    </a>
                @endforeach
            </div>

            {{-- ── Sort Bar ── --}}
            <div class="sort-bar">
                <span class="results-count">
                    @if(request('search'))
                        Resultados para "<strong>{{ request('search') }}</strong>" — {{ $packs->total() }} encontrados
                    @elseif(request('category'))
                        @php $catName = $categories->firstWhere('slug', request('category')) @endphp
                        Categoria: <strong>{{ $catName ? $catName->name : request('category') }}</strong>
                    @else
                        Todos os Packs
                    @endif
                </span>
                <form action="/" method="GET" style="display: flex; gap: var(--space-sm);">
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    <select name="sort" onchange="this.form.submit()">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Mais Recentes</option>
                        <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Mais Populares</option>
                        <option value="best-selling" {{ request('sort') == 'best-selling' ? 'selected' : '' }}>Mais Vendidos</option>
                        <option value="price-low" {{ request('sort') == 'price-low' ? 'selected' : '' }}>Menor Preço</option>
                        <option value="price-high" {{ request('sort') == 'price-high' ? 'selected' : '' }}>Maior Preço</option>
                    </select>
                </form>
            </div>

            {{-- ── Packs Grid ── --}}
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
                            @if($pack->is_featured)
                                <span class="pack-badge">⭐ Destaque</span>
                            @endif
                        </div>
                        <div class="pack-info">
                            <div class="pack-title">{{ $pack->title }}</div>
                            <div class="pack-creator">
                                <img src="{{ $pack->user->avatar_url }}" alt="{{ $pack->user->name }}">
                                <span>{{ $pack->user->name }}</span>
                            </div>
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
                        <div class="empty-icon">🔍</div>
                        <h3>Nenhum pack encontrado</h3>
                        <p>Tente buscar por outros termos ou explore as categorias.</p>
                        <a href="/" class="btn btn-secondary">Ver Todos</a>
                    </div>
                @endforelse
            </div>

            {{-- ── Pagination ── --}}
            @if($packs->hasPages())
                <div class="pagination">
                    {{ $packs->appends(request()->query())->links('pagination.custom') }}
                </div>
            @endif
        </section>
    </div>
</div>
@endsection
