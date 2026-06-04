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

    const ICONS = {
        play: `<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>`,
        pause: `<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg>`,
        volumeUp: `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon><path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07"></path></svg>`,
        volumeMute: `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon><line x1="23" y1="9" x2="17" y2="15"></line><line x1="17" y1="9" x2="23" y2="15"></line></svg>`,
        fullscreen: `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"></path></svg>`
    };

    function createCustomVideoPlayer(url) {
        const playerWrapper = document.createElement('div');
        playerWrapper.className = 'custom-player';

        const ratioWrapper = document.createElement('div');
        ratioWrapper.className = 'video-ratio-wrapper';

        const video = document.createElement('video');
        video.src = url;
        video.autoplay = true;
        video.playsInline = true;
        video.setAttribute('controlsList', 'nodownload');
        video.oncontextmenu = (e) => e.preventDefault();

        video.onloadeddata = () => {
            loader.style.display = 'none';
            video.classList.add('loaded');
        };

        ratioWrapper.appendChild(video);
        playerWrapper.appendChild(ratioWrapper);

        // Center Play overlay
        const centerPlay = document.createElement('div');
        centerPlay.className = 'player-center-play';
        centerPlay.innerHTML = `<span class="play-icon">▶</span>`;
        playerWrapper.appendChild(centerPlay);

        // Controls bar
        const controls = document.createElement('div');
        controls.className = 'custom-player-controls';

        // Play/Pause button
        const playPauseBtn = document.createElement('button');
        playPauseBtn.className = 'player-ctrl-btn';
        playPauseBtn.type = 'button';
        playPauseBtn.innerHTML = ICONS.pause; // Since autoplay is true
        controls.appendChild(playPauseBtn);

        // Timeline
        const timelineContainer = document.createElement('div');
        timelineContainer.className = 'player-timeline-container';

        const timeline = document.createElement('div');
        timeline.className = 'player-timeline';

        const bufferBar = document.createElement('div');
        bufferBar.className = 'player-buffer';

        const progressBar = document.createElement('div');
        progressBar.className = 'player-progress';

        const handle = document.createElement('div');
        handle.className = 'player-timeline-handle';

        timeline.appendChild(bufferBar);
        timeline.appendChild(progressBar);
        timeline.appendChild(handle);
        timelineContainer.appendChild(timeline);
        controls.appendChild(timelineContainer);

        // Time Display
        const timeDisplay = document.createElement('div');
        timeDisplay.className = 'player-time';
        timeDisplay.textContent = '00:00 / 00:00';
        controls.appendChild(timeDisplay);

        // Volume Group
        const volumeGroup = document.createElement('div');
        volumeGroup.className = 'player-volume-group';

        const volumeBtn = document.createElement('button');
        volumeBtn.className = 'player-ctrl-btn';
        volumeBtn.type = 'button';
        volumeBtn.innerHTML = ICONS.volumeUp;

        const volumeSlider = document.createElement('input');
        volumeSlider.type = 'range';
        volumeSlider.className = 'player-volume-slider';
        volumeSlider.min = '0';
        volumeSlider.max = '1';
        volumeSlider.step = '0.05';
        volumeSlider.value = '1';

        volumeGroup.appendChild(volumeBtn);
        volumeGroup.appendChild(volumeSlider);
        controls.appendChild(volumeGroup);

        // Fullscreen
        const fullscreenBtn = document.createElement('button');
        fullscreenBtn.className = 'player-ctrl-btn';
        fullscreenBtn.type = 'button';
        fullscreenBtn.innerHTML = ICONS.fullscreen;
        controls.appendChild(fullscreenBtn);

        playerWrapper.appendChild(controls);

        // Watermark template clone
        if (watermarkTemplate) {
            const wmClone = watermarkTemplate.cloneNode(true);
            wmClone.style.bottom = '80px';
            ratioWrapper.appendChild(wmClone);
        }

        // Helpers
        function formatTime(seconds) {
            if (isNaN(seconds) || seconds === Infinity) return '00:00';
            const hrs = Math.floor(seconds / 3600);
            const mins = Math.floor((seconds % 3600) / 60);
            const secs = Math.floor(seconds % 60);
            const pad = (val) => String(val).padStart(2, '0');
            if (hrs > 0) {
                return `${pad(hrs)}:${pad(mins)}:${pad(secs)}`;
            }
            return `${pad(mins)}:${pad(secs)}`;
        }

        function updateProgress() {
            const duration = video.duration || 0;
            const currentTime = video.currentTime || 0;
            const percent = duration > 0 ? (currentTime / duration) * 100 : 0;
            progressBar.style.width = percent + '%';
            handle.style.left = percent + '%';
            
            if (video.buffered.length > 0 && duration > 0) {
                let activeBufferIndex = 0;
                for (let i = 0; i < video.buffered.length; i++) {
                    if (video.buffered.start(i) <= currentTime && video.buffered.end(i) >= currentTime) {
                        activeBufferIndex = i;
                        break;
                    }
                }
                const bufferedEnd = video.buffered.end(activeBufferIndex);
                const bufferPercent = (bufferedEnd / duration) * 100;
                bufferBar.style.width = bufferPercent + '%';
            } else {
                bufferBar.style.width = '0%';
            }
            
            timeDisplay.textContent = `${formatTime(currentTime)} / ${formatTime(duration)}`;
        }

        // Event listeners
        video.addEventListener('timeupdate', updateProgress);
        video.addEventListener('progress', updateProgress);
        video.addEventListener('loadedmetadata', updateProgress);

        function togglePlay() {
            if (video.paused) {
                video.play();
            } else {
                video.pause();
            }
        }

        video.addEventListener('click', togglePlay);
        playPauseBtn.addEventListener('click', togglePlay);

        video.addEventListener('play', () => {
            playerWrapper.classList.add('playing');
            playPauseBtn.innerHTML = ICONS.pause;
            resetHideTimer();
        });

        video.addEventListener('pause', () => {
            playerWrapper.classList.remove('playing');
            playPauseBtn.innerHTML = ICONS.play;
            playerWrapper.classList.remove('hide-controls');
            clearTimeout(hideTimeout);
        });

        // Seek timeline dragging
        function seek(e) {
            const rect = timeline.getBoundingClientRect();
            const clientX = e.clientX || (e.touches && e.touches[0] ? e.touches[0].clientX : 0);
            const clickX = clientX - rect.left;
            const width = rect.width;
            const duration = video.duration;
            if (duration > 0 && width > 0) {
                let percent = clickX / width;
                if (percent < 0) percent = 0;
                if (percent > 1) percent = 1;
                video.currentTime = percent * duration;
                updateProgress();
            }
        }

        let isDragging = false;
        
        const onMouseMove = (e) => {
            if (isDragging) seek(e);
        };

        const onMouseUp = () => {
            isDragging = false;
            document.removeEventListener('mousemove', onMouseMove);
            document.removeEventListener('mouseup', onMouseUp);
        };

        timelineContainer.addEventListener('mousedown', (e) => {
            isDragging = true;
            seek(e);
            document.addEventListener('mousemove', onMouseMove);
            document.addEventListener('mouseup', onMouseUp);
            e.preventDefault();
        });

        // Touch support
        const onTouchMove = (e) => {
            if (isDragging) seek(e);
        };

        const onTouchEnd = () => {
            isDragging = false;
            document.removeEventListener('touchmove', onTouchMove);
            document.removeEventListener('touchend', onTouchEnd);
        };

        timelineContainer.addEventListener('touchstart', (e) => {
            isDragging = true;
            seek(e);
            document.addEventListener('touchmove', onTouchMove, { passive: true });
            document.addEventListener('touchend', onTouchEnd, { passive: true });
            e.preventDefault();
        });

        // Volume handlers
        function updateVolumeUI() {
            if (video.muted || video.volume === 0) {
                volumeBtn.innerHTML = ICONS.volumeMute;
                volumeSlider.value = 0;
            } else {
                volumeBtn.innerHTML = ICONS.volumeUp;
                volumeSlider.value = video.volume;
            }
        }

        volumeBtn.addEventListener('click', () => {
            video.muted = !video.muted;
            updateVolumeUI();
        });

        volumeSlider.addEventListener('input', (e) => {
            video.volume = e.target.value;
            video.muted = (video.volume === 0);
            updateVolumeUI();
        });

        video.addEventListener('volumechange', updateVolumeUI);

        // Fullscreen
        function toggleFullscreen() {
            if (!document.fullscreenElement) {
                if (playerWrapper.requestFullscreen) {
                    playerWrapper.requestFullscreen();
                } else if (playerWrapper.webkitRequestFullscreen) {
                    playerWrapper.webkitRequestFullscreen();
                } else if (playerWrapper.msRequestFullscreen) {
                    playerWrapper.msRequestFullscreen();
                }
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                } else if (document.webkitExitFullscreen) {
                    document.webkitExitFullscreen();
                } else if (document.msExitFullscreen) {
                    document.msExitFullscreen();
                }
            }
        }

        fullscreenBtn.addEventListener('click', toggleFullscreen);

        // Auto-hide controls
        let hideTimeout;
        function resetHideTimer() {
            playerWrapper.classList.remove('hide-controls');
            clearTimeout(hideTimeout);
            if (!video.paused) {
                hideTimeout = setTimeout(() => {
                    playerWrapper.classList.add('hide-controls');
                }, 2500);
            }
        }

        playerWrapper.addEventListener('mousemove', resetHideTimer);
        playerWrapper.addEventListener('click', resetHideTimer);
        playerWrapper.addEventListener('touchstart', resetHideTimer);

        return playerWrapper;
    }

    function openLightbox(index) {
        currentIndex = index;
        lightbox.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        renderMedia();
    }

    function stopActiveVideos() {
        const activeVideos = content.querySelectorAll('video');
        activeVideos.forEach(v => {
            v.pause();
            v.src = '';
            v.load();
        });
    }

    function closeLightbox() {
        stopActiveVideos();
        lightbox.style.display = 'none';
        document.body.style.overflow = '';
        content.innerHTML = '';
    }

    function renderMedia() {
        stopActiveVideos();
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
                img.style.border = '2px solid red';
                img.style.padding = '20px';
                img.style.backgroundColor = 'rgba(255,0,0,0.1)';
            };
            content.appendChild(img);
        } else {
            const player = createCustomVideoPlayer(media.url);
            content.appendChild(player);
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
