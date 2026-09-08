@props(['title', 'breadcrumb' => null, 'image' => asset('images/truck.jpg')])

<section class="relative overflow-hidden rounded-[20px] mx-4 sm:mx-6 lg:mx-8 mt-4 max-h-[420px]">
    <div class="absolute inset-0">
        <img src="{{ $image }}" alt="{{ $title }}" class="w-full h-full object-cover" loading="lazy">
        <div class="absolute inset-0 bg-navy/80"></div>
    </div>
    <div class="relative max-w-7xl mx-auto px-6 py-24 lg:py-32">
        <h1 class="text-4xl lg:text-5xl font-extrabold text-white mb-3">{{ $title }}</h1>
        @if ($breadcrumb)
            <nav class="text-sm text-slate-300">
                <a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a>
                <span class="mx-2">/</span>
                <span class="text-white">{{ $breadcrumb }}</span>
            </nav>
        @endif
    </div>
</section>
