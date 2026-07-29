@php
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Storage;

    // Admin user profile for sidebar footer
    $sidebarAdmin = auth()->guard('admin')->user() ?? auth()->user();
    $sidebarAdminName = $sidebarAdmin ? ($sidebarAdmin->fullname ?? $sidebarAdmin->username ?? 'Administrator') : 'Administrator';
    $sidebarAdminEmail = $sidebarAdmin?->email ?? '';
    $sidebarAdminInitial = Str::upper(Str::substr($sidebarAdminName, 0, 1));
    $sidebarAdminAvatar = null;
    if ($sidebarAdmin && $sidebarAdmin->profile_picture) {
        $pic = $sidebarAdmin->profile_picture;
        if (Str::startsWith($pic, ['http://', 'https://'])) {
            $sidebarAdminAvatar = $pic;
        } else {
            $path = ltrim($pic, '/');
            $sidebarAdminAvatar = Storage::disk('public')->exists($path) ? asset('storage/' . $path) : null;
        }
    }
@endphp
@php
    // Get pending registrations count
    try {
        $pendingRegistrationsCount = \App\Models\PendingRegistration::pending()->count();
    } catch (\Exception $e) {
        $pendingRegistrationsCount = 0;
    }

    // Get pending dues verifications count
    try {
        $pendingDuesCount = \App\Models\Due::where('payment_status', 'pending_verification')
            ->where('payment_method', 'manual')
            ->count();
    } catch (\Exception $e) {
        $pendingDuesCount = 0;
    }

    $navConfig = [
        [
            'section' => 'Overview & Accounts',
            'items' => [
                [
                    'label' => 'Dashboard Overview',
                    'route_name' => 'admin.dashboard',
                    'pattern' => 'admin.dashboard',
                    'icon' => 'ri-layout-grid-line',
                ],
                [
                    'label' => 'Pending Registrations',
                    'route_name' => 'admin.pending-registrations.index',
                    'pattern' => 'admin.pending-registrations.*',
                    'icon' => 'ri-user-received-2-line',
                    'badge' => $pendingRegistrationsCount > 0 ? $pendingRegistrationsCount : null,
                    'badge_color' => 'bg-amber-500',
                    'href' => url('/admin/pending-registrations'),
                ],
                [
                    'label' => 'Student Accounts',
                    'route_name' => 'admin.students.index',
                    'pattern' => 'admin.students.*',
                    'icon' => 'ri-team-line',
                ],
            ]
        ],
        [
            'section' => 'Financial Operations',
            'items' => [
                [
                    'label' => 'Dues & Fees',
                    'route_name' => 'admin.dues.index',
                    'pattern' => 'admin.dues.index',
                    'icon' => 'ri-coins-line',
                ],
                [
                    'label' => 'Payment Verifications',
                    'route_name' => 'admin.dues.verifications.index',
                    'pattern' => 'admin.dues.verifications.*',
                    'icon' => 'ri-file-shield-2-line',
                    'badge' => $pendingDuesCount > 0 ? $pendingDuesCount : null,
                    'badge_color' => 'bg-emerald-600',
                    'href' => url('/admin/dues/verifications'),
                ],
            ]
        ],
        [
            'section' => 'Academic & Content',
            'items' => [
                [
                    'label' => 'Events Calendar',
                    'route_name' => 'admin.events.index',
                    'pattern' => 'admin.events.*',
                    'icon' => 'ri-calendar-event-line',
                ],
                [
                    'label' => 'Announcements',
                    'route_name' => 'admin.announcements.index',
                    'pattern' => 'admin.announcements.*',
                    'icon' => 'ri-megaphone-line',
                ],
                [
                    'label' => 'Academic Timeline',
                    'route_name' => 'admin.timeline.index',
                    'pattern' => 'admin.timeline.*',
                    'icon' => 'ri-time-line',
                ],
                [
                    'label' => 'Resources Library',
                    'route_name' => 'admin.resources.index',
                    'pattern' => 'admin.resources.*',
                    'icon' => 'ri-book-read-line',
                ],
                [
                    'label' => 'Feedback Hub',
                    'route_name' => 'admin.suggestions.index',
                    'pattern' => 'admin.suggestions.*',
                    'icon' => 'ri-message-3-line',
                ],
            ]
        ],
        [
            'section' => 'Settings',
            'items' => [
                [
                    'label' => 'Admin Profile',
                    'route_name' => 'admin.profile',
                    'pattern' => 'admin.profile*',
                    'icon' => 'ri-settings-3-line',
                ],
            ]
        ]
    ];

    $sections = collect($navConfig)->map(function ($section) {
        $items = collect($section['items'])->map(function ($item) {
            // Use direct href if provided, otherwise try route
            if (!empty($item['href'])) {
                $href = $item['href'];
            } else {
                $routeName = $item['route_name'] ?? null;
                $href = ($routeName && \Illuminate\Support\Facades\Route::has($routeName)) ? route($routeName) : '#';
            }

            return [
                'label' => $item['label'],
                'icon' => $item['icon'],
                'href' => $href,
                'active' => ! empty($item['pattern']) ? request()->routeIs($item['pattern']) : false,
                'badge' => $item['badge'] ?? null,
                'badge_color' => $item['badge_color'] ?? 'bg-rose-500',
            ];
        })->toArray();

        return [
            'section' => $section['section'],
            'items' => $items,
        ];
    })->toArray();
