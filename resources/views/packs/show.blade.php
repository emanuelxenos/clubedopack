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
                        @foreach($pack->media as $index => $media)
                            <div class="gallery-item {{ $hasAccess ? 'lightbox-trigger' : '' }}" data-index="{{ $index }}" style="{{ $hasAccess ? 'cursor: pointer;' : '' }}">
                                @if($hasAccess)
                                    @if($media->isImage())
                                        <img src="{{ $media->url }}" alt="Pack media" loading="lazy">
                                    @else
                                        <div style="position:relative; width:100%; height:100%;">
                                            <video src="{{ $media->url }}#t=0.1" preload="metadata" muted playsinline style="width:100%; height:100%; object-fit:cover;"></video>
                                            <div style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%); background:rgba(0,0,0,0.6); width:40px; height:40px; border-radius:50%; display:flex; align-items:center; justify-content:center;">
                                                <span style="color:#fff; font-size:1.2rem; margin-left:3px;">▶</span>
                                            </div>
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
                            <form action="{{ route('pack.purchase', $pack) }}" method="POST" style="margin-bottom: var(--space-md);" onsubmit="return confirm('⚠️ Compra Irreversível\n\nPor se tratar de conteúdo digital com entrega imediata, esta compra NÃO é elegível para reembolso ou estornos. Deseja prosseguir com a compra?')">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-block btn-lg">
                                    🛒 Comprar Pack — {{ $pack->formatted_price }}
                                </button>
                            </form>

                            @if($pack->user->subscription_price > 0)
                                <div style="text-align: center; margin-bottom: var(--space-md); color: var(--text-tertiary); font-size: 0.85rem;">ou</div>
                                <form action="{{ route('creator.subscribe', $pack->user) }}" method="POST" onsubmit="return confirm('⚠️ Assinatura de Conteúdo\n\nPor se tratar de conteúdo digital com entrega imediata, esta assinatura NÃO é elegível para reembolsos fracionados ou estornos. Deseja prosseguir com a assinatura?')">
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
                            <div style="font-size:0.85rem;color:var(--text-tertiary);">{{ '@' }}{{ $pack->user->username }}</div>
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

{{-- ── Lightbox Nativo ── --}}
@if($hasAccess && $pack->media->count())
<div id="native-lightbox" class="lightbox-overlay" style="display: none;">
    <div class="lightbox-toolbar">
        <div class="lightbox-counter"><span id="lb-current">1</span> / <span id="lb-total">{{ $pack->media->count() }}</span></div>
        <button id="lb-close" class="lightbox-btn" title="Fechar (Esc)">✕</button>
    </div>
    
    <button id="lb-prev" class="lightbox-btn lightbox-nav lb-nav-left" title="Anterior (Seta Esquerda)">‹</button>
    
    <div class="lightbox-content-wrapper">
        <div id="lb-loader" class="lb-spinner" style="display: none;"></div>
        <div id="lb-content"></div>
        {{-- O watermark original escondido como template --}}
        <div id="lb-watermark-template" style="display: none;">
            <div id="lb-watermark-ui" style="display: flex;">
                <img src="{{ asset('icon.png') }}" alt="Ícone">
                <div class="lb-wm-text">
                    <div class="lb-wm-title">Clube do Pack</div>
                    <div class="lb-wm-user">{{ '@' . $pack->user->username }}</div>
                </div>
            </div>
        </div>
    </div>
    
    <button id="lb-next" class="lightbox-btn lightbox-nav lb-nav-right" title="Próxima (Seta Direita)">›</button>
</div>

<style>
.lightbox-overlay {
    position: fixed; top: 0; left: 0; width: 100%; height: 100vh;
    background: rgba(0, 0, 0, 0.95); z-index: 99999;
    display: flex; flex-direction: column;
}
.lightbox-toolbar {
    position: absolute; top: 0; left: 0; right: 0;
    height: 60px; display: flex; justify-content: space-between; align-items: center;
    padding: 0 var(--space-lg); z-index: 2;
    background: linear-gradient(to bottom, rgba(0,0,0,0.8), transparent);
}
.lightbox-counter {
    color: #fff; font-size: 1.1rem; font-weight: 500; font-family: monospace;
}
.lightbox-btn {
    background: transparent; border: none; color: rgba(255,255,255,0.7);
    font-size: 2rem; cursor: pointer; transition: color 0.2s, transform 0.2s;
    display: flex; align-items: center; justify-content: center;
}
.lightbox-btn:hover { color: #fff; transform: scale(1.1); }
#lb-close { font-size: 1.8rem; }
.lightbox-nav {
    position: absolute; top: 50%; transform: translateY(-50%);
    height: 100px; width: 60px; z-index: 2;
}
.lightbox-nav:hover { background: rgba(255,255,255,0.05); transform: translateY(-50%); }
.lb-nav-left { left: 0; }
.lb-nav-right { right: 0; }

.lightbox-content-wrapper {
    flex: 1; display: flex; align-items: center; justify-content: center;
    position: relative; width: 100%; height: 100%; overflow: hidden;
}
#lb-content {
    max-width: 90vw; max-height: 90vh; display: flex; align-items: center; justify-content: center;
}
#lb-content img, #lb-content video {
    max-width: 100%; max-height: 90vh; object-fit: contain;
    border-radius: 4px; box-shadow: 0 4px 20px rgba(0,0,0,0.5);
    opacity: 0; transition: opacity 0.3s ease;
}
#lb-content img.loaded, #lb-content video.loaded { opacity: 1; }

