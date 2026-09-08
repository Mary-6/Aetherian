@extends('layouts.public')

@section('title', 'About Us - Aetherian Cargo')

@section('content')
    <x-page-banner title="About Us" breadcrumb="About Us" />

    {{-- Who we are --}}
    <section class="py-24 lg:py-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div>
                    <p class="text-accent-500 font-semibold uppercase tracking-widest text-sm mb-3">About Us</p>
                    <h2 class="text-3xl lg:text-5xl font-bold text-slate-900 mb-6 leading-tight">
                        Your trusted logistics partner since 2017
                    </h2>
                    <p class="text-slate-600 text-lg mb-8">
                        Aetherian Cargo is a leading logistics and delivery service provider dedicated to offering fast, reliable, and affordable shipping solutions for businesses and individuals across the country.
                    </p>

                    <ul class="space-y-4 mb-8">
                        <li class="flex items-center gap-4 text-slate-900 font-semibold">
                            <span class="w-12 h-12 rounded-full bg-brand-50 text-brand-600 flex items-center justify-center border border-brand-100">
                                <i data-lucide="shield-check" class="w-6 h-6"></i>
                            </span>
                            Reliable deliveries every single time
                        </li>
                        <li class="flex items-center gap-4 text-slate-900 font-semibold">
                            <span class="w-12 h-12 rounded-full bg-brand-50 text-brand-600 flex items-center justify-center border border-brand-100">
                                <i data-lucide="award" class="w-6 h-6"></i>
                            </span>
                            Expert logistics for your business
                        </li>
                        <li class="flex items-center gap-4 text-slate-900 font-semibold">
                            <span class="w-12 h-12 rounded-full bg-brand-50 text-brand-600 flex items-center justify-center border border-brand-100">
                                <i data-lucide="package" class="w-6 h-6"></i>
                            </span>
                            Streamlined supply chain management
                        </li>
                    </ul>

                    <a href="{{ route('track') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-brand-600 text-white font-semibold rounded-full hover:bg-brand-700 transition-colors">
                        Track shipment
                        <i data-lucide="arrow-right" class="w-5 h-5"></i>
                    </a>
                </div>

                <div class="relative">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="rounded-[20px] overflow-hidden shadow-xl">
                            <img src="{{ asset('images/warehouse.jpg') }}" alt="Warehouse" class="w-full max-h-[260px] sm:max-h-[320px] lg:max-h-[420px] object-cover" loading="lazy">
                        </div>
                        <div class="rounded-[20px] overflow-hidden shadow-xl mt-8">
                            <img src="{{ asset('images/truck.jpg') }}" alt="Truck" class="w-full max-h-[260px] sm:max-h-[320px] lg:max-h-[420px] object-cover" loading="lazy">
                        </div>
                    </div>
                    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 bg-accent-500 text-navy py-4 px-8 rounded-2xl font-bold text-xl shadow-lg">
                        6k+ Trusted Customers
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Why we're the best --}}
    <section class="py-24 lg:py-32 bg-[#F5F5F5]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div>
                    <p class="text-accent-500 font-semibold uppercase tracking-widest text-sm mb-3">Why We're the Best</p>
                    <h2 class="text-3xl lg:text-4xl font-bold text-slate-900 mb-8">
                        Efficiency at its best with Aetherian Cargo
                    </h2>
                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <div class="w-14 h-14 rounded-full bg-white border border-slate-200 text-brand-600 flex items-center justify-center shrink-0">
                                <i data-lucide="map-pin" class="w-8 h-8"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-slate-900 mb-1">Real time tracking</h3>
                                <p class="text-slate-600">Track your shipments in real time with our advanced tracking portal.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-14 h-14 rounded-full bg-white border border-slate-200 text-brand-600 flex items-center justify-center shrink-0">
                                <i data-lucide="clock" class="w-8 h-8"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-slate-900 mb-1">On time delivery</h3>
                                <p class="text-slate-600">We guarantee on-time delivery for every shipment we handle.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-14 h-14 rounded-full bg-white border border-slate-200 text-brand-600 flex items-center justify-center shrink-0">
                                <i data-lucide="users" class="w-8 h-8"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-slate-900 mb-1">24/7 online support</h3>
                                <p class="text-slate-600">Our support team is available around the clock to assist you.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="rounded-[20px] overflow-hidden shadow-2xl">
                    <img src="{{ asset('images/ship.jpg') }}" alt="Shipping" class="w-full max-h-[400px] sm:max-h-[520px] object-cover" loading="lazy">
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="relative py-24 bg-navy text-white overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col lg:flex-row items-center justify-between gap-8">
            <div>
                <h3 class="text-3xl font-bold mb-2">Need any help? contact us!</h3>
                <p class="text-slate-300">Our team is ready to answer your questions and handle your shipments.</p>
            </div>
            <div class="flex items-center gap-4 bg-white/10 rounded-2xl px-8 py-5">
                <i data-lucide="phone" class="w-8 h-8 text-accent-500"></i>
                <div>
                    <p class="text-sm text-slate-300">Need help?</p>
                    <a href="tel:1-800-AETHER" class="text-2xl font-bold hover:text-accent-500 transition-colors">1-800-AETHER</a>
                </div>
            </div>
        </div>
    </section>
@endsection
