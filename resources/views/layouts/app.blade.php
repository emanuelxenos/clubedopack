<!DOCTYPE html>
<html lang="pt-BR" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Clube do Pack - Marketplace de conteúdo exclusivo dos melhores criadores. Packs de fotos e vídeos premium.">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Clube do Pack') - Marketplace de Conteúdo Exclusivo</title>
    <link rel="manifest" href="/manifest.json">
    <link rel="icon" type="image/png" href="/icon.png">
    <meta name="theme-color" content="#e91e8c">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>
    {{-- ── Header ── --}}
    <header class="header">
        <div class="header-inner">
            <button class="mobile-menu-btn" id="mobileMenuBtn">☰</button>

            <a href="/" class="logo" style="display: flex; align-items: center; gap: 8px;">
                <img src="/icon.png" alt="Clube do Pack Icon" style="height: 32px; width: 32px; object-fit: cover; border-radius: 8px; box-shadow: 0 4px 10px rgba(233, 30, 140, 0.25);">
                <span style="font-weight: 700; background: linear-gradient(to right, var(--text-primary), #e91e8c); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Clube do Pack</span>
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
                                <a href="{{ route('admin.earnings') }}">💰 Meus Ganhos</a>
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
            <a href="/" class="logo" style="display: flex; align-items: center; gap: 8px;">
                <img src="/icon.png" alt="Clube do Pack Icon" style="height: 28px; width: 28px; object-fit: cover; border-radius: 6px;">
                <span style="font-weight: 700; background: linear-gradient(to right, var(--text-primary), #e91e8c); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Clube do Pack</span>
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
                    <a href="{{ route('admin') }}">🛡️ Admin Dashboard</a>
                    <a href="{{ route('admin.users') }}">👥 Admin: Usuários</a>
                    <a href="{{ route('admin.transactions') }}">💳 Admin: Transações</a>
                    <a href="{{ route('admin.earnings') }}">💰 Admin: Meus Ganhos</a>
                    <a href="{{ route('admin.withdrawals') }}">💸 Admin: Saques Criadores</a>
                    <a href="{{ route('admin.categories') }}">🏷️ Admin: Categorias</a>
                    <a href="{{ route('admin.settings') }}">⚙️ Admin: Configurações</a>
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
                <a href="/" class="logo" style="margin-bottom: var(--space-sm); display: inline-flex; align-items: center; gap: 8px;">
                    <img src="/icon.png" alt="Clube do Pack Icon" style="height: 28px; width: 28px; object-fit: cover; border-radius: 6px;">
                    <span style="font-weight: 700; background: linear-gradient(to right, var(--text-primary), #e91e8c); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Clube do Pack</span>
                </a>
                <p>A plataforma definitiva para criadores de conteúdo exclusivo. Monetize seu talento e conecte-se com sua audiência.</p>
            </div>
            <div class="footer-col">
                <h4>Plataforma</h4>
                <a href="/">Explorar</a>
                <a href="{{ route('register') }}">Seja um Criador</a>
                <a href="{{ route('pages.how-it-works') }}">Como Funciona</a>
                <a href="{{ route('pages.pricing') }}">Preços</a>
            </div>
            <div class="footer-col">
                <h4>Suporte</h4>
                <a href="{{ route('pages.help-center') }}">Central de Ajuda</a>
                <a href="{{ route('pages.contact') }}">Contato</a>
                <a href="{{ route('pages.faq') }}">FAQ</a>
            </div>
            <div class="footer-col">
                <h4>Legal</h4>
                <a href="{{ route('pages.terms') }}">Termos de Uso</a>
                <a href="{{ route('pages.privacy') }}">Privacidade</a>
                <a href="{{ route('pages.cookies') }}">Política de Cookies</a>
            </div>
        </div>
        <div class="footer-bottom">
            <span>&copy; {{ date('Y') }} Clube do Pack. Todos os direitos reservados.</span>
            <span>Feito com 💖 no Brasil</span>
        </div>
    </footer>
    @endif


    {{-- ── PWA Smart Install Banner ── --}}
    <div id="pwa-install-banner" style="display: none; position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%); width: calc(100% - 40px); max-width: 480px; background: rgba(13, 14, 18, 0.85); backdrop-filter: blur(16px); border: 1px solid rgba(233, 30, 140, 0.3); border-radius: 16px; padding: 14px 20px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5); z-index: 99999; align-items: center; justify-content: space-between; gap: 16px; animation: slideUpPwa 0.5s cubic-bezier(0.16, 1, 0.3, 1);">
        <div style="display: flex; align-items: center; gap: 12px; flex: 1;">
            <img src="/icon.png" alt="Clube do Pack" style="height: 36px; width: 36px; object-fit: cover; border-radius: 8px; box-shadow: 0 4px 10px rgba(233, 30, 140, 0.25);">
            <div>
                <h4 style="margin: 0; color: var(--text-primary); font-size: 0.95rem; font-weight: 700;">Instale o Clube do Pack</h4>
                <p style="margin: 2px 0 0 0; color: var(--text-secondary); font-size: 0.8rem; line-height: 1.2;">Tenha acesso rápido e em tela cheia na sua tela inicial!</p>
            </div>
        </div>
        <div style="display: flex; align-items: center; gap: 8px;">
            <button id="pwa-install-btn" class="btn btn-primary btn-sm" style="padding: 6px 14px; font-size: 0.85rem; font-weight: 600; border-radius: 8px; box-shadow: 0 4px 12px rgba(233, 30, 140, 0.3);">Instalar</button>
            <button id="pwa-close-btn" style="background: none; border: none; color: var(--text-tertiary); font-size: 1.1rem; cursor: pointer; padding: 4px; display: flex; align-items: center; justify-content: center;">✕</button>
        </div>
    </div>

    <style>
    @keyframes slideUpPwa {
        from { transform: translate(-50%, 100px); opacity: 0; }
        to { transform: translate(-50%, 0); opacity: 1; }
    }
    </style>

    <script>
        // Registro do Service Worker para suporte PWA
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(reg => console.log('PWA Service Worker ativo!', reg.scope))
                    .catch(err => console.warn('Falha ao registrar Service Worker:', err));
            });
        }

        // Lógica elegante de captura e exibição do Smart Banner de Instalação
        let deferredPrompt;
        const pwaBanner = document.getElementById('pwa-install-banner');
        const installBtn = document.getElementById('pwa-install-btn');
        const closeBtn = document.getElementById('pwa-close-btn');

        window.addEventListener('beforeinstallprompt', (e) => {
            // Impede o banner padrão do navegador de aparecer
            e.preventDefault();
            deferredPrompt = e;

            // Checa se o usuário já fechou este banner nesta sessão para não ser intrusivo
            if (!sessionStorage.getItem('pwa-banner-dismissed')) {
                pwaBanner.style.display = 'flex';
            }
        });

        installBtn.addEventListener('click', async () => {
            if (deferredPrompt) {
                deferredPrompt.prompt();
                const { outcome } = await deferredPrompt.userChoice;
                console.log(`Resposta da instalação PWA: ${outcome}`);
                deferredPrompt = null;
                pwaBanner.style.display = 'none';
            }
        });

        closeBtn.addEventListener('click', () => {
            pwaBanner.style.display = 'none';
            sessionStorage.setItem('pwa-banner-dismissed', 'true');
        });
    </script>

    {{-- Global Checkout Modal --}}
    @auth
    <div id="global-checkout-modal" class="modal-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100vh; background: rgba(0,0,0,0.85); backdrop-filter: blur(12px); z-index: 100000; justify-content: center; align-items: center; padding: 20px;">
        <div class="modal-card" style="background: var(--bg-secondary); border: 1px solid rgba(255,255,255,0.08); border-radius: var(--radius-lg); width: 100%; max-width: 480px; box-shadow: var(--shadow-xl); overflow: hidden; animation: modalFadeIn 0.3s ease;">
            <div style="padding: 20px 24px; border-bottom: 1px solid var(--border-primary); display: flex; justify-content: space-between; align-items: center; background: rgba(255,255,255,0.02);">
                <h3 id="checkout-title" style="margin: 0; font-size: 1.25rem; font-weight: 700; color: var(--text-primary);">Finalizar Compra</h3>
                <button onclick="closeCheckoutModal()" style="background: none; border: none; color: var(--text-tertiary); font-size: 1.5rem; cursor: pointer; display: flex; align-items: center; justify-content: center;">✕</button>
            </div>

            <form id="checkout-form" method="POST" action="" style="padding: 24px; max-height: calc(100vh - 120px); overflow-y: auto;">
                @csrf
                
                <div id="checkout-price-display" style="text-align: center; margin-bottom: var(--space-lg); font-size: 2rem; font-weight: 800; color: var(--accent-primary); text-shadow: var(--shadow-glow);">
                    R$ 0,00
                </div>

                {{-- Método de Pagamento --}}
                <div style="margin-bottom: var(--space-lg);">
                    <label style="display: block; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-tertiary); margin-bottom: 8px; font-weight: 600;">Forma de Pagamento</label>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-md);">
                        <label class="pay-method-option" style="display: flex; flex-direction: column; align-items: center; gap: 8px; padding: 14px; border: 2px solid var(--accent-primary); border-radius: var(--radius-md); cursor: pointer; background: rgba(233,30,140,0.05); transition: all var(--transition-fast);">
                            <input type="radio" name="payment_method" value="pix" checked style="display: none;" onchange="togglePaymentMethod('pix')">
                            <span style="font-size: 1.5rem;">⚡</span>
                            <span style="font-weight: 600; font-size: 0.9rem; color: #fff;">Pix (Imediato)</span>
                        </label>
                        <label class="pay-method-option" style="display: flex; flex-direction: column; align-items: center; gap: 8px; padding: 14px; border: 2px solid rgba(255,255,255,0.08); border-radius: var(--radius-md); cursor: pointer; transition: all var(--transition-fast);">
                            <input type="radio" name="payment_method" value="credit_card" style="display: none;" onchange="togglePaymentMethod('credit_card')">
                            <span style="font-size: 1.5rem;">💳</span>
                            <span style="font-weight: 600; font-size: 0.9rem; color: var(--text-secondary);">Cartão de Crédito</span>
                        </label>
                    </div>
                </div>

                {{-- Formulário de Cartão --}}
                <div id="credit-card-form" style="display: none; animation: slideDown 0.3s ease;">
                    <div style="margin-bottom: var(--space-md);">
                        <label class="form-label" style="display:block;margin-bottom:6px;font-size:0.85rem;">Número do Cartão</label>
                        <input type="text" name="card_number" id="card_number" class="form-input" placeholder="0000 0000 0000 0000" maxlength="19">
                    </div>

                    <div style="margin-bottom: var(--space-md);">
                        <label class="form-label" style="display:block;margin-bottom:6px;font-size:0.85rem;">Nome Impresso no Cartão</label>
                        <input type="text" name="card_name" class="form-input" placeholder="COMO NO CARTÃO" style="text-transform: uppercase;">
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-md); margin-bottom: var(--space-md);">
                        <div>
                            <label class="form-label" style="display:block;margin-bottom:6px;font-size:0.85rem;">Validade</label>
                            <input type="text" name="card_expiry" id="card_expiry" class="form-input" placeholder="MM/AA" maxlength="5">
                        </div>
                        <div>
                            <label class="form-label" style="display:block;margin-bottom:6px;font-size:0.85rem;">CVV</label>
                            <input type="text" name="card_cvv" class="form-input" placeholder="123" maxlength="4">
                        </div>
                    </div>

                    <div style="border-top: 1px solid var(--border-primary); margin: var(--space-lg) 0; padding-top: var(--space-md);">
                        <h4 style="margin: 0 0 var(--space-md) 0; font-size: 0.9rem; color: var(--text-primary); font-weight: 600;">Dados de Cobrança</h4>
                        
                        <div style="margin-bottom: var(--space-md);">
                            <label class="form-label" style="display:block;margin-bottom:6px;font-size:0.85rem;">CPF do Titular</label>
                            <input type="text" name="holder_cpf" id="holder_cpf" class="form-input" placeholder="000.000.000-00" maxlength="14">
                        </div>

                        <div style="margin-bottom: var(--space-md);">
                            <label class="form-label" style="display:block;margin-bottom:6px;font-size:0.85rem;">Celular do Titular</label>
                            <input type="text" name="holder_phone" id="holder_phone" class="form-input" placeholder="(00) 90000-0000" maxlength="15">
                        </div>

                        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: var(--space-md);">
                            <div>
                                <label class="form-label" style="display:block;margin-bottom:6px;font-size:0.85rem;">CEP</label>
                                <input type="text" name="holder_zip" id="holder_zip" class="form-input" placeholder="00000-000" maxlength="9">
                            </div>
                            <div>
                                <label class="form-label" style="display:block;margin-bottom:6px;font-size:0.85rem;">Número Residencial</label>
                                <input type="text" name="holder_address_num" class="form-input" placeholder="123">
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg" style="margin-top: var(--space-md);">
                    Confirmar e Pagar
                </button>
            </form>
        </div>
    </div>

    {{-- Modal de Pagamento Pix --}}
    @if(session('show_pix_modal'))
    <div id="pix-payment-modal" class="modal-overlay" style="display: flex; position: fixed; top: 0; left: 0; width: 100%; height: 100vh; background: rgba(0,0,0,0.85); backdrop-filter: blur(12px); z-index: 100001; justify-content: center; align-items: center; padding: 20px;">
        <div class="modal-card" style="background: var(--bg-secondary); border: 1px solid rgba(255,255,255,0.08); border-radius: var(--radius-lg); width: 100%; max-width: 440px; box-shadow: var(--shadow-xl); text-align: center; padding: 30px; animation: modalFadeIn 0.3s ease;">
            <h3 style="margin: 0 0 10px 0; color: var(--text-primary); font-weight: 700; font-size: 1.3rem;">⚡ Pagamento via PIX</h3>
            <p style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 20px;">Escaneie o QR Code abaixo ou copie a chave copia e cola para pagar.</p>
            
            <div style="background: white; padding: 16px; border-radius: var(--radius-md); display: inline-block; margin-bottom: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.3);">
                <img src="data:image/png;base64,{{ session('pix_qr_base64') }}" alt="QR Code Pix" style="width: 200px; height: 200px; display: block;">
            </div>

            <div style="margin-bottom: 25px; text-align: left;">
                <label style="display:block; font-size:0.8rem; color:var(--text-tertiary); margin-bottom:6px; text-transform:uppercase; font-weight:600;">Pix Copia e Cola</label>
                <div style="display: flex; gap: 8px;">
                    <input type="text" id="pix-emv-input" class="form-input" value="{{ session('pix_copy_paste') }}" readonly style="font-family: monospace; font-size: 0.8rem; flex: 1;">
                    <button onclick="copyPixCode()" class="btn btn-primary" style="white-space: nowrap; padding: 0 16px; font-size: 0.9rem;">Copiar</button>
                </div>
            </div>

            <div style="background: rgba(255, 255, 255, 0.03); padding: 12px; border-radius: var(--radius-md); border: 1px dashed rgba(255,255,255,0.1); margin-bottom: 25px; display: flex; align-items: center; justify-content: center; gap: 10px;">
                <div class="lb-spinner" style="position:static; transform:none; width: 16px; height: 16px; border-width: 2px;"></div>
                <span style="font-size: 0.85rem; color: var(--text-secondary);">Aguardando confirmação do pagamento...</span>
            </div>

            <button onclick="closePixModal()" class="btn btn-block" style="background: rgba(255,255,255,0.05); color: var(--text-secondary);">Fechar</button>
            @if(config('app.env') === 'local')
                <button onclick="simulatePixPayment('{{ session('purchase_id') }}', '{{ session('subscription_id') }}')" class="btn btn-secondary btn-block" style="margin-top: 10px; background: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.2); width: 100%;">
                    ⚡ Simular Pagamento (Dev)
                </button>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let checkRoute = '';
            @if(session('purchase_id'))
                checkRoute = "/purchase/{{ session('purchase_id') }}/status";
            @elseif(session('subscription_id'))
                checkRoute = "/subscription/{{ session('subscription_id') }}/status";
            @endif

            if (checkRoute) {
                const interval = setInterval(async () => {
                    try {
                        const res = await fetch(checkRoute);
                        const data = await res.json();
                        if (data.status === 'confirmed' || data.status === 'active') {
                            clearInterval(interval);
                            alert('🎉 Pagamento confirmado com sucesso!');
                            window.location.reload();
                        }
                    } catch (e) {
                        console.error("Error checking payment status:", e);
                    }
                }, 3000);
            }
        });

        async function simulatePixPayment(purchaseId, subscriptionId) {
            const externalRef = purchaseId ? `purchase_${purchaseId}` : `subscription_${subscriptionId}`;
            try {
                const response = await fetch('/webhooks/asaas', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        event: 'PAYMENT_RECEIVED',
                        payment: {
                            externalReference: externalRef,
                            id: 'mock_payment_' + Math.random().toString(36).substr(2, 9),
                            billingType: 'PIX'
                        }
                    })
                });
                const data = await response.json();
                if (data.status === 'success') {
                    if (window.showToast) {
                        window.showToast('Pagamento Pix simulado com sucesso!', 'success');
                    } else {
                        alert('Pagamento Pix simulado com sucesso!');
                    }
                } else {
                    alert('Erro ao simular pagamento: ' + JSON.stringify(data));
                }
            } catch (e) {
                console.error(e);
                alert('Erro na requisição de simulação.');
            }
        }
    </script>
    @endif

    <script>
        function openCheckoutModal(type, id, title, price) {
            const modal = document.getElementById('global-checkout-modal');
            const form = document.getElementById('checkout-form');
            const titleEl = document.getElementById('checkout-title');
            const priceEl = document.getElementById('checkout-price-display');

            titleEl.textContent = title;
            priceEl.textContent = price;
            
            if (type === 'pack') {
                form.action = `/pack/${id}/purchase`;
            } else {
                form.action = `/creator/${id}/subscribe`;
            }

            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeCheckoutModal() {
            const modal = document.getElementById('global-checkout-modal');
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }

        function togglePaymentMethod(method) {
            const cardForm = document.getElementById('credit-card-form');
            const options = document.querySelectorAll('.pay-method-option');
            
            options.forEach(opt => {
                const input = opt.querySelector('input');
                if (input.value === method) {
                    opt.style.borderColor = 'var(--accent-primary)';
                    opt.style.background = 'rgba(233,30,140,0.05)';
                    opt.querySelector('span:last-child').style.color = '#fff';
                } else {
                    opt.style.borderColor = 'rgba(255,255,255,0.08)';
                    opt.style.background = 'none';
                    opt.querySelector('span:last-child').style.color = 'var(--text-secondary)';
                }
            });

            if (method === 'credit_card') {
                cardForm.style.display = 'block';
            } else {
                cardForm.style.display = 'none';
            }
        }

        function copyPixCode() {
            const input = document.getElementById('pix-emv-input');
            input.select();
            input.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(input.value);
            alert('Chave copia e cola copiada para a área de transferência!');
        }

        function closePixModal() {
            const modal = document.getElementById('pix-payment-modal');
            if (modal) modal.style.display = 'none';
        }

        document.addEventListener('DOMContentLoaded', () => {
            // Mask Card Number
            const cardNum = document.getElementById('card_number');
            if (cardNum) {
                cardNum.addEventListener('input', (e) => {
                    let v = e.target.value.replace(/\D/g, '');
                    v = v.replace(/(\d{4})(?=\d)/g, '$1 ');
                    e.target.value = v.substring(0, 19);
                });
            }

            // Mask Expiry
            const cardExp = document.getElementById('card_expiry');
            if (cardExp) {
                cardExp.addEventListener('input', (e) => {
                    let v = e.target.value.replace(/\D/g, '');
                    if (v.length > 2) {
                        v = v.substring(0, 2) + '/' + v.substring(2, 4);
                    }
                    e.target.value = v.substring(0, 5);
                });
            }

            // Mask CPF
            const holderCpf = document.getElementById('holder_cpf');
            if (holderCpf) {
                holderCpf.addEventListener('input', (e) => {
                    let v = e.target.value.replace(/\D/g, '');
                    if (v.length > 9) {
                        v = v.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, "$1.$2.$3-$4");
                    } else if (v.length > 6) {
                        v = v.replace(/(\d{3})(\d{3})(\d{3})/, "$1.$2.$3");
                    } else if (v.length > 3) {
                        v = v.replace(/(\d{3})(\d{3})/, "$1.$2");
                    }
                    e.target.value = v.substring(0, 14);
                });
            }

            // Mask Phone
            const holderPhone = document.getElementById('holder_phone');
            if (holderPhone) {
                holderPhone.addEventListener('input', (e) => {
                    let v = e.target.value.replace(/\D/g, '');
                    if (v.length > 10) {
                        v = v.replace(/^(\d{2})(\d{5})(\d{4})$/, "($1) $2-$3");
                    } else if (v.length > 5) {
                        v = v.replace(/^(\d{2})(\d{4})(\d{0,4})$/, "($1) $2-$3");
                    } else if (v.length > 2) {
                        v = v.replace(/^(\d{2})(\d{0,5})$/, "($1) $2");
                    } else if (v.length > 0) {
                        v = "(" + v;
                    }
                    e.target.value = v.substring(0, 15);
                });
            }

            // Mask Zip
            const holderZip = document.getElementById('holder_zip');
            if (holderZip) {
                holderZip.addEventListener('input', (e) => {
                    let v = e.target.value.replace(/\D/g, '');
                    if (v.length > 5) {
                        v = v.substring(0, 5) + '-' + v.substring(5, 8);
                    }
                    e.target.value = v.substring(0, 9);
                });
            }
        });
    </script>
    @endauth

    @stack('scripts')
</body>
</html>
