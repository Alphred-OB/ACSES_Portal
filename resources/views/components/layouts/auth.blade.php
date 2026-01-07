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

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@0.456.0/dist/umd/lucide.min.js" defer></script>
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    <div id="cursor-gradient"></div>
    <div id="cursor-circle"></div>
    <div id="main-layout" class="relative flex min-h-screen flex-col lg:h-screen lg:overflow-hidden">
        <div id="layout-grid" class="grid flex-1 gap-0 lg:h-full lg:grid-cols-2">
            <div id="left-panel" class="relative hidden overflow-hidden bg-gradient-to-br from-[#0b3019] via-[#11502a] to-[#0b3019] lg:flex">
                <div class="absolute inset-0 animate-gradient bg-[linear-gradient(135deg,#0b3019,#1a6335,#0b3019)] opacity-70"></div>
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(255,255,255,0.18),_transparent_55%)] mix-blend-screen"></div>
                
                <!-- Decorative SVGs -->
                <style>
                    @keyframes float {
                        0%, 100% { transform: translateY(0); }
                        50% { transform: translateY(-20px); }
                    }
                    @keyframes spin {
                        from { transform: rotate(0deg); }
                        to { transform: rotate(360deg); }
                    }
                    .animate-float { animation: float 6s ease-in-out infinite; }
                    .animate-spin-slow { animation: spin 12s linear infinite; }
                    .animate-spin-reverse-slow { animation: spin 15s linear infinite reverse; }
                    
                    /* Entrance Animations */
                    @keyframes popIn {
                        0% { 
                            opacity: 0; 
                            transform: translateY(30px) scale(0.9); 
                        }
                        70% { 
                            opacity: 1; 
                            transform: translateY(-5px) scale(1.02); 
                        }
                        100% { 
                            opacity: 1; 
                            transform: translateY(0) scale(1); 
                        }
                    }
                    .animate-fade-in-up {
                        opacity: 0;
                        animation: popIn 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
                    }
                    .delay-100 { animation-delay: 0.15s; }
                    .delay-200 { animation-delay: 0.3s; }
                    
                    /* Mouse Follower */
                    #cursor-gradient {
                        position: fixed;
                        top: 0;
                        left: 0;
                        width: 500px;
                        height: 500px;
                        background: radial-gradient(circle, rgba(11, 48, 25, 0.08) 0%, rgba(11, 48, 25, 0) 70%);
                        border-radius: 50%;
                        transform: translate(-50%, -50%);
                        pointer-events: none;
                        z-index: 9998;
                        mix-blend-mode: multiply;
                        transition: opacity 0.3s ease;
                        opacity: 0;
                    }
                    #cursor-circle {
                        position: fixed;
                        top: 0;
                        left: 0;
                        width: 24px;
                        height: 24px;
                        border: 2px solid rgba(11, 48, 25, 0.5);
                        border-radius: 50%;
                        transform: translate(-50%, -50%);
                        pointer-events: none;
                        z-index: 9999;
                        transition: transform 0.15s ease, opacity 0.3s ease;
                        opacity: 0;
                    }
                    #cursor-circle.hovering {
                        transform: translate(-50%, -50%) scale(1.5);
                        border-color: rgba(11, 48, 25, 0.8);
                    }
                </style>
                <style>
                    @media (min-width: 1024px) {
                        #main-layout {
                            height: 100vh !important;
                            overflow: hidden !important;
                        }
                        #layout-grid {
                            height: 100% !important;
                        }
                        #left-panel {
                            height: 100% !important;
                            overflow: hidden !important;
                        }
                        #right-panel {
                            height: 100% !important;
                            overflow-y: auto !important;
                        }
                    }
                </style>
                <div class="absolute inset-0 overflow-hidden pointer-events-none">
                    <svg class="animate-float" style="position: absolute; left: -4rem; top: -4rem; width: 10rem; height: 10rem; opacity: 0.1; animation-duration: 8s;" viewBox="0 0 256 256" fill="currentColor">
                        <path d="M 228 0 C 172.772 0 128 44.772 128 100 L 128 0 L 0 0 L 0 28 C 0 83.228 44.772 128 100 128 L 0 128 L 0 256 L 28 256 C 83.228 256 128 211.228 128 156 L 128 256 L 256 256 L 256 228 C 256 172.772 211.228 128 156 128 L 256 128 L 256 0 Z"></path>
                    </svg>
                    <svg class="animate-spin-slow" style="position: absolute; bottom: -5rem; right: -5rem; width: 14rem; height: 14rem; opacity: 0.1;" viewBox="0 0 256 256" fill="currentColor">
                        <path d="M 128 192 L 128 256 L 64.5 256 L 32 223 L 0 192 L 0 128 L 64 128 Z M 256 192 L 256 256 L 192.5 256 L 160 223 L 128 192 L 128 128 L 192 128 Z M 128 64 L 128 128 L 64.5 128 L 32 95 L 0 64 L 0 0 L 64 0 Z M 256 64 L 256 128 L 192.5 128 L 160 95 L 128 64 L 128 0 L 192 0 Z"></path>
                    </svg>
                    <svg class="animate-float" style="position: absolute; right: 2rem; top: 5rem; width: 6rem; height: 6rem; opacity: 0.1; animation-delay: 1s;" viewBox="0 0 256 256" fill="currentColor">
                        <path d="M 128 0 C 198.692 0 256 57.308 256 128 C 256 198.692 198.692 256 128 256 C 57.308 256 0 198.692 0 128 C 0 57.308 57.308 0 128 0 Z M 128 32 C 74.98 32 32 74.98 32 128 C 32 181.019 74.98 224 128 224 C 181.019 224 224 181.019 224 128 C 224 74.98 181.019 32 128 32 Z M 128 56 C 167.765 56 200 88.236 200 128 C 200 167.765 167.765 200 128 200 C 88.236 200 56 167.765 56 128 C 56 88.236 88.236 56 128 56 Z M 128 88 C 105.909 88 88 105.909 88 128 C 88 150.091 105.909 168 128 168 C 150.091 168 168 150.091 168 128 C 168 105.909 150.091 88 128 88 Z M 128 112 C 136.837 112 144 119.163 144 128 C 144 136.837 136.837 144 128 144 C 119.163 144 112 136.837 112 128 C 112 119.163 119.163 112 128 112 Z"></path>
                    </svg>
                    <svg class="animate-spin-reverse-slow" style="position: absolute; bottom: 33%; left: -2rem; width: 5rem; height: 5rem; opacity: 0.1;" viewBox="0 0 256 256" fill="currentColor">
                        <path d="M 64 256 L 0 256 L 0 192 L 64 192 Z M 160 256 L 96 256 L 96 160 L 0 160 L 0 96 L 160 96 Z M 256 256 L 192 256 L 192 64 L 0 64 L 0 0 L 256 0 Z"></path>
                    </svg>
                    <svg class="animate-float" style="position: absolute; left: 3rem; bottom: 6rem; width: 8rem; height: 8rem; opacity: 0.1; animation-delay: 2s; animation-duration: 7s;" viewBox="0 0 256 256" fill="currentColor">
                        <path d="M 112 32 L 54.627 32 L 128 105.373 L 201.373 32 L 144 32 L 144 0 L 256 0 L 256 112 L 224 112 L 224 54.627 L 150.627 128 L 224 201.373 L 224 144 L 256 144 L 256 256 L 144 256 L 144 224 L 201.373 224 L 128 150.627 L 54.627 224 L 112 224 L 112 256 L 0 256 L 0 144 L 32 144 L 32 201.373 L 105.373 128 L 32 54.627 L 32 112 L 0 112 L 0 0 L 112 0 Z"></path>
                    </svg>
                    <svg class="animate-float" style="position: absolute; left: 50%; top: -3rem; width: 6rem; height: 6rem; opacity: 0.1;" viewBox="0 0 256 256" fill="currentColor">
                        <path d="M 228 0 C 172.772 0 128 44.772 128 100 L 128 0 L 0 0 L 0 28 C 0 83.228 44.772 128 100 128 L 0 128 L 0 256 L 28 256 C 83.228 256 128 211.228 128 156 L 128 256 L 256 256 L 256 228 C 256 172.772 211.228 128 156 128 L 256 128 L 256 0 Z"></path>
                    </svg>
                    <svg class="animate-spin-reverse-slow" style="position: absolute; right: 10%; bottom: 40%; width: 8rem; height: 8rem; opacity: 0.1;" viewBox="0 0 256 256" fill="currentColor">
                        <path d="M 128 192 L 128 256 L 64.5 256 L 32 223 L 0 192 L 0 128 L 64 128 Z M 256 192 L 256 256 L 192.5 256 L 160 223 L 128 192 L 128 128 L 192 128 Z M 128 64 L 128 128 L 64.5 128 L 32 95 L 0 64 L 0 0 L 64 0 Z M 256 64 L 256 128 L 192.5 128 L 160 95 L 128 64 L 128 0 L 192 0 Z"></path>
                    </svg>
                </div>
                <div class="relative z-10 flex w-full flex-col items-center justify-center px-8 py-16 text-center text-white xl:px-16 animate-fade-in-up">
                    @if (isset($hero) && ! $hero->isEmpty())
                        {{ $hero }}
                    @else
                        <div class="space-y-7">
                            <div class="flex flex-col items-center gap-3 text-white/90">
                                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white/15 shadow-lg">
                                    <img src="{{ asset('logo.png') }}" alt="ACSES" class="h-8 w-8 object-contain" loading="lazy">
                                </div>
                                <div class="inline-flex items-center gap-2 rounded-full bg-white/10 px-5 py-2 text-xs font-semibold uppercase tracking-[0.35em] text-emerald-100">
                                    Secure access • Step 2 of 2
                                </div>
                            </div>

                            <h2 class="text-3xl font-semibold leading-snug text-white md:text-4xl">
                                Check your inbox for the ACSES verification code.
                            </h2>
                            <p class="mx-auto max-w-xl text-base text-white/80">
                                We just emailed a 6-digit One-Time Passcode to finish signing in. Look for the message with subject
                                <span class="font-semibold text-emerald-100">"ACSES verification code"</span>. It usually arrives instantly—if you don’t see it, check your spam or promotions folder.
                            </p>

                            <div class="grid w-full gap-4 text-left md:grid-cols-2">
                                <div class="rounded-2xl border border-white/15 bg-white/10 p-5 backdrop-blur-sm transition hover:-translate-y-1 hover:bg-white/15">
                                    <p class="text-sm font-semibold text-emerald-100">Tip — Search your mailbox</p>
                                    <p class="mt-2 text-sm text-white/85">Search for <span class="font-semibold">"ACSES"</span> or <span class="font-semibold">{{ config('mail.from.address') }}</span> to surface the code email quickly.</p>
                                </div>
                                <div class="rounded-2xl border border-white/15 bg-white/10 p-5 backdrop-blur-sm transition hover:-translate-y-1 hover:bg-white/15">
                                    <p class="text-sm font-semibold text-emerald-100">Didn’t receive it?</p>
                                    <p class="mt-2 text-sm text-white/85">Use "Resend verification code" below, then stay on this page—the latest email always contains the valid OTP.</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div id="right-panel" class="flex h-full flex-col overflow-y-auto bg-gradient-to-br from-white via-slate-100 to-slate-200 lg:h-full">
                <div class="flex min-h-full flex-col px-4 py-24 sm:px-6 lg:px-12" style="padding-top: 6rem; padding-bottom: 6rem;">
                    <div class="mx-auto w-full my-auto animate-fade-in-up delay-100 {{ $cardWidth }}">
                        <div class="mb-8 flex items-center justify-center lg:hidden">
                            <div class="flex h-16 w-16 items-center justify-center overflow-hidden rounded-full bg-white shadow-md ring-1 ring-slate-200">
                                <img src="{{ asset('logo.png') }}" alt="ACSES Portal Logo" class="h-12 w-12 object-contain" loading="lazy">
                            </div>
                        </div>
                        {{ $slot ?? '' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Mouse Follower Logic
            const cursorGradient = document.getElementById('cursor-gradient');
            const cursorCircle = document.getElementById('cursor-circle');
            let mouseX = 0;
            let mouseY = 0;
            let gradientX = 0;
            let gradientY = 0;
            let circleX = 0;
            let circleY = 0;

            document.addEventListener('mousemove', (e) => {
                mouseX = e.clientX;
                mouseY = e.clientY;
                cursorGradient.style.opacity = '1';
                cursorCircle.style.opacity = '1';
            });

            // Hover effect for interactive elements
            const interactiveElements = document.querySelectorAll('a, button, input, select, textarea, [role="button"]');
            interactiveElements.forEach(el => {
                el.addEventListener('mouseenter', () => cursorCircle.classList.add('hovering'));
                el.addEventListener('mouseleave', () => cursorCircle.classList.remove('hovering'));
            });

            function animateCursors() {
                // Gradient follows slowly (lerp 0.08)
                gradientX += (mouseX - gradientX) * 0.08;
                gradientY += (mouseY - gradientY) * 0.08;
                cursorGradient.style.left = `${gradientX}px`;
                cursorGradient.style.top = `${gradientY}px`;
                
                // Circle follows faster (lerp 0.25)
                circleX += (mouseX - circleX) * 0.25;
                circleY += (mouseY - circleY) * 0.25;
                cursorCircle.style.left = `${circleX}px`;
                cursorCircle.style.top = `${circleY}px`;
                
                requestAnimationFrame(animateCursors);
            }
            animateCursors();

            // Existing form overlay logic
            const forms = document.querySelectorAll('[data-auth-form]');
            const overlay = document.getElementById('auth-loading-overlay');
            forms.forEach((form) => {
                form.addEventListener('submit', () => {
                    if (!overlay) {
                        return;
                    }
                    overlay.classList.remove('hidden');
                    overlay.classList.add('flex');
                });
            });
        });
    </script>

    {{-- Rate limit handler for countdown timer --}}
    <x-rate-limit-handler />
</body>
</html>
