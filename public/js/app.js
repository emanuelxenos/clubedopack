/**
 * Clube do Pack - Main JavaScript
 * Theme Toggle, Dropdowns, Toasts, Upload Preview
 */

document.addEventListener('DOMContentLoaded', () => {
    initThemeToggle();
    initDropdowns();
    initMobileMenu();
    initToasts();
    initUploadZones();
    initDeleteConfirmations();
    initSearchForm();
    initAntiScreenshot();
});

// ═══════════════════ THEME TOGGLE ═══════════════════
function initThemeToggle() {
    const savedTheme = localStorage.getItem('clubedopack-theme') || 'dark';
    document.documentElement.setAttribute('data-theme', savedTheme);
    updateThemeIcon(savedTheme);

    document.querySelectorAll('.theme-toggle').forEach(btn => {
        btn.addEventListener('click', () => {
            const current = document.documentElement.getAttribute('data-theme');
            const next = current === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', next);
            localStorage.setItem('clubedopack-theme', next);
            updateThemeIcon(next);
        });
    });
}

function updateThemeIcon(theme) {
    document.querySelectorAll('.theme-toggle').forEach(btn => {
        btn.innerHTML = theme === 'dark' ? '☀️' : '🌙';
        btn.title = theme === 'dark' ? 'Mudar para tema claro' : 'Mudar para tema escuro';
    });
}

// ═══════════════════ DROPDOWNS ═══════════════════
function initDropdowns() {
    document.querySelectorAll('[data-dropdown]').forEach(trigger => {
        trigger.addEventListener('click', (e) => {
            e.stopPropagation();
            const target = document.getElementById(trigger.dataset.dropdown);
            if (target) {
                target.classList.toggle('active');
            }
        });
    });

    document.addEventListener('click', () => {
        document.querySelectorAll('.user-dropdown.active').forEach(dd => {
            dd.classList.remove('active');
        });
    });
}

// ═══════════════════ MOBILE MENU ═══════════════════
function initMobileMenu() {
    const menuBtn = document.getElementById('mobileMenuBtn');
    const sidebar = document.getElementById('mobileSidebar');
    const overlay = document.getElementById('mobileOverlay');
    const closeBtn = document.getElementById('mobileCloseBtn');

    if (!menuBtn || !sidebar) return;

    function openMenu() {
        sidebar.classList.add('active');
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeMenu() {
        sidebar.classList.remove('active');
        overlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    menuBtn.addEventListener('click', openMenu);
    if (closeBtn) closeBtn.addEventListener('click', closeMenu);
    if (overlay) overlay.addEventListener('click', closeMenu);
}

// ═══════════════════ TOASTS ═══════════════════
function initToasts() {
    const container = document.getElementById('toastContainer');
    if (!container) return;

    container.querySelectorAll('.toast').forEach(toast => {
        const closeBtn = toast.querySelector('.toast-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => dismissToast(toast));
        }
        setTimeout(() => dismissToast(toast), 5000);
    });
}

function dismissToast(toast) {
    toast.style.opacity = '0';
    toast.style.transform = 'translateX(100%)';
    toast.style.transition = 'all 0.3s ease';
    setTimeout(() => toast.remove(), 300);
}

function showToast(message, type = 'info') {
    let container = document.getElementById('toastContainer');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toastContainer';
        container.className = 'toast-container';
        document.body.appendChild(container);
    }

    const icons = { success: '✓', error: '✕', warning: '⚠', info: 'ℹ' };
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerHTML = `
        <span>${icons[type] || 'ℹ'}</span>
        <span>${message}</span>
        <button class="toast-close">✕</button>
    `;

    container.appendChild(toast);

    toast.querySelector('.toast-close').addEventListener('click', () => dismissToast(toast));
    setTimeout(() => dismissToast(toast), 5000);
}

// ═══════════════════ UPLOAD ZONES ═══════════════════
function initUploadZones() {
    document.querySelectorAll('.upload-zone').forEach(zone => {
        const input = zone.querySelector('input[type="file"]');
        const preview = zone.parentElement.querySelector('.upload-preview');

        if (!input) return;

        // Click to open file picker
        zone.addEventListener('click', () => input.click());

        // Drag & drop
        zone.addEventListener('dragover', (e) => {
            e.preventDefault();
            zone.classList.add('dragover');
        });

        zone.addEventListener('dragleave', () => {
            zone.classList.remove('dragover');
        });

        zone.addEventListener('drop', (e) => {
            e.preventDefault();
            zone.classList.remove('dragover');
            if (e.dataTransfer.files.length) {
                input.files = e.dataTransfer.files;
                input.dispatchEvent(new Event('change'));
            }
        });

        // Preview
        if (preview) {
            input.addEventListener('change', () => {
                preview.innerHTML = '';
                Array.from(input.files).forEach((file, i) => {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            const item = document.createElement('div');
                            item.className = 'upload-preview-item';
                            item.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                            preview.appendChild(item);
                        };
                        reader.readAsDataURL(file);
                    } else {
                        const item = document.createElement('div');
                        item.className = 'upload-preview-item';
                        item.innerHTML = `<div class="placeholder-image">🎬</div>`;
                        preview.appendChild(item);
                    }
                });
            });
        }
    });
}

