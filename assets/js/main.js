/**
 * SMK Pertamaku - Main JavaScript (Tailwind version)
 */

/* ============================================================
   Theme Toggle
   ============================================================ */
const THEME_KEY = 'smk_theme';

function initTheme() {
    const saved = localStorage.getItem(THEME_KEY) || 'light';
    if (saved === 'dark') {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
    // Sync icons on load
    syncThemeIcons();
}

function toggleTheme() {
    const isDark = document.documentElement.classList.contains('dark');
    if (isDark) {
        document.documentElement.classList.remove('dark');
        localStorage.setItem(THEME_KEY, 'light');
    } else {
        document.documentElement.classList.add('dark');
        localStorage.setItem(THEME_KEY, 'dark');
    }
    syncThemeIcons();
}

function syncThemeIcons() {
    const isDark = document.documentElement.classList.contains('dark');
    // Show sun icon in dark mode, moon icon in light mode
    document.querySelectorAll('.theme-icon-light').forEach(el => {
        el.style.display = isDark ? 'none' : '';
    });
    document.querySelectorAll('.theme-icon-dark').forEach(el => {
        el.style.display = isDark ? '' : 'none';
    });
}

/* ============================================================
   Mobile Menu Toggle
   ============================================================ */
function initMobileMenu() {
    const btn = document.getElementById('mobileMenuBtn');
    const menu = document.getElementById('mobileMenu');
    const icon = document.getElementById('menuIcon');
    if (!btn || !menu) return;

    btn.addEventListener('click', () => {
        const isHidden = menu.classList.contains('hidden');
        if (isHidden) {
            menu.classList.remove('hidden');
            icon.className = 'fas fa-times text-lg';
        } else {
            menu.classList.add('hidden');
            icon.className = 'fas fa-bars text-lg';
        }
    });
}

/* ============================================================
   Navbar Scroll Effect
   ============================================================ */
function initNavbarScroll() {
    const navbar = document.getElementById('mainNav');
    if (!navbar) return;
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            navbar.classList.add('border-slate-200', 'dark:border-slate-700', 'shadow-sm');
            navbar.classList.remove('border-transparent');
        } else {
            navbar.classList.remove('border-slate-200', 'dark:border-slate-700', 'shadow-sm');
            navbar.classList.add('border-transparent');
        }
    });
}

/* ============================================================
   Back to Top Button
   ============================================================ */
function initBackToTop() {
    const btn = document.getElementById('backToTop');
    if (!btn) return;
    window.addEventListener('scroll', () => {
        if (window.scrollY > 300) {
            btn.classList.add('visible');
        } else {
            btn.classList.remove('visible');
        }
    });
    btn.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
}

/* ============================================================
   Counter Animation for Stats
   ============================================================ */
function animateCounter(el, target, duration) {
    let start = 0;
    const step = target / (duration / 16);
    const timer = setInterval(() => {
        start += step;
        if (start >= target) {
            el.textContent = target.toLocaleString('id-ID');
            clearInterval(timer);
        } else {
            el.textContent = Math.floor(start).toLocaleString('id-ID');
        }
    }, 16);
}

function initCounters() {
    const counters = document.querySelectorAll('.stat-number[data-target]');
    if (!counters.length) return;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const el = entry.target;
                const target = parseInt(el.getAttribute('data-target'), 10);
                animateCounter(el, target, 1500);
                observer.unobserve(el);
            }
        });
    }, { threshold: 0.5 });

    counters.forEach(c => observer.observe(c));
}

/* ============================================================
   Hero Slider
   ============================================================ */
