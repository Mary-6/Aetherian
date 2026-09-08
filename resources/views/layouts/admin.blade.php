<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - Aetherian Cargo</title>
    <script>window.vapidPublicKey = '{{ config('webpush.vapid.public_key') }}';</script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="font-sans antialiased bg-slate-100">
    <div class="min-h-screen flex">
        <aside class="w-64 bg-navy text-white flex-shrink-0">
            <a href="{{ route('home') }}" class="p-4 flex items-center gap-3 font-bold text-lg">
                <img src="{{ asset('brand-logo.png') }}" alt="Aetherian Cargo" class="w-8 h-8 rounded">
                Aetherian Cargo
            </a>
            <nav class="space-y-1 px-2">
                <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded hover:bg-white/10">Dashboard</a>

                <div class="px-3 py-2 text-xs uppercase text-slate-400 font-semibold tracking-wider mt-3">Shipping</div>
                <a href="{{ route('admin.shipments.index') }}" class="block px-3 py-2 rounded hover:bg-white/10">List Shipments</a>
                <a href="{{ route('admin.shipments.create') }}" class="block px-3 py-2 rounded hover:bg-white/10">Create Shipment</a>
                <a href="{{ route('admin.pickups.index') }}" class="block px-3 py-2 rounded hover:bg-white/10">Pickups</a>
                <a href="{{ route('admin.consolidated.index') }}" class="block px-3 py-2 rounded hover:bg-white/10">Consolidated</a>
                <a href="{{ route('admin.locker.index') }}" class="block px-3 py-2 rounded hover:bg-white/10">Locker Packages</a>
                <a href="{{ route('admin.transactions.index') }}" class="block px-3 py-2 rounded hover:bg-white/10">Transactions</a>
                <a href="{{ route('admin.reports.index') }}" class="block px-3 py-2 rounded hover:bg-white/10">General Reports</a>

                <div class="px-3 py-2 text-xs uppercase text-slate-400 font-semibold tracking-wider mt-3">Users</div>
                <a href="{{ route('admin.customers.index') }}" class="block px-3 py-2 rounded hover:bg-white/10">Client List</a>
                <a href="{{ route('admin.recipients.index') }}" class="block px-3 py-2 rounded hover:bg-white/10">My Recipients</a>
                <a href="{{ route('admin.drivers.index') }}" class="block px-3 py-2 rounded hover:bg-white/10">Drivers</a>
                <a href="{{ route('admin.vehicles.index') }}" class="block px-3 py-2 rounded hover:bg-white/10">Vehicles</a>
                <a href="{{ route('admin.users.index') }}" class="block px-3 py-2 rounded hover:bg-white/10">All Users</a>
                <a href="{{ route('admin.roles.index') }}" class="block px-3 py-2 rounded hover:bg-white/10">Roles & Permissions</a>

                <div class="px-3 py-2 text-xs uppercase text-slate-400 font-semibold tracking-wider mt-3">Operations</div>
                <a href="{{ route('admin.branches.index') }}" class="block px-3 py-2 rounded hover:bg-white/10">Branches</a>
                <a href="{{ route('admin.warehouses.index') }}" class="block px-3 py-2 rounded hover:bg-white/10">Warehouses</a>
                <a href="{{ route('admin.support-tickets.index') }}" class="block px-3 py-2 rounded hover:bg-white/10">Support Tickets</a>
                <a href="{{ route('admin.contact-messages.index') }}" class="block px-3 py-2 rounded hover:bg-white/10">Contact Messages</a>
                <a href="{{ route('admin.chat.index') }}" class="block px-3 py-2 rounded hover:bg-white/10">Live Chat</a>

                <div class="px-3 py-2 text-xs uppercase text-slate-400 font-semibold tracking-wider mt-3">Settings</div>
                <a href="{{ route('admin.settings.index') }}" class="block px-3 py-2 rounded hover:bg-white/10">System Settings</a>
                <a href="{{ route('admin.profile.edit') }}" class="block px-3 py-2 rounded hover:bg-white/10">Account Profile</a>
            </nav>
        </aside>

        <div class="flex-1 flex flex-col">
            <header class="bg-white shadow px-6 py-4 flex justify-between items-center">
                <h2 class="font-semibold text-xl text-navy">@yield('title')</h2>
                <div class="flex items-center space-x-5">
                    <a href="{{ route('admin.chat.index') }}" id="chat-notification-link" class="relative text-slate-600 hover:text-navy" aria-label="Live chat notifications">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bell"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                        @if ($pendingChatCount > 0)
                            <span class="absolute -top-1.5 -right-1.5 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white ring-2 ring-white">{{ $pendingChatCount }}</span>
                        @endif
                    </a>
                    <span class="text-sm text-slate-600">{{ auth()->user()->name }}</span>
                    <a href="{{ route('home') }}" class="text-sm text-brand-600 hover:underline">View site</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-red-600 hover:underline">Logout</button>
                    </form>
                </div>
            </header>

            <main class="p-6 flex-1">
                @if (session('success'))
                    <div class="bg-green-100 text-green-800 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="bg-red-100 text-red-800 px-4 py-3 rounded mb-4">{{ session('error') }}</div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
    @stack('scripts')
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js').catch(function (err) { console.error('SW registration failed', err); });
        }

        (function () {
            function urlBase64ToUint8Array(base64String) {
                const padding = '='.repeat((4 - base64String.length % 4) % 4);
                const base64 = (base64String + padding).replace(/\-/g, '+').replace(/_/g, '/');
                const rawData = window.atob(base64);
                return Uint8Array.from([...rawData].map(char => char.charCodeAt(0)));
            }

            async function subscribeAdminPush() {
                const vapidPublicKey = window.vapidPublicKey;
                if (!vapidPublicKey || !('serviceWorker' in navigator) || !('PushManager' in window)) return;
                const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
                try {
                    const registration = await navigator.serviceWorker.ready;
                    const existing = await registration.pushManager.getSubscription();
                    if (existing) {
                        await sendAdminSubscription(existing, csrf);
                        return;
                    }
                    const subscription = await registration.pushManager.subscribe({
                        userVisibleOnly: true,
                        applicationServerKey: urlBase64ToUint8Array(vapidPublicKey),
                    });
                    await sendAdminSubscription(subscription, csrf);
                } catch (e) { console.error('Admin push subscribe failed', e); }
            }

            async function sendAdminSubscription(subscription, csrf) {
                const keys = subscription.getKey ? {
                    p256dh: btoa(String.fromCharCode.apply(null, new Uint8Array(subscription.getKey('p256dh')))),
                    auth: btoa(String.fromCharCode.apply(null, new Uint8Array(subscription.getKey('auth')))),
                } : {};
                await fetch('{{ route('admin.chat.subscribe') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                    },
                    body: JSON.stringify({
                        endpoint: subscription.endpoint,
                        keys: keys,
                        content_encoding: (subscription.options && subscription.options.contentEncoding) || 'aes128gcm',
                    }),
                });
            }

            subscribeAdminPush();

            const chatLink = document.getElementById('chat-notification-link');
            let lastCount = 0;

            function getBadge() {
                return chatLink ? chatLink.querySelector('span') : null;
            }

            async function pollChatCount() {
                try {
                    const res = await fetch('{{ route('admin.chat.count') }}');
                    const data = await res.json();
                    if (data && typeof data.count === 'number') {
                        let badge = getBadge();
                        if (data.count > 0) {
                            if (!badge && chatLink) {
                                badge = document.createElement('span');
                                badge.className = 'absolute -top-1.5 -right-1.5 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white ring-2 ring-white';
                                chatLink.appendChild(badge);
                            }
                            if (badge) {
                                badge.textContent = data.count;
                                badge.style.display = 'flex';
                            }
                            if (data.count > lastCount && lastCount > 0) {
                                // try a subtle notification beep
                                const audio = new Audio('data:audio/wav;base64,UklGRiYAAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQAAAAA=');
                                audio.play().catch(() => {});
                            }
                        } else if (badge) {
                            badge.style.display = 'none';
                        }
                        lastCount = data.count;
                    }
                } catch (e) { console.error('Chat count poll failed', e); }
            }

            setInterval(pollChatCount, 15000);
            pollChatCount();

            const originalTitle = document.title;
            function updateTitle() {
                document.title = lastCount > 0 ? '(' + lastCount + ') ' + originalTitle : originalTitle;
            }
            setInterval(updateTitle, 5000);
        })();
    </script>
</body>
</html>
