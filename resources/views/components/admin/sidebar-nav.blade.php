@props(['navItems'])

<nav {{ $attributes->class(['flex flex-col gap-1']) }}>
    @foreach ($navItems as $item)
        <a href="{{ $item['href'] }}" @class([
            'flex items-center gap-3 rounded-2xl px-4 py-3 font-semibold transition',
            'bg-[#0b3019] text-white shadow-lg shadow-[#0b3019]/20' => $item['active'],
            'text-slate-600 hover:bg-[#0b3019]/5 hover:text-[#0b3019]' => ! $item['active'],
        ])>
            <span @class([
                'flex h-9 w-9 items-center justify-center rounded-xl border transition',
                'border-white/50 bg-white/10 text-white' => $item['active'],
                'border-[#0b3019]/15 bg-white text-[#0b3019]/70 hover:border-[#0b3019]/40' => ! $item['active'],
            ])>
                <i class="{{ $item['icon'] }} text-lg" aria-hidden="true"></i>
            </span>
            <span>{{ $item['label'] }}</span>
        </a>
    @endforeach
</nav>
