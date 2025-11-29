@php
    use Illuminate\Support\Str;
    $title = 'Send announcement';
    $targetType = old('target_type', 'all');
@endphp

<x-layouts.admin :title="$title">
    <div class="mx-auto w-full max-w-5xl space-y-10 px-5 py-10 sm:px-6 lg:px-8">
        <header class="space-y-3 rounded-3xl border border-[#0b3019]/15 bg-white p-6 shadow-lg shadow-[#0b3019]/10">
            <p class="inline-flex items-center gap-2 rounded-full bg-[#0b3019]/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.25em] text-[#0b3019]">
                <i class="ri-megaphone-line text-base" aria-hidden="true"></i>
                Compose announcement
            </p>
            <h1 class="text-3xl font-semibold text-[#0b3019]">Share updates with students</h1>
            <p class="text-sm text-slate-600">Target all students or focus on a specific audience. Announcements are sent instantly via email.</p>
        </header>

        @include('dashboards.admin.announcements.partials.form', [
            'types' => $types,
            'priorities' => $priorities,
            'targetTypes' => $targetTypes,
            'options' => $options,
        ])
    </div>
</x-layouts.admin>