function initHeroSlider() {
    const slides = document.querySelectorAll('.hero-slide');
    const indicators = document.querySelectorAll('.hero-indicator');
    if (slides.length < 2) return;

    let current = 0;
    const total = slides.length;

    function goTo(index) {
        slides[current].classList.remove('active');
        if (indicators[current]) indicators[current].classList.remove('w-6', 'bg-white');
        if (indicators[current]) indicators[current].classList.add('w-2', 'bg-white/50');

        current = index;

        slides[current].classList.add('active');
        if (indicators[current]) indicators[current].classList.remove('w-2', 'bg-white/50');
        if (indicators[current]) indicators[current].classList.add('w-6', 'bg-white');
    }

    function next() { goTo((current + 1) % total); }
    function prev() { goTo((current - 1 + total) % total); }

    // Auto play
    let interval = setInterval(next, 5500);

    // Controls
    const prevBtn = document.getElementById('heroPrev');
    const nextBtn = document.getElementById('heroNext');
    if (prevBtn) prevBtn.addEventListener('click', () => { clearInterval(interval); prev(); interval = setInterval(next, 5500); });
    if (nextBtn) nextBtn.addEventListener('click', () => { clearInterval(interval); next(); interval = setInterval(next, 5500); });

    // Indicators
    indicators.forEach((dot, i) => {
        dot.addEventListener('click', () => { clearInterval(interval); goTo(i); interval = setInterval(next, 5500); });
    });

    // Touch/Swipe support for mobile
    const sliderContainer = document.querySelector('.hero-slider');
    if (sliderContainer) {
        let touchStartX = 0;
        let touchEndX = 0;
        sliderContainer.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });
        sliderContainer.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            const diff = touchStartX - touchEndX;
            if (Math.abs(diff) > 50) {
                clearInterval(interval);
                if (diff > 0) { next(); } else { prev(); }
                interval = setInterval(next, 5500);
            }
        }, { passive: true });
    }
}

/* ============================================================
   Gallery Lightbox
   ============================================================ */
function initLightbox() {
    const items = document.querySelectorAll('.gallery-item[data-src]');
    if (!items.length) return;

    items.forEach(item => {
        item.addEventListener('click', () => {
            openLightbox(item.getAttribute('data-src'), item.getAttribute('data-title') || '');
        });
    });
}

function openLightbox(src, caption) {
    const overlay = document.getElementById('lightboxOverlay');
    const img = document.getElementById('lightboxImg');
    const cap = document.getElementById('lightboxCaption');
    if (!overlay) return;
    img.src = src;
    img.style.display = 'block';
    if (cap) cap.textContent = caption;
    overlay.style.display = 'flex';
    overlay.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeLightbox() {
    const overlay = document.getElementById('lightboxOverlay');
    const img = document.getElementById('lightboxImg');
    if (!overlay) return;
    overlay.classList.remove('active');
    overlay.style.display = 'none';
    if (img) { img.style.display = 'none'; img.src = ''; }
    document.body.style.overflow = '';
}

/* ============================================================
   SPMB Multi-step Form
   ============================================================ */
function initMultiStep() {
    const form = document.getElementById('spmbForm');
    if (!form) return;

    let currentStep = 0;
    const panels = form.querySelectorAll('.step-panel');
    const stepItems = document.querySelectorAll('.step-item');
    const nextBtns = form.querySelectorAll('.btn-next');
    const prevBtns = form.querySelectorAll('.btn-prev');

    function showStep(n) {
        panels.forEach((p, i) => {
            p.classList.toggle('active', i === n);
        });
        stepItems.forEach((s, i) => {
            s.classList.remove('text-blue-600', 'border-blue-600', 'text-green-600', 'border-green-600');
            if (i === n) { s.classList.add('text-blue-600', 'border-blue-600'); }
            if (i < n) { s.classList.add('text-green-600', 'border-green-600'); }
        });
        currentStep = n;
    }

    function validateStep(n) {
        const panel = panels[n];
        const required = panel.querySelectorAll('[required]');
        let valid = true;
        required.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('border-red-500');
                valid = false;
            } else {
                field.classList.remove('border-red-500');
            }
        });
        return valid;
    }

    nextBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            if (validateStep(currentStep) && currentStep < panels.length - 1) {
                if (currentStep === panels.length - 2) buildPreview();
                showStep(currentStep + 1);
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });
    });

    prevBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            if (currentStep > 0) {
                showStep(currentStep - 1);
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });
    });

    showStep(0);
}

/* ============================================================
   Build Preview for SPMB form
   ============================================================ */
function buildPreview() {
    const form = document.getElementById('spmbForm');
    if (!form) return;
    const preview = document.getElementById('previewData');
    if (!preview) return;

    const fields = form.querySelectorAll('input, select, textarea');
    let html = '<dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-2">';
    fields.forEach(f => {
        if (!f.name || f.type === 'hidden' || f.type === 'file') return;
        const label = form.querySelector('label[for="' + f.id + '"]');
        const labelText = label ? label.textContent.replace('*','').trim() : f.name;
        let val = f.value;
        if (f.tagName === 'SELECT') {
            const opt = f.options[f.selectedIndex];
            val = opt ? opt.text : val;
        }
        if (val) {
            html += `<dt class="text-sm text-slate-500">${labelText}</dt><dd class="text-sm font-medium text-slate-800 dark:text-white mb-2">${val}</dd>`;
        }
    });
    html += '</dl>';
    preview.innerHTML = html;
}

