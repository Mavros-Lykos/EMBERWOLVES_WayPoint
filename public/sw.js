importScripts('https://storage.googleapis.com/workbox-cdn/releases/7.0.0/workbox-sw.js');

if (workbox) {
  console.log(`Yay! Workbox is loaded 🎉`);

  // Cache JS, CSS, and Images
  workbox.routing.registerRoute(
    /\.(?:js|css|png|gif|jpg|svg|ico)$/,
    new workbox.strategies.StaleWhileRevalidate({
      cacheName: 'static-resources',
    })
  );

  // Cache external APIs (like OpenStreetMap tiles)
  workbox.routing.registerRoute(
    new RegExp('^https://.*\\.tile\\.openstreetmap\\.org/'),
    new workbox.strategies.CacheFirst({
      cacheName: 'osm-tiles',
      plugins: [
        new workbox.expiration.ExpirationPlugin({
          maxEntries: 100,
          maxAgeSeconds: 30 * 24 * 60 * 60, // 30 Days
        }),
      ],
    })
  );

  // Offline fallback for navigation routes (e.g. driver hitting dead zones)
  workbox.routing.registerRoute(
    ({request}) => request.mode === 'navigate',
    new workbox.strategies.NetworkFirst({
      cacheName: 'pages',
    })
  );

} else {
  console.log(`Boo! Workbox didn't load 😬`);
}
