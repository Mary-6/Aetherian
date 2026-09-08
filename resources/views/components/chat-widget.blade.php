<div id="chat-widget" class="fixed bottom-5 right-5 z-50 font-sans">
    <button id="chat-toggle" class="relative w-14 h-14 rounded-full bg-accent-500 text-navy shadow-lg flex items-center justify-center hover:bg-accent-400 transition-colors" aria-label="Open chat">
        <i data-lucide="message-circle" class="w-7 h-7"></i>
        <span id="chat-badge" class="hidden absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold w-5 h-5 flex items-center justify-center rounded-full border-2 border-white">0</span>
    </button>

    <div id="chat-panel" class="hidden absolute bottom-16 right-0 w-80 sm:w-96 bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden flex flex-col max-h-[520px]">
        <div class="bg-navy text-white px-4 py-3 flex items-center justify-between">
            <div>
                <h4 class="font-semibold text-sm">Aetherian Cargo Chat</h4>
                <p id="chat-status" class="text-xs text-slate-300">Online</p>
            </div>
            <button id="chat-close" class="hover:text-slate-300"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>

        <div id="chat-form-step" class="p-4 space-y-3">
            <p class="text-sm text-slate-600">Enter your details to chat with support.</p>
            <input type="text" id="chat-name" placeholder="Your name" class="w-full px-3 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-accent-500" required>
            <input type="email" id="chat-email" placeholder="Your email (optional)" class="w-full px-3 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-accent-500">
            <input type="tel" id="chat-phone" placeholder="Your phone (optional)" class="w-full px-3 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-accent-500">
            <button id="chat-start" class="w-full py-2 bg-accent-500 text-navy font-semibold rounded-lg hover:bg-accent-400">Start chat</button>
        </div>

        <div id="chat-room" class="hidden flex-col flex-1" style="min-height: 320px;">
            <div id="chat-messages" class="flex-1 overflow-y-auto p-4 space-y-3 max-h-72"></div>
            <form id="chat-send" class="p-3 border-t flex items-center gap-2">
                <input type="text" id="chat-input" placeholder="Type a message..." class="flex-1 px-3 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-accent-500" required>
                <button type="submit" class="p-2 bg-navy text-white rounded-lg hover:bg-navy/90"><i data-lucide="send" class="w-4 h-4"></i></button>
            </form>
        </div>
    </div>
</div>