/* ============================================================
   Auto Slug Generator (admin forms)
   ============================================================ */
function initAutoSlug() {
    const titleField = document.getElementById('title');
    const slugField = document.getElementById('slug');
    if (!titleField || !slugField) return;

    titleField.addEventListener('input', () => {
        if (slugField.dataset.auto !== 'false') {
            slugField.value = titleField.value
                .toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .trim()
                .replace(/\s+/g, '-');
        }
    });

    slugField.addEventListener('input', () => {
        slugField.dataset.auto = 'false';
    });
}

/* ============================================================
   Image Upload Preview
   ============================================================ */
function initImagePreview() {
    document.querySelectorAll('.image-upload-input').forEach(input => {
        input.addEventListener('change', function () {
            const preview = document.querySelector(this.dataset.preview);
            if (!preview) return;
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = e => { preview.src = e.target.result; preview.style.display = 'block'; };
                reader.readAsDataURL(file);
            }
        });
    });
}

/* ============================================================
   Preloader
   ============================================================ */
function initPreloader() {
    const loader = document.getElementById('preloader');
    if (!loader) return;
    const hide = () => loader.classList.add('hidden');
    if (document.readyState === 'complete') {
        setTimeout(hide, 200);
    } else {
        window.addEventListener('load', () => setTimeout(hide, 300));
    }
}

/* ============================================================
   Admin Sidebar Toggle (mobile)
   ============================================================ */
function initAdminSidebar() {
    const toggle = document.getElementById('sidebarToggle');
    const sidebar = document.querySelector('.admin-sidebar');
    if (!toggle || !sidebar) return;
    toggle.addEventListener('click', () => {
        sidebar.classList.toggle('open');
    });
}

/* ============================================================
   Alert Auto-dismiss
   ============================================================ */
function initAlertDismiss() {
    document.querySelectorAll('[data-dismiss-auto]').forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';
            setTimeout(() => alert.remove(), 300);
        }, 4000);
    });

    // Manual close buttons
    document.querySelectorAll('[data-dismiss-alert]').forEach(btn => {
        btn.addEventListener('click', () => {
            const alert = btn.closest('[role="alert"]');
            if (alert) {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 200);
            }
        });
    });
}

/* ============================================================
   Confirm Delete
   ============================================================ */
function initConfirmActions() {
    document.querySelectorAll('[data-confirm]').forEach(el => {
        el.addEventListener('click', function(e) {
            if (!confirm(this.dataset.confirm || 'Apakah Anda yakin?')) {
                e.preventDefault();
            }
        });
    });
}

/* ============================================================
   Tabs (simple vanilla)
   ============================================================ */
function initTabs() {
    document.querySelectorAll('[data-tab-target]').forEach(btn => {
        btn.addEventListener('click', () => {
            const group = btn.closest('[data-tab-group]');
            if (!group) return;
            // Deactivate all tabs
            group.querySelectorAll('[data-tab-target]').forEach(t => {
                t.classList.remove('border-blue-600', 'text-blue-600');
                t.classList.add('border-transparent', 'text-slate-500');
            });
            // Activate clicked
            btn.classList.remove('border-transparent', 'text-slate-500');
            btn.classList.add('border-blue-600', 'text-blue-600');
            // Show panel
            const target = btn.getAttribute('data-tab-target');
            const container = document.querySelector('[data-tab-content]') || group.parentElement;
            container.querySelectorAll('[data-tab-panel]').forEach(p => {
                p.classList.add('hidden');
            });
            const panel = container.querySelector('[data-tab-panel="' + target + '"]');
            if (panel) panel.classList.remove('hidden');
        });
    });
}

/* ============================================================
   Init All on DOMContentLoaded
   ============================================================ */
document.addEventListener('DOMContentLoaded', () => {
    initTheme();
    initMobileMenu();
    initNavbarScroll();
    initBackToTop();
    initCounters();
    initHeroSlider();
    initLightbox();
    initMultiStep();
    initAutoSlug();
    initImagePreview();
    initPreloader();
    initAdminSidebar();
    initAlertDismiss();
    initConfirmActions();
    initTabs();

    // Theme toggle buttons
    document.querySelectorAll('#themeToggle, #themeToggleMobile').forEach(btn => {
        btn.addEventListener('click', toggleTheme);
    });
});

// Init preloader immediately
initPreloader();
