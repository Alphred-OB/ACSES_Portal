<x-layouts.dashboard title="ACSES Developers">
    @push('styles')
        <style>
            @keyframes pulse-ring {
                0% { transform: scale(0.95); opacity: 0.5; }
                50% { transform: scale(1.1); opacity: 0.8; }
                100% { transform: scale(0.95); opacity: 0.5; }
            }
            .glow-pulse {
                animation: pulse-ring 2s infinite ease-in-out;
            }
            .grid-mesh {
                background-size: 40px 40px;
                background-image: 
                    linear-gradient(to right, rgba(11, 48, 25, 0.04) 1px, transparent 1px),
                    linear-gradient(to bottom, rgba(11, 48, 25, 0.04) 1px, transparent 1px);
            }
            .radial-overlay {
                background: radial-gradient(circle at 50% 50%, rgba(255,255,255,0) 0%, rgba(248,250,252,1) 85%);
            }
            .text-glow {
                text-shadow: 0 0 20px rgba(16, 185, 129, 0.15);
            }
            .terminal-font {
                font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            }
        </style>
    @endpush

    @php
        $hasContactRoute = \Illuminate\Support\Facades\Route::has('marketing.contact');
    @endphp

    <section class="relative overflow-hidden bg-slate-50/70 min-h-screen pb-20">
        <!-- Interactive & Decorative Ambient Backgrounds -->
        <div class="absolute inset-0 grid-mesh pointer-events-none"></div>
        <div class="absolute inset-0 radial-overlay pointer-events-none"></div>
        <div class="absolute -top-48 -left-48 h-[600px] w-[600px] rounded-full bg-emerald-500/10 blur-3xl pointer-events-none animate-float"></div>
        <div class="absolute top-[40%] -right-48 h-[700px] w-[700px] rounded-full bg-teal-500/5 blur-3xl pointer-events-none animate-float" style="animation-delay: -3s;"></div>

        <div class="relative mx-auto w-full max-w-[1600px] px-5 py-12 sm:px-6 lg:px-8">
            <div class="space-y-16">

                <!-- ================= BRAND HERO BANNER ================= -->
                <header class="mx-auto max-w-4xl text-center space-y-6 animate-fade-slide">
                    <span class="inline-flex items-center gap-2 rounded-full bg-emerald-50 border border-emerald-200/60 px-4 py-1.5 text-[10px] font-extrabold uppercase tracking-[0.25em] text-[#0b3019] shadow-sm">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                        ACSES Product Studio
                    </span>
                    <h1 class="text-4xl font-extrabold tracking-tight text-slate-900 sm:text-6xl text-glow leading-none">
                        Shaping the Digital Future <br class="hidden sm:inline">
                        <span class="text-[#0b3019]">of Student Experience</span>
                    </h1>
                    <p class="mx-auto max-w-2xl text-base leading-relaxed text-slate-500 sm:text-lg">
                        We design, build, and optimize every digital touchpoint inside the ACSES Portal, translating complex backend queries into sleek, hardware-accelerated user experiences.
                    </p>
                </header>

                <!-- ================= CORE DEVELOPER TEAM GRID ================= -->
                <div class="space-y-10">
                    <div class="text-center space-y-2">
                        <h2 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Meet the Architects</h2>
                        <p class="text-sm text-slate-500 max-w-lg mx-auto">The core team driving strategy, delivery, and system robustness.</p>
                    </div>

                    <div class="grid gap-8 md:grid-cols-3">
                        
                        <!-- Kingsley Adu -->
                        <article class="group relative flex h-full flex-col overflow-hidden rounded-[28px] border border-slate-200 bg-white/80 p-4 shadow-sm backdrop-blur-md transition-all duration-500 hover:shadow-xl hover:border-emerald-500/20 hover:-translate-y-1 animate-fade-slide animate-fade-slide-delay-200">
                            <!-- Image Frame -->
                            <div class="relative h-72 w-full overflow-hidden rounded-2xl bg-slate-950">
                                <img src="{{ asset('assets/images/Kingsley.jpg') }}" alt="Kingsley Adu" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105" loading="lazy">
                                <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-900/10 to-transparent"></div>
                                <div class="absolute bottom-4 left-4 right-4">
                                    <h3 class="text-xl font-bold text-white tracking-tight">Kingsley Adu</h3>
                                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-emerald-300 mt-0.5">Project Lead · Full-stack</p>
                                </div>
                            </div>

                            <div class="flex flex-1 flex-col gap-6 px-3 pt-5">
                                <p class="text-sm leading-relaxed text-slate-500">
                                    Architects the end-to-end portal schema, building rigid auth policies, secure middle-tier guards, and coordinating general delivery.
                                </p>
                                <div class="mt-auto pt-4 border-t border-slate-100">
                                    <a href="https://www.linkedin.com/in/kingsley-aduhene-778538224/" target="_blank" rel="noopener" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-50/50 border border-emerald-200/50 px-4 py-2.5 text-xs font-bold text-emerald-800 transition-all duration-200 hover:bg-emerald-600 hover:text-white hover:border-emerald-600 active:scale-98">
                                        <i class="ri-linkedin-box-fill text-base"></i>
                                        <span>LinkedIn Profile</span>
                                    </a>
                                </div>
                            </div>
                        </article>

                        <!-- Alfred Boakye -->
                        <article class="group relative flex h-full flex-col overflow-hidden rounded-[28px] border border-slate-200 bg-white/80 p-4 shadow-sm backdrop-blur-md transition-all duration-500 hover:shadow-xl hover:border-emerald-500/20 hover:-translate-y-1 animate-fade-slide animate-fade-slide-delay-400">
                            <!-- Image Frame -->
                            <div class="relative h-72 w-full overflow-hidden rounded-2xl bg-slate-950">
                                <img src="{{ asset('assets/images/Alfred.png') }}" alt="Alfred Boakye" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105" loading="lazy">
                                <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-900/10 to-transparent"></div>
                                <div class="absolute bottom-4 left-4 right-4">
                                    <h3 class="text-xl font-bold text-white tracking-tight">Alfred Boakye</h3>
                                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-emerald-300 mt-0.5">Lead Portal Engineer</p>
                                </div>
                            </div>

                            <div class="flex flex-1 flex-col gap-6 px-3 pt-5">
                                <p class="text-sm leading-relaxed text-slate-500">
                                    Translates complex student workflows into robust relational models and controllers, backing operations with strict caching engines.
                                </p>
                                <div class="mt-auto pt-4 border-t border-slate-100">
                                    <a href="https://www.linkedin.com/in/alfredboakye/" target="_blank" rel="noopener" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-50/50 border border-emerald-200/50 px-4 py-2.5 text-xs font-bold text-emerald-800 transition-all duration-200 hover:bg-emerald-600 hover:text-white hover:border-emerald-600 active:scale-98">
                                        <i class="ri-linkedin-box-fill text-base"></i>
                                        <span>LinkedIn Profile</span>
                                    </a>
                                </div>
                            </div>
                        </article>

                        <!-- Obed Acquah -->
                        <article class="group relative flex h-full flex-col overflow-hidden rounded-[28px] border border-slate-200 bg-white/80 p-4 shadow-sm backdrop-blur-md transition-all duration-500 hover:shadow-xl hover:border-emerald-500/20 hover:-translate-y-1 animate-fade-slide animate-fade-slide-delay-600">
                            <!-- Image Frame -->
                            <div class="relative h-72 w-full overflow-hidden rounded-2xl bg-slate-950">
                                <img src="{{ asset('assets/images/Obed.jpeg') }}" alt="Obed Acquah" class="h-full w-full object-cover object-top transition-transform duration-700 group-hover:scale-105" loading="lazy">
                                <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-900/10 to-transparent"></div>
                                <div class="absolute bottom-4 left-4 right-4">
                                    <h3 class="text-xl font-bold text-white tracking-tight">Obed Acquah</h3>
                                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-emerald-300 mt-0.5">Web Experience Engineer</p>
                                </div>
                            </div>

                            <div class="flex flex-1 flex-col gap-6 px-3 pt-5">
                                <p class="text-sm leading-relaxed text-slate-500">
                                    Crafts the responsive client layers, styling consistent and fast components that breathe alive under rich animations and gestures.
                                </p>
                                <div class="mt-auto pt-4 border-t border-slate-100">
                                    <a href="https://www.linkedin.com/in/obed-acquah-017687301/" target="_blank" rel="noopener" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-50/50 border border-emerald-200/50 px-4 py-2.5 text-xs font-bold text-emerald-800 transition-all duration-200 hover:bg-emerald-600 hover:text-white hover:border-emerald-600 active:scale-98">
                                        <i class="ri-linkedin-box-fill text-base"></i>
                                        <span>LinkedIn Profile</span>
                                    </a>
                                </div>
                            </div>
                        </article>

                    </div>
                </div>

                <!-- ================= PARTNERSHIP CALL TO ACTION ================= -->
                <div 
                    x-data="{ 
                        email: '', 
                        submitted: false, 
                        loading: false, 
                        submitForm() {
                            if (!this.email) return;
                            this.loading = true;
                            setTimeout(() => {
                                this.loading = false;
                                this.submitted = true;
                                this.email = '';
                            }, 1200);
                        }
                    }"
                    class="relative overflow-hidden rounded-[36px] border border-[#0b3019]/15 bg-gradient-to-br from-[#0b3019] via-[#114127] to-[#0b3019] px-8 py-16 text-center text-white shadow-xl shadow-[#0b3019]/25 animate-fade-slide animate-fade-slide-delay-600"
                >
                    <!-- Background ambient flares -->
                    <div class="absolute -top-32 -left-32 h-96 w-96 rounded-full bg-emerald-400/10 blur-3xl pointer-events-none"></div>
                    <div class="absolute -bottom-32 -right-32 h-96 w-96 rounded-full bg-emerald-400/10 blur-3xl pointer-events-none"></div>
                    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(255,255,255,0.15),_transparent_65%)] pointer-events-none"></div>
                    
                    <div class="relative space-y-6 max-w-2xl mx-auto z-10">
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-white/10 px-3 py-1 text-[10px] font-bold uppercase tracking-widest text-emerald-300 border border-white/5 backdrop-blur-sm">
                            Connect with us
                        </span>
                        <h2 class="text-3xl font-extrabold sm:text-4xl tracking-tight leading-none text-white">Need help shaping portal tools?</h2>
                        <p class="mx-auto max-w-lg text-sm leading-relaxed text-emerald-100/80">
                            Our product studio collaborates with departmental chairs and administrative heads to design secure, highly resilient student tooling.
                        </p>
                        
                        <!-- Real-time interactive newsletter/lead form -->
                        <div class="max-w-md mx-auto pt-4">
                            <form @submit.preventDefault="submitForm" class="flex flex-col sm:flex-row gap-2.5 relative">
                                <div class="relative flex-1">
                                    <i class="ri-mail-line absolute left-4 top-1/2 -translate-y-1/2 text-lg transition-colors duration-300" :class="email ? 'text-white' : 'text-emerald-200/50'"></i>
                                    <input 
                                        type="email" 
                                        x-model="email" 
                                        placeholder="Enter your administrative email" 
                                        required 
                                        :disabled="submitted || loading"
                                        class="w-full rounded-2xl bg-white/15 border border-white/20 pl-11 pr-4 py-3.5 text-xs font-medium text-white placeholder-emerald-100/60 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:bg-white/20 focus:border-transparent transition duration-300 disabled:opacity-50"
                                    />
                                </div>
                                <button 
                                    type="submit" 
                                    :disabled="submitted || loading || !email"
                                    class="rounded-2xl px-6 py-3.5 text-xs font-bold transition-all duration-300 flex items-center justify-center gap-2 border"
                                    :class="email && !loading && !submitted ? 'bg-white text-[#0b3019] border-white shadow-lg shadow-emerald-950/20 hover:-translate-y-0.5 hover:shadow-xl active:scale-95' : 'bg-white/10 text-emerald-100/40 border-white/10 cursor-not-allowed'"
                                >
                                    <span x-show="loading" class="animate-spin h-4 w-4 border-2 border-white border-t-transparent rounded-full"></span>
                                    <span x-show="!loading" x-text="submitted ? 'Request Sent!' : 'Start Consultation'">Start Consultation</span>
                                </button>
                            </form>
                            
                            <!-- Custom Success Toast/Banner -->
                            <div 
                                x-show="submitted" 
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 translate-y-2"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                class="mt-4 p-3.5 rounded-2xl bg-emerald-500/20 border border-emerald-400/30 text-emerald-200 text-xs font-medium flex items-center gap-2 justify-center"
                            >
                                <i class="ri-checkbox-circle-fill text-emerald-400 text-lg"></i>
                                <span>Inquiry successfully logged! A representative will connect shortly.</span>
                            </div>
                        </div>

                        <!-- Secondary CTAs -->
                        <div class="mt-6 flex flex-wrap justify-center gap-4 text-xs font-bold pt-4">
                            <a href="mailto:hello@acses.edu" class="inline-flex items-center gap-2 text-white/80 hover:text-white transition">
                                <i class="ri-mail-open-line text-lg"></i>
                                <span>hello@acses.edu</span>
                            </a>
                            @if ($hasContactRoute)
                                <span class="text-white/30 hidden sm:inline">|</span>
                                <a href="{{ route('marketing.contact') }}" class="inline-flex items-center gap-2 text-white/80 hover:text-white transition">
                                    <i class="ri-calendar-event-line text-lg"></i>
                                    <span>Schedule a discovery call</span>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-white pointer-events-none"></div>
    </section>
</x-layouts.dashboard>
