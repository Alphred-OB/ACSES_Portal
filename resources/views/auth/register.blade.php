@php($title = 'Create Account')

<x-layouts.auth :title="$title" card-width="max-w-2xl">
    <x-slot:hero>
        <div class="mx-auto w-full max-w-lg text-center">
            <div class="mx-auto flex h-20 w-20 items-center justify-center overflow-hidden rounded-full bg-white/90 shadow-lg">
                <img src="{{ asset('logo.png') }}" alt="ACSES Portal Logo" class="h-full w-full object-contain" loading="lazy">
            </div>
            <h1 class="mt-8 text-3xl font-semibold tracking-tight text-white lg:text-4xl">Join the ACSES Community</h1>
            <p class="mt-4 max-w-md text-base text-white/80 mx-auto">
                Create an account to manage registrations, dues, and stay informed about departmental updates.
            </p>
        </div>
    </x-slot:hero>

    <div class="mx-auto w-full max-w-7xl">
        <div class="mb-8 text-center lg:text-left">
            <h2 class="text-2xl font-semibold text-slate-900">Student Registration</h2>
            <p class="mt-2 text-sm text-slate-600">Provide your student details to get started.</p>
        </div>

        <form method="POST" action="{{ route('auth.register.submit') }}" class="space-y-12" data-auth-form>
            @csrf

            <!-- Security Honeypot (Invisible to humans) -->
            <div class="hidden" aria-hidden="true">
                <input type="text" name="website_origin" tabindex="-1" autocomplete="off">
            </div>

            @if(session('error'))
                <div class="rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800 animate-pulse">
                    <div class="flex items-center gap-2">
                        <i class="ri-alert-line text-lg"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif
                <div class="grid gap-6 lg:grid-cols-2">
                    <div class="lg:col-span-2 grid gap-6 md:grid-cols-2">
                        <div class="space-y-2">
                            <label for="first_name" class="block text-sm font-medium text-slate-700">First name</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                    <i class="ri-user-line text-lg" aria-hidden="true"></i>
                                </span>
                                <input id="first_name" name="first_name" type="text" value="{{ old('first_name') }}" required autocomplete="given-name" class="block w-full rounded-xl border border-slate-300 bg-white py-3 pl-11 pr-4 text-sm text-slate-900 shadow-sm transition focus:border-[#0b3019] focus:outline-none focus:ring-2 focus:ring-[#0b3019]/30" placeholder="Kofi" />
                            </div>
                            @error('first_name')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="last_name" class="block text-sm font-medium text-slate-700">Last name</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                    <i class="ri-user-3-line text-lg" aria-hidden="true"></i>
                                </span>
                                <input id="last_name" name="last_name" type="text" value="{{ old('last_name') }}" required autocomplete="family-name" class="block w-full rounded-xl border border-slate-300 bg-white py-3 pl-11 pr-4 text-sm text-slate-900 shadow-sm transition focus:border-[#0b3019] focus:outline-none focus:ring-2 focus:ring-[#0b3019]/30" placeholder="Mensah" />
                            </div>
                            @error('last_name')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="username" class="block text-sm font-medium text-slate-700">Username</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                <i class="ri-user-star-line text-lg" aria-hidden="true"></i>
                            </span>
                            <input id="username" name="username" type="text" value="{{ old('username') }}" required autocomplete="username" class="block w-full rounded-xl border border-slate-300 bg-white py-3 pl-11 pr-12 text-sm text-slate-900 shadow-sm transition focus:border-[#0b3019] focus:outline-none focus:ring-2 focus:ring-[#0b3019]/30" placeholder="kofimensah" />
                            {{-- Validation status indicator --}}
                            <div id="username-status-icon" class="absolute inset-y-0 right-0 flex items-center pr-3">
                                {{-- Spinner (checking) --}}
                                <i id="username-checking" class="ri-loader-4-line hidden animate-spin text-lg text-slate-400" aria-hidden="true"></i>
                                {{-- Checkmark (available) --}}
                                <i id="username-available" class="ri-check-line hidden text-lg text-emerald-500" aria-hidden="true"></i>
                                {{-- X (taken) --}}
                                <i id="username-taken" class="ri-close-line hidden text-lg text-rose-500" aria-hidden="true"></i>
                            </div>
                        </div>
                        {{-- Validation message --}}
                        <div id="username-feedback" class="hidden rounded-lg px-3 py-2 text-xs"></div>
                        @error('username')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="index_number" class="block text-sm font-medium text-slate-700">Reference number</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                <i class="ri-hashtag text-lg" aria-hidden="true"></i>
                            </span>
                            <input id="index_number" name="index_number" type="text" value="{{ old('index_number') }}" required inputmode="numeric" pattern="\d{9,11}" maxlength="11" data-numeric-only class="block w-full rounded-xl border border-slate-300 bg-white py-3 pl-11 pr-12 text-sm text-slate-900 shadow-sm transition focus:border-[#0b3019] focus:outline-none focus:ring-2 focus:ring-[#0b3019]/30" placeholder="9012345623" />
                            {{-- Validation status indicator --}}
                            <div id="index_number-status-icon" class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <i id="index_number-checking" class="ri-loader-4-line hidden animate-spin text-lg text-slate-400" aria-hidden="true"></i>
                                <i id="index_number-available" class="ri-check-line hidden text-lg text-emerald-500" aria-hidden="true"></i>
                                <i id="index_number-taken" class="ri-close-line hidden text-lg text-rose-500" aria-hidden="true"></i>
                            </div>
                        </div>
                        {{-- Validation message --}}
                        <div id="index_number-feedback" class="hidden rounded-lg px-3 py-2 text-xs"></div>
                        @error('index_number')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-medium text-slate-700">Email address</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                <i class="ri-mail-line text-lg" aria-hidden="true"></i>
                            </span>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email" class="block w-full rounded-xl border border-slate-300 bg-white py-3 pl-11 pr-12 text-sm text-slate-900 shadow-sm transition focus:border-[#0b3019] focus:outline-none focus:ring-2 focus:ring-[#0b3019]/30" placeholder="cy-yourname@st.umat.edu.gh" />
                            {{-- Validation status indicator --}}
                            <div id="email-status-icon" class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <i id="email-checking" class="ri-loader-4-line hidden animate-spin text-lg text-slate-400" aria-hidden="true"></i>
                                <i id="email-available" class="ri-check-line hidden text-lg text-emerald-500" aria-hidden="true"></i>
                                <i id="email-taken" class="ri-close-line hidden text-lg text-rose-500" aria-hidden="true"></i>
                            </div>
                        </div>
                        {{-- Validation message --}}
                        <div id="email-feedback" class="hidden rounded-lg px-3 py-2 text-xs"></div>
                        @error('email')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        {{-- Email verification notice --}}
                        <div id="email-verification-notice" class="hidden rounded-xl border p-3 text-xs">
                            <div id="school-email-notice" class="hidden">
                                <div class="flex items-start gap-2 text-emerald-700">
                                    <i class="ri-shield-check-line text-base mt-0.5"></i>
                                    <div>
                                        <p class="font-semibold">School Email Detected</p>
                                        <p class="text-emerald-600">You'll receive a quick email verification code to activate your account instantly.</p>
                                    </div>
                                </div>
                            </div>
                            <div id="non-school-email-notice" class="hidden">
                                <div class="flex items-start gap-2 text-amber-700">
                                    <i class="ri-user-search-line text-base mt-0.5"></i>
                                    <div>
                                        <p class="font-semibold">Manual Verification Required</p>
                                        <p class="text-amber-600">Non-school emails require admin approval. You'll receive an email once your application is reviewed (usually 1-2 business days).</p>
                                    </div>
                                </div>
                            </div>
                            <div id="email-mismatch-notice" class="hidden">
                                <div class="flex items-start gap-2 text-rose-700">
                                    <i class="ri-error-warning-line text-base mt-0.5"></i>
                                    <div>
                                        <p class="font-semibold">Program Mismatch</p>
                                        <p class="text-rose-600" id="mismatch-message"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="phone_number" class="block text-sm font-medium text-slate-700">Phone number</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                <i class="ri-phone-line text-lg" aria-hidden="true"></i>
                            </span>
                            <input id="phone_number" name="phone_number" type="tel" value="{{ old('phone_number') }}" inputmode="numeric" pattern="\d{9,11}" maxlength="11" data-numeric-only class="block w-full rounded-xl border border-slate-300 bg-white py-3 pl-11 pr-12 text-sm text-slate-900 shadow-sm transition focus:border-[#0b3019] focus:outline-none focus:ring-2 focus:ring-[#0b3019]/30" placeholder="0541234567" />
                            {{-- Validation status indicator --}}
                            <div id="phone_number-status-icon" class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <i id="phone_number-checking" class="ri-loader-4-line hidden animate-spin text-lg text-slate-400" aria-hidden="true"></i>
                                <i id="phone_number-available" class="ri-check-line hidden text-lg text-emerald-500" aria-hidden="true"></i>
                                <i id="phone_number-taken" class="ri-close-line hidden text-lg text-rose-500" aria-hidden="true"></i>
                            </div>
                        </div>
                        {{-- Validation message --}}
                        <div id="phone_number-feedback" class="hidden rounded-lg px-3 py-2 text-xs"></div>
                        @error('phone_number')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="lg:col-span-2 grid gap-6 md:grid-cols-2">
                        <div class="space-y-2">
                            <label for="class" class="block text-sm font-medium text-slate-700">Program</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                    <i class="ri-book-open-line text-lg" aria-hidden="true"></i>
                                </span>
                                <select id="class" name="class" required class="block w-full rounded-xl border border-slate-300 bg-white py-3 pl-11 pr-4 text-sm text-slate-900 shadow-sm transition focus:border-[#0b3019] focus:outline-none focus:ring-2 focus:ring-[#0b3019]/30">
                                    <option value="" disabled {{ old('class') ? '' : 'selected' }}>Select program</option>
                                    @foreach (['Cyber Security', 'Computer Science', 'Information System'] as $program)
                                        <option value="{{ $program }}" {{ old('class') === $program ? 'selected' : '' }}>{{ $program }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('class')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="year" class="block text-sm font-medium text-slate-700">Year</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                    <i class="ri-medal-line text-lg" aria-hidden="true"></i>
                                </span>
                                <select id="year" name="year" required class="block w-full rounded-xl border border-slate-300 bg-white py-3 pl-11 pr-4 text-sm text-slate-900 shadow-sm transition focus:border[#0b3019] focus:outline-none focus:ring-2 focus:ring-[#0b3019]/30">
                                    <option value="" disabled {{ old('year') ? '' : 'selected' }}>Select year</option>
                                    @foreach (['1', '2', '3', '4'] as $year)
                                        <option value="{{ $year }}" {{ old('year') === $year ? 'selected' : '' }}>Year {{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('year')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                <i class="ri-lock-password-line text-lg" aria-hidden="true"></i>
                            </span>
                            <input id="password" name="password" type="password" required autocomplete="new-password" class="block w-full rounded-xl border border-slate-300 bg-white py-3 pl-11 pr-12 text-sm text-slate-900 shadow-sm transition focus:border-[#0b3019] focus:outline-none focus:ring-2 focus:ring-[#0b3019]/30" placeholder="Create a secure password" />
                            <button type="button" data-password-toggle="#password" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 transition hover:text-slate-600" aria-label="Toggle password visibility">
                                <i data-eye class="ri-eye-line text-lg" aria-hidden="true"></i>
                                <i data-eye-off class="ri-eye-off-line hidden text-lg" aria-hidden="true"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-3 rounded-xl border border-slate-200 bg-slate-50/70 p-4" data-password-strength data-password-input="#password">
                        <div class="flex items-center justify-between text-xs font-medium text-slate-600">
                            <span>Password strength</span>
                            <span data-password-strength-label class="text-slate-500">Weak</span>
                        </div>
                        <div class="h-2 w-full rounded-full bg-slate-200">
                            <div data-password-strength-bar class="h-2 w-1/12 rounded-full bg-red-500 transition-all duration-300"></div>
                        </div>
                        <ul class="space-y-1 text-xs text-slate-500">
                            <li class="flex items-center gap-2 data-[state=pass]:text-[#0b3019]" data-password-rule="length" data-state="fail">
                                <svg data-pass-icon xmlns="http://www.w3.org/2000/svg" class="hidden h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="m5 12 4.5 4.5L19 7" />
                                </svg>
                                <svg data-fail-icon xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="m5 5 14 14" />
                                    <path d="m19 5-14 14" />
                                </svg>
                                <span>At least 8 characters</span>
                            </li>
                            <li class="flex items-center gap-2 data-[state=pass]:text-[#0b3019]" data-password-rule="mixed" data-state="fail">
                                <svg data-pass-icon xmlns="http://www.w3.org/2000/svg" class="hidden h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="m5 12 4.5 4.5L19 7" />
                                </svg>
                                <svg data-fail-icon xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="m5 5 14 14" />
                                    <path d="m19 5-14 14" />
                                </svg>
                                <span>Includes uppercase and lowercase letters</span>
                            </li>
                            <li class="flex items-center gap-2 data-[state=pass]:text-[#0b3019]" data-password-rule="number" data-state="fail">
                                <svg data-pass-icon xmlns="http://www.w3.org/2000/svg" class="hidden h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="m5 12 4.5 4.5L19 7" />
                                </svg>
                                <svg data-fail-icon xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="m5 5 14 14" />
                                    <path d="m19 5-14 14" />
                                </svg>
                                <span>Contains at least one number</span>
                            </li>
                            <li class="flex items-center gap-2 data-[state=pass]:text-[#0b3019]" data-password-rule="symbol" data-state="fail">
                                <svg data-pass-icon xmlns="http://www.w3.org/2000/svg" class="hidden h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="m5 12 4.5 4.5L19 7" />
                                </svg>
                                <svg data-fail-icon xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="m5 5 14 14" />
                                    <path d="m19 5-14 14" />
                                </svg>
                                <span>Contains at least one special character</span>
                            </li>
                        </ul>
                    </div>

                    <div class="space-y-2">
                        <label for="password_confirmation" class="block text-sm font-medium text-slate-700">Confirm password</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <rect width="18" height="11" x="3" y="11" rx="2" />
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                                </svg>
                            </span>
                            <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" class="block w-full rounded-xl border border-slate-300 bg-white py-3 pl-11 pr-12 text-sm text-slate-900 shadow-sm transition focus:border-[#0b3019] focus:outline-none focus:ring-2 focus:ring-[#0b3019]/30" placeholder="Confirm your password" />
                            <button type="button" data-password-toggle="#password_confirmation" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 transition hover:text-slate-600" aria-label="Toggle password visibility">
                                <svg data-eye xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M2.458 12C3.732 7.943 7.522 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S3.732 16.057 2.458 12Z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                                <svg data-eye-off xmlns="http://www.w3.org/2000/svg" class="hidden h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="m3 3 18 18" />
                                    <path d="M10.584 10.59a1.999 1.999 0 0 0 2.828 2.83" />
                                    <path d="M9.878 5.132A9.76 9.76 0 0 1 12 5c4.478 0 8.268 2.943 9.542 7a9.88 9.88 0 0 1-1.616 3.043m-4.112 2.773A9.711 9.711 0 0 1 12 19c-4.478 0-8.268-2.943-9.542-7a9.835 9.835 0 0 1 2.223-3.592" />
                                </svg>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="flex items-start space-x-3 rounded-xl border border-slate-200 bg-slate-50/70 p-4">
                <input id="accept_terms" name="accept_terms" type="checkbox" value="1" required class="mt-1 h-4 w-4 rounded border-slate-300 text-[#0b3019] focus:ring-[#0b3019]" {{ old('accept_terms') ? 'checked' : '' }}>
                <label for="accept_terms" class="text-sm text-slate-600">
                    I agree to the
                    <a href="{{ route('legal.terms') }}" class="font-semibold text-[#0b3019] hover:underline">Terms</a>
                    and
                    <a href="{{ route('legal.privacy') }}" class="font-semibold text-[#0b3019] hover:underline">Privacy Policy</a>
                    of the ACSES Portal.
                </label>
            </div>
            @error('accept_terms')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror

            <div class="flex items-center justify-end">
                <button id="submit-btn" type="submit" class="flex items-center justify-center space-x-2 rounded-xl bg-[#0b3019] px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-[#0b3019]/30 transition duration-200 ease-out hover:-translate-y-0.5 hover:bg-[#094018] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#0b3019] disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:translate-y-0">
                    <i class="ri-user-add-line text-lg" aria-hidden="true"></i>
                    <span>Create account</span>
                </button>
            </div>
        </form>

        <div class="mt-8 text-center text-sm text-slate-600">
            <p>
                Already have an account?
                <a href="{{ route('login') }}" class="font-semibold text-[#0b3019] hover:underline">Sign in</a>
                instead.
            </p>
        </div>
    </div>

    <div id="auth-loading-overlay" class="hidden fixed inset-0 z-40 items-center justify-center bg-white/80 backdrop-blur-sm">
        <div class="flex flex-col items-center space-y-4">
            <div class="flex h-12 w-12 items-center justify-center rounded-full border-2 border-[#0b3019]/20">
                <i class="ri-loader-4-line animate-spin text-2xl text-[#0b3019]" aria-hidden="true"></i>
            </div>
            <p class="text-sm font-medium text-slate-700">Creating your account…</p>
        </div>
    </div>

    {{-- Email validation script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const emailInput = document.getElementById('email');
            const classSelect = document.getElementById('class');
            const noticeContainer = document.getElementById('email-verification-notice');
            const schoolEmailNotice = document.getElementById('school-email-notice');
            const nonSchoolEmailNotice = document.getElementById('non-school-email-notice');
            const mismatchNotice = document.getElementById('email-mismatch-notice');
            const mismatchMessage = document.getElementById('mismatch-message');

            const SCHOOL_DOMAIN = 'st.umat.edu.gh';
            const CLASS_PREFIXES = {
                'cy': 'Cyber Security',
                'is': 'Information System',
                'ce': 'Computer Science'
            };

            function validateEmail() {
                const email = emailInput.value.trim().toLowerCase();
                const selectedClass = classSelect.value;

                // Hide all notices first
                noticeContainer.classList.add('hidden');
                schoolEmailNotice.classList.add('hidden');
                nonSchoolEmailNotice.classList.add('hidden');
                mismatchNotice.classList.add('hidden');

                if (!email || !email.includes('@')) {
                    return;
                }

                const domain = email.split('@')[1] || '';
                const isSchoolEmail = domain === SCHOOL_DOMAIN;

                noticeContainer.classList.remove('hidden');

                if (isSchoolEmail) {
                    // Check for prefix match
                    const localPart = email.split('@')[0] || '';
                    const match = localPart.match(/^([a-z]{2})-/i);
                    const prefix = match ? match[1].toLowerCase() : null;
                    const expectedClass = prefix ? CLASS_PREFIXES[prefix] : null;

                    if (selectedClass && expectedClass && expectedClass !== selectedClass) {
                        // Mismatch
                        mismatchNotice.classList.remove('hidden');
                        noticeContainer.classList.remove('border-emerald-200', 'bg-emerald-50', 'border-amber-200', 'bg-amber-50');
                        noticeContainer.classList.add('border-rose-200', 'bg-rose-50');
                        mismatchMessage.textContent = `Your school email prefix "${prefix.toUpperCase()}" indicates ${expectedClass}, but you selected ${selectedClass}. Please select the correct program.`;
                    } else if (!expectedClass) {
                        // Unknown prefix
                        mismatchNotice.classList.remove('hidden');
                        noticeContainer.classList.remove('border-emerald-200', 'bg-emerald-50', 'border-amber-200', 'bg-amber-50');
                        noticeContainer.classList.add('border-rose-200', 'bg-rose-50');
                        mismatchMessage.textContent = 'Your school email prefix is not recognized. Expected prefixes: CY (Cyber Security), IS (Information System), CE (Computer Science).';
                    } else {
                        // Valid school email
                        schoolEmailNotice.classList.remove('hidden');
                        noticeContainer.classList.remove('border-rose-200', 'bg-rose-50', 'border-amber-200', 'bg-amber-50');
                        noticeContainer.classList.add('border-emerald-200', 'bg-emerald-50');
                    }
                } else {
                    // Non-school email
                    nonSchoolEmailNotice.classList.remove('hidden');
                    noticeContainer.classList.remove('border-emerald-200', 'bg-emerald-50', 'border-rose-200', 'bg-rose-50');
                    noticeContainer.classList.add('border-amber-200', 'bg-amber-50');
                }
            }

            emailInput.addEventListener('input', validateEmail);
            emailInput.addEventListener('change', validateEmail);
            classSelect.addEventListener('change', validateEmail);

            // Run on page load if there's a value
            if (emailInput.value) {
                validateEmail();
            }
        });
    </script>

    {{-- Generic Field Validation Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            class ValidationManager {
                constructor() {
                    this.form = document.querySelector('[data-auth-form]');
                    this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
                    this.validationState = {
                        username: false,
                        email: false,
                        index_number: false,
                        phone_number: false
                    };
                    
                    this.fields = {
                        username: {
                            input: document.getElementById('username'),
                            regex: /^[a-zA-Z0-9_-]+$/ 
                        },
                        email: {
                            input: document.getElementById('email'),
                            // Regex is handled by API check mostly, but we can do basic format
                            regex: /^\S+@\S+\.\S+$/
                        },
                        index_number: {
                            input: document.getElementById('index_number'),
                            regex: /^\d{9,11}$/
                        },
                        phone_number: {
                            input: document.getElementById('phone_number'),
                            regex: /^\d{9,11}$/,
                            optional: true // phone is technically nullable in DB but standard users should provide it
                        }
                    };

                    this.init();
                }

                init() {
                    Object.keys(this.fields).forEach(field => {
                        const config = this.fields[field];
                        if (config.input) {
                            config.input.addEventListener('input', () => this.handleInput(field));
                            config.input.addEventListener('change', () => this.handleInput(field));
                            
                            // Initialize state (mark as false initially if empty and required)
                            if (!config.optional && !config.input.value.trim()) {
                                this.validationState[field] = false;
                            } else {
                                // If pre-filled, trigger check
                                if (config.input.value.trim()) {
                                    this.handleInput(field);
                                } else {
                                    // Empty and optional
                                    if(config.optional) this.validationState[field] = true;
                                }
                            }
                        }
                    });

                    // Intercept form submission
                    if (this.form) {
                        this.form.addEventListener('submit', (e) => this.handleSubmit(e));
                    }
                }

                handleInput(field) {
                    const config = this.fields[field];
                    const value = config.input.value.trim();
                    const state = this.getElements(field);

                    // Clear existing timer
                    if (config.timer) clearTimeout(config.timer);

                    // Allow empty if optional
                    if (!value) {
                       this.updateUI(field, 'idle');
                       this.validationState[field] = !!config.optional;
                       return;
                    }

                    // Basic Regex Check
                    if (config.regex && !config.regex.test(value)) {
                         this.updateUI(field, 'error', `Invalid format.`);
                         this.validationState[field] = false;
                         return;
                    }

                    // Debounce API Check
                    this.updateUI(field, 'checking');
                    config.timer = setTimeout(() => this.checkAvailability(field, value), 500);
                }

                async checkAvailability(field, value) {
                    try {
                        const response = await fetch('{{ route("auth.register.check-availability") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': this.csrfToken,
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({ field, value })
                        });

                        // Handle Rate Limit
                        if (response.status === 429) {
                             const data = await response.json();
                             this.updateUI(field, 'error', 'Too many checks. Please wait.');
                             if (window.RateLimitHandler && data.type === 'rate_limit') {
                                 window.RateLimitHandler.show(data.retry_after, data.message, 'Slow Down');
                             }
                             return;
                        }

                        const data = await response.json();
                        
                        // Ensure value hasn't changed since request started
                        if (this.fields[field].input.value.trim() !== value) return;

                        if (data.available) {
                            this.updateUI(field, 'available', data.message);
                            this.validationState[field] = true;
                        } else {
                            this.updateUI(field, 'taken', data.message);
                            this.validationState[field] = false;
                        }

                    } catch (error) {
                        console.error('Validation error:', error);
                        this.updateUI(field, 'idle'); // Fail gracefully
                    }
                }

                getElements(field) {
                    return {
                        checking: document.getElementById(`${field}-checking`),
                        available: document.getElementById(`${field}-available`),
                        taken: document.getElementById(`${field}-taken`),
                        feedback: document.getElementById(`${field}-feedback`)
                    };
                }

                updateUI(field, status, message = '') {
                    const els = this.getElements(field);
                    const input = this.fields[field].input;

                    if (!els.checking) return; // Guard for missing elements

                    // Reset icons
                    els.checking.classList.add('hidden');
                    els.available.classList.add('hidden');
                    els.taken.classList.add('hidden');
                    
                    // Reset feedback
                    els.feedback.classList.remove('hidden', 'bg-emerald-50', 'text-emerald-700', 'bg-rose-50', 'text-rose-700');
                    els.feedback.textContent = message;

                    // Reset input border
                    input.classList.remove('border-emerald-400', 'border-rose-400');

                    switch (status) {
                        case 'checking':
                            els.checking.classList.remove('hidden');
                            els.feedback.classList.add('hidden');
                            break;
                        case 'available':
                            els.available.classList.remove('hidden');
                            els.feedback.classList.add('bg-emerald-50', 'text-emerald-700');
                            input.classList.add('border-emerald-400');
                            break;
                        case 'taken':
                        case 'error':
                            els.taken.classList.remove('hidden');
                            els.feedback.classList.add('bg-rose-50', 'text-rose-700');
                            input.classList.add('border-rose-400');
                            break;
                        case 'idle':
                            els.feedback.classList.add('hidden');
                            break;
                    }
                }

                async handleSubmit(e) {
                    // Check if there are any invalid fields (that are not empty/optional)
                    const invalidFields = Object.keys(this.fields).filter(field => {
                        const config = this.fields[field];
                        if (!config.input) return false;
                        
                        const val = config.input.value.trim();
                        // If it has a value and our state says it's invalid, block it
                        // Exception: Phone is optional, but if provided must be valid
                        if (val && this.validationState[field] === false) return true;
                        
                        // If it's required and empty (HTML5 required handles this mostly, but good double check)
                        if (!config.optional && !val) return false; // let native validation handle empty
                        
                        return false; 
                    });

                    if (invalidFields.length > 0) {
                        e.preventDefault();
                        const firstInvalid = invalidFields[0];
                        this.fields[firstInvalid].input.focus();
                        alert(`Please fix the ${firstInvalid.replace('_', ' ')} before submitting.`);
                        return false;
                    }
                    
                    // AJAX Submission for Rate Limit Handling
                    e.preventDefault();
                    
                    const overlay = document.getElementById('auth-loading-overlay');
                    if (overlay) {
                        overlay.classList.remove('hidden');
                        overlay.classList.add('flex');
                    }

                    const formData = new FormData(this.form);

                    try {
                        const response = await fetch(this.form.action, {
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

                        // Handle Success or Validation Errors
                        if (response.redirected) {
                            window.location.href = response.url;
                        } else if (response.ok) {
                            const html = await response.text();
                             document.open();
                             document.write(html);
                             document.close();
                        } else {
                            // Fallback
                             const html = await response.text();
                             document.open();
                             document.write(html);
                             document.close();
                        }

                    } catch (error) {
                        console.error('Registration error:', error);
                        // Fallback: Submit normally if fetch fails
                        this.form.submit();
                    }
                }
            }

            new ValidationManager();
        });
    </script>

</x-layouts.auth>