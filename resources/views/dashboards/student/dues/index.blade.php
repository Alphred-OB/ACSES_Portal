<x-layouts.dashboard :title="$title">
    @include('components.dashboard.skeleton-styles')
    <div x-data="{ loading: true }" x-init="setTimeout(() => { loading = false }, 600)" class="mx-auto w-full max-w-6xl px-5 py-12 sm:px-6 lg:px-8">
        <div x-show="loading" x-transition.opacity.duration.200ms class="space-y-10" role="status" aria-live="polite">
            <section class="grid gap-6 md:grid-cols-2">
                <article class="flex h-full flex-col rounded-3xl border border-[#0b3019]/15 bg-[#0b3019] p-6 text-white shadow-lg shadow-[#0b3019]/20">
                    <div class="space-y-5">
                        <div class="skeleton h-3 w-40 rounded-full bg-white/30"></div>
                        <div class="skeleton h-8 w-32 rounded-2xl bg-white/40"></div>
                        <div class="skeleton mt-6 h-12 w-full rounded-2xl bg-white/20"></div>
                    </div>
                </article>

                <article class="flex h-full flex-col rounded-3xl border border-emerald-100 bg-emerald-50 p-6 text-emerald-900 shadow-lg shadow-emerald-100/40">
                    <div class="space-y-5">
                        <div class="skeleton h-3 w-36 rounded-full bg-emerald-200/60"></div>
                        <div class="skeleton h-8 w-28 rounded-2xl bg-emerald-200/80"></div>
                        <div class="skeleton mt-6 h-12 w-full rounded-2xl bg-emerald-100/80"></div>
                    </div>
                </article>
            </section>

            <section class="space-y-6 rounded-3xl border border-[#0b3019]/15 bg-white p-6 shadow-lg shadow-[#0b3019]/10">
                <div class="space-y-3">
                    <div class="skeleton h-5 w-48 rounded-full bg-slate-200"></div>
                    <div class="skeleton h-4 w-64 rounded-full bg-slate-100"></div>
                </div>

                <div class="grid gap-4 rounded-2xl border border-slate-200/70 bg-slate-50/60 p-4 md:grid-cols-5">
                    <div class="skeleton h-11 rounded-2xl bg-white md:col-span-2"></div>
                    <div class="skeleton h-11 rounded-2xl bg-white"></div>
                    <div class="skeleton h-11 rounded-2xl bg-white"></div>
                    <div class="flex items-center justify-end gap-3 md:col-span-2 md:col-start-4">
                        <div class="skeleton h-11 w-24 rounded-2xl bg-white"></div>
                        <div class="skeleton h-11 w-28 rounded-2xl bg-[#0b3019]/10"></div>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200/70">
                    <ul class="divide-y divide-slate-200">
                        @for ($i = 0; $i < 4; $i++)
                            <li class="grid gap-4 bg-white px-5 py-4 lg:grid-cols-7">
                                <div class="skeleton h-4 w-40 rounded-full bg-slate-100 lg:col-span-2"></div>
                                <div class="skeleton h-4 w-24 rounded-full bg-slate-100"></div>
                                <div class="skeleton h-4 w-24 rounded-full bg-slate-100"></div>
                                <div class="skeleton h-4 w-24 rounded-full bg-slate-100"></div>
                                <div class="skeleton h-4 w-24 rounded-full bg-slate-100"></div>
                                <div class="skeleton h-4 w-24 rounded-full bg-slate-100"></div>
                                <div class="skeleton h-9 rounded-full bg-slate-100"></div>
                            </li>
                        @endfor
                    </ul>
                </div>
            </section>
        </div>

        <div x-show="!loading" x-transition.opacity.duration.200ms x-cloak class="space-y-10">
            @if (session('status'))
                <div class="rounded-3xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-700 shadow-sm">
                    <div class="flex items-start gap-3">
                        <span class="mt-0.5 inline-flex h-6 w-6 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                            <i class="ri-check-line" aria-hidden="true"></i>
                        </span>
                        <div>
                            <p class="font-semibold uppercase tracking-[0.25em] text-emerald-500">Success</p>
                            <p class="mt-1 text-sm">{{ session('status') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="rounded-3xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-700 shadow-sm">
                    <div class="flex items-start gap-3">
                        <span class="mt-0.5 inline-flex h-6 w-6 items-center justify-center rounded-full bg-rose-100 text-rose-600">
                            <i class="ri-error-warning-line" aria-hidden="true"></i>
                        </span>
                        <div>
                            <p class="font-semibold uppercase tracking-[0.25em] text-rose-500">Payment error</p>
                            <p class="mt-1 text-sm">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <section class="grid gap-6 md:grid-cols-2">
                <article class="flex h-full flex-col rounded-3xl border border-[#0b3019]/15 bg-[#0b3019] p-6 text-white shadow-lg shadow-[#0b3019]/20">
                    <header class="flex items-center justify-between gap-4">
                        <div class="space-y-1">
                            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-white/70">Outstanding balance</p>
                            <p class="text-3xl font-semibold">GHS {{ number_format((float) ($summary['outstanding_amount'] ?? 0), 2) }}</p>
                        </div>
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/15"><i class="ri-error-warning-line text-2xl"></i></span>
                    </header>
                </article>

                <article class="flex h-full flex-col rounded-3xl border border-emerald-100 bg-emerald-50 p-6 text-emerald-900 shadow-lg shadow-emerald-100/40">
                    <header class="flex items-center justify-between gap-4">
                        <div class="space-y-1">
                            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-emerald-600">Payments recorded</p>
                            <p class="text-3xl font-semibold">GHS {{ number_format((float) ($summary['paid_amount'] ?? 0), 2) }}</p>
                        </div>
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-700"><i class="ri-coins-line text-2xl"></i></span>
                    </header>
                </article>
            </section>

            <section class="space-y-6 rounded-3xl border border-[#0b3019]/15 bg-white p-6 shadow-lg shadow-[#0b3019]/10">
            <header class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-[#0b3019]">My dues history</h2>
                    <p class="text-sm text-slate-600">Filter by academic year, status, or search by description or reference.</p>
                </div>
            </header>

            @php($activeStatus = $filters['status'] ?? '')
            @php($activeYear = $filters['academic_year'] ?? '')
            @php($searchTerm = $filters['search'] ?? '')

            <form method="GET" class="grid gap-4 rounded-2xl border border-slate-200/70 bg-slate-50/60 p-4 md:grid-cols-5">
                <div class="md:col-span-2 flex flex-col gap-2">
                    <label class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Search description or reference</label>
                    <input type="search" name="search" value="{{ $searchTerm }}" placeholder="e.g. departmental dues" class="h-11 w-full rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-700 shadow-sm transition focus:border-[#0b3019] focus:outline-none focus:ring-2 focus:ring-[#0b3019]/30">
                </div>

                <div class="flex flex-col gap-2">
                    <label class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Status</label>
                    <div class="relative">
                        <select name="status" class="h-11 w-full appearance-none rounded-2xl border border-slate-200 bg-white pl-4 pr-10 text-sm text-slate-700 shadow-sm transition focus:border-[#0b3019] focus:outline-none focus:ring-2 focus:ring-[#0b3019]/30">
                            <option value="">All statuses</option>
                            @foreach ($filterOptions['statuses'] ?? [] as $value => $label)
                                <option value="{{ $value }}" @selected($activeStatus === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <i class="ri-arrow-down-s-line pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    </div>
                </div>

                <div class="flex flex-col gap-2">
                    <label class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Academic year</label>
                    <div class="relative">
                        <select name="academic_year" class="h-11 w-full appearance-none rounded-2xl border border-slate-200 bg-white pl-4 pr-10 text-sm text-slate-700 shadow-sm transition focus:border-[#0b3019] focus:outline-none focus:ring-2 focus:ring-[#0b3019]/30">
                            <option value="">All years</option>
                            @foreach ($filterOptions['academic_years'] ?? [] as $year)
                                <option value="{{ $year }}" @selected($activeYear === $year)>{{ $year }}</option>
                            @endforeach
                        </select>
                        <i class="ri-arrow-down-s-line pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    </div>
                </div>

                <div class="flex items-end justify-end gap-3 md:col-span-2 md:col-start-4">
                    <a href="{{ route('student.dues.index') }}" class="inline-flex h-11 items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                        Reset
                    </a>
                    <button type="submit" class="inline-flex h-11 items-center justify-center gap-2 rounded-2xl bg-[#0b3019] px-5 text-sm font-semibold uppercase tracking-[0.2em] text-white shadow-lg shadow-[#0b3019]/20 transition hover:-translate-y-0.5 hover:bg-[#0b3019]/90">
                        <i class="ri-filter-3-line text-base" aria-hidden="true"></i>
                        Apply
                    </button>
                </div>
            </form>

            <div class="flex flex-col gap-4 rounded-2xl border border-slate-200/70 bg-white/80 p-4 text-sm text-slate-600 md:flex-row md:items-center md:justify-between">
                <p class="font-semibold">Showing {{ number_format($dues->firstItem() ?? 0) }}–{{ number_format($dues->lastItem() ?? 0) }} of {{ number_format($dues->total()) }} dues</p>
                <form method="GET" class="flex items-center gap-2">
                    @foreach (request()->except(['per_page', 'page']) as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                    <label for="per_page" class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Rows per page</label>
                    <select id="per_page" name="per_page" class="h-9 rounded-xl border border-slate-200 bg-white px-3 text-xs text-slate-700 shadow-sm focus:border-[#0b3019] focus:ring-[#0b3019]" onchange="this.form.submit()">
                        @foreach ($perPageOptions as $option)
                            <option value="{{ $option }}" @selected($option === $currentPerPage)>{{ $option }}</option>
                        @endforeach
                    </select>
                </form>
            </div>

            @php($statusColors = [
                'owing' => 'bg-rose-50 text-rose-600 border border-rose-100',
                'pending_verification' => 'bg-amber-50 text-amber-600 border border-amber-100',
                'paid' => 'bg-emerald-50 text-emerald-700 border border-emerald-100',
            ])

            <div class="overflow-hidden rounded-2xl border border-slate-200/70">
                <table class="hidden min-w-full divide-y divide-slate-200 text-left text-sm text-slate-600 lg:table">
                    <thead class="bg-slate-50/80 text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-400">
                        <tr>
                            <th class="px-5 py-3">Description</th>
                            <th class="px-5 py-3">Academic year</th>
                            <th class="px-5 py-3">Amount</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3">Due date</th>
                            <th class="px-5 py-3">Reference</th>
                            <th class="px-5 py-3 text-right">Payment</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse ($dues as $due)
                            <tr class="transition hover:bg-slate-50/70">
                                <td class="px-5 py-4">
                                    <p class="text-sm font-semibold text-slate-900">{{ $due->description }}</p>
                                    @if ($due->payment_notes)
                                        <p class="mt-1 text-xs text-slate-500">{{ $due->payment_notes }}</p>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-xs text-slate-500">{{ $due->academic_year }}</td>
                                <td class="px-5 py-4 text-sm font-semibold text-slate-900">GHS {{ number_format((float) $due->amount, 2) }}</td>
                                <td class="px-5 py-4">
                                    @php($status = $due->payment_status)
                                    <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] {{ $statusColors[$status] ?? 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                                        <i class="ri-checkbox-circle-line text-sm" aria-hidden="true"></i>
                                        {{ $statusLabels[$status] ?? ucfirst(str_replace('_', ' ', $status)) }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-xs text-slate-500">{{ optional($due->due_date)->format('M j, Y') ?? '—' }}</td>
                                <td class="px-5 py-4 text-xs text-slate-500">{{ $due->payment_reference ?? $due->reference_number ?? '—' }}</td>
                                <td class="px-5 py-4">
                                    @php($status = $due->payment_status)
                                    @if ($status === 'owing')
                                        @if(($paymentSettings['mode'] ?? 'automated') === 'automated')
                                            <form method="POST" action="{{ route('student.payments.paystack.initialize', $due) }}" class="flex justify-end">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-[#0b3019] px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-[#0b3019]/90">
                                                    <i class="ri-secure-payment-line text-base" aria-hidden="true"></i>
                                                    Pay with Paystack
                                                </button>
                                            </form>
                                        @else
                                            <div class="flex justify-end">
                                                <button 
                                                    type="button"
                                                    @click="$dispatch('open-modal', 'manual-pay-{{ $due->due_id }}')"
                                                    class="inline-flex items-center gap-2 rounded-full bg-[#0b3019] px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-[#0b3019]/90"
                                                >
                                                    <i class="ri-bank-card-line text-base" aria-hidden="true"></i>
                                                    Pay Manually
                                                </button>
                                            </div>

                                            <x-modal name="manual-pay-{{ $due->due_id }}" focusable>
                                                <form action="{{ route('student.payments.manual.submit', $due) }}" method="POST" enctype="multipart/form-data" class="p-8">
                                                    @csrf
                                                    <h2 class="text-xl font-bold text-slate-900">Manual Payment Transfer</h2>
                                                    <p class="mt-1 text-sm text-slate-500">Please follow the instructions below to complete your payment.</p>

                                                    <div class="mt-6 grid gap-6 md:grid-cols-2">
                                                        <div class="rounded-2xl bg-slate-50 p-4 border border-slate-100">
                                                            <h4 class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-3">Bank Details</h4>
                                                            <div class="space-y-2">
                                                                <div class="flex justify-between text-xs"><span class="text-slate-500">Bank:</span> <span class="font-bold text-slate-800">{{ $paymentSettings['bank_name'] }}</span></div>
                                                                <div class="flex justify-between text-xs"><span class="text-slate-500">Account:</span> <span class="font-bold text-slate-800">{{ $paymentSettings['account_name'] }}</span></div>
                                                                <div class="flex justify-between text-xs"><span class="text-slate-500">Number:</span> <span class="font-bold text-slate-800 tracking-wider">{{ $paymentSettings['account_number'] }}</span></div>
                                                            </div>
                                                        </div>
                                                        <div class="rounded-2xl bg-emerald-50 p-4 border border-emerald-100">
                                                            <h4 class="text-[10px] font-bold uppercase tracking-widest text-emerald-600 mb-3">Mobile Money</h4>
                                                            <div class="space-y-2">
                                                                <div class="flex justify-between text-xs"><span class="text-emerald-600">Merchant/Name:</span> <span class="font-bold text-emerald-900">{{ $paymentSettings['momo_name'] }}</span></div>
                                                                <div class="flex justify-between text-xs"><span class="text-emerald-600">Phone:</span> <span class="font-bold text-emerald-900 tracking-wider">{{ $paymentSettings['momo_number'] }}</span></div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="mt-6 p-4 rounded-2xl bg-amber-50 border border-amber-100/50">
                                                        <h4 class="text-xs font-bold text-amber-700 flex items-center gap-2"><i class="ri-information-line"></i> Instructions</h4>
                                                        <p class="mt-1 text-xs text-amber-600 italic">{{ $paymentSettings['instructions'] ?: 'Ensure you use your reference number as the payment reference.' }}</p>
                                                    </div>

                                                    <div class="mt-8 space-y-4">
                                                        <div>
                                                            <label class="text-sm font-bold text-slate-700">Upload Transfer Receipt (Image)</label>
                                                            <input type="file" name="receipt" accept="image/*" class="mt-2 block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#0b3019] file:text-white hover:file:bg-[#0b3019]/80 cursor-pointer" required>
                                                        </div>
                                                        <div>
                                                            <label class="text-sm font-bold text-slate-700">Transaction ID / Reference (Optional)</label>
                                                            <input type="text" name="reference" placeholder="e.g. 159823476" class="mt-2 w-full rounded-xl border-slate-200 text-sm focus:border-[#0b3019] focus:ring-[#0b3019]">
                                                        </div>
                                                    </div>

                                                    <div class="mt-8 flex justify-end gap-3">
                                                        <button type="button" @click="$dispatch('close')" class="px-6 py-2 rounded-xl text-sm font-bold text-slate-500 bg-slate-100 hover:bg-slate-200 transition">Cancel</button>
                                                        <button type="submit" class="px-6 py-2 rounded-xl text-sm font-bold text-white shadow-lg transition" style="background-color: #0b3019;">Submit Proof of Payment</button>
                                                    </div>
                                                </form>
                                            </x-modal>
                                        @endif
                                    @elseif ($status === 'pending_verification')
                                        <div class="flex flex-col items-end gap-1">
                                            <div class="text-right text-xs font-bold text-amber-600">Awaiting Verification</div>
                                            @if($due->payment_method === 'manual')
                                                <div class="text-[9px] text-slate-400 bg-slate-50 px-2 py-0.5 rounded border">Payment proof submitted</div>
                                            @endif
                                        </div>
                                    @elseif ($status === 'paid')
                                        <a href="{{ route('student.payments.paystack.receipt', $due) }}" class="inline-flex items-center gap-2 rounded-full border border-[#0b3019]/30 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-[#0b3019] shadow-sm transition hover:-translate-y-0.5 hover:border-[#0b3019]/50">
                                            <i class="ri-file-download-line text-base" aria-hidden="true"></i>
                                            Download receipt
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            @if($due->rejection_reason && $status === 'owing')
                                <tr class="bg-rose-50/30 border-t-0">
                                    <td colspan="7" class="px-5 py-2">
                                        <div class="flex items-center gap-2 text-[10px] font-bold text-rose-600">
                                            <i class="ri-error-warning-line"></i>
                                            <span>Previous Submission Rejected: {{ $due->rejection_reason }}</span>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-sm text-slate-500">
                                    <div class="flex flex-col items-center gap-3">
                                        <i class="ri-archive-drawer-line text-3xl text-slate-300" aria-hidden="true"></i>
                                        <p class="font-semibold text-slate-600">No dues found for the selected filters.</p>
                                        <p class="text-xs text-slate-500">Adjust the filters or contact support if you believe this is incorrect.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="grid gap-4 lg:hidden">
                    @forelse ($dues as $due)
                        <article class="rounded-2xl border border-slate-200/70 bg-white p-5 shadow-sm">
                            <header class="flex items-start justify-between">
                                <div>
                                    <h3 class="text-base font-semibold text-slate-900">{{ $due->description }}</h3>
                                    <p class="text-xs text-slate-500">{{ $due->academic_year }} · GHS {{ number_format((float) $due->amount, 2) }}</p>
                                </div>
                                @php($status = $due->payment_status)
                                <span class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.2em] {{ $statusColors[$status] ?? 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                                    {{ $statusLabels[$status] ?? ucfirst(str_replace('_', ' ', $status)) }}
                                </span>
                            </header>
                            <dl class="mt-4 space-y-1 text-xs text-slate-500">
                                <div class="flex justify-between">
                                    <dt>Due date</dt>
                                    <dd class="text-right text-slate-700">{{ optional($due->due_date)->format('M j, Y') ?? '—' }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt>Reference</dt>
                                    <dd class="text-right text-slate-700">{{ $due->payment_reference ?? $due->reference_number ?? '—' }}</dd>
                                </div>
                                @if ($due->payment_notes)
                                    <div class="mt-2 rounded-xl bg-slate-50 p-3 text-left text-xs text-slate-500">
                                        {{ $due->payment_notes }}
                                    </div>
                                @endif
                            </dl>
                            @if ($due->payment_status === 'owing')
                                @if(($paymentSettings['mode'] ?? 'automated') === 'automated')
                                    <form method="POST" action="{{ route('student.payments.paystack.initialize', $due) }}" class="mt-4">
                                        @csrf
                                        <button type="submit" class="w-full rounded-full bg-[#0b3019] px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-[#0b3019]/90">
                                            <i class="ri-secure-payment-line text-base" aria-hidden="true"></i>
                                            Pay Now
                                        </button>
                                    </form>
                                @else
                                    <button 
                                        type="button" 
                                        @click="$dispatch('open-modal', 'manual-pay-{{ $due->due_id }}')"
                                        class="mt-4 w-full rounded-full bg-[#0b3019] px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-[#0b3019]/90"
                                    >
                                        <i class="ri-bank-card-line text-base"></i>
                                        Pay Manually
                                    </button>
                                @endif
                            @elseif ($due->payment_status === 'pending_verification')
                                <p class="mt-4 text-center text-xs font-bold text-amber-600">Verification in progress…</p>
                            @elseif ($due->payment_status === 'paid')
                                <a href="{{ route('student.payments.paystack.receipt', $due) }}" class="mt-4 inline-flex w-full items-center justify-center gap-2 rounded-full border border-[#0b3019]/30 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-[#0b3019] shadow-sm transition hover:-translate-y-0.5 hover:border-[#0b3019]/50">
                                    <i class="ri-file-download-line text-base" aria-hidden="true"></i>
                                    Download receipt
                                </a>
                            @endif

                            @if($due->rejection_reason && $due->payment_status === 'owing')
                                <div class="mt-3 rounded-xl bg-rose-50 p-2 text-[10px] font-bold text-rose-600 flex items-center gap-2">
                                    <i class="ri-error-warning-line"></i>
                                    <span>Rejected: {{ $due->rejection_reason }}</span>
                                </div>
                            @endif
                        </article>
                    @empty
                        <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50/70 p-8 text-center text-sm text-slate-500">
                            <i class="ri-archive-drawer-line text-3xl text-slate-300" aria-hidden="true"></i>
                            <p class="mt-3 font-semibold text-slate-600">No dues found.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="flex flex-col gap-3 border-t border-slate-200/70 pt-4 text-center sm:flex-row sm:items-center sm:justify-between sm:text-left">
                <p class="text-xs text-slate-500">Page {{ number_format($dues->currentPage()) }} of {{ number_format($dues->lastPage()) }}</p>
                <div class="flex justify-center sm:ml-auto sm:justify-end">
                    {{ $dues->onEachSide(1)->links('vendor.pagination.data-limit') }}
                </div>
            </div>
        </section>
    </div>
</x-layouts.dashboard>
