<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="font-sans antialiased bg-white text-slate-900 overflow-x-hidden">
    <header class="bg-navy text-white sticky top-0 z-50">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <a href="{{ url('/') }}" class="flex items-center gap-2 font-bold text-xl shrink-0">
                    <img src="{{ asset('brand-logo.png') }}" alt="{{ config('app.name') }}" class="w-8 h-8 rounded">
                    <span class="text-base sm:text-lg font-bold truncate max-w-[140px] sm:max-w-none">{{ config('app.name') }}</span>
                </a>

                <div class="hidden lg:flex items-center justify-center flex-1 gap-8">
                    @php
                        $current = request()->path();
                    @endphp
                    <a href="{{ url('/') }}" class="text-sm font-semibold uppercase tracking-wider transition-colors {{ $current == '/' ? 'text-accent-500' : 'text-slate-300 hover:text-white' }}">Home</a>
                    <a href="{{ route('about') }}" class="text-sm font-semibold uppercase tracking-wider transition-colors {{ $current == 'about' ? 'text-accent-500' : 'text-slate-300 hover:text-white' }}">About</a>
                    <a href="{{ route('services') }}" class="text-sm font-semibold uppercase tracking-wider transition-colors {{ $current == 'services' ? 'text-accent-500' : 'text-slate-300 hover:text-white' }}">Services</a>

                    <div class="relative group" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                        <button type="button" class="flex items-center gap-1 text-sm font-semibold uppercase tracking-wider transition-colors text-slate-300 hover:text-white">
                            Pages
                            <i data-lucide="chevron-down" class="w-4 h-4 transition-transform group-hover:rotate-180"></i>
                        </button>
                        <div x-show="open" x-cloak class="absolute top-full left-0 w-48 bg-white rounded-xl shadow-lg py-2 text-slate-900"
                             @mouseenter="open = true" @mouseleave="open = false"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="opacity-0 -translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0">
                            <a href="{{ route('faq') }}" class="block px-4 py-2 text-sm hover:bg-slate-100 {{ $current == 'faq' ? 'text-accent-500 font-semibold' : '' }}">FAQ</a>
                            <a href="{{ route('testimonials') }}" class="block px-4 py-2 text-sm hover:bg-slate-100 {{ $current == 'testimonials' ? 'text-accent-500 font-semibold' : '' }}">Testimonials</a>
                        </div>
                    </div>

                    <a href="{{ route('contact') }}" class="text-sm font-semibold uppercase tracking-wider transition-colors {{ $current == 'contact' ? 'text-accent-500' : 'text-slate-300 hover:text-white' }}">Contact</a>
                </div>

                <div class="hidden lg:flex items-center gap-6 shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center">
                            <i data-lucide="phone" class="w-5 h-5"></i>
                        </div>
                        <div class="text-sm">
                            <p class="text-slate-400 text-xs">Need help?</p>
                            <a href="tel:1-800-AETHER" class="font-semibold hover:text-brand-300">1-800-AETHER</a>
                        </div>
                    </div>
                    <a href="{{ route('track') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-accent-500 text-navy text-sm font-bold uppercase tracking-wider rounded-full hover:bg-accent-400 transition-colors">
                        Track shipment
                        <span class="w-6 h-6 rounded-full bg-navy/20 flex items-center justify-center">
                            <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                        </span>
                    </a>
                </div>

                <button id="mobile-menu-toggle" class="lg:hidden p-2" aria-label="Toggle menu">
                    <i data-lucide="menu" class="w-6 h-6 menu-open"></i>
                    <i data-lucide="x" class="w-6 h-6 menu-close hidden"></i>
                </button>
            </div>

            <div id="mobile-menu" class="hidden lg:hidden pb-6 space-y-4 border-t border-white/10 pt-4">
                <a href="{{ url('/') }}" class="block text-slate-300 hover:text-white">Home</a>
                <a href="{{ route('about') }}" class="block text-slate-300 hover:text-white">About</a>
                <a href="{{ route('services') }}" class="block text-slate-300 hover:text-white">Services</a>
                <a href="{{ route('contact') }}" class="block text-slate-300 hover:text-white">Contact</a>
                <a href="{{ route('faq') }}" class="block text-slate-300 hover:text-white">FAQ</a>
                <a href="{{ route('testimonials') }}" class="block text-slate-300 hover:text-white">Testimonials</a>
                <a href="{{ route('track') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-accent-500 text-navy text-sm font-bold rounded-full">
                    Track shipment <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                </a>
                <div class="pt-6 border-t border-white/10 space-y-3">
                    <a href="mailto:support@aetheriancargo.com" class="flex items-center gap-3 text-slate-300 hover:text-white">
                        <i data-lucide="mail" class="w-5 h-5"></i>
                        support@aetheriancargo.com
                    </a>
                    <a href="tel:1-800-AETHER" class="flex items-center gap-3 text-slate-300 hover:text-white">
                        <i data-lucide="phone" class="w-5 h-5"></i>
                        1-800-AETHER
                    </a>
                </div>
            </div>
        </nav>
    </header>

    @if (session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 max-w-7xl mx-auto rounded mt-4">
            {{ session('success') }}
        </div>
    @endif

    <main>
        @yield('content')
    </main>

    <footer class="bg-navy text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-12 items-center mb-12">
                <div>
                    <h3 class="text-2xl font-bold mb-2">Stay in the loop</h3>
                    <p class="text-slate-400">Get updates on new routes, tools, and logistics tips straight to your inbox.</p>
                </div>
                <form action="{{ route('home') }}" method="GET" class="flex flex-col sm:flex-row gap-2">
                    <input type="email" name="newsletter" placeholder="Your email address" required class="flex-1 px-5 py-3 rounded-full bg-white/10 border border-white/20 text-white placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500">
                    <button type="submit" class="px-6 py-3 bg-brand-600 text-white font-semibold rounded-full hover:bg-brand-700 transition-colors">Subscribe</button>
                </form>
            </div>

            <div class="border-t border-white/10 pt-8 flex flex-col md:flex-row items-center justify-between gap-4 text-sm text-slate-400">
                <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All Rights Reserved.</p>
                <div class="flex gap-6">
                    <a href="{{ route('terms') }}" class="hover:text-white transition-colors">Terms</a>
                    <a href="{{ route('privacy') }}" class="hover:text-white transition-colors">Privacy</a>
                    <a href="{{ route('cookies') }}" class="hover:text-white transition-colors">Cookies</a>
                    <a href="{{ route('contact') }}" class="hover:text-white transition-colors">Contact</a>
                    <a href="{{ route('track') }}" class="hover:text-white transition-colors">Track</a>
                </div>
            </div>
        </div>
    </footer>

    @include('components.chat-widget')

    <script src="{{ asset('vendor/lucide.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            lucide.createIcons();

            const toggle = document.getElementById('mobile-menu-toggle');
            const menu = document.getElementById('mobile-menu');
            if (toggle && menu) {
                toggle.addEventListener('click', function () {
                    menu.classList.toggle('hidden');
                    document.querySelectorAll('.menu-open, .menu-close').forEach(el => el.classList.toggle('hidden'));
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
