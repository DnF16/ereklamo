import './bootstrap';

import * as bootstrap from 'bootstrap';

window.bootstrap = bootstrap;

const sidebarToggle = document.querySelector('[data-sidebar-toggle]');
const sidebarBackdrop = document.querySelector('[data-sidebar-backdrop]');
const sidebar = document.getElementById('appSidebar');

if (sidebarToggle && sidebarBackdrop && sidebar) {
    const setSidebarOpen = (open) => {
        document.body.classList.toggle('sidebar-open', open);
        sidebarToggle.setAttribute('aria-expanded', String(open));
        sidebarToggle.setAttribute('aria-label', open ? 'Close navigation menu' : 'Open navigation menu');
        sidebarToggle.querySelector('i')?.classList.replace(open ? 'bi-list' : 'bi-x-lg', open ? 'bi-x-lg' : 'bi-list');
        sidebarBackdrop.hidden = !open;
    };

    sidebarToggle.addEventListener('click', () => {
        setSidebarOpen(sidebarToggle.getAttribute('aria-expanded') !== 'true');
    });

    sidebarBackdrop.addEventListener('click', () => setSidebarOpen(false));
    sidebar.addEventListener('click', (event) => {
        if (event.target instanceof Element && event.target.closest('a')) {
            setSidebarOpen(false);
        }
    });
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            setSidebarOpen(false);
        }
    });
    window.addEventListener('resize', () => {
        if (window.innerWidth >= 992) {
            setSidebarOpen(false);
        }
    });
}
