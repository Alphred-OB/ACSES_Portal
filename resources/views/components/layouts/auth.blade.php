@props(['title' => null, 'cardWidth' => 'max-w-md'])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ trim($title ?? '') ? $title . ' | ' : '' }}{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicon-96x96.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Typography: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="preload" as="image" href="{{ asset('logo.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@0.456.0/dist/umd/lucide.min.js" defer></script>
    <style>
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; }
        /* Faint Light Green Background */
        .faint-green-bg {
            background-color: #f0fdf4;
            background-image: 
                radial-gradient(at 10% 10%, rgba(220, 252, 231, 0.8) 0px, transparent 50%),
                radial-gradient(at 90% 10%, rgba(209, 250, 229, 0.7) 0px, transparent 50%),
                radial-gradient(at 50% 90%, rgba(240, 253, 244, 0.9) 0px, transparent 50%);
        }
        /* Entrance Animations */
        @keyframes popIn {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up {
            opacity: 0;
            animation: popIn 0.5s ease-out forwards;
        }
        .delay-100 { animation-delay: 0.1s; }
    </style>
</head>
<body class="min-h-screen faint-green-bg text-slate-900 antialiased flex items-center justify-center p-4 sm:p-6 lg:p-8">
    <div id="main-layout" class="w-full max-w-5xl my-auto animate-fade-in-up">
        <div class="mx-auto w-full bg-white rounded-2xl shadow-xl border border-emerald-100/60 {{ $cardWidth }}">
            <div class="px-6 py-8 sm:px-10 sm:py-10">
                <!-- Brand Header -->
                <div class="mb-6 flex flex-col items-center text-center">
                    <a href="{{ url('/') }}" class="group flex flex-col items-center gap-2.5 transition">
                        <img src="{{ asset('logo.png') }}" alt="ACSES Portal Logo" class="h-16 w-auto object-contain transition group-hover:scale-105" fetchpriority="high">
                        <span class="text-xs font-bold uppercase tracking-[0.25em] text-[#0b3019]">ACSES Portal</span>
                    </a>
                </div>

                {{ $slot ?? '' }}
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="auth-loading-overlay" class="hidden fixed inset-0 z-50 items-center justify-center bg-white/60 backdrop-blur-sm">
        <div class="flex flex-col items-center space-y-4">
            <div class="flex h-10 w-10 items-center justify-center rounded-full border-[3px] border-slate-200 border-t-[#0b3019] animate-spin"></div>
            <p class="text-sm font-semibold text-slate-700 tracking-tight">Processing…</p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons({
                    attrs: {
                        width: '1em',
                        height: '1em',
                        'stroke-width': '2'
                    }
                });
            }
            const forms = document.querySelectorAll('[data-auth-form]');
            const overlay = document.getElementById('auth-loading-overlay');
            forms.forEach((form) => {
                form.addEventListener('submit', () => {
                    if (overlay) {
                        overlay.classList.remove('hidden');
                        overlay.classList.add('flex');
                    }
                });
            });
        });
    </script>
    <x-rate-limit-handler />
</body>
</html>
