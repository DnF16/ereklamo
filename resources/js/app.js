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


// Register e-REKLAMO Service Worker
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js')
            .then((registration) => {
                console.log(
                    'e-REKLAMO Service Worker registered:',
                    registration.scope
                );
            })
            .catch((error) => {
                console.error(
                    'Service Worker registration failed:',
                    error
                );
            });
    });
}

// PWA Installation Prompt

// =====================================
// e-REKLAMO PWA INSTALLATION POPUP
// =====================================

let deferredPrompt = null;

const installPopup = document.querySelector('[data-pwa-popup]');
const installButtons = document.querySelectorAll('[data-pwa-install]');
const dismissButtons = document.querySelectorAll('[data-pwa-dismiss]');

const DISMISS_KEY = 'ereklamoInstallPopupDismissed';

const popupWasDismissed = () => {
    return sessionStorage.getItem(DISMISS_KEY) === 'true';
};

const showInstallPopup = () => {
    if (!installPopup || popupWasDismissed()) return;

    installPopup.hidden = false;

    requestAnimationFrame(() => {
        installPopup.classList.add('is-visible');
    });
};

const hideInstallPopup = () => {
    if (!installPopup) return;

    installPopup.classList.remove('is-visible');

    setTimeout(() => {
        installPopup.hidden = true;
    }, 250);
};

// Browser says installation is available
window.addEventListener('beforeinstallprompt', (event) => {
    event.preventDefault();

    deferredPrompt = event;

    showInstallPopup();
});

// Install button
installButtons.forEach((button) => {
    button.addEventListener('click', async () => {
        if (!deferredPrompt) return;

        const promptEvent = deferredPrompt;
        deferredPrompt = null;

        await promptEvent.prompt();

        const choice = await promptEvent.userChoice;

        console.log('e-REKLAMO installation:', choice.outcome);

        hideInstallPopup();
    });
});

// Dismiss button
dismissButtons.forEach((button) => {
    button.addEventListener('click', () => {
        sessionStorage.setItem(DISMISS_KEY, 'true');
        hideInstallPopup();
    });
});

// Successful installation
window.addEventListener('appinstalled', () => {
    deferredPrompt = null;
    hideInstallPopup();
});


// Hide after successful installation
window.addEventListener('appinstalled', () => {
    deferredPrompt = null;
    hideInstallPopup();
});


// Handle install button click
installButtons.forEach((button) => {
    button.addEventListener('click', async () => {
        if (!deferredPrompt) {
            return;
        }

        deferredPrompt.prompt();

        await deferredPrompt.userChoice;

        deferredPrompt = null;

        // Hide the button after the prompt
        installButtons.forEach((installButton) => {
            installButton.hidden = true;
        });
    });
});

// Detect successful installation
window.addEventListener('appinstalled', () => {
    installButtons.forEach((button) => {
        button.hidden = true;
    });

    deferredPrompt = null;
});

