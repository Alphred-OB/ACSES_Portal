@php($title = $title ?? 'Pending Registrations')

<x-layouts.admin :title="$title">
    @include('components.dashboard.skeleton-styles')

    <div x-data="{ loading: true, rejectModalOpen: false, bulkRejectModalOpen: false, selectedId: null, selectedIds: [], selectAll: false }" 
         x-init="setTimeout(() => { loading = false }, 600)" 
         class="relative">
        
        {{-- Loading Skeleton --}}
        <div x-show="loading" x-transition.opacity.duration.200ms class="pointer-events-none absolute inset-0 z-10 flex justify-center bg-slate-50/80 backdrop-blur-sm">
            <div class="mx-auto flex w-full max-w-6xl flex-col gap-6 px-5 py-10 sm:px-6 lg:px-8">
                <header class="flex flex-col gap-4 rounded-3xl border border-[#0b3019]/15 bg-white/90 p-6 shadow-lg shadow-[#0b3019]/10">
                    <div class="space-y-3">
                        <div class="skeleton inline-flex h-7 w-56 items-center rounded-full bg-[#0b3019]/10"></div>
                        <div class="skeleton h-8 w-72 rounded-2xl bg-slate-200"></div>
                        <div class="skeleton h-4 w-96 max-w-full rounded-2xl bg-slate-100"></div>
                    </div>
                </header>
            </div>
        </div>

        {{-- Main Content --}}
        <div x-show="!loading" x-transition.opacity.duration.200ms x-cloak class="mx-auto w-full max-w-6xl space-y-8 px-5 py-10 sm:px-6 lg:px-8">
            
            {{-- Header --}}
            <header class="flex flex-col gap-4 rounded-3xl border border-[#0b3019]/15 bg-white/80 p-6 shadow-lg shadow-[#0b3019]/5 sm:flex-row sm:items-center sm:justify-between">
                <div class="space-y-2">
                    <p class="inline-flex items-center gap-2 rounded-full bg-[#0b3019]/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.25em] text-[#0b3019]">
                        <i class="ri-user-add-line text-base" aria-hidden="true"></i>
                        Registration Verification
                    </p>
                    <h1 class="text-2xl font-semibold text-[#0b3019] sm:text-3xl">Pending Registrations</h1>
                    <p class="text-sm text-slate-600">Review and verify student registration requests. Approve or reject applications.</p>
                </div>
            </header>

            {{-- Statistics Cards --}}
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-2xl border border-amber-200/60 bg-gradient-to-br from-amber-50 to-amber-100/50 p-5 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-500/15 text-amber-600">
                            <i class="ri-time-line text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-amber-700">{{ $statistics['pending'] }}</p>
                            <p class="text-xs font-medium uppercase tracking-wider text-amber-600/80">Pending</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-emerald-200/60 bg-gradient-to-br from-emerald-50 to-emerald-100/50 p-5 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-500/15 text-emerald-600">
                            <i class="ri-checkbox-circle-line text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-emerald-700">{{ $statistics['approved'] }}</p>
                            <p class="text-xs font-medium uppercase tracking-wider text-emerald-600/80">Approved</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-rose-200/60 bg-gradient-to-br from-rose-50 to-rose-100/50 p-5 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-rose-500/15 text-rose-600">
                            <i class="ri-close-circle-line text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-rose-700">{{ $statistics['rejected'] }}</p>
                            <p class="text-xs font-medium uppercase tracking-wider text-rose-600/80">Rejected</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200/60 bg-gradient-to-br from-slate-50 to-slate-100/50 p-5 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#0b3019]/15 text-[#0b3019]">
                            <i class="ri-file-list-3-line text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-[#0b3019]">{{ $statistics['total'] }}</p>
                            <p class="text-xs font-medium uppercase tracking-wider text-slate-500">Total</p>
                        </div>
                    </div>
                </div>
            </div>

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

            {{-- Filters --}}
            <section class="space-y-4 rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                <form method="GET" class="space-y-3 md:flex md:flex-wrap md:items-end md:gap-3 md:space-y-0">
                    <input type="hidden" name="per_page" value="{{ request('per_page', $currentPerPage) }}">

                    <div class="flex w-full flex-col gap-2 md:w-64">
                        <label for="filter_search" class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Search</label>
                        <div class="relative">
                            <i class="ri-search-line pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input id="filter_search" type="search" name="search" value="{{ $search }}" placeholder="Name, email, index..." class="h-11 w-full rounded-2xl border border-slate-200 bg-white pl-11 pr-4 text-sm text-slate-700 shadow-sm transition focus:border-[#0b3019] focus:outline-none focus:ring-2 focus:ring-[#0b3019]/30" />
                        </div>
                    </div>

                    <div class="flex w-full flex-col gap-2 md:w-40">
                        <label for="filter_status" class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Status</label>
                        <div class="relative">
                            <select id="filter_status" name="status" class="h-11 w-full appearance-none rounded-2xl border border-slate-200 bg-white pl-4 pr-10 text-sm text-slate-700 shadow-sm transition focus:border-[#0b3019] focus:outline-none focus:ring-2 focus:ring-[#0b3019]/30">
                                <option value="" @selected($activeStatus === null || $activeStatus === '')>All</option>
                                @foreach ($statuses as $statusOption)
                                    <option value="{{ $statusOption }}" @selected($activeStatus === $statusOption)>{{ Str::title($statusOption) }}</option>
                                @endforeach
                            </select>
                            <i class="ri-arrow-down-s-line pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        </div>
                    </div>

                    <div class="flex w-full flex-col gap-2 md:w-48">
                        <label for="filter_class" class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Program</label>
                        <div class="relative">
                            <select id="filter_class" name="class" class="h-11 w-full appearance-none rounded-2xl border border-slate-200 bg-white pl-4 pr-10 text-sm text-slate-700 shadow-sm transition focus:border-[#0b3019] focus:outline-none focus:ring-2 focus:ring-[#0b3019]/30">
                                <option value="" @selected($activeClass === null || $activeClass === '')>All</option>
                                @foreach ($classOptions as $option)
                                    <option value="{{ $option }}" @selected($activeClass === $option)>{{ $option }}</option>
                                @endforeach
                            </select>
                            <i class="ri-arrow-down-s-line pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        </div>
                    </div>

                    <div class="flex w-full flex-col gap-2 md:w-32">
                        <label for="filter_year" class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Year</label>
                        <div class="relative">
                            <select id="filter_year" name="year" class="h-11 w-full appearance-none rounded-2xl border border-slate-200 bg-white pl-4 pr-10 text-sm text-slate-700 shadow-sm transition focus:border-[#0b3019] focus:outline-none focus:ring-2 focus:ring-[#0b3019]/30">
                                <option value="" @selected($activeYear === null || $activeYear === '')>All</option>
                                @foreach ($yearOptions as $option)
                                    <option value="{{ $option }}" @selected($activeYear === $option)>Year {{ $option }}</option>
                                @endforeach
                            </select>
                            <i class="ri-arrow-down-s-line pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        </div>
                    </div>

                    <div class="flex items-end md:ml-auto">
                        <button type="submit" class="inline-flex h-11 min-w-[120px] items-center justify-center gap-2 rounded-2xl bg-[#0b3019] px-5 text-sm font-semibold text-white shadow-lg shadow-[#0b3019]/20 transition hover:-translate-y-0.5 hover:bg-[#0b3019]/90">
                            <i class="ri-equalizer-line"></i>
                            Apply
                        </button>
                    </div>
                </form>
            </section>

            {{-- Bulk Actions Bar --}}
            <div x-show="selectedIds.length > 0" x-cloak x-transition class="rounded-2xl border border-[#0b3019]/20 bg-[#0b3019]/5 p-4">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-sm font-medium text-[#0b3019]">
                        <span x-text="selectedIds.length"></span> registration(s) selected
                    </p>
                    <div class="flex gap-2">
                        <form method="POST" action="{{ route('admin.pending-registrations.bulk') }}" class="inline">
                            @csrf
                            <template x-for="id in selectedIds" :key="id">
                                <input type="hidden" name="ids[]" :value="id">
                            </template>
                            <input type="hidden" name="action" value="approve">
                            <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                                <i class="ri-checkbox-circle-line"></i>
                                Bulk Approve
                            </button>
                        </form>
                        <button type="button" @click="bulkRejectModalOpen = true" class="inline-flex items-center gap-2 rounded-xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-rose-700">
                            <i class="ri-close-circle-line"></i>
                            Bulk Reject
                        </button>
                    </div>
                </div>
            </div>

            {{-- Registrations Table --}}
            <section class="rounded-2xl border border-slate-200/80 bg-white shadow-sm overflow-hidden">
                {{-- Rows per page --}}
                <div class="flex items-center justify-between border-b border-slate-200/60 bg-slate-50/40 px-4 py-2">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" x-model="selectAll" @change="selectedIds = selectAll ? @js($registrations->pluck('id')) : []" class="h-4 w-4 rounded border-slate-300 text-[#0b3019] focus:ring-[#0b3019]">
                        <span class="text-xs text-slate-500">Select all</span>
                    </div>
                    <form method="GET" class="flex items-center gap-2">
                        @foreach (request()->except(['page','per_page']) as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        <label for="rows_per_page" class="text-xs font-medium text-slate-500">Rows</label>
                        <select id="rows_per_page" name="per_page" class="rounded-lg border border-slate-200 bg-white px-2 py-1 text-xs text-slate-700 focus:border-[#0b3019] focus:ring-[#0b3019]/60" onchange="this.form.submit()">
                            @foreach ($perPageOptions as $opt)
                                <option value="{{ $opt }}" @selected($currentPerPage === $opt)>{{ $opt }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>

                {{-- Desktop Table --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-left text-sm text-slate-600">
                        <thead class="bg-slate-50/80 text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">
                            <tr>
                                <th class="w-12 px-4 py-3"></th>
                                <th class="px-4 py-3">Student</th>
                                <th class="px-4 py-3">Program</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Submitted</th>
                                <th class="px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 bg-white">
                            @forelse ($registrations as $registration)
                                <tr class="hover:bg-slate-50/50">
                                    <td class="px-4 py-4">
                                        <input type="checkbox" 
                                               :value="{{ $registration->id }}" 
                                               x-model.number="selectedIds"
                                               class="h-4 w-4 rounded border-slate-300 text-[#0b3019] focus:ring-[#0b3019]">
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex flex-col">
                                            <span class="font-semibold text-slate-900">{{ $registration->fullname }}</span>
                                            <span class="text-xs text-slate-500">{{ $registration->email }}</span>
                                            <span class="text-xs text-slate-400">Ref: {{ $registration->index_number }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex flex-col">
                                            <span class="text-slate-700">{{ $registration->class }}</span>
                                            <span class="text-xs text-slate-400">Year {{ $registration->year }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <span class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-xs font-semibold {{ match($registration->status) {
                                            'approved' => 'bg-emerald-50 text-emerald-700',
                                            'rejected' => 'bg-rose-50 text-rose-600',
                                            default => 'bg-amber-50 text-amber-700',
                                        } }}">
                                            <i class="{{ match($registration->status) {
                                                'approved' => 'ri-checkbox-circle-line',
                                                'rejected' => 'ri-close-circle-line',
                                                default => 'ri-time-line',
                                            } }} text-sm"></i>
                                            {{ Str::title($registration->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-slate-500">
                                        {{ $registration->created_at->format('M j, Y') }}
                                        <span class="text-slate-400">{{ $registration->created_at->format('g:i A') }}</span>
                                    </td>
                                    <td class="px-4 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            @if ($registration->isPending() || $registration->isRejected())
                                                <form method="POST" action="{{ route('admin.pending-registrations.approve', $registration) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" class="inline-flex items-center gap-1 rounded-lg bg-emerald-100 px-3 py-1.5 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-200" title="Approve">
                                                        <i class="ri-checkbox-circle-line"></i>
                                                        Approve
                                                    </button>
                                                </form>
                                            @endif
                                            @if ($registration->isPending() || $registration->isApproved())
                                                <button type="button" @click="selectedId = {{ $registration->id }}; rejectModalOpen = true" class="inline-flex items-center gap-1 rounded-lg bg-rose-100 px-3 py-1.5 text-xs font-semibold text-rose-700 transition hover:bg-rose-200" title="Reject">
                                                    <i class="ri-close-circle-line"></i>
                                                    Reject
                                                </button>
                                            @endif
                                            <a href="{{ route('admin.pending-registrations.show', $registration) }}" class="inline-flex items-center gap-1 rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:border-[#0b3019]/40 hover:text-[#0b3019]" title="View Details">
                                                <i class="ri-eye-line"></i>
                                                View
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-10 text-center text-sm text-slate-500">
                                        <div class="flex flex-col items-center gap-3">
                                            <i class="ri-user-search-line text-4xl text-slate-300"></i>
                                            <p class="font-semibold text-slate-600">No registrations found.</p>
                                            <p>Adjust filters or check back later.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Mobile Cards --}}
                <div class="grid gap-4 p-4 md:hidden">
                    @forelse ($registrations as $registration)
                        <article class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h2 class="text-lg font-semibold text-slate-900">{{ $registration->fullname }}</h2>
                                    <p class="text-xs text-slate-500">{{ $registration->email }}</p>
                                    <p class="text-xs text-slate-400">{{ $registration->class }} · Year {{ $registration->year }}</p>
                                </div>
                                <span class="inline-flex items-center gap-1 rounded-full px-2 py-1 text-xs font-semibold {{ match($registration->status) {
                                    'approved' => 'bg-emerald-50 text-emerald-700',
                                    'rejected' => 'bg-rose-50 text-rose-600',
                                    default => 'bg-amber-50 text-amber-700',
                                } }}">
                                    {{ Str::title($registration->status) }}
                                </span>
                            </div>
                            <div class="mt-4 flex items-center justify-between">
                                <span class="text-xs text-slate-400">{{ $registration->created_at->format('M j, Y') }}</span>
                                <div class="flex gap-2">
                                    @if ($registration->isPending() || $registration->isRejected())
                                        <form method="POST" action="{{ route('admin.pending-registrations.approve', $registration) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="rounded-lg bg-emerald-100 p-2 text-emerald-700 transition hover:bg-emerald-200">
                                                <i class="ri-checkbox-circle-line"></i>
                                            </button>
                                        </form>
                                    @endif
                                    @if ($registration->isPending() || $registration->isApproved())
                                        <button type="button" @click="selectedId = {{ $registration->id }}; rejectModalOpen = true" class="rounded-lg bg-rose-100 p-2 text-rose-700 transition hover:bg-rose-200">
                                            <i class="ri-close-circle-line"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50/60 p-8 text-center text-sm text-slate-500">
                            <i class="ri-user-search-line text-4xl text-slate-300"></i>
                            <p class="mt-3 font-semibold text-slate-600">No registrations found.</p>
                        </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                <div class="flex flex-col gap-3 border-t border-slate-200/60 p-4 text-center sm:flex-row sm:items-center sm:justify-between sm:text-left">
                    <p class="text-xs text-slate-500">Showing {{ $registrations->firstItem() ?? 0 }}–{{ $registrations->lastItem() ?? 0 }} of {{ $registrations->total() }} registrations</p>
                    <div class="flex justify-center sm:ml-auto sm:justify-end">
                        {{ $registrations->onEachSide(1)->links('vendor.pagination.data-limit') }}
                    </div>
                </div>
            </section>
        </div>

        {{-- Reject Modal (Individual) --}}
        <div x-show="rejectModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm" x-transition.opacity>
            <div @click.outside="rejectModalOpen = false" class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
                <h3 class="text-lg font-semibold text-slate-900">Reject Registration</h3>
                <p class="mt-1 text-sm text-slate-500">Please provide a reason for rejecting this registration. This will be sent to the student.</p>
                
                <form method="POST" :action="'/admin/pending-registrations/' + selectedId + '/reject'" class="mt-4">
                    @csrf
                    <div class="space-y-3">
                        <label for="rejection_reason" class="block text-sm font-medium text-slate-700">Rejection Reason</label>
                        <textarea id="rejection_reason" name="rejection_reason" rows="4" required class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm text-slate-700 focus:border-[#0b3019] focus:outline-none focus:ring-2 focus:ring-[#0b3019]/30" placeholder="Explain why this registration is being rejected..."></textarea>
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

        {{-- Bulk Reject Modal --}}
        <div x-show="bulkRejectModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm" x-transition.opacity>
            <div @click.outside="bulkRejectModalOpen = false" class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
                <h3 class="text-lg font-semibold text-slate-900">Bulk Reject Registrations</h3>
                <p class="mt-1 text-sm text-slate-500">You are about to reject <span class="font-semibold text-rose-600" x-text="selectedIds.length"></span> registration(s). Please provide a reason.</p>
                
                <form method="POST" action="{{ route('admin.pending-registrations.bulk') }}" class="mt-4">
                    @csrf
                    <template x-for="id in selectedIds" :key="id">
                        <input type="hidden" name="ids[]" :value="id">
                    </template>
                    <input type="hidden" name="action" value="reject">
                    
                    <div class="space-y-3">
                        <label for="bulk_rejection_reason" class="block text-sm font-medium text-slate-700">Rejection Reason</label>
                        <textarea id="bulk_rejection_reason" name="rejection_reason" rows="4" required class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm text-slate-700 focus:border-[#0b3019] focus:outline-none focus:ring-2 focus:ring-[#0b3019]/30" placeholder="Explain why these registrations are being rejected..."></textarea>
                    </div>
                    <div class="mt-6 flex gap-3">
                        <button type="button" @click="bulkRejectModalOpen = false" class="flex-1 rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                            Cancel
                        </button>
                        <button type="submit" class="flex-1 rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-rose-700">
                            Reject Selected
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.admin>
