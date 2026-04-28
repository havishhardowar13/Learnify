/**
 * Learnify – app.js
 * Lightweight vanilla JS — no build step required.
 */

document.addEventListener('DOMContentLoaded', () => {
    initFlashDismiss();
    initScrollFadeIn();
    initCourseCardHover();
    initRoleSelector();
    initMobileNav();
    initFormEnhancements();
});

/* ── Auto-dismiss flash messages after 5 s ─────────────────── */
function initFlashDismiss() {
    document.querySelectorAll('.flash').forEach(el => {
        setTimeout(() => el.remove(), 5000);
    });
}

/* ── Intersection-observer fade-in-up ─────────────────────── */
function initScrollFadeIn() {
    const els = document.querySelectorAll('.fade-in-up');
    if (!els.length) return;

    const io = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                e.target.style.opacity   = '1';
                e.target.style.transform = 'translateY(0)';
                io.unobserve(e.target);
            }
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

    els.forEach(el => {
        // Only animate elements not already in the viewport on load
        if (el.getBoundingClientRect().top > window.innerHeight) {
            el.style.opacity   = '0';
            el.style.transform = 'translateY(28px)';
            el.style.transition = 'opacity .55s ease, transform .55s ease';
        }
        io.observe(el);
    });
}

/* ── Subtle lift on course cards ───────────────────────────── */
function initCourseCardHover() {
    document.querySelectorAll('.course-card').forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.style.transform = 'translateY(-6px)';
        });
        card.addEventListener('mouseleave', () => {
            card.style.transform = '';
        });
    });
}

/* ── Role selector radio → visual highlight ────────────────── */
function initRoleSelector() {
    document.querySelectorAll('.role-option input[type="radio"]').forEach(radio => {
        radio.addEventListener('change', () => {
            document.querySelectorAll('.role-option').forEach(o => o.classList.remove('selected'));
            if (radio.checked) radio.closest('.role-option').classList.add('selected');
        });
        // Initialise on load
        if (radio.checked) radio.closest('.role-option').classList.add('selected');
    });
}

/* ── Mobile nav toggle (hamburger) ────────────────────────── */
function initMobileNav() {
    const btn = document.getElementById('mobile-menu-btn');
    const nav = document.querySelector('.nav-links');
    if (!btn || !nav) return;

    btn.addEventListener('click', () => {
        const open = nav.classList.toggle('nav-open');
        btn.setAttribute('aria-expanded', open);
        btn.querySelector('i').className = open ? 'fas fa-times' : 'fas fa-bars';
    });

    // Close on outside click
    document.addEventListener('click', e => {
        if (!e.target.closest('.navbar')) {
            nav.classList.remove('nav-open');
            btn.setAttribute('aria-expanded', false);
        }
    });
}

/* ── Form UX enhancements ──────────────────────────────────── */
function initFormEnhancements() {
    // Disable submit button after first click to prevent double-submit
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', () => {
            const btn = form.querySelector('[type="submit"]');
            if (!btn) return;
            setTimeout(() => {
                btn.disabled = true;
                btn.dataset.originalHtml = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Please wait…';
            }, 0);
        });
    });

    // Password visibility toggle (adds a small eye icon inside password fields)
    document.querySelectorAll('input[type="password"]').forEach(input => {
        const wrap = input.closest('.input-icon-wrap');
        if (!wrap) return;

        const toggle = document.createElement('button');
        toggle.type      = 'button';
        toggle.className = 'pw-toggle';
        toggle.innerHTML = '<i class="fas fa-eye"></i>';
        toggle.style.cssText = [
            'position:absolute', 'right:.75rem', 'top:50%',
            'transform:translateY(-50%)',
            'background:none', 'border:none', 'cursor:pointer',
            'color:var(--text-muted)', 'padding:0', 'line-height:1'
        ].join(';');

        toggle.addEventListener('click', () => {
            const visible = input.type === 'text';
            input.type = visible ? 'password' : 'text';
            toggle.querySelector('i').className = visible ? 'fas fa-eye' : 'fas fa-eye-slash';
        });

        wrap.style.position = 'relative';
        wrap.appendChild(toggle);
    });

    // Animate progress bars to their data-width on scroll into view
    const bars = document.querySelectorAll('.progress-bar-fill[data-width]');
    if (bars.length) {
        const io = new IntersectionObserver(entries => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    e.target.style.width = e.target.dataset.width + '%';
                    io.unobserve(e.target);
                }
            });
        });
        bars.forEach(b => { b.style.width = '0'; io.observe(b); });
    }
}