@endphp

<aside
    class="hidden shrink-0 border-r border-slate-100 bg-white py-6 text-sm text-slate-600 lg:flex lg:flex-col lg:sticky lg:top-0 lg:h-screen lg:overflow-y-auto transition-all duration-300 relative"
    :class="adminSidebarCollapsed ? 'w-20 px-3' : 'w-64 px-5'"
    aria-label="Admin navigation"
>
    <!-- Collapse / Expand Toggle Button for Desktop (Notion / Linear style floating on border edge) -->
    <button type="button" 
            @click="adminSidebarCollapsed = !adminSidebarCollapsed; localStorage.setItem('admin-sidebar-collapsed', adminSidebarCollapsed)"
            class="hidden lg:flex absolute top-7 z-40 h-7 w-7 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-400 hover:text-[#0b3019] shadow-sm hover:scale-105 transition-all duration-150"
            style="right: -14px;"
            :title="adminSidebarCollapsed ? 'Expand sidebar' : 'Collapse sidebar'">
        <i class="text-xs transition-transform duration-300" :class="adminSidebarCollapsed ? 'ri-arrow-right-s-line' : 'ri-arrow-left-s-line'"></i>
    </button>

    <div class="flex h-full flex-col">
        <!-- Sidebar Header -->
        <div class="flex items-center text-[#0b3019]" :class="adminSidebarCollapsed ? 'justify-center' : 'gap-3'">
            <img src="{{ asset('logo.png') }}" alt="ACSES" class="h-10 w-10 rounded-xl border border-[#0b3019]/20 object-contain" loading="lazy">
            <div x-show="!adminSidebarCollapsed" x-transition:enter="transition-opacity duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="min-w-0">
                <p class="text-xs font-semibold uppercase tracking-[0.35em] text-[#0b3019]/70 truncate">ACSES Admin</p>
                <p class="text-base font-semibold text-[#0b3019] truncate">Control Center</p>
            </div>
        </div>

        <x-admin.sidebar-nav :sections="$sections" class="mt-8 flex-1" />

        <!-- Admin Profile Footer -->
        <div class="mt-4 border-t border-slate-100 pt-4">
            <div class="flex items-center rounded-xl py-2 hover:bg-slate-50 transition-colors duration-150 group"
                 :class="adminSidebarCollapsed ? 'justify-center px-1' : 'gap-3 px-2'">
                <span class="flex h-8 w-8 shrink-0 items-center justify-center overflow-hidden rounded-full border border-[#0b3019]/15 bg-[#0b3019]/8 text-xs font-bold text-[#0b3019]">
                    @if ($sidebarAdminAvatar)
                        <img src="{{ $sidebarAdminAvatar }}" alt="{{ $sidebarAdminName }}" class="h-full w-full object-cover">
                    @else
                        {{ $sidebarAdminInitial }}
                    @endif
                </span>
                <div class="min-w-0 flex-1" x-show="!adminSidebarCollapsed">
                    <p class="truncate text-xs font-bold leading-tight text-slate-800">{{ Str::limit($sidebarAdminName, 18) }}</p>
                    <p class="truncate text-[10px] leading-none text-slate-400 mt-0.5">{{ Str::limit($sidebarAdminEmail, 22) }}</p>
                </div>
                <form method="POST" action="{{ route('auth.logout') }}" class="shrink-0" x-show="!adminSidebarCollapsed">
                    @csrf
                    <button type="submit" class="flex h-7 w-7 items-center justify-center rounded-lg text-slate-400 transition-colors hover:bg-rose-50 hover:text-rose-600 active:scale-95" title="Sign out">
                        <i class="ri-logout-box-r-line text-sm" aria-hidden="true"></i>
                    </button>
                </form>
            </div>
            
            <!-- Collapsed Logout Button (shown below avatar when collapsed) -->
            <div x-show="adminSidebarCollapsed" class="mt-2 flex justify-center">
                <form method="POST" action="{{ route('auth.logout') }}">
                    @csrf
                    <button type="submit" class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-100 text-slate-400 transition-colors hover:bg-rose-50 hover:text-rose-600 active:scale-95" title="Sign out">
                        <i class="ri-logout-box-r-line text-sm" aria-hidden="true"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</aside>

