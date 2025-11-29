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

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@0.456.0/dist/umd/lucide.min.js" defer></script>
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    <div class="relative flex min-h-screen flex-col">
        <div class="grid flex-1 gap-0 lg:grid-cols-2">
            <div class="relative hidden overflow-hidden bg-gradient-to-br from-[#0b3019] via-[#11502a] to-[#0b3019] lg:flex">
                <div class="absolute inset-0 animate-gradient bg-[linear-gradient(135deg,#0b3019,#1a6335,#0b3019)] opacity-70"></div>
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(255,255,255,0.18),_transparent_55%)] mix-blend-screen"></div>
                <div class="relative z-10 flex w-full flex-col items-center justify-center px-8 py-16 text-center text-white xl:px-16">
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

            <div class="flex min-h-screen items-center justify-center bg-gradient-to-br from-white via-slate-100 to-slate-200 px-4 py-12 sm:px-6 lg:px-12">
                <div class="w-full {{ $cardWidth }}">
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

    <script>
        document.addEventListener('DOMContentLoaded', () => {
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
</body>
</html>
