self.addEventListener('push', function (event) {
    if (!event.data) {
        return;
    }

    let payload;
    try {
        payload = event.data.json();
    } catch (e) {
        payload = { title: 'Aetherian Cargo', body: event.data.text() };
    }

    const title = payload.title || 'Aetherian Cargo';
    const options = {
        body: payload.body || 'New message',
        icon: payload.icon || '/brand-logo.png',
        badge: payload.badge || '/brand-logo.png',
        tag: payload.tag || 'aetherian-chat',
        requireInteraction: false,
        data: payload.data || {},
        actions: (payload.data?.url ? [{ action: 'open', title: 'Open' }] : []),
    };

    event.waitUntil(self.registration.showNotification(title, options));
});

self.addEventListener('notificationclick', function (event) {
    event.notification.close();

    const data = event.notification.data || {};
    const url = data.url || '/';

    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then(function (clientList) {
            for (const client of clientList) {
                if (client.url.includes(url) && 'focus' in client) {
                    return client.focus();
                }
            }
            if (clients.openWindow) {
                return clients.openWindow(url);
            }
        })
    );
});
