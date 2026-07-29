@php($title = 'Sign In')

<x-layouts.auth :title="$title">
    <div class="w-full">
        <div class="mb-8 text-center">
            <h2 class="text-2xl font-bold tracking-tight text-slate-900">Login to your account</h2>
            <p class="mt-2 text-sm text-slate-500">Enter your credentials to continue</p>
        </div>

        @if(session('status'))
            @if(session('pending_registration'))
            <div class="mb-6 rounded-xl border border-amber-300/50 bg-amber-50 px-4 py-4 text-sm text-amber-800">
                <div class="flex items-start gap-3">
                    <i data-lucide="clock" class="text-xl text-amber-500"></i>
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

        <form method="POST" action="{{ route('auth.login.submit') }}" class="space-y-5" data-auth-form>
            @csrf
            <div class="space-y-1.5">
                <label for="email" class="block text-sm font-medium text-slate-700">Email address</label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                        <i data-lucide="mail" class="text-base" aria-hidden="true"></i>
                    </span>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="email" aria-describedby="email-error" @error('email') aria-invalid="true" @enderror class="block w-full rounded-lg border border-slate-200 bg-white py-2 pl-10 pr-3 text-sm text-slate-900 shadow-sm transition hover:border-slate-300 focus:border-[#0b3019] focus:outline-none focus:ring-1 focus:ring-[#0b3019] @error('email') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror" placeholder="you@example.com" />
                </div>
                @error('email')
                    <p id="email-error" class="text-xs text-red-500" role="alert">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-1.5">
                <div class="flex items-center justify-between">
                    <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
                    <a href="{{ route('password.request') }}" class="text-sm font-medium text-[#0b3019] transition hover:text-[#094018] hover:underline">Forgot password?</a>
                </div>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                        <i data-lucide="lock" class="text-base" aria-hidden="true"></i>
                    </span>
                    <input id="password" name="password" type="password" required autocomplete="current-password" aria-describedby="password-error" @error('password') aria-invalid="true" @enderror class="block w-full rounded-lg border border-slate-200 bg-white py-2 pl-10 pr-10 text-sm text-slate-900 shadow-sm transition hover:border-slate-300 focus:border-[#0b3019] focus:outline-none focus:ring-1 focus:ring-[#0b3019] @error('password') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror" placeholder="Enter your password" />
                    <button type="button" data-password-toggle="#password" class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 transition hover:text-slate-600" aria-label="Toggle password visibility">
                        <i data-eye data-lucide="eye" class="text-base" aria-hidden="true"></i>
                        <i data-eye-off data-lucide="eye-off" class="hidden text-base" aria-hidden="true"></i>
                    </button>
                </div>
                @error('password')
                    <p id="password-error" class="text-xs text-red-500" role="alert">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <label for="remember" class="flex items-center space-x-2 text-sm text-slate-500 cursor-pointer">
                    <input id="remember" name="remember" type="checkbox" value="1" class="h-4 w-4 rounded border-slate-300 text-[#0b3019] focus:ring-[#0b3019] cursor-pointer">
                    <span>Remember me</span>
                </label>
                <span aria-hidden="true"></span>
            </div>

            <button type="submit" class="flex w-full items-center justify-center space-x-2 rounded-lg bg-[#0b3019] px-4 py-2 text-sm font-medium text-white shadow-sm transition-all hover:bg-[#094018] active:scale-[0.98] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#0b3019]">
                <span>Login</span>
                <i data-lucide="arrow-right" class="text-sm" aria-hidden="true"></i>
            </button>
        </form>

        <div class="mt-8 space-y-3 text-center text-sm text-slate-500">
            <p>
                Don’t have an account?
                <a href="{{ route('auth.register') }}" class="font-medium text-[#0b3019] hover:underline hover:text-[#094018]">Sign up</a>
            </p>
            <p>
                Need help?
                <a href="mailto:acsesrepos@gmail.com" class="font-medium text-[#0b3019] hover:underline hover:text-[#094018]">Contact support</a>
            </p>
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

