@php($title = $title ?? 'Registration Details')

<x-layouts.admin :title="$title">
    <div class="mx-auto w-full max-w-4xl space-y-8 px-5 py-10 sm:px-6 lg:px-8">
        {{-- Back Button --}}
        <div>
            <a href="{{ route('admin.pending-registrations.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 transition hover:text-[#0b3019]">
                <i class="ri-arrow-left-line"></i>
                Back to Registrations
            </a>
        </div>

        {{-- Header --}}
        <header class="flex flex-col gap-4 rounded-3xl border border-[#0b3019]/15 bg-white/80 p-6 shadow-lg shadow-[#0b3019]/5 sm:flex-row sm:items-center sm:justify-between">
            <div class="space-y-2">
                <p class="inline-flex items-center gap-2 rounded-full bg-[#0b3019]/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.25em] text-[#0b3019]">
                    <i class="ri-user-line text-base" aria-hidden="true"></i>
                    Registration Details
                </p>
                <h1 class="text-2xl font-semibold text-[#0b3019] sm:text-3xl">{{ $registration->fullname }}</h1>
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-sm font-semibold {{ match($registration->status) {
                        'approved' => 'bg-emerald-50 text-emerald-700',
                        'rejected' => 'bg-rose-50 text-rose-600',
                        default => 'bg-amber-50 text-amber-700',
                    } }}">
                        <i class="{{ match($registration->status) {
                            'approved' => 'ri-checkbox-circle-line',
                            'rejected' => 'ri-close-circle-line',
                            default => 'ri-time-line',
                        } }}"></i>
                        {{ Str::title($registration->status) }}
                    </span>
                    @if ($registration->reviewed_at)
                        <span class="text-xs text-slate-500">
                            Reviewed by {{ $registration->reviewer?->fullname ?? 'Admin' }} on {{ $registration->reviewed_at->format('M j, Y g:i A') }}
                        </span>
                    @endif
                </div>
            </div>
        </header>

        {{-- Alerts --}}
        @if (session('status'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-700 shadow-sm">
                <div class="flex items-start gap-3">
                    <i class="ri-check-double-line text-lg" aria-hidden="true"></i>
                    <p>{{ session('status') }}</p>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-700 shadow-sm">
                <div class="flex items-start gap-3">
                    <i class="ri-error-warning-line text-lg" aria-hidden="true"></i>
                    <p>{{ $errors->first() }}</p>
                </div>
            </div>
        @endif

        {{-- Duplicate Warning --}}
        @if (! $canCreateUser['canCreate'])
            <div class="rounded-2xl border border-amber-200 bg-amber-50 px-5 py-4 text-sm text-amber-700 shadow-sm">
                <div class="flex items-start gap-3">
                    <i class="ri-alert-line text-lg" aria-hidden="true"></i>
                    <div>
                        <p class="font-semibold">Cannot create user account</p>
                        <ul class="mt-1 list-inside list-disc text-amber-600">
                            @foreach ($canCreateUser['issues'] as $issue)
                                <li>{{ $issue }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        {{-- Student Information --}}
        <section class="rounded-2xl border border-slate-200/80 bg-white shadow-sm overflow-hidden">
            <div class="border-b border-slate-200/60 bg-slate-50/40 px-6 py-4">
                <h2 class="text-sm font-semibold uppercase tracking-wider text-slate-500">Student Information</h2>
            </div>
            <div class="p-6">
                <dl class="grid gap-4 sm:grid-cols-2">
                    <div class="space-y-1">
                        <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400">Full Name</dt>
                        <dd class="text-base font-medium text-slate-900">{{ $registration->fullname }}</dd>
                    </div>
                    <div class="space-y-1">
                        <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400">Username</dt>
                        <dd class="text-base font-medium text-slate-900">{{ $registration->username }}</dd>
                    </div>
                    <div class="space-y-1">
                        <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400">Email Address</dt>
                        <dd class="text-base font-medium text-slate-900">{{ $registration->email }}</dd>
                    </div>
                    <div class="space-y-1">
                        <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400">Phone Number</dt>
                        <dd class="text-base font-medium text-slate-900">{{ $registration->phone_number ?? 'Not provided' }}</dd>
                    </div>
                    <div class="space-y-1">
                        <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400">Reference Number</dt>
                        <dd class="text-base font-medium text-slate-900">{{ $registration->index_number }}</dd>
                    </div>
                    <div class="space-y-1">
                        <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400">Program</dt>
                        <dd class="text-base font-medium text-slate-900">{{ $registration->class }}</dd>
                    </div>
                    <div class="space-y-1">
                        <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400">Year</dt>
                        <dd class="text-base font-medium text-slate-900">Year {{ $registration->year }}</dd>
                    </div>
                    <div class="space-y-1">
                        <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400">Submitted On</dt>
                        <dd class="text-base font-medium text-slate-900">{{ $registration->created_at->format('F j, Y \a\t g:i A') }}</dd>
                    </div>
                </dl>
            </div>
        </section>

        {{-- Rejection Reason (if rejected) --}}
        @if ($registration->isRejected() && $registration->rejection_reason)
            <section class="rounded-2xl border border-rose-200/80 bg-rose-50/50 shadow-sm overflow-hidden">
                <div class="border-b border-rose-200/60 bg-rose-100/40 px-6 py-4">
                    <h2 class="text-sm font-semibold uppercase tracking-wider text-rose-600">Rejection Reason</h2>
                </div>
                <div class="p-6">
                    <p class="text-slate-700">{{ $registration->rejection_reason }}</p>
                </div>
            </section>
        @endif

        {{-- Actions --}}
        <section class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm" x-data="{ rejectModalOpen: false }">
            <h2 class="mb-4 text-sm font-semibold uppercase tracking-wider text-slate-500">Actions</h2>
            <div class="flex flex-wrap gap-3">
                @if ($registration->isPending() || $registration->isRejected())
                    <form method="POST" action="{{ route('admin.pending-registrations.approve', $registration) }}" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700" {{ ! $canCreateUser['canCreate'] ? 'disabled' : '' }}>
                            <i class="ri-checkbox-circle-line"></i>
                            {{ $registration->isRejected() ? 'Re-Approve' : 'Approve' }} Registration
                        </button>
                    </form>
                @endif

                @if ($registration->isPending() || $registration->isApproved())
                    <button type="button" @click="rejectModalOpen = true" class="inline-flex items-center gap-2 rounded-xl bg-rose-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-rose-700">
                        <i class="ri-close-circle-line"></i>
                        {{ $registration->isApproved() ? 'Revoke & Reject' : 'Reject' }} Registration
                    </button>
                @endif
            </div>

            {{-- Reject Modal --}}
            <div x-show="rejectModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm" x-transition.opacity>
                <div @click.outside="rejectModalOpen = false" class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
                    <h3 class="text-lg font-semibold text-slate-900">Reject Registration</h3>
                    <p class="mt-1 text-sm text-slate-500">Please provide a reason for rejecting this registration. This will be sent to the student via email.</p>
                    
                    <form method="POST" action="{{ route('admin.pending-registrations.reject', $registration) }}" class="mt-4">
                        @csrf
                        <div class="space-y-3">
                            <label for="rejection_reason" class="block text-sm font-medium text-slate-700">Rejection Reason</label>
                            <textarea id="rejection_reason" name="rejection_reason" rows="4" required class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm text-slate-700 focus:border-[#0b3019] focus:outline-none focus:ring-2 focus:ring-[#0b3019]/30" placeholder="Explain why this registration is being rejected...">{{ $registration->rejection_reason }}</textarea>
                        </div>
                        <div class="mt-6 flex gap-3">
                            <button type="button" @click="rejectModalOpen = false" class="flex-1 rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                                Cancel
                            </button>
                            <button type="submit" class="flex-1 rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-rose-700">
                                Reject Registration
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
</x-layouts.admin>
