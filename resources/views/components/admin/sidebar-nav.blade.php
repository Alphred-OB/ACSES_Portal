@props(['sections'])

<nav {{ $attributes->class(['flex flex-col gap-6']) }}>
    @foreach ($sections as $section)
        <div class="space-y-2">
            <!-- Section Header -->
            @if(!empty($section['section']))
                <div class="flex items-center gap-2 px-3 mb-1" x-show="!adminSidebarCollapsed">
                    <span class="h-1.5 w-1.5 rounded-full bg-[#0b3019]/30"></span>
                    <h4 class="text-[10px] font-bold uppercase tracking-[0.15em] text-[#0b3019]/60">
                        {{ $section['section'] }}
                    </h4>
                </div>
                <!-- Collapsed Section Divider Line -->
                <div class="border-t border-slate-100/60 my-2 mx-2" x-show="adminSidebarCollapsed"></div>
            @endif

            <div class="flex flex-col gap-1">
                @foreach ($section['items'] as $item)
                    <a href="{{ $item['href'] }}" 
                       @class([
                           'group flex items-center rounded-xl py-2 font-medium transition-all duration-200 border relative',
                           'bg-[#0b3019]/5 border-[#0b3019]/10 text-[#0b3019] shadow-sm shadow-[#0b3019]/5' => $item['active'],
                           'border-transparent text-slate-600 hover:bg-slate-50 hover:text-[#0b3019]' => ! $item['active'],
                       ])
                       :class="adminSidebarCollapsed ? 'justify-center px-1' : 'gap-3 px-3'"
                       title="{{ $item['label'] }}"
                    >
                        <!-- Icon Container with vibrant active pop -->
                        <span @class([
                            'flex h-8 w-8 items-center justify-center rounded-lg transition-all duration-200 shrink-0 shadow-sm relative',
                            'bg-[#0b3019] text-white' => $item['active'],
                            'bg-slate-100 text-slate-500 group-hover:bg-[#0b3019]/10 group-hover:text-[#0b3019] group-hover:scale-105' => ! $item['active'],
                        ])>
                            <i class="{{ $item['icon'] }} text-base" aria-hidden="true"></i>
                            
                            <!-- Collapsed status notification dot overlay -->
                            @if (!empty($item['badge']))
                                <span x-show="adminSidebarCollapsed" class="absolute -top-0.5 -right-0.5 flex h-2.5 w-2.5 rounded-full border border-white {{ $item['badge_color'] ?? 'bg-rose-500' }}"></span>
                            @endif
                        </span>
                        
                        <span class="flex-1 text-sm font-semibold tracking-tight" x-show="!adminSidebarCollapsed">{{ $item['label'] }}</span>
                        
                        @if (!empty($item['badge']))
                            <span x-show="!adminSidebarCollapsed" @class([
                                'flex h-5 min-w-5 items-center justify-center rounded-full px-2 text-[10px] font-bold tracking-tight border',
                                'bg-[#0b3019]/15 border-[#0b3019]/10 text-[#0b3019]' => $item['active'],
                                'bg-rose-50 border-rose-100 text-rose-600' => ! $item['active'] && (str_contains($item['badge_color'] ?? '', 'rose') || str_contains($item['badge_color'] ?? '', 'red')),
                                'bg-amber-50 border-amber-100 text-amber-600' => ! $item['active'] && str_contains($item['badge_color'] ?? '', 'amber'),
                                'bg-emerald-50 border-emerald-100 text-emerald-600' => ! $item['active'] && (str_contains($item['badge_color'] ?? '', 'emerald') || str_contains($item['badge_color'] ?? '', 'green')),
                            ])>
                                {{ $item['badge'] > 99 ? '99+' : $item['badge'] }}
                            </span>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>
    @endforeach
</nav>

