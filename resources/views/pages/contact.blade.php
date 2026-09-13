@extends('layouts.public')

@section('title', 'Contact Us - Aetherian Cargo')

@section('content')
    <x-page-banner title="Contact Us" breadcrumb="Contact Us" />

    <section class="py-24 lg:py-32 bg-[#F5F5F5]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12">
                <div>
                    <p class="text-accent-500 font-semibold uppercase tracking-widest text-sm mb-3">Get in touch</p>
                    <h2 class="text-3xl lg:text-4xl font-bold text-slate-900 mb-6">We would love to hear from you</h2>
                    <p class="text-slate-600 leading-relaxed mb-10">
                        Have a question about a shipment, our services, or your account? Reach out and our team will respond as soon as possible.
                    </p>

                    <div class="space-y-5">
                        <a href="tel:{{ $companyPhone }}" class="flex items-center gap-4 bg-white p-5 rounded-2xl border border-slate-200 group">
                            <div class="w-12 h-12 rounded-full bg-brand-50 text-brand-600 flex items-center justify-center group-hover:bg-brand-600 group-hover:text-white transition-colors">
                                <i data-lucide="phone" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <p class="text-sm text-slate-500">Phone</p>
                                <p class="font-semibold text-slate-900 group-hover:text-brand-600 transition-colors">{{ $companyPhone }}</p>
                            </div>
                        </a>
                        <a href="mailto:{{ $companyEmail }}" class="flex items-center gap-4 bg-white p-5 rounded-2xl border border-slate-200 group">
                            <div class="w-12 h-12 rounded-full bg-brand-50 text-brand-600 flex items-center justify-center group-hover:bg-brand-600 group-hover:text-white transition-colors">
                                <i data-lucide="mail" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <p class="text-sm text-slate-500">Email</p>
                                <p class="font-semibold text-slate-900 group-hover:text-brand-600 transition-colors">{{ $companyEmail }}</p>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-[20px] shadow-lg border border-slate-200">
                    <form action="{{ route('contact.store') }}" method="POST" class="space-y-5">
                        @csrf
                        <div class="grid sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-semibold text-slate-900 mb-2">Your Name</label>
                                <input type="text" name="name" value="{{ old('name') }}" class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-brand-500" placeholder="John Doe" required>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-900 mb-2">Your Email</label>
                                <input type="email" name="email" value="{{ old('email') }}" class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-brand-500" placeholder="john@example.com" required>
                            </div>
                        </div>
                        <div class="grid sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-semibold text-slate-900 mb-2">Phone Number</label>
                                <input type="text" name="phone" value="{{ old('phone') }}" class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-brand-500" placeholder="+1 800 000 0000">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-900 mb-2">Subject</label>
                                <input type="text" name="subject" value="{{ old('subject') }}" class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-brand-500" placeholder="Shipment inquiry">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-900 mb-2">Message</label>
                            <textarea name="message" rows="4" class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-brand-500" placeholder="How can we help you?" required>{{ old('message') }}</textarea>
                        </div>
                        <button type="submit" class="w-full sm:w-auto px-8 py-4 bg-brand-600 text-white font-bold rounded-full hover:bg-brand-700 transition-colors inline-flex items-center justify-center gap-2">
                            Send Message
                            <i data-lucide="arrow-right" class="w-5 h-5"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
