const CACHE_NAME = 'waypoint-v1';
const OFFLINE_URL = '/offline.html';

const ASSETS_TO_CACHE = [
    '/',
    '/login',
    '/manifest.json',
    'https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;600&display=swap',
    'https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0',
    'https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js',
    '//unpkg.com/alpinejs'
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(ASSETS_TO_CACHE);
        })
    );
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((name) => {
                    if (name !== CACHE_NAME) {
                        return caches.delete(name);
                    }
                })
            );
        })
    );
    self.clients.claim();
});

self.addEventListener('fetch', (event) => {
    // For API requests, try network first, then fail (Alpine handles offline queueing)
    if (event.request.method !== 'GET' || event.request.url.includes('/api/')) {
        return;
    }

    event.respondWith(
        caches.match(event.request).then((cachedResponse) => {
            if (cachedResponse) {
                return cachedResponse;
            }
            return fetch(event.request).then((networkResponse) => {
                // Optionally cache dynamically fetched assets
                return networkResponse;
            }).catch(() => {
                // If offline and request is for a document, we could return a fallback
                // But for the hackathon, returning cached assets is enough for PWA
                if (event.request.headers.get('accept').includes('text/html')) {
                    return caches.match('/login');
                }
            });
        })
    );
});