<div class="lg:hidden">
    <div x-show="adminSidebarOpen" style="display: none;" x-transition.opacity class="fixed inset-0 z-40 bg-slate-900/60" aria-hidden="true" @click="adminSidebarOpen = false"></div>

    <div id="admin-mobile-sidebar" x-show="adminSidebarOpen" style="display: none;" x-transition:enter="transition transform ease-out duration-200" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition transform ease-in duration-150" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="fixed inset-y-0 left-0 z-50 w-64 overflow-y-auto border-r border-slate-100 bg-white px-5 py-6 text-sm text-slate-600 shadow-xl">
        <div class="flex items-center justify-between text-[#0b3019]">
            <div class="flex items-center gap-3">
                <img src="{{ asset('logo.png') }}" alt="ACSES" class="h-9 w-9 rounded-xl border border-[#0b3019]/20 object-contain" loading="lazy">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.35em] text-[#0b3019]/70">Admin</p>
                    <p class="text-base font-semibold text-[#0b3019]">Control Center</p>
                </div>
            </div>
            <button type="button" class="rounded-full border border-slate-200/70 p-2 text-slate-500 transition hover:text-[#0b3019]" aria-label="Close sidebar" @click="adminSidebarOpen = false">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <path d="m6 6 12 12" />
                    <path d="m6 18 12-12" />
                </svg>
            </button>
        </div>

        <x-admin.sidebar-nav :sections="$sections" class="mt-6 flex-1" />

        <!-- Admin Profile Footer (Mobile) -->
        <div class="mt-4 border-t border-slate-100 pt-4">
            <div class="flex items-center gap-3 rounded-xl px-2 py-2 hover:bg-slate-50 transition-colors duration-150">
                <span class="flex h-8 w-8 shrink-0 items-center justify-center overflow-hidden rounded-full border border-[#0b3019]/15 bg-[#0b3019]/8 text-xs font-bold text-[#0b3019]">
                    @if ($sidebarAdminAvatar)
                        <img src="{{ $sidebarAdminAvatar }}" alt="{{ $sidebarAdminName }}" class="h-full w-full object-cover">
                    @else
                        {{ $sidebarAdminInitial }}
                    @endif
                </span>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-xs font-bold leading-tight text-slate-800">{{ Str::limit($sidebarAdminName, 18) }}</p>
                    <p class="truncate text-[10px] leading-none text-slate-400 mt-0.5">{{ Str::limit($sidebarAdminEmail, 22) }}</p>
                </div>
                <form method="POST" action="{{ route('auth.logout') }}">
                    @csrf
                    <button type="submit" class="flex h-7 w-7 items-center justify-center rounded-lg text-slate-400 transition-colors hover:bg-rose-50 hover:text-rose-600 active:scale-95" title="Sign out">
                        <i class="ri-logout-box-r-line text-sm" aria-hidden="true"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