<script>
(function () {
    const widget = document.getElementById('chat-widget');
    const toggle = document.getElementById('chat-toggle');
    const badge = document.getElementById('chat-badge');
    const panel = document.getElementById('chat-panel');
    const close = document.getElementById('chat-close');
    const startBtn = document.getElementById('chat-start');
    const formStep = document.getElementById('chat-form-step');
    const roomStep = document.getElementById('chat-room');
    const nameInput = document.getElementById('chat-name');
    const emailInput = document.getElementById('chat-email');
    const phoneInput = document.getElementById('chat-phone');
    const input = document.getElementById('chat-input');
    const sendForm = document.getElementById('chat-send');
    const messagesEl = document.getElementById('chat-messages');

    const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
    const vapidPublicKey = window.vapidPublicKey || '';
    const roomKey = 'aetherian_chat_room';

    let roomId = localStorage.getItem(roomKey) || '';
    let name = localStorage.getItem('aetherian_chat_name') || '';
    let email = localStorage.getItem('aetherian_chat_email') || '';
    let phone = localStorage.getItem('aetherian_chat_phone') || '';
    let pollInterval = null;
    let unreadInterval = null;

    function uuid() {
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
            const r = Math.random() * 16 | 0, v = c === 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        });
    }

    function urlBase64ToUint8Array(base64String) {
        const padding = '='.repeat((4 - base64String.length % 4) % 4);
        const base64 = (base64String + padding).replace(/\-/g, '+').replace(/_/g, '/');
        const rawData = window.atob(base64);
        return Uint8Array.from([...rawData].map(char => char.charCodeAt(0)));
    }

    if (name) nameInput.value = name;
    if (email) emailInput.value = email;
    if (phone) phoneInput.value = phone;

    function updateBadge(count) {
        const value = parseInt(count, 10) || 0;
        if (value > 0) {
            badge.textContent = value > 99 ? '99+' : value;
            badge.classList.remove('hidden');
        } else {
            badge.classList.add('hidden');
        }
    }

    function scrollToBottom() {
        messagesEl.scrollTop = messagesEl.scrollHeight;
    }

    function renderMessages(messages) {
        messagesEl.innerHTML = '';
        if (!messages.length) {
            messagesEl.innerHTML = '<p class="text-center text-sm text-slate-500">A support agent will be with you shortly.</p>';
        } else {
            messages.forEach(function (msg) {
                const wrapper = document.createElement('div');
                wrapper.className = 'flex ' + (msg.is_admin ? 'justify-start' : 'justify-end');
                const bubble = document.createElement('div');
                bubble.className = 'max-w-[80%] px-3 py-2 rounded-xl text-sm ' + (msg.is_admin ? 'bg-slate-100 text-slate-900 rounded-bl-none' : 'bg-accent-500 text-navy rounded-br-none');
                const time = msg.created_at ? new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) : '';
                bubble.innerHTML = '<p>' + escapeHtml(msg.content) + '</p><span class="text-[10px] opacity-70 mt-1 block">' + (msg.sender_name || 'You') + ' ' + time + '</span>';
                wrapper.appendChild(bubble);
                messagesEl.appendChild(wrapper);
            });
        }
        scrollToBottom();
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    async function loadMessages() {
        if (!roomId) return;
        try {
            const res = await fetch('{{ route('chat.messages') }}?room_id=' + encodeURIComponent(roomId));
            const data = await res.json();
            renderMessages(data.messages || []);
            updateBadge(data.unread_count ?? 0);
        } catch (e) { console.error('chat poll error', e); }
    }

    async function fetchUnread() {
        if (!roomId) return;
        try {
            const res = await fetch('{{ route('chat.unread') }}?room_id=' + encodeURIComponent(roomId));
            const data = await res.json();
            updateBadge(data.count || 0);
        } catch (e) { console.error('chat unread error', e); }
    }

    async function subscribePush() {
        if (!roomId || !vapidPublicKey || !('serviceWorker' in navigator) || !('PushManager' in window)) return;
        try {
            const registration = await navigator.serviceWorker.ready;
            const existing = await registration.pushManager.getSubscription();
            if (existing) {
                await sendSubscription(existing);
                return;
            }
            const subscription = await registration.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: urlBase64ToUint8Array(vapidPublicKey),
            });
            await sendSubscription(subscription);
        } catch (e) {
            console.error('Push subscription failed', e);
        }
    }

    async function sendSubscription(subscription) {
        const keys = subscription.getKey ? {
            p256dh: btoa(String.fromCharCode.apply(null, new Uint8Array(subscription.getKey('p256dh')))),
            auth: btoa(String.fromCharCode.apply(null, new Uint8Array(subscription.getKey('auth')))),
        } : {};
        await fetch('{{ route('chat.subscribe') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
            },
            body: JSON.stringify({
                room_id: roomId,
                guest_name: name || null,
                guest_email: email || null,
                guest_phone: phone || null,
                endpoint: subscription.endpoint,
                keys: keys,
                content_encoding: (subscription.options && subscription.options.contentEncoding) || 'aes128gcm',
            }),
        });
    }

    async function sendMessage(content) {
        try {
            const res = await fetch('{{ route('chat.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ room_id: roomId, guest_name: name, guest_email: email, guest_phone: phone, content: content }),
            });
            const data = await res.json();
            if (data.id) {
                input.value = '';
                loadMessages();
            }
        } catch (e) { console.error('chat send error', e); }
    }

    function startPolling() {
        if (pollInterval) return;
        loadMessages();
        pollInterval = setInterval(loadMessages, 5000);
    }

    function stopPolling() {
        if (pollInterval) { clearInterval(pollInterval); pollInterval = null; }
    }

    function startUnreadPolling() {
        if (unreadInterval) return;
        fetchUnread();
        unreadInterval = setInterval(fetchUnread, 5000);
    }

    function stopUnreadPolling() {
        if (unreadInterval) { clearInterval(unreadInterval); unreadInterval = null; }
    }

    function openRoom() {
        if (!roomId) { roomId = uuid(); localStorage.setItem(roomKey, roomId); }
        formStep.classList.add('hidden');
        roomStep.classList.remove('hidden');
        roomStep.classList.add('flex');
        startPolling();
        stopUnreadPolling();
        subscribePush();
        if (window.lucide) lucide.createIcons();
    }

    function closePanel() {
        panel.classList.add('hidden');
        stopPolling();
        startUnreadPolling();
    }

    toggle.addEventListener('click', function () {
        panel.classList.remove('hidden');
        if (name) openRoom();
        if (window.lucide) lucide.createIcons();
    });

    close.addEventListener('click', closePanel);

    startBtn.addEventListener('click', function () {
        name = nameInput.value.trim();
        email = emailInput.value.trim();
        phone = phoneInput.value.trim();
        if (!name) { nameInput.focus(); return; }
        localStorage.setItem('aetherian_chat_name', name);
        if (email) localStorage.setItem('aetherian_chat_email', email);
        if (phone) localStorage.setItem('aetherian_chat_phone', phone);
        openRoom();
    });

    sendForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const content = input.value.trim();
        if (!content) return;
        sendMessage(content);
    });

    const params = new URLSearchParams(window.location.search);
    const requestedRoom = params.get('chat_room');
    if (requestedRoom) {
        roomId = requestedRoom;
        localStorage.setItem(roomKey, roomId);
        panel.classList.remove('hidden');
        if (name) {
            openRoom();
        } else {
            formStep.classList.add('hidden');
            roomStep.classList.remove('hidden');
            roomStep.classList.add('flex');
            startPolling();
            subscribePush();
        }
        window.history.replaceState({}, document.title, window.location.pathname);
    }

    startUnreadPolling();
    if (window.lucide) lucide.createIcons();
})();
</script>
