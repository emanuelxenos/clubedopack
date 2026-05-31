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
