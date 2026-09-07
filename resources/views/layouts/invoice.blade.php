<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Invoice') - {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @media print {
            .no-print { display: none !important; }
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
    @stack('styles')
</head>
<body class="font-sans antialiased bg-slate-100 text-slate-900">
    <div class="max-w-5xl mx-auto p-4 sm:p-6">
        <div class="no-print flex justify-end mb-4">
            <button type="button" onclick="window.print()" class="bg-blue-900 text-white px-5 py-2.5 rounded font-semibold hover:bg-blue-800 transition">Print / Save as PDF</button>
        </div>

        @yield('content')
    </div>
</body>
</html>
