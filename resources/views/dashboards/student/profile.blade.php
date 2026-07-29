<x-layouts.dashboard :title="$title">
    @include('components.dashboard.skeleton-styles')

    @php
        $displayName = trim($student->fullname ?? $student->username ?? 'Student');
        $initials = collect(preg_split('/\s+/', $displayName))->filter()->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))->take(2)->implode('');
        $croppedPreview = old('profile_picture_cropped');
        $storedImage = $student->profile_picture ? asset('storage/' . $student->profile_picture) : null;
        $profileImage = $croppedPreview ?: $storedImage;
        $removeRequested = old('remove_profile_picture') === '1';
        $hasProfileImage = (bool) ($profileImage && ! $removeRequested);
    @endphp

    <div class="mx-auto w-full max-w-[1400px] px-4 py-8 sm:px-6 lg:px-8" x-data="{ activeTab: 'id_card' }">
        
        <!-- Status Messages -->
        @if (session('status'))
            <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50/80 p-4 text-emerald-950 shadow-sm backdrop-blur-sm animate-fade-slide">
                <div class="flex items-start gap-3">
                    <i class="ri-checkbox-circle-fill text-xl text-emerald-600"></i>
                    <div>
                        <p class="text-sm font-bold">Action Completed</p>
                        <p class="text-xs text-emerald-800 mt-0.5">{{ session('status') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50/80 p-4 text-rose-950 shadow-sm backdrop-blur-sm animate-fade-slide">
                <div class="flex items-start gap-3">
                    <i class="ri-error-warning-fill text-xl text-rose-600"></i>
                    <div>
                        <p class="text-sm font-bold">Please correct the following errors:</p>
                        <ul class="mt-1 list-disc space-y-0.5 pl-5 text-xs text-rose-800">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Tabbed Navigation Bar -->
        <nav class="flex items-center gap-1.5 border-b border-slate-200/60 pb-px mb-8 overflow-x-auto whitespace-nowrap scrollbar-none" aria-label="Settings tabs">
            <button type="button" @click="activeTab = 'id_card'" 
                    :class="activeTab === 'id_card' ? 'border-[#0b3019] text-[#0b3019] bg-[#0b3019]/5 font-bold' : 'border-transparent text-slate-500 hover:text-slate-900 font-semibold'"
                    class="inline-flex items-center gap-2 border-b-2 px-4 py-3 text-sm transition-all duration-200 rounded-t-xl">
                <i class="ri-id-card-line text-base"></i>
                <span>Digital ID Card</span>
            </button>
            <button type="button" @click="activeTab = 'profile_settings'" 
                    :class="activeTab === 'profile_settings' ? 'border-[#0b3019] text-[#0b3019] bg-[#0b3019]/5 font-bold' : 'border-transparent text-slate-500 hover:text-slate-900 font-semibold'"
                    class="inline-flex items-center gap-2 border-b-2 px-4 py-3 text-sm transition-all duration-200 rounded-t-xl">
                <i class="ri-user-settings-line text-base"></i>
                <span>Personal Profile</span>
            </button>
            <button type="button" @click="activeTab = 'security_devices'" 
                    :class="activeTab === 'security_devices' ? 'border-[#0b3019] text-[#0b3019] bg-[#0b3019]/5 font-bold' : 'border-transparent text-slate-500 hover:text-slate-900 font-semibold'"
                    class="inline-flex items-center gap-2 border-b-2 px-4 py-3 text-sm transition-all duration-200 rounded-t-xl">
                <i class="ri-shield-keyhole-line text-base"></i>
                <span>Security & Devices</span>
            </button>
        </nav>

        <!-- Main Form Scope (All Settings are in one form to maintain input validity) -->
        <form method="POST" action="{{ route('student.profile.update') }}" enctype="multipart/form-data" 
              class="space-y-8" data-profile-form>
            @csrf

            <!-- ================= TAB 1: DIGITAL ID CARD ================= -->
            <div x-show="activeTab === 'id_card'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
                <div class="max-w-2xl mx-auto">
                    <!-- Glassmorphic ID Card Container -->
                    <div class="relative overflow-hidden rounded-[32px] border border-[#0b3019]/30 bg-gradient-to-br from-[#0b3019] via-[#0d3f21] to-[#051e0f] p-8 text-white shadow-[0_25px_60px_-15px_rgba(11,48,25,0.45)] transition-all duration-500 group/card hover:shadow-[0_30px_70px_-15px_rgba(11,48,25,0.55)]">
                        
                        <!-- Glow highlight -->
                        <div class="absolute -right-24 -top-24 h-48 w-48 rounded-full bg-emerald-500/10 blur-3xl pointer-events-none"></div>
                        <div class="absolute -left-24 -bottom-24 h-48 w-48 rounded-full bg-emerald-600/10 blur-3xl pointer-events-none"></div>

                        <!-- Micro biometric scanning lines (watermark style) -->
                        <div class="absolute inset-0 bg-[radial-gradient(#ffffff03_1px,transparent_1px)] [background-size:16px_16px] pointer-events-none"></div>

                        <!-- Card Header -->
                        <div class="flex items-center justify-between border-b border-white/10 pb-5 relative z-10">
                            <div class="flex items-center gap-3.5">
                                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/10 p-2 backdrop-blur-md border border-white/20">
                                    <img src="{{ asset('logo.png') }}" alt="ACSES Logo" class="h-full w-full object-contain">
                                </div>
                                <div>
                                    <p class="text-[9px] font-black uppercase tracking-[0.25em] text-emerald-400">ACSES Student Member</p>
                                    <p class="text-xs font-bold uppercase tracking-wider text-white/80">University of Mines & Technology</p>
                                </div>
                            </div>
                            
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-500/15 px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-emerald-300 border border-emerald-500/30">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                Active
                            </span>
                        </div>

                        <!-- Card Body -->
                        <div class="mt-8 flex flex-col items-center gap-8 sm:flex-row sm:items-start text-center sm:text-left relative z-10">
                            <!-- Avatar Portrait -->
                            <div class="relative shrink-0 group/avatar">
                                <div class="relative flex h-36 w-36 items-center justify-center overflow-hidden rounded-2xl border border-white/20 bg-white/5 p-1 shadow-2xl backdrop-blur-sm transition-all duration-500 group-hover/avatar:scale-105 group-hover/avatar:border-emerald-400/40">
                                    @if ($hasProfileImage)
                                        <img data-avatar-preview src="{{ $profileImage }}" alt="{{ $displayName }} profile photo" class="h-full w-full rounded-xl object-cover" />
                                    @else
                                        <span data-avatar-fallback class="text-4xl font-bold uppercase text-emerald-300">{{ $initials ?: 'ST' }}</span>
                                    @endif
                                </div>
                                <div class="absolute -bottom-2 right-4 rounded-md bg-[#0b3019] px-2 py-0.5 text-[8px] font-black uppercase tracking-wider text-emerald-400 border border-emerald-500/30">Verified</div>
                            </div>

                            <!-- Student placement & metrics -->
                            <div class="flex-1 w-full space-y-5">
                                <div>
                                    <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white leading-tight">{{ $student->fullname ?? 'Sample Student' }}</h2>
                                    <p class="text-[10px] font-black uppercase tracking-[0.3em] text-emerald-400 mt-1.5">MEMBER DIRECTORY</p>
                                </div>

                                <!-- Placement grid info -->
                                <div class="grid grid-cols-1 gap-x-6 gap-y-3.5 text-xs text-emerald-100/90 sm:grid-cols-2 pt-2">
                                    <div class="flex items-center gap-3 justify-center sm:justify-start">
                                        <i class="ri-hashtag text-emerald-400 text-sm"></i>
                                        <span>Ref: <strong class="text-white">{{ $student->index_number ?? 'Not assigned' }}</strong></span>
                                    </div>
                                    <div class="flex items-center gap-3 justify-center sm:justify-start">
                                        <i class="ri-graduation-cap-line text-emerald-400 text-sm"></i>
                                        <span>Prog: <strong class="text-white">{{ $student->class ?? 'Not set' }}</strong></span>
                                    </div>
                                    <div class="flex items-center gap-3 justify-center sm:justify-start">
                                        <i class="ri-calendar-line text-emerald-400 text-sm"></i>
                                        <span>Year: <strong class="text-white">Year {{ $student->year ?? 'N/A' }}</strong></span>
                                    </div>
                                    <div class="flex items-center gap-3 justify-center sm:justify-start">
                                        <i class="ri-phone-line text-emerald-400 text-sm"></i>
                                        <span>Phone: <strong class="text-white">{{ $student->phone_number ?? 'Not set' }}</strong></span>
                                    </div>
                                </div>

                                <!-- Email Row -->
                                <div class="flex items-center gap-3 justify-center sm:justify-start text-xs border-t border-white/10 pt-4 mt-2 w-full">
                                    <i class="ri-mail-line text-emerald-400"></i>
                                    <span class="text-emerald-200/80 font-medium">{{ $student->email }}</span>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Manage quick access note -->
                    <div class="mt-6 text-center">
                        <button type="button" @click="activeTab = 'profile_settings'" class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-5 py-2.5 text-xs font-bold text-slate-700 shadow-sm transition hover:bg-slate-50 active:scale-98">
                            <i class="ri-edit-line"></i>
                            <span>Edit Profile Contact Info</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- ================= TAB 2: PROFILE SETTINGS ================= -->
            <div x-show="activeTab === 'profile_settings'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
                
                <div class="grid gap-6 lg:grid-cols-3">
                    
                    <!-- Left: Profile avatar image update -->
                    <article class="rounded-[24px] border border-slate-200/80 bg-white p-6 shadow-sm">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Profile avatar</h3>
                        <div class="mt-6 flex flex-col items-center gap-4">
                            <div class="relative flex h-32 w-32 items-center justify-center overflow-hidden rounded-full border border-[#0b3019]/20 bg-slate-50 transition-transform duration-300">
                                <img data-avatar-preview src="{{ $hasProfileImage ? $profileImage : '' }}" alt="{{ $displayName }} profile photo" class="{{ $hasProfileImage ? '' : 'hidden' }} h-full w-full object-cover" />
                                <span data-avatar-fallback class="{{ $hasProfileImage ? 'hidden' : '' }} text-2xl font-bold uppercase text-[#0b3019]">{{ $initials ?: 'ST' }}</span>
                            </div>
                            
                            <div class="flex flex-wrap items-center justify-center gap-2">
                                <button type="button" @click="document.querySelector('[data-avatar-input]').click()" data-avatar-trigger class="inline-flex h-9 items-center justify-center gap-1.5 rounded-full bg-[#0b3019] px-4 text-xs font-bold text-white shadow-sm transition hover:bg-[#082212] active:scale-95">
                                    <i class="ri-upload-cloud-line"></i>
                                    <span>Upload Photo</span>
                                </button>
                                <button type="button" data-avatar-remove class="{{ $hasProfileImage ? '' : 'hidden' }} inline-flex h-9 items-center justify-center gap-1.5 rounded-full border border-rose-200 bg-rose-50 px-4 text-xs font-bold text-rose-700 transition hover:bg-rose-100 active:scale-95">
                                    <i class="ri-delete-bin-6-line"></i>
                                    <span>Remove</span>
                                </button>
                            </div>
                            <p class="text-center text-[10px] leading-relaxed text-slate-500 max-w-xs" data-avatar-helper>Select a square image (minimum 400×400px). A crop dialog will assist you in positioning.</p>
                        </div>
                    </article>

                    <!-- Right: Contact form fields -->
                    <article class="lg:col-span-2 rounded-[24px] border border-slate-200/80 bg-white p-6 shadow-sm space-y-6">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Contact information</h3>
                        
                        <div class="grid gap-4 sm:grid-cols-2">
                            <!-- Primary Email -->
                            <label class="flex flex-col gap-1.5">
                                <span class="text-xs font-bold uppercase tracking-[0.1em] text-slate-400">Primary Email Address</span>
                                <div class="relative">
                                    <i class="ri-mail-line pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                    <input type="email" name="pending_email" id="pending_email" value="{{ old('pending_email', $student->pending_email ?? $student->email) }}" 
                                           class="h-11 w-full rounded-xl border border-slate-200 bg-white pl-11 pr-4 text-sm text-slate-900 shadow-sm focus:border-[#0b3019]/60 focus:outline-none focus:ring-2 focus:ring-[#0b3019]/20 transition-all">
                                </div>
                                
                                @if ($student->pending_email)
                                    <div class="mt-2 rounded-xl border border-amber-200 bg-amber-50 p-3 text-xs text-amber-800">
                                        <p class="font-bold">Verification Pending</p>
                                        <p class="mt-0.5 leading-relaxed">Sent verification link to <strong>{{ $student->pending_email }}</strong>. Revert or verify to finalize.</p>
                                    </div>
                                @endif
                            </label>

                            <!-- Phone Number -->
                            <label class="flex flex-col gap-1.5">
                                <span class="text-xs font-bold uppercase tracking-[0.1em] text-slate-400">Phone Number</span>
                                <div class="relative">
                                    <i class="ri-phone-line pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                    <input type="text" name="phone_number" value="{{ old('phone_number', $student->phone_number) }}" 
                                           class="h-11 w-full rounded-xl border border-slate-200 bg-white pl-11 pr-4 text-sm text-slate-900 shadow-sm focus:border-[#0b3019]/60 focus:outline-none focus:ring-2 focus:ring-[#0b3019]/20 transition-all">
                                </div>
                            </label>
                        </div>

                        <!-- Readonly Placements -->
                        <div class="pt-6 border-t border-slate-100 space-y-4">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">Academic Placement (Read-only)</h4>
                            <div class="grid gap-4 sm:grid-cols-3 text-xs text-slate-500">
                                <div>
                                    <span class="block font-bold text-slate-400 uppercase tracking-wider text-[10px]">Full name</span>
                                    <span class="text-sm font-semibold text-slate-800 mt-1 block">{{ $student->fullname }}</span>
                                </div>
                                <div>
                                    <span class="block font-bold text-slate-400 uppercase tracking-wider text-[10px]">Programme</span>
                                    <span class="text-sm font-semibold text-slate-800 mt-1 block">{{ $student->class }}</span>
                                </div>
                                <div>
                                    <span class="block font-bold text-slate-400 uppercase tracking-wider text-[10px]">Year Level</span>
                                    <span class="text-sm font-semibold text-slate-800 mt-1 block">Year {{ $student->year }}</span>
                                </div>
                            </div>

                            <div class="rounded-xl border border-slate-100 bg-slate-50/70 p-3.5 text-xs text-slate-500 flex gap-3">
                                <i class="ri-information-line text-slate-400 text-lg shrink-0"></i>
                                <div>
                                    <span class="font-bold text-slate-700 block">Need to update official details?</span>
                                    <span class="mt-0.5 block leading-relaxed text-slate-500">Official registry placements (Name, Programme, and Year) cannot be changed directly by students. Please contact an executive or system administrator to request updates.</span>
                                </div>
                            </div>
                        </div>

                    </article>
                </div>

                <input type="file" name="profile_picture" accept="image/*" class="hidden" data-avatar-input>
                <input type="hidden" name="profile_picture_cropped" value="{{ old('profile_picture_cropped') }}" data-avatar-cropped>
                <input type="hidden" name="remove_profile_picture" value="{{ $removeRequested ? '1' : '0' }}" data-avatar-remove-input>

                <!-- Action Button Controls -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="submit" class="inline-flex h-10 items-center justify-center gap-1.5 rounded-full bg-[#0b3019] px-6 text-sm font-bold text-white shadow-sm transition hover:bg-[#082212] active:scale-95">
                        <i class="ri-save-line"></i>
                        <span>Save Profile Changes</span>
                    </button>
                </div>
            </div>

            <!-- ================= TAB 3: SECURITY & DEVICES ================= -->
            <div x-show="activeTab === 'security_devices'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
                
                <div class="grid gap-6 md:grid-cols-2">
                    
                    <!-- Left: Password Change block -->
                    <article class="rounded-[24px] border border-slate-200/80 bg-white p-6 shadow-sm flex flex-col justify-between" x-data="{ showPass: false, showNew: false }">
                        <div>
                            <div class="flex items-center gap-3">
                                <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-700">
                                    <i class="ri-lock-password-line text-xl"></i>
                                </span>
                                <div>
                                    <h3 class="text-base font-bold text-slate-900">Security Credentials</h3>
                                    <p class="text-xs text-slate-500">Update account login password.</p>
                                </div>
                            </div>

                            <p class="mt-4 text-xs leading-relaxed text-slate-500" x-show="!showPass">
                                Maintain login security. Leave blank if you don't intend to reset your current password.
                            </p>

                            <!-- Password Fields -->
                            <div x-show="showPass" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="mt-4 space-y-4 pt-4 border-t border-slate-100">
                                
                                <label class="flex flex-col gap-1.5">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Current Password</span>
                                    <input type="password" name="current_password" class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3 text-sm text-slate-900 focus:border-[#0b3019]/60 focus:outline-none">
                                </label>

                                <label class="flex flex-col gap-1.5">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">New Password</span>
                                    <input type="password" name="password" class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3 text-sm text-slate-900 focus:border-[#0b3019]/60 focus:outline-none">
                                </label>

                                <label class="flex flex-col gap-1.5">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Confirm Password</span>
                                    <input type="password" name="password_confirmation" class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3 text-sm text-slate-900 focus:border-[#0b3019]/60 focus:outline-none">
                                </label>

                            </div>
                        </div>

                        <div class="mt-6 flex gap-2">
                            <button type="button" x-show="!showPass" @click="showPass = true" 
                                    class="inline-flex h-10 w-full items-center justify-center gap-1.5 rounded-full border border-slate-200 bg-white px-4 text-xs font-bold text-slate-700 transition hover:bg-slate-50 active:scale-95">
                                <i class="ri-key-2-line"></i>
                                <span>Change password</span>
                            </button>
                            <button type="button" x-show="showPass" @click="showPass = false" 
                                    class="inline-flex h-10 w-1/2 items-center justify-center rounded-full border border-slate-200 bg-white px-4 text-xs font-bold text-slate-600 transition hover:bg-slate-50">
                                Cancel
                            </button>
                            <button type="submit" x-show="showPass" 
                                    class="inline-flex h-10 w-1/2 items-center justify-center rounded-full bg-[#0b3019] px-4 text-xs font-bold text-white transition hover:bg-[#082212]">
                                Save password
                            </button>
                        </div>
                    </article>

                    <!-- Right: Trusted Devices -->
                    <article class="rounded-[24px] border border-slate-200/80 bg-white p-6 shadow-sm">
                        <header class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                            <div class="flex items-center gap-3">
                                <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-amber-50 text-amber-600">
                                    <i class="ri-device-line text-xl"></i>
                                </span>
                                <div>
                                    <h3 class="text-base font-bold text-slate-900">Trusted Devices</h3>
                                    <p class="text-xs text-slate-500">Authorized devices bypassing 2FA.</p>
                                </div>
                            </div>

                            @if(isset($trustedDevices) && $trustedDevices->count() > 1)
                                <button type="submit" form="revoke-all-form" class="inline-flex items-center gap-1.5 rounded-full border border-rose-200 bg-rose-50 px-3.5 py-1.5 text-[10px] font-bold text-rose-700 transition hover:bg-rose-100 active:scale-95">
                                    <i class="ri-logout-circle-r-line"></i>
                                    <span>Sign out others</span>
                                </button>
                            @endif
                        </header>

                        <!-- Device List -->
                        <div class="mt-6 max-h-[220px] overflow-y-auto overflow-x-hidden pr-1 scrollbar-thin">
                            @if(isset($trustedDevices) && $trustedDevices->count() > 0)
                                <ul class="divide-y divide-slate-100">
                                    @foreach($trustedDevices as $device)
                                        @php($isCurrent = isset($currentFingerprint) && $device->device_fingerprint === $currentFingerprint)
                                        <li class="flex items-center justify-between gap-4 py-3 px-2 rounded-xl transition hover:bg-slate-50/80 {{ $isCurrent ? 'bg-emerald-50/40' : '' }} group/device">
                                            <div class="flex items-center gap-3 min-w-0">
                                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full {{ $isCurrent ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                                    @if(str_contains(strtolower($device->device_name ?? ''), 'mobile') || str_contains(strtolower($device->device_name ?? ''), 'android') || str_contains(strtolower($device->device_name ?? ''), 'ios'))
                                                        <i class="ri-smartphone-line"></i>
                                                    @else
                                                        <i class="ri-computer-line"></i>
                                                    @endif
                                                </span>
                                                <div class="min-w-0 flex-1">
                                                    <div class="flex items-center gap-2">
                                                        <span class="font-bold text-slate-900 truncate text-xs">{{ $device->device_name ?? 'Unknown Device' }}</span>
                                                        @if($isCurrent)
                                                            <span class="inline-flex items-center rounded-full bg-emerald-100 px-1.5 py-0.2 text-[8px] font-black text-emerald-700 uppercase">Current</span>
                                                        @endif
                                                    </div>
                                                    <p class="text-[10px] text-slate-500 truncate mt-0.5">
                                                        {{ $device->ip_address ?? 'Unknown IP' }} &middot; Active {{ $device->last_used_at ? $device->last_used_at->diffForHumans() : 'Unknown' }}
                                                    </p>
                                                </div>
                                            </div>
                                            @if(!$isCurrent)
                                                <button type="submit" form="revoke-device-{{ $device->id }}" class="text-slate-400 hover:text-rose-600 transition-colors p-1 hover:scale-110 active:scale-95">
                                                    <i class="ri-close-circle-line text-lg"></i>
                                                </button>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <div class="text-center py-8 text-xs text-slate-400">
                                    No trusted devices recorded yet.
                                </div>
                            @endif
                        </div>
                    </article>

                </div>
            </div>

            <!-- ================= PHOTO CROPPER MODALS & OVERLAYS ================= -->
            <div data-avatar-loading-overlay class="fixed inset-0 z-[65] hidden items-center justify-center bg-white/80 backdrop-blur-sm">
                <div class="flex flex-col items-center space-y-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-full border border-[#0b3019]/20 bg-white shadow-md">
                        <i class="ri-loader-4-line animate-spin text-2xl text-[#0b3019]" aria-hidden="true"></i>
                    </div>
                    <p class="text-xs font-bold text-slate-700">Updating profile photo...</p>
                </div>
            </div>

            <div data-avatar-overlay class="fixed inset-0 z-[60] hidden items-center justify-center overflow-y-auto bg-slate-950/60 px-4 py-10 backdrop-blur-sm md:px-8">
                <div data-avatar-editor class="relative mx-auto w-full max-w-2xl rounded-[28px] border border-white/10 bg-white p-6 shadow-2xl md:p-8">
                    <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                        <div class="space-y-1">
                            <h3 class="text-lg font-bold text-[#0b3019] md:text-xl">Adjust Profile Photo</h3>
                            <p class="text-xs text-slate-500 md:max-w-2xl">Drag the image to reposition, or zoom so your face fits within the border.</p>
                        </div>
                        <button type="button" data-avatar-cancel class="inline-flex h-8 w-8 items-center justify-center self-start rounded-full border border-slate-200 text-slate-400 transition hover:text-[#0b3019] hover:bg-slate-50">
                            <span class="sr-only">Close crop dialog</span>
                            <i class="ri-close-line text-base" aria-hidden="true"></i>
                        </button>
                    </div>
                    <div class="mt-6 flex max-h-[60vh] min-h-[300px] items-center justify-center overflow-hidden rounded-2xl border border-slate-200 bg-slate-50 p-2 md:min-h-[360px]">
                        <img data-avatar-editor-image class="max-h-full w-full object-contain" alt="Adjust profile crop" />
                    </div>
                    <div data-avatar-controls class="mt-6 hidden flex flex-wrap items-center justify-end gap-2">
                        <button type="button" data-avatar-cancel class="inline-flex h-9 items-center justify-center rounded-full border border-slate-200 bg-white px-4 text-xs font-bold text-slate-600 transition hover:bg-slate-50">
                            Cancel
                        </button>
                        <button type="button" data-avatar-apply class="inline-flex h-9 items-center justify-center gap-1.5 rounded-full bg-[#0b3019] px-5 text-xs font-bold text-white shadow transition hover:bg-[#082212]">
                            <i class="ri-check-line text-sm" aria-hidden="true"></i>
                            Apply Crop
                        </button>
                    </div>
                </div>
            </div>

        </form>

        <!-- Hidden Device Revocation Forms (Outside the main form block) -->
        @if(isset($trustedDevices))
            @foreach($trustedDevices as $device)
                <form id="revoke-device-{{ $device->id }}" method="POST" action="{{ route('student.profile.devices.revoke', $device) }}" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            @endforeach
            
            <form id="revoke-all-form" method="POST" action="{{ route('student.profile.devices.revoke-all') }}" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        @endif

    </div>
</x-layouts.dashboard>