.lb-spinner {
    position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
    width: 50px; height: 50px; border: 4px solid rgba(255,255,255,0.1);
    border-top: 4px solid #e91e8c; border-radius: 50%;
    animation: lb-spin 1s linear infinite; z-index: 1;
}
@keyframes lb-spin { 0% { transform: translate(-50%, -50%) rotate(0deg); } 100% { transform: translate(-50%, -50%) rotate(360deg); } }

#lb-watermark-ui {
    position: absolute; bottom: 20px; right: 20px; z-index: 1000;
    pointer-events: none; opacity: 0.7; align-items: center;
    /* Removido o fundo preto a pedido do usuário */
    text-shadow: 1px 1px 3px rgba(0,0,0,0.8); /* Adicionado sombra sutil para legibilidade em fundo claro */
}
#lb-watermark-ui img { width: 40px; height: auto; margin-right: 10px; }
.lb-wm-text { display: flex; flex-direction: column; text-align: left; }
.lb-wm-title { font-size: 1.1rem; font-weight: bold; color: rgba(255,255,255,0.9); margin-bottom: 0px; }
.lb-wm-user { font-size: 0.9rem; color: rgba(255,255,255,0.8); }

/* Efeito de hover no grid para indicar que é clicável */
.lightbox-trigger:hover { transform: scale(1.02); transition: transform 0.2s; box-shadow: 0 8px 24px rgba(233,30,140,0.2); }
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Coleta dados das mídias com json_encode para evitar que o Blade converta & em &amp; (isso quebra a URL assinada)
    const mediaList = {!! json_encode($pack->media->map(function($media) {
        return [
            'type' => $media->isImage() ? 'image' : 'video',
            'url' => $media->url
        ];
    })->toArray()) !!};

    const triggers = document.querySelectorAll('.lightbox-trigger');
    const lightbox = document.getElementById('native-lightbox');
    const content = document.getElementById('lb-content');
    const loader = document.getElementById('lb-loader');
    const currentSpan = document.getElementById('lb-current');
    const watermarkTemplate = document.getElementById('lb-watermark-ui');
    
    let currentIndex = 0;

    function openLightbox(index) {
        currentIndex = index;
        lightbox.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        renderMedia();
    }

    function closeLightbox() {
        lightbox.style.display = 'none';
        document.body.style.overflow = '';
        content.innerHTML = '';
    }

    function renderMedia() {
        content.innerHTML = '';
        loader.style.display = 'block';
        currentSpan.innerText = currentIndex + 1;
        
        const media = mediaList[currentIndex];
        if (media.type === 'image') {
            const img = new Image();
            img.src = media.url;
            img.onload = () => {
                loader.style.display = 'none';
                img.classList.add('loaded');
            };
            img.onerror = () => {
                loader.style.display = 'none';
                img.alt = 'Erro ao carregar imagem';
                img.style.opacity = 1;
                // Adiciona uma borda vermelha e um texto para indicar que falhou
                img.style.border = '2px solid red';
                img.style.padding = '20px';
                img.style.backgroundColor = 'rgba(255,0,0,0.1)';
            };
            content.appendChild(img);
        } else {
            const container = document.createElement('div');
            container.style.position = 'relative';
            container.style.display = 'inline-block';
            container.style.maxWidth = '100%';
            container.style.maxHeight = '90vh';
            
            const video = document.createElement('video');
            video.src = media.url;
            video.controls = true;
            video.autoplay = true;
            video.setAttribute('controlsList', 'nodownload');
            video.oncontextmenu = (e) => e.preventDefault();
            
            video.onloadeddata = () => {
                loader.style.display = 'none';
                video.classList.add('loaded');
            };
            
            container.appendChild(video);
            
            // Injeta a marca d'água dentro do container do vídeo (grudado nele!)
            const wmClone = watermarkTemplate.cloneNode(true);
            container.appendChild(wmClone);
            
            content.appendChild(container);
        }
    }

    function prevMedia() {
        currentIndex = (currentIndex > 0) ? currentIndex - 1 : mediaList.length - 1;
        renderMedia();
    }

    function nextMedia() {
        currentIndex = (currentIndex < mediaList.length - 1) ? currentIndex + 1 : 0;
        renderMedia();
    }

    triggers.forEach(trigger => {
        trigger.addEventListener('click', () => {
            openLightbox(parseInt(trigger.getAttribute('data-index')));
        });
    });

    document.getElementById('lb-close').addEventListener('click', closeLightbox);
    document.getElementById('lb-prev').addEventListener('click', prevMedia);
    document.getElementById('lb-next').addEventListener('click', nextMedia);

    // Fechar ao clicar fora do conteúdo
    lightbox.addEventListener('click', (e) => {
        if (e.target === lightbox || e.target.classList.contains('lightbox-content-wrapper')) {
            closeLightbox();
        }
    });

    // Teclas de atalho
    document.addEventListener('keydown', (e) => {
        if (lightbox.style.display === 'flex') {
            if (e.key === 'Escape') closeLightbox();
            if (e.key === 'ArrowLeft') prevMedia();
            if (e.key === 'ArrowRight') nextMedia();
        }
    });
});
</script>
@endif
@endsection
