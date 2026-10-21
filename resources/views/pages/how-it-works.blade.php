@extends('layouts.app')

@section('title', 'How It Works - Global Nikkah | Halal Matrimonial Journey')

@section('content')
<div class="min-h-screen overflow-hidden bg-slate-50 text-slate-800 dark:bg-slate-950 dark:text-slate-100">
    <style>
        @keyframes how-it-works-pulse {
            0%, 100% { opacity: .45; transform: scale(.92); }
            50% { opacity: 1; transform: scale(1.08); }
        }

        @keyframes how-it-works-travel {
            0% { stroke-dashoffset: 72; opacity: 0; }
            15% { opacity: 1; }
            72% { opacity: 1; }
            100% { stroke-dashoffset: 0; opacity: 0; }
        }

        @keyframes how-it-works-float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }

        @keyframes how-it-works-beacon {
            0%, 100% { box-shadow: 0 0 0 0 rgba(52, 211, 153, .28); }
            50% { box-shadow: 0 0 0 14px rgba(52, 211, 153, 0); }
        }

        @keyframes how-it-works-draw-line {
            from { stroke-dashoffset: 420; }
            to { stroke-dashoffset: 0; }
        }

        .how-it-works-orbit { animation: how-it-works-pulse 3.6s ease-in-out infinite; transform-origin: center; }
        .how-it-works-packet { stroke-dasharray: 8 64; animation: how-it-works-travel 3.4s linear infinite; }
        .how-it-works-packet.delay-1 { animation-delay: 1.1s; }
        .how-it-works-packet.delay-2 { animation-delay: 2.2s; }
        .how-it-works-float { animation: how-it-works-float 5s ease-in-out infinite; }
        .how-it-works-float.delay-1 { animation-delay: 1.2s; }
        .how-it-works-float.delay-2 { animation-delay: 2.4s; }
        .how-it-works-beacon { animation: how-it-works-beacon 2.8s ease-out infinite; }
        .how-it-works-draw-line { stroke-dasharray: 420; animation: how-it-works-draw-line 2.8s ease-out both; }

        @media (prefers-reduced-motion: reduce) {
            .how-it-works-orbit,
            .how-it-works-packet,
            .how-it-works-float,
            .how-it-works-beacon,
            .how-it-works-draw-line { animation: none; }
        }
    </style>

    <!-- Hero -->
    <section class="relative isolate overflow-hidden bg-emerald-950 py-16 text-white sm:py-24">
        <div class="absolute inset-0 -z-10 bg-[radial-gradient(circle_at_15%_20%,rgba(16,185,129,.2),transparent_30%),radial-gradient(circle_at_85%_80%,rgba(245,158,11,.15),transparent_28%)]"></div>
        <div class="absolute -left-20 top-16 h-72 w-72 rounded-full border border-emerald-400/10"></div>
        <div class="absolute -right-24 bottom-0 h-96 w-96 rounded-full border border-amber-300/10"></div>

        <div class="mx-auto grid max-w-7xl items-center gap-14 px-4 sm:px-6 lg:grid-cols-12 lg:px-8">
            <div class="lg:col-span-5">
                <div class="mb-5 inline-flex items-center gap-2 rounded-full border border-emerald-400/30 bg-emerald-900/60 px-3.5 py-1.5 text-xs font-bold uppercase tracking-[.18em] text-emerald-200">
                    <span class="text-amber-300">✦</span>
                    A clear path to Nikah
                </div>
                <h1 class="font-heading text-4xl font-extrabold leading-[1.08] tracking-tight sm:text-6xl">
                    Three hearts.<br>
                    <span class="text-amber-300">One trusted circle.</span>
                </h1>
                <p class="mt-6 max-w-xl text-sm leading-7 text-emerald-100/75 sm:text-base">
                    Global Nikkah connects two serious seekers through a respectful, family-aware process—so a meaningful introduction can grow with clarity, safety, and Barakah.
                </p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('register') }}" class="rounded-xl bg-amber-400 px-5 py-3 text-sm font-extrabold text-emerald-950 shadow-xl shadow-amber-950/30 transition hover:-translate-y-0.5 hover:bg-amber-300">Start your profile &rarr;</a>
                    <a href="#journey" class="rounded-xl border border-white/20 bg-white/10 px-5 py-3 text-sm font-bold text-white transition hover:bg-white/15">Explore the journey</a>
                </div>
            </div>

            <!-- Animated relationship map -->
            <div class="relative lg:col-span-7" aria-label="Animated diagram showing two seekers and a Wali connected through Global Nikkah">
                <div class="relative mx-auto aspect-[1.25] max-w-2xl rounded-[2rem] border border-white/10 bg-white/[.045] p-4 shadow-2xl shadow-emerald-950/40 backdrop-blur sm:p-8">
                    <div class="absolute left-1/2 top-1/2 h-44 w-44 -translate-x-1/2 -translate-y-1/2 rounded-full bg-emerald-400/10 blur-3xl"></div>
                    <svg class="absolute inset-0 h-full w-full" viewBox="0 0 700 560" fill="none" role="img" aria-labelledby="relationship-map-title">
                        <title id="relationship-map-title">Two seekers and their Wali connected through a trusted matrimonial process</title>
                        <path d="M153 166 C235 210 270 238 325 274" stroke="rgba(110,231,183,.36)" stroke-width="2" stroke-linecap="round" />
                        <path d="M547 166 C465 210 430 238 375 274" stroke="rgba(110,231,183,.36)" stroke-width="2" stroke-linecap="round" />
                        <path d="M350 450 C350 393 350 362 350 318" stroke="rgba(251,191,36,.42)" stroke-width="2" stroke-linecap="round" />
                        <path class="how-it-works-packet" d="M153 166 C235 210 270 238 325 274" stroke="#6ee7b7" stroke-width="4" stroke-linecap="round" />
                        <path class="how-it-works-packet delay-1" d="M547 166 C465 210 430 238 375 274" stroke="#6ee7b7" stroke-width="4" stroke-linecap="round" />
                        <path class="how-it-works-packet delay-2" d="M350 450 C350 393 350 362 350 318" stroke="#fbbf24" stroke-width="4" stroke-linecap="round" />
                        <circle class="how-it-works-orbit" cx="350" cy="295" r="76" stroke="rgba(110,231,183,.26)" stroke-width="1" stroke-dasharray="3 9" />
                        <circle cx="350" cy="295" r="58" fill="#064e3b" stroke="#6ee7b7" stroke-opacity=".45" />
                        <path d="M350 260l7.8 15.8 17.4 2.5-12.6 12.3 3 17.4-15.6-8.2-15.6 8.2 3-17.4-12.6-12.3 17.4-2.5L350 260Z" fill="#fcd34d" />
                    </svg>

                    <div class="how-it-works-float absolute left-[4%] top-[8%] w-40 rounded-2xl border border-emerald-300/20 bg-slate-950/70 p-3 shadow-xl backdrop-blur sm:left-[8%] sm:w-48 sm:p-4">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-400/15 text-xl">👤</div>
                            <div><p class="text-xs font-extrabold text-white">Seeker A</p><p class="mt-0.5 text-[10px] text-emerald-200/60">Verified intention</p></div>
                        </div>
                        <div class="mt-3 flex items-center gap-1.5 text-[10px] font-bold text-emerald-300"><span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span> Profile ready</div>
                    </div>

                    <div class="how-it-works-float delay-1 absolute right-[4%] top-[8%] w-40 rounded-2xl border border-emerald-300/20 bg-slate-950/70 p-3 shadow-xl backdrop-blur sm:right-[8%] sm:w-48 sm:p-4">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-400/15 text-xl">👩</div>
                            <div><p class="text-xs font-extrabold text-white">Seeker B</p><p class="mt-0.5 text-[10px] text-emerald-200/60">Values aligned</p></div>
                        </div>
                        <div class="mt-3 flex items-center gap-1.5 text-[10px] font-bold text-amber-300"><span class="h-1.5 w-1.5 rounded-full bg-amber-300"></span> Mutual interest</div>
                    </div>

                    <div class="absolute left-1/2 top-1/2 w-36 -translate-x-1/2 -translate-y-1/2 text-center sm:w-44">
                        <p class="text-[10px] font-extrabold uppercase tracking-[.22em] text-emerald-200/60">Global Nikkah</p>
                        <p class="mt-1 text-sm font-extrabold text-white sm:text-base">Trusted introduction</p>
                    </div>

                    <div class="how-it-works-float delay-2 absolute bottom-[5%] left-1/2 w-48 -translate-x-1/2 rounded-2xl border border-amber-300/25 bg-slate-950/75 p-3 shadow-xl backdrop-blur sm:bottom-[7%] sm:w-56 sm:p-4">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-400/15 text-xl">🛡️</div>
                            <div><p class="text-xs font-extrabold text-white">Wali / Family</p><p class="mt-0.5 text-[10px] text-emerald-200/60">Visible when it matters</p></div>
                        </div>
                        <div class="mt-3 flex items-center gap-1.5 text-[10px] font-bold text-amber-300"><span class="h-1.5 w-1.5 rounded-full bg-amber-300"></span> Chaperoned clarity</div>
                    </div>

                    <div class="absolute left-1/2 top-3 -translate-x-1/2 rounded-full border border-white/10 bg-white/10 px-3 py-1 text-[9px] font-bold uppercase tracking-widest text-emerald-100/70 sm:top-5">Intent &amp; trust layer</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Interactive system walkthrough -->
    <section class="relative overflow-hidden border-b border-slate-200 bg-white py-16 dark:border-slate-800 dark:bg-slate-950 sm:py-24"
             x-data="{ activeStage: 1, timer: null }"
             x-init="timer = setInterval(() => { activeStage = activeStage === 6 ? 1 : activeStage + 1 }, 4800)"
             x-on:mouseenter="clearInterval(timer)"
             x-on:mouseleave="timer = setInterval(() => { activeStage = activeStage === 6 ? 1 : activeStage + 1 }, 4800)">
        <div class="absolute -right-32 top-20 h-80 w-80 rounded-full bg-emerald-100/60 blur-3xl dark:bg-emerald-950/30"></div>
        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-3xl text-center">
                <span class="text-xs font-extrabold uppercase tracking-[.2em] text-emerald-700 dark:text-emerald-400">See the system in motion</span>
                <h2 class="mt-3 font-heading text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white sm:text-5xl">A guided path, never a guessing game.</h2>
                <p class="mx-auto mt-4 max-w-2xl text-sm leading-6 text-slate-600 dark:text-slate-400">Follow each signal as it moves through the platform. Select a stage to see who acts, what unlocks, and how the Wali enters the circle.</p>
            </div>

            <div class="mt-12 overflow-hidden rounded-[2rem] border border-slate-200 bg-slate-50 shadow-2xl shadow-slate-900/5 dark:border-slate-800 dark:bg-slate-900/70">
                <div class="grid lg:grid-cols-[1.25fr_.75fr]">
                    <!-- Visual flow canvas -->
                    <div class="relative min-h-[520px] overflow-hidden border-b border-slate-200 p-5 dark:border-slate-800 sm:p-8 lg:border-b-0 lg:border-r">
                        <div class="absolute inset-0 opacity-60 [background-image:linear-gradient(rgba(16,185,129,.08)_1px,transparent_1px),linear-gradient(90deg,rgba(16,185,129,.08)_1px,transparent_1px)] [background-size:28px_28px] dark:opacity-30"></div>
                        <div class="relative flex items-center justify-between">
                            <div><p class="text-[10px] font-extrabold uppercase tracking-[.18em] text-slate-500 dark:text-slate-400">Live journey map</p><p class="mt-1 text-sm font-bold text-slate-900 dark:text-white">Trust builds one verified signal at a time</p></div>
                            <div class="flex items-center gap-1.5 rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-[10px] font-bold text-emerald-700 dark:border-emerald-900 dark:bg-emerald-950/50 dark:text-emerald-300"><span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Process active</div>
                        </div>

                        <div class="relative mx-auto mt-10 max-w-2xl">
                            <svg class="absolute inset-x-0 top-11 h-32 w-full" viewBox="0 0 720 140" fill="none" preserveAspectRatio="none" aria-hidden="true">
                                <path d="M70 70 C190 70 200 70 320 70 S520 70 650 70" stroke="rgba(148,163,184,.35)" stroke-width="3" stroke-linecap="round" />
                                <path class="how-it-works-draw-line" d="M70 70 C190 70 200 70 320 70 S520 70 650 70" stroke="#10b981" stroke-width="4" stroke-linecap="round" />
                                <circle cx="70" cy="70" r="7" fill="#10b981" /><circle cx="200" cy="70" r="7" fill="#f59e0b" /><circle cx="330" cy="70" r="7" fill="#0ea5e9" /><circle cx="460" cy="70" r="7" fill="#8b5cf6" /><circle cx="650" cy="70" r="7" fill="#f59e0b" />
                            </svg>

                            <div class="relative grid grid-cols-3 gap-3 sm:grid-cols-6 sm:gap-2">
                                @foreach([
                                    1 => ['icon' => '🪪', 'label' => 'Profile', 'tone' => 'emerald'],
                                    2 => ['icon' => '🛡️', 'label' => 'Verify', 'tone' => 'amber'],
                                    3 => ['icon' => '✨', 'label' => 'Match', 'tone' => 'sky'],
                                    4 => ['icon' => '💌', 'label' => 'Interest', 'tone' => 'purple'],
                                    5 => ['icon' => '👥', 'label' => 'Wali', 'tone' => 'amber'],
                                    6 => ['icon' => '🤝', 'label' => 'Families', 'tone' => 'emerald'],
                                ] as $stage => $item)
                                    <button type="button"
                                            @click="activeStage = {{ $stage }}"
                                            :aria-current="activeStage === {{ $stage }} ? 'step' : false"
                                            class="group flex flex-col items-center gap-2 text-center focus:outline-none">
                                        <span class="how-it-works-beacon relative flex h-14 w-14 items-center justify-center rounded-2xl border-4 border-slate-50 bg-white text-xl shadow-lg transition duration-300 group-hover:-translate-y-1 dark:border-slate-900 dark:bg-slate-800 sm:h-16 sm:w-16"
                                              :class="activeStage === {{ $stage }} ? 'ring-4 ring-emerald-300/60 dark:ring-emerald-800/80' : 'opacity-70 grayscale-[.35]'">{{ $item['icon'] }}</span>
                                        <span class="text-[10px] font-extrabold uppercase tracking-wider transition sm:text-xs"
                                              :class="activeStage === {{ $stage }} ? 'text-emerald-700 dark:text-emerald-400' : 'text-slate-500 dark:text-slate-400'">{{ $item['label'] }}</span>
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <div class="relative mx-auto mt-10 grid max-w-2xl gap-3 sm:grid-cols-3">
                            <div class="rounded-2xl border border-emerald-200 bg-emerald-50/80 p-4 dark:border-emerald-900/60 dark:bg-emerald-950/30"><p class="text-[10px] font-extrabold uppercase tracking-wider text-emerald-700 dark:text-emerald-400">Always protected</p><p class="mt-2 text-xs font-bold text-slate-800 dark:text-slate-200">Identity &amp; photo privacy</p><p class="mt-1 text-[11px] leading-5 text-slate-500 dark:text-slate-400">Haya-first defaults from the first visit.</p></div>
                            <div class="rounded-2xl border border-amber-200 bg-amber-50/80 p-4 dark:border-amber-900/60 dark:bg-amber-950/30"><p class="text-[10px] font-extrabold uppercase tracking-wider text-amber-700 dark:text-amber-400">Only when mutual</p><p class="mt-2 text-xs font-bold text-slate-800 dark:text-slate-200">Interest &amp; conversation</p><p class="mt-1 text-[11px] leading-5 text-slate-500 dark:text-slate-400">No cold messages or hidden pressure.</p></div>
                            <div class="rounded-2xl border border-sky-200 bg-sky-50/80 p-4 dark:border-sky-900/60 dark:bg-sky-950/30"><p class="text-[10px] font-extrabold uppercase tracking-wider text-sky-700 dark:text-sky-400">When it matters</p><p class="mt-2 text-xs font-bold text-slate-800 dark:text-slate-200">Wali &amp; family clarity</p><p class="mt-1 text-[11px] leading-5 text-slate-500 dark:text-slate-400">The next step becomes visible to families.</p></div>
                        </div>
                    </div>

                    <!-- Dynamic stage explanation -->
                    <div class="relative bg-white p-6 dark:bg-slate-950 sm:p-8">
                        <div class="flex items-center justify-between"><p class="text-[10px] font-extrabold uppercase tracking-[.18em] text-slate-500 dark:text-slate-400">What happens now</p><span class="rounded-full bg-slate-100 px-2.5 py-1 text-[10px] font-bold text-slate-500 dark:bg-slate-800 dark:text-slate-400"><span x-text="activeStage"></span> / 6</span></div>

                        <div class="mt-8 min-h-[235px]">
                            <div x-show="activeStage === 1" x-transition.opacity><span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-100 text-xl dark:bg-emerald-950">🪪</span><h3 class="mt-5 font-heading text-2xl font-extrabold text-slate-900 dark:text-white">Start with your biodata</h3><p class="mt-3 text-sm leading-6 text-slate-600 dark:text-slate-400">A seeker describes their deen, character, lifestyle, and hopes for marriage. This gives the matching system meaningful context.</p><div class="mt-5 space-y-2 text-xs font-semibold text-slate-700 dark:text-slate-300"><p>✓ Religious practice and values</p><p>✓ Family and partner preferences</p><p>✓ Modest profile with privacy controls</p></div></div>
                            <div x-show="activeStage === 2" x-transition.opacity><span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-amber-100 text-xl dark:bg-amber-950">🛡️</span><h3 class="mt-5 font-heading text-2xl font-extrabold text-slate-900 dark:text-white">Build a trusted identity</h3><p class="mt-3 text-sm leading-6 text-slate-600 dark:text-slate-400">Verification and moderation help serious people meet in a space designed for accountability—not anonymous browsing.</p><div class="mt-5 space-y-2 text-xs font-semibold text-slate-700 dark:text-slate-300"><p>✓ Email and identity signals</p><p>✓ Human moderation for verification</p><p>✓ Safer discovery for active profiles</p></div></div>
                            <div x-show="activeStage === 3" x-transition.opacity><span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-sky-100 text-xl dark:bg-sky-950">✨</span><h3 class="mt-5 font-heading text-2xl font-extrabold text-slate-900 dark:text-white">Discover with compatibility</h3><p class="mt-3 text-sm leading-6 text-slate-600 dark:text-slate-400">The platform surfaces candidates using deen-weighted compatibility signals, preferences, and respectful profile details.</p><div class="mt-5 space-y-2 text-xs font-semibold text-slate-700 dark:text-slate-300"><p>✓ Prayer and madhhab context</p><p>✓ Clear compatibility highlights</p><p>✓ Filters for real-life preferences</p></div></div>
                            <div x-show="activeStage === 4" x-transition.opacity><span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-purple-100 text-xl dark:bg-purple-950">💌</span><h3 class="mt-5 font-heading text-2xl font-extrabold text-slate-900 dark:text-white">Make a respectful approach</h3><p class="mt-3 text-sm leading-6 text-slate-600 dark:text-slate-400">An interest request creates a clear signal. The recipient can accept, decline, or ignore—without unsolicited chat.</p><div class="mt-5 space-y-2 text-xs font-semibold text-slate-700 dark:text-slate-300"><p>✓ Consent before conversation</p><p>✓ Mutual interest unlocks the next step</p><p>✓ Notifications keep everyone informed</p></div></div>
                            <div x-show="activeStage === 5" x-transition.opacity><span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-amber-100 text-xl dark:bg-amber-950">👥</span><h3 class="mt-5 font-heading text-2xl font-extrabold text-slate-900 dark:text-white">Bring the Wali into view</h3><p class="mt-3 text-sm leading-6 text-slate-600 dark:text-slate-400">A linked Wali can oversee profiles and conversations, approve steps, or participate as a chaperone with family-selected permissions.</p><div class="mt-5 space-y-2 text-xs font-semibold text-slate-700 dark:text-slate-300"><p>✓ View-only or approval permissions</p><p>✓ Chaperoned conversation context</p><p>✓ Family guidance without confusion</p></div></div>
                            <div x-show="activeStage === 6" x-transition.opacity><span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-100 text-xl dark:bg-emerald-950">🤝</span><h3 class="mt-5 font-heading text-2xl font-extrabold text-slate-900 dark:text-white">Move toward families and Nikah</h3><p class="mt-3 text-sm leading-6 text-slate-600 dark:text-slate-400">With mutual clarity, the conversation can progress toward family introductions and the real-world steps that lead to Nikah.</p><div class="mt-5 space-y-2 text-xs font-semibold text-slate-700 dark:text-slate-300"><p>✓ Purposeful, moderated chat</p><p>✓ Clear next-step visibility</p><p>✓ A journey designed to conclude well</p></div></div>
                        </div>

                        <div class="border-t border-slate-200 pt-5 dark:border-slate-800">
                            <div class="flex items-center justify-between gap-3"><button type="button" @click="activeStage = activeStage === 1 ? 6 : activeStage - 1" class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-bold text-slate-600 transition hover:border-emerald-300 hover:text-emerald-700 dark:border-slate-800 dark:text-slate-300 dark:hover:border-emerald-700 dark:hover:text-emerald-400">&larr; Previous</button><div class="flex gap-1.5" aria-label="Journey stage selector">@for($stage = 1; $stage <= 6; $stage++)<button type="button" @click="activeStage = {{ $stage }}" class="h-2 rounded-full transition-all" :class="activeStage === {{ $stage }} ? 'w-7 bg-emerald-500' : 'w-2 bg-slate-200 dark:bg-slate-700'" aria-label="Go to stage {{ $stage }}"></button>@endfor</div><button type="button" @click="activeStage = activeStage === 6 ? 1 : activeStage + 1" class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-bold text-slate-600 transition hover:border-emerald-300 hover:text-emerald-700 dark:border-slate-800 dark:text-slate-300 dark:hover:border-emerald-700 dark:hover:text-emerald-400">Next &rarr;</button></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Role clarity -->
    <section class="border-b border-slate-200 bg-white py-16 dark:border-slate-800 dark:bg-slate-950 sm:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <span class="text-xs font-extrabold uppercase tracking-[.2em] text-emerald-700 dark:text-emerald-400">Every role has a place</span>
                <h2 class="mt-3 font-heading text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white sm:text-4xl">A shared journey, clear responsibilities</h2>
                <p class="mt-4 text-sm leading-6 text-slate-600 dark:text-slate-400">The process stays simple because everyone knows when to step forward—and when to protect the space.</p>
            </div>

            <div class="mt-12 grid gap-5 md:grid-cols-3">
                <div class="group rounded-3xl border border-emerald-200 bg-emerald-50/70 p-6 transition hover:-translate-y-1 hover:shadow-xl hover:shadow-emerald-900/10 dark:border-emerald-900/60 dark:bg-emerald-950/30">
                    <div class="flex items-start justify-between"><div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-600 text-2xl text-white shadow-lg shadow-emerald-900/20">👤</div><span class="rounded-full bg-white/80 px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-wider text-emerald-700 dark:bg-emerald-900/60 dark:text-emerald-300">Seeker</span></div>
                    <h3 class="mt-6 font-heading text-xl font-extrabold text-slate-900 dark:text-white">Bring honest intention</h3>
                    <p class="mt-3 text-sm leading-6 text-slate-600 dark:text-slate-300">Create a thoughtful profile, share your values, and approach every introduction with marriage in mind.</p>
                    <div class="mt-5 border-t border-emerald-200/70 pt-4 text-xs font-bold text-emerald-800 dark:border-emerald-800/60 dark:text-emerald-300">Profile · preferences · consent</div>
                </div>

                <div class="group rounded-3xl border border-amber-200 bg-amber-50/70 p-6 transition hover:-translate-y-1 hover:shadow-xl hover:shadow-amber-900/10 dark:border-amber-900/60 dark:bg-amber-950/20">
                    <div class="flex items-start justify-between"><div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-500 text-2xl text-white shadow-lg shadow-amber-900/20">🛡️</div><span class="rounded-full bg-white/80 px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-wider text-amber-700 dark:bg-amber-900/60 dark:text-amber-300">Wali</span></div>
                    <h3 class="mt-6 font-heading text-xl font-extrabold text-slate-900 dark:text-white">Bring family confidence</h3>
                    <p class="mt-3 text-sm leading-6 text-slate-600 dark:text-slate-300">A linked guardian can oversee, approve, or participate with permissions chosen by the family.</p>
                    <div class="mt-5 border-t border-amber-200/70 pt-4 text-xs font-bold text-amber-800 dark:border-amber-800/60 dark:text-amber-300">Visibility · guidance · approval</div>
                </div>

                <div class="group rounded-3xl border border-sky-200 bg-sky-50/70 p-6 transition hover:-translate-y-1 hover:shadow-xl hover:shadow-sky-900/10 dark:border-sky-900/60 dark:bg-sky-950/20">
                    <div class="flex items-start justify-between"><div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-sky-600 text-2xl text-white shadow-lg shadow-sky-900/20">⚖️</div><span class="rounded-full bg-white/80 px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-wider text-sky-700 dark:bg-sky-900/60 dark:text-sky-300">Platform</span></div>
                    <h3 class="mt-6 font-heading text-xl font-extrabold text-slate-900 dark:text-white">Bring a safer structure</h3>
                    <p class="mt-3 text-sm leading-6 text-slate-600 dark:text-slate-300">Verification, compatibility signals, privacy defaults, and moderation keep the process dignified.</p>
                    <div class="mt-5 border-t border-sky-200/70 pt-4 text-xs font-bold text-sky-800 dark:border-sky-800/60 dark:text-sky-300">Trust · privacy · accountability</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Role-based walkthrough -->
    <section class="bg-slate-100 py-16 dark:bg-slate-900 sm:py-24" x-data="{ role: 'seeker' }">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-3xl text-center">
                <span class="text-xs font-extrabold uppercase tracking-[.2em] text-sky-700 dark:text-sky-400">Choose your perspective</span>
                <h2 class="mt-3 font-heading text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white sm:text-4xl">See exactly where you fit in.</h2>
                <p class="mt-4 text-sm leading-6 text-slate-600 dark:text-slate-400">The same trusted journey feels different depending on your role. Select a perspective to see the actions, visibility, and next step that belong to you.</p>
            </div>

            <div class="mx-auto mt-10 flex max-w-xl rounded-2xl border border-slate-200 bg-white p-1.5 shadow-sm dark:border-slate-800 dark:bg-slate-950" role="tablist" aria-label="Choose your role">
                <button type="button" @click="role = 'seeker'" :aria-selected="role === 'seeker'" class="flex-1 rounded-xl px-3 py-3 text-xs font-extrabold transition" :class="role === 'seeker' ? 'bg-emerald-600 text-white shadow-md shadow-emerald-900/20' : 'text-slate-500 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-900'" role="tab">👤 Seeker</button>
                <button type="button" @click="role = 'wali'" :aria-selected="role === 'wali'" class="flex-1 rounded-xl px-3 py-3 text-xs font-extrabold transition" :class="role === 'wali' ? 'bg-amber-500 text-white shadow-md shadow-amber-900/20' : 'text-slate-500 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-900'" role="tab">🛡️ Wali</button>
                <button type="button" @click="role = 'family'" :aria-selected="role === 'family'" class="flex-1 rounded-xl px-3 py-3 text-xs font-extrabold transition" :class="role === 'family' ? 'bg-sky-600 text-white shadow-md shadow-sky-900/20' : 'text-slate-500 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-900'" role="tab">🏠 Family</button>
            </div>

            <div class="mx-auto mt-8 max-w-5xl overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-xl shadow-slate-900/5 dark:border-slate-800 dark:bg-slate-950">
                <div x-show="role === 'seeker'" x-transition.opacity class="grid lg:grid-cols-[.8fr_1.2fr]">
                    <div class="bg-emerald-950 p-7 text-white sm:p-10"><span class="inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-400/15 text-3xl">👤</span><p class="mt-8 text-[10px] font-extrabold uppercase tracking-[.2em] text-emerald-300">For the seeker</p><h3 class="mt-3 font-heading text-3xl font-extrabold">You bring the intention.</h3><p class="mt-4 text-sm leading-6 text-emerald-100/70">Your profile is your first respectful introduction. You decide what to share, who to approach, and when a conversation moves forward.</p><a href="{{ route('register') }}" class="mt-8 inline-flex rounded-xl bg-amber-400 px-4 py-3 text-xs font-extrabold text-emerald-950 transition hover:bg-amber-300">Build my profile &rarr;</a></div>
                    <div class="p-7 sm:p-10"><p class="text-xs font-extrabold uppercase tracking-wider text-emerald-700 dark:text-emerald-400">Your four moments</p><div class="mt-6 space-y-5"><div class="flex gap-4"><span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-xs font-extrabold text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300">01</span><div><h4 class="text-sm font-extrabold text-slate-900 dark:text-white">Complete your biodata</h4><p class="mt-1 text-xs leading-5 text-slate-500 dark:text-slate-400">Add your deen, lifestyle, family values, and preferences.</p></div></div><div class="flex gap-4"><span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-xs font-extrabold text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300">02</span><div><h4 class="text-sm font-extrabold text-slate-900 dark:text-white">Explore compatible candidates</h4><p class="mt-1 text-xs leading-5 text-slate-500 dark:text-slate-400">Review meaningful compatibility signals before photos or chat.</p></div></div><div class="flex gap-4"><span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-xs font-extrabold text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300">03</span><div><h4 class="text-sm font-extrabold text-slate-900 dark:text-white">Send one respectful interest</h4><p class="mt-1 text-xs leading-5 text-slate-500 dark:text-slate-400">The other person chooses whether the door opens.</p></div></div><div class="flex gap-4"><span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-xs font-extrabold text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300">04</span><div><h4 class="text-sm font-extrabold text-slate-900 dark:text-white">Move with clarity</h4><p class="mt-1 text-xs leading-5 text-slate-500 dark:text-slate-400">If mutual, chat and Wali involvement guide the next family step.</p></div></div></div></div>
                </div>

                <div x-show="role === 'wali'" x-transition.opacity class="grid lg:grid-cols-[.8fr_1.2fr]">
                    <div class="bg-amber-950 p-7 text-white sm:p-10"><span class="inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-amber-400/15 text-3xl">🛡️</span><p class="mt-8 text-[10px] font-extrabold uppercase tracking-[.2em] text-amber-300">For the Wali</p><h3 class="mt-3 font-heading text-3xl font-extrabold">You bring family confidence.</h3><p class="mt-4 text-sm leading-6 text-amber-100/70">You are not an afterthought or a hidden observer. Your linked permissions make your guidance visible at the moments that matter.</p><a href="{{ route('register') }}" class="mt-8 inline-flex rounded-xl bg-amber-400 px-4 py-3 text-xs font-extrabold text-amber-950 transition hover:bg-amber-300">Understand Wali access &rarr;</a></div>
                    <div class="p-7 sm:p-10"><p class="text-xs font-extrabold uppercase tracking-wider text-amber-700 dark:text-amber-400">Your four moments</p><div class="mt-6 space-y-5"><div class="flex gap-4"><span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-xs font-extrabold text-amber-700 dark:bg-amber-950 dark:text-amber-300">01</span><div><h4 class="text-sm font-extrabold text-slate-900 dark:text-white">Receive a trusted invitation</h4><p class="mt-1 text-xs leading-5 text-slate-500 dark:text-slate-400">A seeker links you directly and chooses the relationship context.</p></div></div><div class="flex gap-4"><span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-xs font-extrabold text-amber-700 dark:bg-amber-950 dark:text-amber-300">02</span><div><h4 class="text-sm font-extrabold text-slate-900 dark:text-white">Choose your permission level</h4><p class="mt-1 text-xs leading-5 text-slate-500 dark:text-slate-400">View-only, approval required, or active chaperone support your family’s needs.</p></div></div><div class="flex gap-4"><span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-xs font-extrabold text-amber-700 dark:bg-amber-950 dark:text-amber-300">03</span><div><h4 class="text-sm font-extrabold text-slate-900 dark:text-white">See the right signals</h4><p class="mt-1 text-xs leading-5 text-slate-500 dark:text-slate-400">Review candidate context and interest activity without guesswork.</p></div></div><div class="flex gap-4"><span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-xs font-extrabold text-amber-700 dark:bg-amber-950 dark:text-amber-300">04</span><div><h4 class="text-sm font-extrabold text-slate-900 dark:text-white">Guide the conversation</h4><p class="mt-1 text-xs leading-5 text-slate-500 dark:text-slate-400">Approval and chaperoned visibility help families progress with confidence.</p></div></div></div></div>
                </div>

                <div x-show="role === 'family'" x-transition.opacity class="grid lg:grid-cols-[.8fr_1.2fr]">
                    <div class="bg-sky-950 p-7 text-white sm:p-10"><span class="inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-sky-400/15 text-3xl">🏠</span><p class="mt-8 text-[10px] font-extrabold uppercase tracking-[.2em] text-sky-300">For the family</p><h3 class="mt-3 font-heading text-3xl font-extrabold">You bring the next chapter.</h3><p class="mt-4 text-sm leading-6 text-sky-100/70">The platform helps families see a clear, respectful path from first introduction to the conversations that matter in real life.</p><a href="{{ route('about') }}" class="mt-8 inline-flex rounded-xl bg-sky-400 px-4 py-3 text-xs font-extrabold text-sky-950 transition hover:bg-sky-300">Learn our principles &rarr;</a></div>
                    <div class="p-7 sm:p-10"><p class="text-xs font-extrabold uppercase tracking-wider text-sky-700 dark:text-sky-400">Your four moments</p><div class="mt-6 space-y-5"><div class="flex gap-4"><span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-sky-100 text-xs font-extrabold text-sky-700 dark:bg-sky-950 dark:text-sky-300">01</span><div><h4 class="text-sm font-extrabold text-slate-900 dark:text-white">Start with context</h4><p class="mt-1 text-xs leading-5 text-slate-500 dark:text-slate-400">See the values, intentions, and family expectations behind a profile.</p></div></div><div class="flex gap-4"><span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-sky-100 text-xs font-extrabold text-sky-700 dark:bg-sky-950 dark:text-sky-300">02</span><div><h4 class="text-sm font-extrabold text-slate-900 dark:text-white">Respect the mutual signal</h4><p class="mt-1 text-xs leading-5 text-slate-500 dark:text-slate-400">A conversation begins only after both people have chosen it.</p></div></div><div class="flex gap-4"><span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-sky-100 text-xs font-extrabold text-sky-700 dark:bg-sky-950 dark:text-sky-300">03</span><div><h4 class="text-sm font-extrabold text-slate-900 dark:text-white">Meet with guidance</h4><p class="mt-1 text-xs leading-5 text-slate-500 dark:text-slate-400">Wali oversight and platform moderation create a calmer first step.</p></div></div><div class="flex gap-4"><span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-sky-100 text-xs font-extrabold text-sky-700 dark:bg-sky-950 dark:text-sky-300">04</span><div><h4 class="text-sm font-extrabold text-slate-900 dark:text-white">Take it offline thoughtfully</h4><p class="mt-1 text-xs leading-5 text-slate-500 dark:text-slate-400">Use the online introduction as a bridge to family-led decisions and Nikah.</p></div></div></div></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Journey -->
    <section id="journey" class="bg-slate-50 py-16 dark:bg-slate-900 sm:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col justify-between gap-5 sm:flex-row sm:items-end">
                <div class="max-w-2xl"><span class="text-xs font-extrabold uppercase tracking-[.2em] text-amber-700 dark:text-amber-400">The journey</span><h2 class="mt-3 font-heading text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white sm:text-4xl">From first profile to family conversation</h2></div>
                <p class="max-w-sm text-sm leading-6 text-slate-600 dark:text-slate-400">A measured process helps good intentions stay clear from the first signal to the next family step.</p>
            </div>

            <div class="relative mt-12 grid gap-5 md:grid-cols-4">
                <div class="absolute left-[10%] right-[10%] top-8 hidden h-px bg-gradient-to-r from-emerald-300 via-amber-300 to-sky-300 dark:from-emerald-800 dark:via-amber-800 dark:to-sky-800 md:block"></div>
                <div class="relative rounded-3xl border border-slate-200 bg-white p-5 dark:border-slate-800 dark:bg-slate-950"><div class="relative z-10 flex h-16 w-16 items-center justify-center rounded-2xl border-4 border-slate-50 bg-emerald-100 text-2xl dark:border-slate-900 dark:bg-emerald-950">🪪</div><span class="mt-5 block text-[10px] font-extrabold uppercase tracking-wider text-emerald-700 dark:text-emerald-400">Step 01</span><h3 class="mt-2 font-heading text-lg font-extrabold text-slate-900 dark:text-white">Build your biodata</h3><p class="mt-2 text-xs leading-5 text-slate-600 dark:text-slate-400">Share deen, character, lifestyle, and partner preferences with intention.</p></div>
                <div class="relative rounded-3xl border border-slate-200 bg-white p-5 dark:border-slate-800 dark:bg-slate-950"><div class="relative z-10 flex h-16 w-16 items-center justify-center rounded-2xl border-4 border-slate-50 bg-amber-100 text-2xl dark:border-slate-900 dark:bg-amber-950">🔎</div><span class="mt-5 block text-[10px] font-extrabold uppercase tracking-wider text-amber-700 dark:text-amber-400">Step 02</span><h3 class="mt-2 font-heading text-lg font-extrabold text-slate-900 dark:text-white">Discover thoughtfully</h3><p class="mt-2 text-xs leading-5 text-slate-600 dark:text-slate-400">Use compatibility signals to explore candidates beyond surface-level swiping.</p></div>
                <div class="relative rounded-3xl border border-slate-200 bg-white p-5 dark:border-slate-800 dark:bg-slate-950"><div class="relative z-10 flex h-16 w-16 items-center justify-center rounded-2xl border-4 border-slate-50 bg-sky-100 text-2xl dark:border-slate-900 dark:bg-sky-950">💬</div><span class="mt-5 block text-[10px] font-extrabold uppercase tracking-wider text-sky-700 dark:text-sky-400">Step 03</span><h3 class="mt-2 font-heading text-lg font-extrabold text-slate-900 dark:text-white">Signal mutual interest</h3><p class="mt-2 text-xs leading-5 text-slate-600 dark:text-slate-400">No unsolicited messaging. Conversation unlocks after mutual interest and the right approvals.</p></div>
                <div class="relative rounded-3xl border border-slate-200 bg-white p-5 dark:border-slate-800 dark:bg-slate-950"><div class="relative z-10 flex h-16 w-16 items-center justify-center rounded-2xl border-4 border-slate-50 bg-purple-100 text-2xl dark:border-slate-900 dark:bg-purple-950">🤝</div><span class="mt-5 block text-[10px] font-extrabold uppercase tracking-wider text-purple-700 dark:text-purple-400">Step 04</span><h3 class="mt-2 font-heading text-lg font-extrabold text-slate-900 dark:text-white">Move with Barakah</h3><p class="mt-2 text-xs leading-5 text-slate-600 dark:text-slate-400">Bring families into the conversation and take the next step toward Nikah.</p></div>
            </div>
        </div>
    </section>

    <!-- Closing CTA -->
    <section class="bg-emerald-950 py-16 text-white sm:py-20">
        <div class="mx-auto flex max-w-5xl flex-col items-center justify-between gap-8 px-4 text-center sm:px-6 lg:flex-row lg:text-left lg:px-8">
            <div><span class="text-xs font-extrabold uppercase tracking-[.2em] text-amber-300">Ready when you are</span><h2 class="mt-3 font-heading text-3xl font-extrabold tracking-tight sm:text-4xl">Start with a sincere intention.</h2><p class="mt-3 max-w-xl text-sm leading-6 text-emerald-100/70">Your next chapter can begin with a profile that reflects who you are and the family values you want to build.</p></div>
            <a href="{{ route('register') }}" class="shrink-0 rounded-xl bg-amber-400 px-6 py-3.5 text-sm font-extrabold text-emerald-950 shadow-xl shadow-amber-950/30 transition hover:-translate-y-0.5 hover:bg-amber-300">Create your profile &rarr;</a>
        </div>
    </section>
</div>
@endsection
