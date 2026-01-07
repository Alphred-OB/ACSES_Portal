@php($title = 'Sign In')

<x-layouts.auth :title="$title">
    <x-slot:hero>
        <div class="mx-auto w-full max-w-lg text-center">
            <div class="mx-auto mb-4 flex h-20 w-20 items-center justify-center overflow-hidden rounded-full bg-white shadow-lg">
                <img src="{{ asset('logo.png') }}" alt="ACSES Portal Logo" class="h-full w-full object-contain" loading="lazy">
            </div>
            <h1 class="text-2xl font-semibold text-white">Welcome back</h1>
            <p class="mt-2 text-sm text-white/80">Enter your credentials to continue</p>
        </div>
    </x-slot:hero>

    <div class="w-full">
        <div class="mb-8 text-center lg:text-left">
            <h2 class="text-2xl font-semibold text-slate-900">Login to your account</h2>
            <p class="mt-2 text-sm text-slate-600">Enter your credentials to continue</p>
        </div>

        @if(session('status'))
            @if(session('pending_registration'))
            <div class="mb-6 rounded-xl border border-amber-300/50 bg-amber-50 px-4 py-4 text-sm text-amber-800">
                <div class="flex items-start gap-3">
                    <i class="ri-time-line text-xl text-amber-500"></i>
                    <div>
                        <p class="font-semibold">Registration Submitted for Review</p>
                        <p class="mt-1 text-amber-700">{{ session('status') }}</p>
                    </div>
                </div>
            </div>
            @else
            <div class="mb-6 rounded-xl border border-[#0b3019]/30 bg-[#0b3019]/10 px-4 py-3 text-sm text-[#0b3019]">
                {{ session('status') }}
            </div>
            @endif
        @endif

        <form method="POST" action="{{ route('auth.login.submit') }}" class="space-y-6" data-auth-form>
            @csrf
            <div class="space-y-2">
                <label for="email" class="block text-sm font-medium text-slate-700">Email address</label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <i class="ri-mail-line text-lg" aria-hidden="true"></i>
                    </span>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="email" class="block w-full rounded-xl border border-slate-300 bg-white py-3 pl-11 pr-3 text-sm text-slate-900 shadow-sm transition focus:border-[#0b3019] focus:outline-none focus:ring-2 focus:ring-[#0b3019]/30" placeholder="you@example.com" />
                </div>
                @error('email')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
                    <a href="{{ route('password.request') }}" class="text-sm font-semibold text-[#0b3019] transition hover:underline">Forgot password?</a>
                </div>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <i class="ri-lock-password-line text-lg" aria-hidden="true"></i>
                    </span>
                    <input id="password" name="password" type="password" required autocomplete="current-password" class="block w-full rounded-xl border border-slate-300 bg-white py-3 pl-11 pr-12 text-sm text-slate-900 shadow-sm transition focus:border-[#0b3019] focus:outline-none focus:ring-2 focus:ring-[#0b3019]/30" placeholder="Enter your password" />
                    <button type="button" data-password-toggle="#password" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 transition hover:text-slate-600" aria-label="Toggle password visibility">
                        <i data-eye class="ri-eye-line text-lg" aria-hidden="true"></i>
                        <i data-eye-off class="ri-eye-off-line hidden text-lg" aria-hidden="true"></i>
                    </button>
                </div>
                @error('password')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <label for="remember" class="flex items-center space-x-2 text-sm text-slate-600">
                    <input id="remember" name="remember" type="checkbox" value="1" class="h-4 w-4 rounded border-slate-300 text-[#0b3019] focus:ring-[#0b3019]">
                    <span>Remember me</span>
                </label>
                <span aria-hidden="true"></span>
            </div>

            <button type="submit" class="flex w-full items-center justify-center space-x-2 rounded-xl bg-[#0b3019] px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-[#0b3019]/30 transition duration-200 ease-out hover:-translate-y-0.5 hover:bg-[#094018] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#0b3019]">
                <i class="ri-login-circle-line text-lg" aria-hidden="true"></i>
                <span>Login</span>
            </button>
        </form>

        <div class="mt-6 space-y-3 text-center text-sm text-slate-600">
            <p>
                Don’t have an account?
                <a href="{{ route('auth.register') }}" class="font-semibold text-[#0b3019] hover:underline">Sign up</a>
            </p>
            <p>
                Need help?
                <a href="mailto:acsesrepos@gmail.com" class="font-semibold text-[#0b3019] hover:underline">Contact support</a>
            </p>
        </div>

        {{-- Device Security Notice --}}
        <div class="mt-6 rounded-2xl border border-[#0b3019]/10 bg-[#0b3019]/5 px-4 py-3">
            <div class="flex items-start gap-3">
                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#0b3019]/10 text-[#0b3019]">
                    <i class="ri-shield-check-line text-base" aria-hidden="true"></i>
                </span>
                <div class="text-xs text-slate-600">
                    <p class="font-semibold text-[#0b3019]">Device-Based Security</p>
                    <p class="mt-1 leading-relaxed">For your protection, we verify new devices with a one-time code sent to your email. Once verified, you can sign in instantly on that device.</p>
                </div>
            </div>
        </div>
    </div>

    <div id="auth-loading-overlay" class="hidden fixed inset-0 z-40 items-center justify-center bg-white/80 backdrop-blur-sm">
        <div class="flex flex-col items-center space-y-4">
            <div class="flex h-12 w-12 items-center justify-center rounded-full border-2 border-[#0b3019]/20">
                <i class="ri-loader-4-line animate-spin text-2xl text-[#0b3019]" aria-hidden="true"></i>
            </div>
            <p class="text-sm font-medium text-slate-700">Signing you in…</p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('[data-auth-form]');
            const overlay = document.getElementById('auth-loading-overlay');

            if (form) {
                form.addEventListener('submit', async function(e) {
                    e.preventDefault();

                    // Show loading overlay
                    if (overlay) {
                        overlay.classList.remove('hidden');
                        overlay.classList.add('flex');
                    }

                    const formData = new FormData(form);

                    try {
                        const response = await fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                            credentials: 'same-origin',
                            redirect: 'follow',
                        });

                        // Check for rate limit
                        if (response.status === 429) {
                            // Hide loading overlay
                            if (overlay) {
                                overlay.classList.add('hidden');
                                overlay.classList.remove('flex');
                            }

                            // Try to get retry_after from response
                            let seconds = 60;
                            let message = 'Too many login attempts. Please wait before trying again.';
                            
                            try {
                                const data = await response.json();
                                seconds = data.retry_after || 60;
                                message = data.message || message;
                            } catch (e) {
                                // Try to get from header
                                const retryAfter = response.headers.get('Retry-After');
                                if (retryAfter) {
                                    seconds = parseInt(retryAfter, 10) || 60;
                                }
                            }

                            // Show the rate limit countdown
                            if (window.RateLimitHandler) {
                                window.RateLimitHandler.show(seconds, message, 'Too Many Login Attempts');
                            } else {
                                alert(message);
                            }
                            return;
                        }

                        // For successful responses or redirects, follow the redirect
                        if (response.redirected) {
                            window.location.href = response.url;
                            return;
                        }

                        // For HTML responses (validation errors), replace the page content
                        if (response.ok) {
                            const html = await response.text();
                            // Check if it's a redirect in the HTML
                            if (response.url !== window.location.href) {
                                window.location.href = response.url;
                            } else {
                                // Replace document with response (for validation errors)
                                document.open();
                                document.write(html);
                                document.close();
                            }
                        } else {
                            // Hide loading overlay on error
                            if (overlay) {
                                overlay.classList.add('hidden');
                                overlay.classList.remove('flex');
                            }
                            // For other errors, submit the form normally
                            form.submit();
                        }
                    } catch (error) {
                        console.error('Login error:', error);
                        // Hide loading overlay
                        if (overlay) {
                            overlay.classList.add('hidden');
                            overlay.classList.remove('flex');
                        }
                        // Fallback to normal form submission
                        form.submit();
                    }
                });
            }
        });
    </script>
</x-layouts.auth>

