
const CACHE_NAME = 'ereklamo-v1';

// Install service worker
self.addEventListener('install', (event) => {
    self.skipWaiting();
});

// Activate service worker
self.addEventListener('activate', (event) => {
    event.waitUntil(
        self.clients.claim()
    );
});
