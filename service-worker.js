self.addEventListener('push', function(event) {
    console.log('Push event received:', event);

    const options = {
        body: event.data ? event.data.text() : 'Default body',
        icon: 'icon.png',
        badge: 'badge.png'
    };

    event.waitUntil(
        self.registration.showNotification('Push Notification', options)
    );
});

self.addEventListener('notificationclick', function(event) {
    event.notification.close();
    event.waitUntil(
        clients.openWindow('https://portal.activesolutions.lk') // Change this to your domain URL
    );
});
