const CACHE_NAME = 'clubedopack-v1';
const ASSETS_TO_CACHE = [
  '/',
  '/favicon.ico',
  '/manifest.json'
];

// Instalação do Service Worker e caching de assets essenciais
self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      return cache.addAll(ASSETS_TO_CACHE);
    })
  );
  self.skipWaiting();
});

// Ativação do Service Worker
self.addEventListener('activate', (event) => {
  event.waitUntil(self.clients.claim());
});

// Responder requisições com cache ou buscar na rede
self.addEventListener('fetch', (event) => {
  // IGNORAR requisições ao servidor de desenvolvimento do Vite (porta 5173) ou arquivos do próprio HMR para não quebrar a estilização local
  if (event.request.url.includes(':5173') || event.request.url.includes('/@vite/') || event.request.url.includes('/resources/')) {
    return;
  }

  event.respondWith(
    caches.match(event.request).then((cachedResponse) => {
      if (cachedResponse) {
        return cachedResponse;
      }
      return fetch(event.request);
    })
  );
});
