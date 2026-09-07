@extends('layouts.public')

@section('title', 'Aetherian Cargo | International Freight')

@section('content')
    {{-- Hero --}}
    <section class="relative min-h-[700px] flex items-center overflow-hidden">
        <img src="{{ asset('images/hero.jpg') }}" alt="Global logistics hub" class="absolute inset-0 w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-r from-navy/90 via-navy/70 to-transparent"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 w-full">
            <div class="max-w-2xl">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur rounded-full text-brand-300 text-sm font-medium mb-6">
                    <i data-lucide="plane" class="w-4 h-4"></i>
                    Reliable Shipping
                </div>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white leading-tight mb-6">
                    Global Deliveries Made Simple
                </h1>
                <p class="text-lg text-slate-200 mb-10 max-w-xl">
                    From warehouse to doorstep, Aetherian Cargo gives you clear tracking, dependable transit, and a team that keeps your cargo moving around the clock.
                </p>

                <a href="{{ route('track') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-accent-500 text-navy font-bold rounded-full hover:bg-accent-400 transition-colors">
                    Track shipment
                    <span class="w-7 h-7 rounded-full bg-navy/20 flex items-center justify-center">
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </span>
                </a>

                <div class="mt-12 flex items-center gap-4 text-white">
                    <div class="w-12 h-12 rounded-full bg-brand-600 flex items-center justify-center">
                        <i data-lucide="phone" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <p class="text-sm text-slate-300">Need help?</p>
                        <a href="tel:1-800-AETHER" class="text-xl font-bold hover:text-brand-300">1-800-AETHER</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- About --}}
    <section class="py-24 bg-[#F5F5F5]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div>
                    <p class="text-brand-600 font-semibold uppercase tracking-widest text-sm mb-3">About Us</p>
                    <h2 class="text-3xl lg:text-5xl font-bold text-slate-900 mb-6 leading-tight">Your shipments in safe hands</h2>
                    <p class="text-slate-600 text-lg mb-8">
                        We combine modern tracking technology with a dedicated operations team so every package is routed, monitored, and delivered with care.
                    </p>
                    <a href="{{ route('track') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-brand-600 text-white font-semibold rounded-full hover:bg-brand-700 transition-colors">
                        Track shipment
                        <span class="w-7 h-7 rounded-full bg-white/20 flex items-center justify-center">
                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </span>
                    </a>
                    <div class="mt-12">
                        <span class="text-4xl sm:text-6xl lg:text-8xl font-bold text-slate-200 select-none break-words">SINCE 2017</span>
                    </div>
                </div>

                <div class="relative">
                    <div class="rounded-[20px] overflow-hidden shadow-2xl">
                        <img src="{{ asset('images/warehouse.jpg') }}" alt="Warehouse operations" class="w-full h-auto object-cover">
                    </div>
                    <div class="absolute -bottom-6 -left-6 bg-brand-600 text-white p-6 rounded-2xl shadow-xl hidden lg:block">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center">
                                <i data-lucide="phone" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <p class="text-sm text-white/80">Need help?</p>
                                <a href="tel:1-800-AETHER" class="text-xl font-bold">1-800-AETHER</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Stats --}}
    <section class="py-24 lg:py-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div class="relative">
                    <div class="rounded-[20px] overflow-hidden shadow-xl">
                        <img src="{{ asset('images/truck.jpg') }}" alt="Truck on the road" class="w-full h-auto object-cover">
                    </div>
                    <div class="absolute -bottom-4 -right-4 bg-accent-500 text-navy py-4 px-8 rounded-2xl font-bold text-xl hidden lg:block">
                        Since 2017
                    </div>
                </div>

                <div class="lg:pl-10">
                    <div class="grid grid-cols-2 gap-6">
                        @php
                            $stats = [
                                ['icon' => 'users', 'value' => '100+', 'label' => 'Trusted Clients'],
                                ['icon' => 'package', 'value' => '100+', 'label' => 'Shipments Moved'],
                                ['icon' => 'map-pin', 'value' => '20k+', 'label' => 'Destinations Covered'],
                                ['icon' => 'user-check', 'value' => '200+', 'label' => 'Logistics Experts'],
                            ];
                        @endphp
                        @foreach ($stats as $stat)
                            <div class="text-center border border-slate-200 rounded-[20px] p-6 bg-white hover:shadow-md transition-shadow">
                                <div class="flex flex-col sm:flex-row items-center justify-center gap-2 sm:gap-4 mb-3">
                                    <span class="text-4xl font-bold text-slate-900">{{ $stat['value'] }}</span>
                                    <span class="text-brand-600"><i data-lucide="{{ $stat['icon'] }}" class="w-8 h-8"></i></span>
                                </div>
                                <p class="text-slate-600">{{ $stat['label'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Services --}}
    <section id="services" class="py-24 lg:py-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-20">
                <p class="text-brand-600 font-semibold uppercase tracking-widest text-sm mb-3">What We Do</p>
                <h2 class="text-3xl lg:text-4xl font-bold text-slate-900">Logistics built around your cargo</h2>
            </div>

            <div class="grid md:grid-cols-3 gap-8 lg:gap-12">
                @php
                    $services = [
                        ['icon' => 'plane', 'title' => 'Express Air Cargo', 'text' => 'Speedy airport-to-airport and door-to-door air transport for time-sensitive freight.'],
                        ['icon' => 'truck', 'title' => 'Ground Distribution', 'text' => 'Flexible road haulage for pallets, parcels, and full truckloads across cities and borders.'],
                        ['icon' => 'ship', 'title' => 'Ocean Freight', 'text' => 'Cost-effective container shipping for bulk goods with end-to-end visibility.'],
                    ];
                @endphp
                @foreach ($services as $service)
                    <div class="service-card group">
                        <div class="service-icon">
                            <i data-lucide="{{ $service['icon'] }}" class="w-8 h-8"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 mb-4 mt-2">{{ $service['title'] }}</h3>
                        <p class="text-slate-600 leading-relaxed">{{ $service['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- How We Work --}}
    <section class="py-24 lg:py-32 bg-[#F5F5F5]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-20">
                <p class="text-brand-600 font-semibold uppercase tracking-widest text-sm mb-3">Our Process</p>
                <h2 class="text-3xl lg:text-4xl font-bold text-slate-900">Three steps to ship anywhere</h2>
            </div>

            <div class="grid md:grid-cols-3 gap-8 relative">
                @php
                    $steps = [
                        ['step' => '01', 'icon' => 'package', 'title' => 'Book & Label', 'text' => 'Schedule a pickup, print labels, and hand your cargo to our courier.'],
                        ['step' => '02', 'icon' => 'warehouse', 'title' => 'Hub & Route', 'text' => 'Your freight is sorted, scanned, and dispatched through the fastest available lane.'],
                        ['step' => '03', 'icon' => 'truck', 'title' => 'Handoff & Confirm', 'text' => 'We deliver to the recipient and update the timeline with proof of delivery.'],
                    ];
                @endphp
                @foreach ($steps as $s)
                    <div class="process-card group">
                        <span class="process-step">{{ $s['step'] }}</span>
                        <h3 class="text-xl font-bold text-slate-900 mb-4">{{ $s['title'] }}</h3>
                        <div class="flex items-start gap-4">
                            <div class="process-icon shrink-0">
                                <i data-lucide="{{ $s['icon'] }}" class="w-12 h-12"></i>
                            </div>
                            <p class="text-slate-600 leading-relaxed">{{ $s['text'] }}</p>
                        </div>
                    </div>
                @endforeach

                <svg class="hidden lg:block absolute top-1/2 left-1/3 -translate-y-1/2 w-24 h-12 text-slate-300" viewBox="0 0 100 40" fill="none">
                    <path d="M0 20 Q50 0 90 20" stroke="currentColor" stroke-width="3" fill="none" marker-end="url(#arrow1)" />
                    <defs>
                        <marker id="arrow1" markerWidth="10" markerHeight="10" refX="8" refY="3" orient="auto" markerUnits="strokeWidth">
                            <path d="M0,0 L0,6 L9,3 z" fill="currentColor" />
                        </marker>
                    </defs>
                </svg>
                <svg class="hidden lg:block absolute top-1/2 left-2/3 -translate-y-1/2 w-24 h-12 text-slate-300" viewBox="0 0 100 40" fill="none">
                    <path d="M0 20 Q50 0 90 20" stroke="currentColor" stroke-width="3" fill="none" marker-end="url(#arrow2)" />
                    <defs>
                        <marker id="arrow2" markerWidth="10" markerHeight="10" refX="8" refY="3" orient="auto" markerUnits="strokeWidth">
                            <path d="M0,0 L0,6 L9,3 z" fill="currentColor" />
                        </marker>
                    </defs>
                </svg>
            </div>
        </div>
    </section>

    {{-- Projects --}}
    <section class="py-24 lg:py-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-[#F5F5F5] rounded-[20px] p-10 flex flex-col justify-between">
                    <div>
                        <p class="text-brand-600 font-semibold uppercase tracking-widest text-sm mb-3">Where We Deliver</p>
                        <h2 class="text-3xl lg:text-4xl font-bold text-slate-900 mb-6">Across every channel</h2>
                    </div>
                    <a href="{{ route('services') }}" class="inline-flex items-center gap-2 self-start px-6 py-3 bg-brand-600 text-white font-semibold rounded-full hover:bg-brand-700 transition-colors">
                        Our services
                        <span class="w-7 h-7 rounded-full bg-white/20 flex items-center justify-center">
                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </span>
                    </a>
                </div>

                @php
                    $projects = [
                        ['img' => 'images/ship.jpg', 'title' => 'Ocean Freight'],
                        ['img' => 'images/warehouse.jpg', 'title' => 'Warehouse Operations'],
                        ['img' => 'images/truck.jpg', 'title' => 'Ground Distribution'],
                        ['img' => 'images/plane.jpg', 'title' => 'Air Cargo Network'],
                    ];
                @endphp
                @foreach ($projects as $project)
                    <div class="project-card group cursor-pointer">
                        <img src="{{ asset($project['img']) }}" alt="{{ $project['title'] }}">
                        <div class="project-overlay"></div>
                        <div class="project-caption">
                            <p class="text-sm font-semibold text-slate-500">Aetherian Cargo</p>
                            <h3 class="text-xl font-bold text-slate-900">{{ $project['title'] }}</h3>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Testimonials --}}
    <section id="testimonials" class="py-24 lg:py-32 bg-[#F5F5F5]">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-brand-600 font-semibold uppercase tracking-widest text-sm mb-3">Testimonials</p>
            <h2 class="text-3xl lg:text-4xl font-bold text-slate-900 mb-12">What our clients say</h2>
            <div class="relative bg-white rounded-[20px] border border-slate-200 p-10 lg:p-16">
                <div class="absolute -top-10 left-1/2 -translate-x-1/2 w-20 h-20 rounded-full bg-brand-600 border-4 border-[#F5F5F5] flex items-center justify-center text-white">
                    <i data-lucide="quote" class="w-8 h-8"></i>
                </div>
                <h3 class="text-2xl font-bold text-slate-900 mt-6">Linda Carter</h3>
                <p class="text-slate-500 mb-6">Operations Lead, FreshMart</p>
                <p class="text-xl lg:text-2xl text-slate-700 leading-relaxed mb-8">
                    &ldquo;From perishables to bulk orders, Aetherian Cargo handles our deliveries with care. The real-time tracking and quick support make a real difference during peak season.&rdquo;
                </p>
                <div class="w-24 h-24 mx-auto rounded-full overflow-hidden border-4 border-[#F5F5F5] mb-4">
                    <img src="{{ asset('images/avatar-sarah.jpg') }}" alt="Linda Carter" class="w-full h-full object-cover">
                </div>
                <div class="flex items-center justify-center text-accent-500">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.429 8.2 1.191-5.934 5.784 1.4 8.169L12 18.896 4.666 23.16l1.4-8.169L0 9.207l8.2-1.191z"/></svg>
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.429 8.2 1.191-5.934 5.784 1.4 8.169L12 18.896 4.666 23.16l1.4-8.169L0 9.207l8.2-1.191z"/></svg>
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.429 8.2 1.191-5.934 5.784 1.4 8.169L12 18.896 4.666 23.16l1-8.169L0 9.207l8.2-1.191z"/></svg>
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.429 8.2 1.191-5.934 5.784 1.4 8.169L12 18.896 4.666 23.16l1.4-8.169L0 9.207l8.2-1.191z"/></svg>
                    <svg class="w-5 h-5 fill-current text-slate-300" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.429 8.2 1.191-5.934 5.784 1.4 8.169L12 18.896 4.666 23.16l1.4-8.169L0 9.207l8.2-1.191z"/></svg>
                </div>
            </div>
        </div>
    </section>
@endsection
