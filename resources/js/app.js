// ---------------------------------------------------------------------------
// Scroll-reveal: any element with [data-reveal] fades/slides into view once
// it enters the viewport. Respects prefers-reduced-motion.
// ---------------------------------------------------------------------------
function initScrollReveal() {
    const targets = document.querySelectorAll('[data-reveal]');

    if (!targets.length) return;

    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (prefersReducedMotion || !('IntersectionObserver' in window)) {
        targets.forEach((el) => el.classList.add('is-visible'));
        return;
    }

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.15, rootMargin: '0px 0px -40px 0px' }
    );

    targets.forEach((el) => observer.observe(el));
}

// ---------------------------------------------------------------------------
// Animated counters: elements with [data-counter="1234"] count up from 0 once
// visible. Supports a [data-counter-suffix] attribute (e.g. "%", "+").
// ---------------------------------------------------------------------------
function initCounters() {
    const targets = document.querySelectorAll('[data-counter]');

    if (!targets.length) return;

    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    const animate = (el) => {
        const target = parseFloat(el.getAttribute('data-counter')) || 0;
        const suffix = el.getAttribute('data-counter-suffix') || '';
        const duration = 1400;

        if (prefersReducedMotion) {
            el.textContent = target.toLocaleString() + suffix;
            return;
        }

        const start = performance.now();

        const step = (now) => {
            const progress = Math.min((now - start) / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 3); // ease-out-cubic
            const value = Math.round(target * eased);
            el.textContent = value.toLocaleString() + suffix;

            if (progress < 1) {
                requestAnimationFrame(step);
            }
        };

        requestAnimationFrame(step);
    };

    if (!('IntersectionObserver' in window)) {
        targets.forEach(animate);
        return;
    }

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    animate(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.4 }
    );

    targets.forEach((el) => observer.observe(el));
}

document.addEventListener('DOMContentLoaded', () => {
    initScrollReveal();
    initCounters();
});
