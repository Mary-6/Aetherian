@extends('layouts.public')

@section('title', 'FAQ - Aetherian Cargo')

@section('content')
    <x-page-banner title="FAQ" breadcrumb="FAQ" />

    <section class="py-24 lg:py-32 bg-[#F5F5F5]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 items-start" x-data="{ open: 0 }">
                {{-- Accordion --}}
                <div>
                    <p class="text-accent-500 font-semibold uppercase tracking-widest text-sm mb-3">FAQ</p>
                    <h2 class="text-3xl lg:text-4xl font-bold text-slate-900 mb-10">
                        Do you have any question please?
                    </h2>

                    <div class="space-y-4">
                        @php
                            $faqs = [
                                ['q' => 'What services does Aetherian Cargo offer?', 'a' => 'Aetherian Cargo offers a comprehensive range of logistics services including express freight solutions, same-day and next-day delivery, supply chain management, distribution services, and residential or commercial moving. We serve both businesses and individual customers.'],
                                ['q' => 'How can I track my shipment?', 'a' => 'Enter your tracking id on the home page or track page. The portal will show the current status, location, and estimated delivery time in real time.'],
                                ['q' => 'What is the delivery timeframe for standard shipments?', 'a' => 'Standard shipments are typically delivered within 2-5 business days depending on origin and destination. Express options are available for faster delivery.'],
                                ['q' => 'Do you offer international shipping?', 'a' => 'Yes. We ship to many countries through our air and ocean freight partners, with customs documentation support where required.'],
                                ['q' => 'What items are restricted from shipping?', 'a' => 'Hazardous materials, illegal goods, perishables without proper packaging, and items prohibited by local or international regulations cannot be shipped. Contact us for specific restrictions.'],
                                ['q' => 'How do I file a claim for a damaged shipment?', 'a' => 'Contact our support team with your tracking id and photos of the damage. We will review and guide you through the claims process.'],
                            ];
                        @endphp

                        @foreach ($faqs as $idx => $f)
                            <div class="border border-slate-200 rounded-[20px] bg-white overflow-hidden">
                                <button
                                    type="button"
                                    @click="open === {{ $idx }} ? open = null : open = {{ $idx }}"
                                    class="w-full flex items-center justify-between p-6 text-left font-semibold text-slate-900"
                                >
                                    {{ $f['q'] }}
                                    <i data-lucide="chevron-down" class="w-5 h-5 text-brand-600 transition-transform" :class="open === {{ $idx }} ? 'rotate-180' : ''"></i>
                                </button>
                                <div
                                    x-show="open === {{ $idx }}"
                                    x-transition
                                    class="px-6 pb-6 text-slate-600 leading-relaxed border-t border-slate-100 pt-4"
                                >
                                    {{ $f['a'] }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Contact form --}}
                <div class="bg-brand-600 text-white rounded-[20px] p-8 lg:p-10 h-fit">
                    <h3 class="text-2xl font-bold mb-2">Get in touch</h3>
                    <p class="text-white/80 mb-8">Send us a message and our team will get back to you within 24 hours.</p>

                    @if (session('success'))
                        <div class="bg-white/10 rounded-xl p-6 text-center mb-6">
                            <p class="font-bold text-lg">Message sent!</p>
                            <p class="text-white/80 text-sm mt-1">We will get back to you shortly.</p>
                        </div>
                    @endif

                    <form action="{{ route('contact.store') }}" method="POST" class="space-y-4">
                        @csrf
                        @if ($errors->any())
                            <div class="bg-red-500/20 text-white p-3 rounded-xl text-sm">
                                {{ $errors->first() }}
                            </div>
                        @endif
                        <div class="grid sm:grid-cols-2 gap-4">
                            <input type="text" name="name" placeholder="Your Name" required value="{{ old('name') }}" class="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white placeholder:text-white/60 focus:outline-none focus:ring-2 focus:ring-white/30">
                            <input type="email" name="email" placeholder="Your Email" required value="{{ old('email') }}" class="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white placeholder:text-white/60 focus:outline-none focus:ring-2 focus:ring-white/30">
                        </div>
                        <input type="text" name="phone" placeholder="Phone Number" value="{{ old('phone') }}" class="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white placeholder:text-white/60 focus:outline-none focus:ring-2 focus:ring-white/30">
                        <input type="hidden" name="subject" value="FAQ question">
                        <textarea name="message" rows="4" placeholder="Message here..." required class="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white placeholder:text-white/60 focus:outline-none focus:ring-2 focus:ring-white/30">{{ old('message') }}</textarea>
                        <button type="submit" class="w-full py-4 bg-accent-500 text-navy font-bold rounded-xl hover:bg-accent-400 transition-colors">Submit Now</button>
                    </form>

                    <div class="mt-8 space-y-3 text-sm text-white/80">
                        <p class="flex items-center gap-3"><i data-lucide="phone" class="w-4 h-4"></i> {{ $companyPhone }}</p>
                        <p class="flex items-center gap-3"><i data-lucide="mail" class="w-4 h-4"></i> {{ $companyEmail }}</p>
                        <p class="flex items-center gap-3"><i data-lucide="map-pin" class="w-4 h-4"></i> {{ $companyAddress }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
