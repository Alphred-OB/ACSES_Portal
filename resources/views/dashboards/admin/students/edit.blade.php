@php($title = 'Edit student account')

<x-layouts.admin :title="$title">
    <div class="mx-auto w-full max-w-4xl space-y-8 px-5 py-10 sm:px-6 lg:px-8">
        <header class="space-y-3 text-center sm:text-left">
            <p class="inline-flex items-center gap-2 rounded-full bg-[#0b3019]/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.25em] text-[#0b3019]">
                <i class="ri-profile-line text-base" aria-hidden="true"></i>
                Student accounts
            </p>
            <h1 class="text-3xl font-semibold text-[#0b3019]">Edit student account</h1>
            <p class="text-sm text-slate-600">Update profile information, credentials, or marketplace access for this student.</p>
        </header>

        <section class="rounded-3xl border border-[#0b3019]/15 bg-white p-6 shadow-lg shadow-[#0b3019]/10">
            <form method="POST" action="{{ route('admin.students.update', $student) }}" class="space-y-8">
                @csrf
                @method('PUT')

                @include('dashboards.admin.students.partials.form', [
                    'student' => $student,
                    'isEdit' => true,
                    'classOptions' => $classOptions ?? [],
                    'yearOptions' => $yearOptions ?? [],
                ])

                <div class="flex flex-col gap-3 border-t border-slate-200/70 pt-6 sm:flex-row sm:justify-between">
                    <a href="{{ route('admin.students.show', $student) }}" class="inline-flex items-center gap-2 rounded-full border border-slate-200/70 bg-white px-4 py-2 text-sm font-semibold text-slate-600 transition hover:border-[#0b3019]/40 hover:text-[#0b3019]">
                        <i class="ri-arrow-left-line text-base" aria-hidden="true"></i>
                        Back to profile
                    </a>
                    <div class="flex flex-wrap items-center gap-3">
                        <a href="{{ route('admin.students.index') }}" class="inline-flex items-center gap-2 rounded-full border border-slate-200/70 bg-white px-4 py-2 text-sm font-semibold text-slate-600 transition hover:border-[#0b3019]/40 hover:text-[#0b3019]">
                            <i class="ri-home-4-line text-base" aria-hidden="true"></i>
                            All students
                        </a>
                        <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-[#0b3019] px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-[#0b3019]/20 transition hover:-translate-y-0.5 hover:shadow-xl">
                            <i class="ri-save-line text-base" aria-hidden="true"></i>
                            Save changes
                        </button>
                    </div>
                </div>
            </form>
        </section>
    </div>
</x-layouts.admin>
