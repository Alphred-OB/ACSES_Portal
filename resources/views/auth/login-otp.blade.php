@php
    $oldCode = old('code', '');
    $codeDigits = array_slice(array_pad(str_split($oldCode), 6, ''), 0, 6);
@endphp

<x-layouts.auth title="Verify New Device" card-width="max-w-lg">
    <div class="space-y-8">
        <div class="space-y-4 text-center">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-amber-100 text-amber-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <rect x="5" y="2" width="14" height="20" rx="2" ry="2"/>
                    <path d="M12 18h.01"/>
                </svg>
            </div>

            <div class="space-y-2">
                <h1 class="text-2xl font-semibold text-slate-900">New device detected</h1>
                <p class="text-sm text-slate-600">
                    We noticed you're signing in from a new device. For your security, enter the 6-digit code we sent to <span class="font-medium text-slate-900">{{ $pending['email'] ?? 'your email' }}</span>.
                </p>
                <p class="text-xs text-slate-500 mt-2">
                    <i class="ri-shield-check-line"></i> Once verified, this device will be trusted for future logins.
                </p>
                @if (session('status'))
                    <p class="rounded-lg bg-green-50 px-4 py-2 text-sm text-green-700">
                        {{ session('status') }}
                    </p>
                @endif
            </div>
        </div>

        <form method="POST" action="{{ route('auth.login.otp.submit') }}" class="space-y-4" data-auth-form>
            @csrf
            <div class="space-y-2" data-otp-container data-otp-target="#login-code">
                <label for="login-code" class="block text-sm font-medium text-slate-700">Device verification code</label>
                <input id="login-code" name="code" type="hidden" value="{{ $oldCode }}" required>
                <div class="flex justify-center gap-3">
                    @foreach ($codeDigits as $index => $digit)
                        <input
                            type="text"
                            inputmode="numeric"
                            maxlength="1"
                            autocomplete="{{ $index === 0 ? 'one-time-code' : 'off' }}"
                            data-otp-input
                            class="h-14 w-12 rounded-xl border border-slate-300 bg-white text-center text-lg font-semibold tracking-widest text-slate-900 shadow-sm transition focus:border-[#0b3019] focus:outline-none focus:ring-2 focus:ring-[#0b3019]/30"
                            aria-label="Login code digit {{ $index + 1 }}"
                            value="{{ $digit }}"
                        >
                    @endforeach
                </div>
                @error('code')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full rounded-xl bg-[#0b3019] py-3 text-sm font-semibold text-white shadow transition hover:-translate-y-0.5 hover:bg-[#094018] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#0b3019]">
                Verify device & continue
            </button>
        </form>

        <div class="space-y-2 text-center text-sm text-slate-600">
            <p>
                Didn’t receive the code? Check your spam folder or promotions tab, then request another email.
            </p>
            <form method="POST" action="{{ route('auth.login.otp.resend') }}" class="flex items-center justify-center gap-2">
                @csrf
                <button type="submit" class="rounded-xl bg-[#0b3019]/10 px-4 py-2 text-sm font-semibold text-[#0b3019] transition hover:-translate-y-0.5 hover:bg-[#0b3019]/20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#0b3019]">
                    Resend login code
                </button>
            </form>
        </div>

        <div class="text-center text-sm text-slate-500">
            <a href="{{ route('login') }}" class="font-semibold text-[#0b3019] hover:underline">Back to sign in</a>
        </div>
    </div>
    </div>

    {{-- Rate limit handler script --}}
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
                        });

                        // Handle Rate Limit (429)
                        if (response.status === 429) {
                            if (overlay) {
                                overlay.classList.add('hidden');
                                overlay.classList.remove('flex');
                            }
                            
                            const data = await response.json();
                            const seconds = data.retry_after || 60;
                            
                            if (window.RateLimitHandler) {
                                window.RateLimitHandler.show(seconds, data.message || 'Too many attempts.', 'Rate Limit Reached');
                            } else {
                                alert(data.message || 'Too many attempts. Please wait.');
                            }
                            return;
                        }

                        // Handle Redirects or Success
                        if (response.redirected) {
                            window.location.href = response.url;
                        } else {
                            const html = await response.text();
                            document.open();
                            document.write(html);
                            document.close();
                        }
                    } catch (error) {
                        console.error('OTP Submit error:', error);
                        form.submit();
                    }
                });
            }
        });
    </script>
</x-layouts.auth>
