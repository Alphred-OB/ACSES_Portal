@props(['navItems'])

<nav {{ $attributes->class(['flex flex-col gap-1']) }}>
    @foreach ($navItems as $item)
        <a href="{{ $item['href'] }}" @class([
            'flex items-center gap-3 rounded-2xl px-4 py-3 font-semibold transition relative',
            'bg-[#0b3019] text-white shadow-lg shadow-[#0b3019]/20' => $item['active'],
            'text-slate-600 hover:bg-[#0b3019]/5 hover:text-[#0b3019]' => ! $item['active'],
        ])>
            <span @class([
                'flex h-9 w-9 items-center justify-center rounded-xl border transition relative',
                'border-white/50 bg-white/10 text-white' => $item['active'],
                'border-[#0b3019]/15 bg-white text-[#0b3019]/70 hover:border-[#0b3019]/40' => ! $item['active'],
            ])>
                <i class="{{ $item['icon'] }} text-lg" aria-hidden="true"></i>
                @if (!empty($item['badge']))
                    <span class="absolute -right-1.5 -top-1.5 flex h-5 min-w-5 items-center justify-center rounded-full {{ $item['badge_color'] ?? 'bg-rose-500' }} px-1.5 text-[10px] font-bold text-white shadow-sm">
                        {{ $item['badge'] > 99 ? '99+' : $item['badge'] }}
                    </span>
                @endif
            </span>
            <span class="flex-1">{{ $item['label'] }}</span>
            @if (!empty($item['badge']))
                <span class="flex h-6 min-w-6 items-center justify-center rounded-full {{ $item['active'] ? 'bg-white/20 text-white' : $item['badge_color'] ?? 'bg-rose-500' . ' text-white' }} px-2 text-xs font-bold">
                    {{ $item['badge'] > 99 ? '99+' : $item['badge'] }}
                </span>
            @endif
        </a>
    @endforeach
</nav>
