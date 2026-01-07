@php($title = 'System Maintenance')

<x-layouts.admin :title="$title">
    @include('components.dashboard.skeleton-styles')

    <div class="mx-auto w-full max-w-6xl px-5 py-10 sm:px-6 lg:px-8">
        <section class="animate-fade-slide overflow-hidden rounded-[28px] border border-red-900/15 bg-gradient-to-br from-red-950 via-red-900 to-red-950 p-10 text-white shadow-[0_24px_60px_-30px_rgba(153,27,27,0.4)]">
            <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
                <div class="space-y-3">
                    <p class="text-xs font-semibold uppercase tracking-[0.35em] text-red-100/80">Hazard Zone</p>
                    <h1 class="text-3xl font-semibold md:text-4xl">System Maintenance</h1>
                    <p class="max-w-2xl text-sm text-red-100/85">Advanced tools to fix database inconsistencies, sync records, and optimize performance. Use with caution.</p>
                </div>
            </div>
        </section>

        @if (session('status'))
            <div class="mt-8 rounded-2xl bg-emerald-50 p-4 border border-emerald-200 text-emerald-800 text-sm">
                {{ session('status') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mt-8 rounded-2xl bg-red-50 p-4 border border-red-200 text-red-800 text-sm">
                {{ session('error') }}
            </div>
        @endif

        <div class="mt-10 grid gap-6 md:grid-cols-2 lg:grid-cols-4">
            <!-- Orphaned Records -->
            <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-base font-semibold text-slate-800">1. "Ghost" Dues</h3>
                <p class="mt-2 text-xs text-slate-500">Dues belonging to deleted or non-existent student accounts.</p>
                <div class="mt-4 flex items-center justify-between rounded-xl bg-slate-50 p-3">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Found</span>
                    <span class="text-lg font-bold text-red-600">{{ $orphanedCount }}</span>
                </div>
                <form action="{{ route('admin.maintenance.delete-orphaned') }}" method="POST" class="mt-4">
                    @csrf
                    <button type="submit" class="w-full rounded-xl py-2 text-xs font-semibold text-white transition hover:opacity-90 shadow-sm" style="background-color: #0b3019;" onclick="return confirm('Kill all orphaned records? This cannot be undone.')">
                        Bulk Delete
                    </button>
                </form>
            </article>

            <!-- Duplicate Dues -->
            <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-base font-semibold text-slate-800">2. Duplicates</h3>
                <p class="mt-2 text-xs text-slate-500">Same Student + Same Description + Same Year.</p>
                <div class="mt-4 flex items-center justify-between rounded-xl bg-slate-50 p-3">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Sets</span>
                    <span class="text-lg font-bold text-orange-600">{{ $duplicateCount }}</span>
                </div>
                <form action="{{ route('admin.maintenance.resolve-duplicates') }}" method="POST" class="mt-4">
                    @csrf
                    <button type="submit" class="w-full rounded-xl py-2 text-xs font-semibold text-white transition hover:opacity-90 shadow-sm" style="background-color: #0b3019;">
                        Resolve All
                    </button>
                </form>
            </article>

            <!-- Dummy Accounts -->
            <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-base font-semibold text-slate-800">3. Dummy Accounts</h3>
                <p class="mt-2 text-xs text-slate-500">Unverified students with non-school emails.</p>
                <div class="mt-4 flex items-center justify-between rounded-xl bg-slate-50 p-3">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Detected</span>
                    <span class="text-lg font-bold text-indigo-600">{{ count($potentialDummies) }}</span>
                </div>
                <a href="#dummy-review" class="mt-4 block w-full text-center rounded-xl py-2 text-xs font-semibold text-white transition hover:opacity-90 shadow-sm" style="background-color: #0b3019;">
                    Review List
                </a>
            </article>
            
            <!-- System Sync -->
            <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-base font-semibold text-slate-800">4. Optimization</h3>
                <p class="mt-2 text-xs text-slate-500">Clear all caches to ensure logic and style fresh sync.</p>
                <form action="{{ route('admin.maintenance.optimize') }}" method="POST" class="mt-10">
                    @csrf
                    <button type="submit" class="w-full rounded-xl py-2 text-xs font-semibold text-white transition hover:opacity-90 shadow-sm" style="background-color: #0b3019;">
                        Full Sync
                    </button>
                </form>
            </article>
        </div>

        <!-- Suspicious Account Review Section -->
        @if(count($potentialDummies) > 0)
        <section id="dummy-review" class="mt-10 rounded-3xl border border-slate-200 bg-white p-8 shadow-sm" x-data="{ selected: [] }">
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
                <div>
                    <h3 class="text-xl font-bold text-slate-900">Suspicious Account Review</h3>
                    <p class="text-sm text-slate-500">Accounts using non-standard emails. Excludes @st.umat, @umat, @gmail, @outlook, @yahoo, and @icloud.</p>
                </div>
                <div class="flex items-center gap-3">
                    <form action="{{ route('admin.maintenance.delete-dummies') }}" method="POST" onsubmit="return confirm('Delete all detected accounts listed below and their dues?')">
                        @csrf
                        <button type="submit" class="rounded-xl bg-red-50 px-4 py-2 text-xs font-bold text-red-600 hover:bg-red-100 transition whitespace-nowrap">
                            Wipe Everything
                        </button>
                    </form>
                    <form action="{{ route('admin.maintenance.delete-dummies') }}" method="POST" x-show="selected.length > 0" onsubmit="return confirm('Delete ' + selected.length + ' selected accounts and their dues?')">
                        @csrf
                        <template x-for="id in selected" :key="id">
                            <input type="hidden" name="ids[]" :value="id">
                        </template>
                        <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2 text-xs font-bold text-white hover:bg-indigo-700 transition whitespace-nowrap">
                            Delete Selected (<span x-text="selected.length"></span>)
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="overflow-hidden rounded-2xl border border-slate-100">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] font-bold">
                        <tr>
                            <th class="px-4 py-3 w-10">
                                <input type="checkbox" @change="if($el.checked) { selected = [ @foreach($potentialDummies as $d) '{{ $d->user_id }}', @endforeach ] } else { selected = [] }" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                            </th>
                            <th class="px-4 py-3">Student</th>
                            <th class="px-4 py-3">Email</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($potentialDummies as $dummy)
                        <tr class="transition hover:bg-slate-50/50" :class="selected.includes('{{ $dummy->user_id }}') ? 'bg-indigo-50/30' : ''">
                            <td class="px-4 py-4">
                                <input type="checkbox" :value="'{{ $dummy->user_id }}'" x-model="selected" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                            </td>
                            <td class="px-4 py-4">
                                <div class="font-bold text-slate-900">{{ $dummy->fullname }}</div>
                                <div class="text-[10px] text-slate-400">@ {{ $dummy->username }}</div>
                            </td>
                            <td class="px-4 py-4 text-slate-600 truncate max-w-[180px]">{{ $dummy->email }}</td>
                            <td class="px-4 py-4">
                                @if($dummy->email_verified_at)
                                    <span class="inline-flex rounded-full bg-emerald-50 px-2 py-0.5 text-[10px] font-bold text-emerald-600">VERIFIED</span>
                                @else
                                    <span class="inline-flex rounded-full bg-rose-50 px-2 py-0.5 text-[10px] font-bold text-rose-600">UNVERIFIED</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-right">
                                <form action="{{ route('admin.maintenance.delete-dummies') }}" method="POST" class="inline" onsubmit="return confirm('Delete this account and its dues?')">
                                    @csrf
                                    <input type="hidden" name="ids[]" value="{{ $dummy->user_id }}">
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-semibold text-xs">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
        @endif

        <div class="mt-10 grid gap-6 lg:grid-cols-2">
            <!-- Sync Missing -->
            <article class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
                <h3 class="text-lg font-semibold text-slate-800">5. Sync Missing Dues</h3>
                <p class="mt-2 text-sm text-slate-500">Only verified students (@st.umat.edu.gh) who have verified emails will be targeted.</p>
                <form action="{{ route('admin.maintenance.sync-missing') }}" method="POST" class="mt-6 space-y-4">
                    @csrf
                    <div class="grid gap-4 sm:grid-cols-2">
                        <select name="academic_year" class="rounded-2xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            <option value="">Academic Year</option>
                            @foreach($academicYears as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
                        <select name="description" class="rounded-2xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            <option value="">Due Description</option>
                            @foreach($dueTypes as $type)
                                <option value="{{ $type }}">{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="w-full rounded-2xl py-3 text-sm font-semibold text-white transition hover:opacity-90 shadow-lg" style="background-color: #0b3019;">
                        Assign Missing Dues
                    </button>
                </form>
            </article>

            <!-- Global Amount Updater -->
            <article class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
                <h3 class="text-lg font-semibold text-slate-800">6. Global Amount Updater</h3>
                <p class="mt-2 text-sm text-slate-500">Update amounts for all "Owing" students in a specific class or year.</p>
                <form action="{{ route('admin.maintenance.update-amounts') }}" method="POST" class="mt-6 space-y-4">
                    @csrf
                    <div class="grid gap-4 sm:grid-cols-2">
                        <select name="class" class="rounded-2xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500" required>
                            @foreach($classes as $c)
                                <option value="{{ $c }}">{{ $c }}</option>
                            @endforeach
                        </select>
                        <select name="year" class="rounded-2xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="">All Years</option>
                            @foreach($years as $y)
                                <option value="{{ $y }}">Year {{ $y }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <select name="academic_year" class="rounded-2xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500" required>
                            @foreach($academicYears as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
                        <input type="number" name="amount" placeholder="New Amount (GHS)" class="rounded-2xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500" required step="0.01">
                    </div>
                    <select name="description" class="w-full rounded-2xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500" required>
                        <option value="">Select Due to Update</option>
                        @foreach($dueTypes as $type)
                            <option value="{{ $type }}">{{ $type }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="w-full rounded-2xl py-3 text-sm font-semibold text-white transition hover:opacity-90 shadow-lg" style="background-color: #0b3019;">
                        Update Global Amounts
                    </button>
                </form>
            </article>
        </div>

        <!-- Due Merger -->
        <article class="mt-10 rounded-3xl border border-slate-200 bg-white p-8 shadow-sm shadow-indigo-50/50">
            <h3 class="text-xl font-bold text-slate-900 flex items-center gap-3">
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 text-base">7</span>
                Due Merger (Harmonization)
            </h3>
            <p class="mt-2 text-sm text-slate-500">Unify fragmented due names (e.g., merge "GESA Dues" into "GESA 2024"). This transfers all payment history.</p>
            <form action="{{ route('admin.maintenance.merge-dues') }}" method="POST" class="mt-6 grid gap-6 md:grid-cols-3">
                @csrf
                <div class="space-y-1">
                    <label class="text-xs font-bold uppercase tracking-widest text-slate-400">Source (Delete)</label>
                    <select name="source_description" class="w-full rounded-2xl border-slate-200 text-sm focus:border-red-500 focus:ring-red-500" required>
                        @foreach($dueTypes as $type)
                            <option value="{{ $type }}">{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-bold uppercase tracking-widest text-slate-400">Target (Keep)</label>
                    <select name="target_description" class="w-full rounded-2xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500" required>
                        @foreach($dueTypes as $type)
                            <option value="{{ $type }}">{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-bold uppercase tracking-widest text-slate-400">Academic Year</label>
                    <select name="academic_year" class="w-full rounded-2xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        @foreach($academicYears as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="md:col-span-3 rounded-2xl py-4 text-sm font-bold text-white transition hover:opacity-90 shadow-xl uppercase tracking-widest" style="background-color: #0b3019;">
                    Execute Migration & Merge
                </button>
            </form>
        </article>

        <!-- Database Schema -->
        <article class="mt-10 rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            <h3 class="text-xl font-bold text-slate-900 flex items-center gap-3">
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-orange-50 text-orange-600 text-base">8</span>
                Database Storage & Schema
            </h3>
            <p class="mt-2 text-sm text-slate-500">Run safe database migrations to update the system schema or apply structural changes.</p>
            
            <div class="mt-8">
                <form action="{{ route('admin.maintenance.migrate') }}" method="POST">
                    @csrf
                    <button type="submit" class="rounded-2xl px-12 py-4 text-sm font-bold text-white transition hover:opacity-90 shadow-xl uppercase tracking-widest bg-orange-600 hover:bg-orange-700 w-full md:w-auto" onclick="return confirm('Ensure you have a database backup before running migrations. Proceed?')">
                        Run Database Migrations
                    </button>
                    <p class="mt-4 text-[10px] text-slate-400 italic flex items-center gap-2">
                        <i class="ri-information-line text-orange-500"></i>
                        This will run 'php artisan migrate --force' on the server.
                    </p>
                </form>
            </div>
        </article>
    </div>
</x-layouts.admin>