// ═══════════════════ DELETE CONFIRMATIONS ═══════════════════
function initDeleteConfirmations() {
    document.querySelectorAll('[data-confirm]').forEach(btn => {
        btn.addEventListener('click', (e) => {
            if (!confirm(btn.dataset.confirm || 'Tem certeza?')) {
                e.preventDefault();
            }
        });
    });
}

// ═══════════════════ SEARCH FORM ═══════════════════
function initSearchForm() {
    const searchInput = document.getElementById('headerSearch');
    if (!searchInput) return;

    let timeout;
    searchInput.addEventListener('keyup', (e) => {
        if (e.key === 'Enter') {
            const value = searchInput.value.trim();
            if (value) {
                window.location.href = `/?search=${encodeURIComponent(value)}`;
            }
        }
    });
}

// ═══════════════════ LIBRARY TABS ═══════════════════
function switchTab(tabName) {
    document.querySelectorAll('.library-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(c => c.classList.add('hidden'));

    document.querySelector(`[data-tab="${tabName}"]`)?.classList.add('active');
    document.getElementById(`tab-${tabName}`)?.classList.remove('hidden');
}

// ═══════════════════ ANTI-SCREENSHOT SYSTEM ═══════════════════
function initAntiScreenshot() {
    // 1. Create dynamic black security overlay
    const overlay = document.createElement('div');
    overlay.id = 'antiScreenshotOverlay';
    overlay.style.cssText = `
        display: none;
        position: fixed;
        inset: 0;
        background: #000000;
        z-index: 99999999;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        color: #ffffff;
        font-family: 'Outfit', sans-serif;
        text-align: center;
        padding: 40px;
    `;
    overlay.innerHTML = `
        <div style="font-size: 5rem; margin-bottom: 20px;">🛡️</div>
        <h1 style="font-size: 2.2rem; margin-bottom: 15px; color: #e91e8c; font-weight: 800; text-transform: uppercase; letter-spacing: 1px;">⚠️ CAPTURA BLOQUEADA!</h1>
        <p style="font-size: 1.15rem; color: #a0a0a0; margin-bottom: 30px; max-width: 550px; line-height: 1.6;">
            Por motivos de segurança e proteção de direitos autorais, capturas de tela, impressões e cópias estão bloqueadas nesta área.
        </p>
        <div style="font-size: 1.4rem; font-style: italic; color: #ff6bb5; font-weight: 700; margin-bottom: 35px;">
            "Haha, pegamos o expertão! 😉🔒"
        </div>
        <div style="background: rgba(255,255,255,0.03); padding: 16px 28px; border-radius: 16px; border: 1px solid rgba(255,255,255,0.08); font-size: 0.95rem; color: #777; line-height: 1.5; max-width: 90%;">
            Plataforma: <strong>Clube do Pack</strong><br>
            Link Protegido: <span style="color: #ff6bb5; word-break: break-all;">${window.location.href}</span>
        </div>
    `;
    document.body.appendChild(overlay);

    function triggerBlocker() {
        overlay.style.display = 'flex';
        // Auto-dismiss after 3 seconds when triggered by keyboard
        setTimeout(() => {
            overlay.style.display = 'none';
        }, 3000);
    }

    // 2. Prevent PrintScreen keyboard button press
    window.addEventListener('keyup', (e) => {
        if (e.key === 'PrintScreen' || e.keyCode === 44) {
            triggerBlocker();
            // Clear clipboard to delete the captured screenshot if stored
            navigator.clipboard.writeText('⚠️ Acesso Bloqueado - Clube do Pack');
        }
    });

    // 3. Prevent Screenshot tools (losing focus / blurring window)
    window.addEventListener('blur', () => {
        // Show overlay to blank screen during capture utility activation
        overlay.style.display = 'flex';
    });

    window.addEventListener('focus', () => {
        // Hide overlay with a smooth transition after focus returns
        setTimeout(() => {
            overlay.style.display = 'none';
        }, 1200);
    });

    // 4. Disable right click, context menu and dragging in the gallery
    document.addEventListener('contextmenu', (e) => {
        if (e.target.closest('.pack-gallery') || e.target.closest('.pack-image') || e.target.closest('.gallery-item')) {
            e.preventDefault();
            showToast('⚠️ Ação não permitida para proteção de direitos autorais!', 'warning');
        }
    });

    document.addEventListener('dragstart', (e) => {
        if (e.target.closest('.pack-gallery') || e.target.closest('.pack-image') || e.target.closest('.gallery-item')) {
            e.preventDefault();
        }
    });

    // 5. Disable key combinations (Ctrl+S, Ctrl+U, Ctrl+Shift+I, Cmd+Shift+4)
    window.addEventListener('keydown', (e) => {
        // Ctrl+S (Save page)
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
            e.preventDefault();
            showToast('⚠️ Salvar página bloqueado.', 'warning');
        }
        // Ctrl+U (View source)
        if ((e.ctrlKey || e.metaKey) && e.key === 'u') {
            e.preventDefault();
            showToast('⚠️ Visualizar código bloqueado.', 'warning');
        }
    });
}
