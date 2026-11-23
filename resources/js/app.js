import Alpine from 'alpinejs';

// Livewire pages (CMS, auth) ship their own Alpine with the navigate plugin.
// Starting a second copy breaks wire:navigate redirects, so only start ours
// on pages without Livewire (the public site).
if (!window.Livewire) {
    window.Alpine = Alpine;
    Alpine.start();
}

// Hover/touch prefetch for every browser. Chrome may also prerender via the
// speculationrules block in the layout, but users can turn that off ("Preload pages").
{
    const prefetched = new Set();
    const publicPath = /^\/(about|academics|admissions|apps|digital-services|announcements|events|student-life)?(\/|$)/;

    const prefetch = (event) => {
        const link = event.target.closest?.('a[href]');
        if (!link || link.target === '_blank' || link.hasAttribute('download')) return;

        const url = new URL(link.href, location.href);
        if (url.origin !== location.origin || !publicPath.test(url.pathname)) return;
        if (url.href === location.href || prefetched.has(url.href)) return;

        prefetched.add(url.href);
        const hint = document.createElement('link');
        hint.rel = 'prefetch';
        hint.href = url.href;
        document.head.appendChild(hint);
    };

    document.addEventListener('mouseover', prefetch, { passive: true });
    document.addEventListener('touchstart', prefetch, { passive: true });
}
