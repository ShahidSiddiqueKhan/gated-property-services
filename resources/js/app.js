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

// ---------------------------------------------------------------------------
// Multi-file upload widget: backs a real <input type="file" multiple> with a
// live preview grid (image thumbnails or file cards) that supports adding
// more files across multiple browse sessions and removing individual files
// before the form is submitted. Since a native FileList is read-only, we keep
// the authoritative selection in this component's `items` array and rebuild
// the input's FileList via a DataTransfer object on every change — the
// standard workaround for editable multi-file inputs.
// Usage: x-data="multiFileUpload({ isImage: true, maxSizeMB: 5 })" on a
// wrapper element containing an <input type="file" x-ref="input"
// @change="handleSelect($event.target.files)">.
// Exposed on window because Vite compiles this file as an ES module, whose
// top-level declarations are not automatically global — Alpine's inline
// x-data expressions are evaluated against the global scope.
// ---------------------------------------------------------------------------
window.multiFileUpload = function (options = {}) {
    const { isImage = false, maxSizeMB = 10 } = options;

    return {
        items: [],
        isImage,
        maxSizeMB,
        error: '',

        handleSelect(fileList) {
            this.error = '';

            const dt = new DataTransfer();
            this.items.forEach((item) => dt.items.add(item.file));

            Array.from(fileList).forEach((file) => {
                const isDuplicate = this.items.some(
                    (item) => item.file.name === file.name
                        && item.file.size === file.size
                        && item.file.lastModified === file.lastModified
                );
                if (isDuplicate) return;

                if (file.size > this.maxSizeMB * 1024 * 1024) {
                    this.error = `"${file.name}" is larger than ${this.maxSizeMB}MB and was skipped.`;
                    return;
                }

                dt.items.add(file);
                this.items.push({
                    file,
                    id: `${file.name}-${file.size}-${file.lastModified}`,
                    url: this.isImage ? URL.createObjectURL(file) : null,
                });
            });

            this.$refs.input.files = dt.files;
        },

        removeItem(id) {
            const removed = this.items.find((item) => item.id === id);
            if (removed?.url) URL.revokeObjectURL(removed.url);

            this.items = this.items.filter((item) => item.id !== id);

            const dt = new DataTransfer();
            this.items.forEach((item) => dt.items.add(item.file));
            this.$refs.input.files = dt.files;
        },

        formatSize(bytes) {
            if (bytes < 1024) return `${bytes} B`;
            if (bytes < 1024 * 1024) return `${(bytes / 1024).toFixed(1)} KB`;
            return `${(bytes / 1024 / 1024).toFixed(1)} MB`;
        },

        extension(name) {
            const parts = name.split('.');
            return parts.length > 1 ? parts.pop().toUpperCase() : 'FILE';
        },
    };
};
