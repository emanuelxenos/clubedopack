<!DOCTYPE html>
<html lang="pt-BR" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Clube do Pack - Marketplace de conteúdo exclusivo dos melhores criadores. Packs de fotos e vídeos premium.">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Clube do Pack') - Marketplace de Conteúdo Exclusivo</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body>
    {{-- ── Header ── --}}
    <header class="header">
        <div class="header-inner">
            <button class="mobile-menu-btn" id="mobileMenuBtn">☰</button>

            <a href="/" class="logo">
                <div class="logo-icon">🔥</div>
                <span>Clube do Pack</span>
            </a>

            <form action="/" method="GET" class="search-bar">
                <span class="search-icon">🔍</span>
                <input type="text" name="search" id="headerSearch" placeholder="Buscar packs, criadores..."
                       value="{{ request('search') }}">
            </form>

            <div class="header-nav">
                <button class="theme-toggle" title="Alternar tema">🌙</button>

                @guest
                    <a href="{{ route('login') }}"><span class="nav-text">Entrar</span></a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Criar Conta</a>
                @endguest

                @auth
                    @if(auth()->user()->isCreator())
                        <a href="{{ route('dashboard') }}"><span class="nav-text">Dashboard</span></a>
                    @endif

                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin') }}"><span class="nav-text">Admin</span></a>
                    @endif

                    <a href="{{ route('library') }}"><span class="nav-text">Biblioteca</span></a>

                    <div class="user-menu">
                        <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}"
                             class="user-avatar" data-dropdown="userDropdown">

                        <div class="user-dropdown" id="userDropdown">
                            <div style="padding: 10px 14px; border-bottom: 1px solid var(--border-primary); margin-bottom: 4px;">
                                <div style="font-weight: 600; font-size: 0.95rem; color: var(--text-primary);">{{ auth()->user()->name }}</div>
                                <div style="font-size: 0.8rem; color: var(--text-tertiary);">{{ auth()->user()->email }}</div>
                            </div>

                            @if(auth()->user()->isCreator())
                                <a href="{{ route('dashboard') }}">📊 Dashboard</a>
                                <a href="{{ route('dashboard.packs') }}">📦 Meus Packs</a>
                                <a href="{{ route('dashboard.earnings') }}">💰 Ganhos</a>
                                <a href="{{ route('dashboard.profile') }}">⚙️ Perfil</a>
                                <a href="/{{ auth()->user()->username }}">👤 Minha Página</a>
                            @endif

                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin') }}">🛡️ Painel Admin</a>
                                <a href="{{ route('admin.users') }}">👥 Usuários</a>
                                <a href="{{ route('admin.transactions') }}">💳 Transações</a>
                            @endif

                            <a href="{{ route('library') }}">📚 Minha Biblioteca</a>

                            <div class="divider"></div>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit">🚪 Sair</button>
                            </form>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </header>

    {{-- ── Mobile Sidebar ── --}}
    <div class="mobile-overlay" id="mobileOverlay"></div>
    <div class="mobile-sidebar" id="mobileSidebar">
        <div class="sidebar-header">
            <a href="/" class="logo">
                <div class="logo-icon">🔥</div>
                <span>Clube do Pack</span>
            </a>
            <button class="close-btn" id="mobileCloseBtn">✕</button>
        </div>

        <div style="margin-bottom: var(--space-lg);">
            <form action="/" method="GET">
                <input type="text" name="search" placeholder="Buscar..." class="form-input"
                       value="{{ request('search') }}">
            </form>
        </div>

        <nav>
            <a href="/">🏠 Início</a>
            @guest
                <a href="{{ route('login') }}">🔑 Entrar</a>
                <a href="{{ route('register') }}">✨ Criar Conta</a>
            @endguest
            @auth
                @if(auth()->user()->isCreator())
                    <a href="{{ route('dashboard') }}">📊 Dashboard</a>
                    <a href="{{ route('dashboard.packs') }}">📦 Meus Packs</a>
                    <a href="{{ route('dashboard.earnings') }}">💰 Ganhos</a>
                @endif
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin') }}">🛡️ Admin</a>
                @endif
                <a href="{{ route('library') }}">📚 Biblioteca</a>
                <div class="divider" style="height:1px;background:var(--border-primary);margin:var(--space-sm) 0;"></div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" style="width:100%;text-align:left;padding:12px 16px;background:none;border:none;color:var(--text-secondary);font-size:1rem;cursor:pointer;font-family:var(--font-body);">🚪 Sair</button>
                </form>
            @endauth
        </nav>

        <div style="margin-top:var(--space-xl);">
            <button class="theme-toggle" style="width:100%;">🌙</button>
        </div>
    </div>

    {{-- ── Toast Notifications ── --}}
    <div class="toast-container" id="toastContainer">
        @if(session('success'))
            <div class="toast toast-success">
                <span>✓</span>
                <span>{{ session('success') }}</span>
                <button class="toast-close">✕</button>
            </div>
        @endif
        @if(session('error'))
            <div class="toast toast-error">
                <span>✕</span>
                <span>{{ session('error') }}</span>
                <button class="toast-close">✕</button>
            </div>
        @endif
        @if(session('info'))
            <div class="toast toast-info">
                <span>ℹ</span>
                <span>{{ session('info') }}</span>
                <button class="toast-close">✕</button>
            </div>
        @endif
        @if(session('warning'))
            <div class="toast toast-warning">
                <span>⚠</span>
                <span>{{ session('warning') }}</span>
                <button class="toast-close">✕</button>
            </div>
        @endif
    </div>

    {{-- ── Main Content ── --}}
    <main>
        @yield('content')
    </main>

    {{-- ── Footer ── --}}
    @hasSection('hide-footer')
    @else
    <footer class="footer">
        <div class="footer-inner">
            <div class="footer-brand">
                <a href="/" class="logo" style="margin-bottom: var(--space-sm); display: inline-flex;">
                    <div class="logo-icon">🔥</div>
                    <span>Clube do Pack</span>
                </a>
                <p>A plataforma definitiva para criadores de conteúdo exclusivo. Monetize seu talento e conecte-se com sua audiência.</p>
            </div>
            <div class="footer-col">
                <h4>Plataforma</h4>
                <a href="/">Explorar</a>
                <a href="{{ route('register') }}">Seja um Criador</a>
                <a href="#">Como Funciona</a>
                <a href="#">Preços</a>
            </div>
            <div class="footer-col">
                <h4>Suporte</h4>
                <a href="#">Central de Ajuda</a>
                <a href="#">Contato</a>
                <a href="#">FAQ</a>
            </div>
            <div class="footer-col">
                <h4>Legal</h4>
                <a href="#">Termos de Uso</a>
                <a href="#">Privacidade</a>
                <a href="#">Política de Cookies</a>
            </div>
        </div>
        <div class="footer-bottom">
            <span>&copy; {{ date('Y') }} Clube do Pack. Todos os direitos reservados.</span>
            <span>Feito com 💖 no Brasil</span>
        </div>
    </footer>
    @endif

    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
