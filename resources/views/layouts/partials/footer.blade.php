<!-- Deep Emerald Continuous Platform Footer (Fix 3) -->
<footer class="section--dark relative overflow-hidden bg-[#062B22] dark:bg-[#04140f] text-[#A9BDB3] text-sm border-t border-[#d4af37]/20 transition-colors m-0">
    <!-- Faint Gold Geometric Line Pattern at Top-Right Corner (< 8% opacity) -->
    <div class="pointer-events-none absolute top-0 right-0 w-64 h-64 opacity-[0.06] text-[#d4af37] select-none" aria-hidden="true">
        <svg viewBox="0 0 160 160" fill="none" stroke="currentColor" stroke-width="1.2" class="w-full h-full">
            <circle cx="160" cy="0" r="140" stroke-dasharray="2 3" />
            <circle cx="160" cy="0" r="100" />
            <polygon points="160,0 120,40 120,80 160,40" stroke-width="0.8" />
            <polygon points="160,0 80,0 100,60 140,40" stroke-width="0.8" />
            <rect x="70" y="-30" width="80" height="80" transform="rotate(45 110 10)" stroke-width="0.8" />
        </svg>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-7 sm:py-8">
        <!-- Main Row: Logo + Tagline (Left) & Single Row of 6 Links (Middle/Right) -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">

            <!-- 1. Left: Light Logo, Brand Name & Tagline (White at 85% brightness, 15-16px, max 1 line desktop) -->
            <div class="space-y-1.5 shrink-0">
                <a href="{{ route('landing') }}" class="inline-flex items-center gap-2.5 group focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#d4af37] rounded" aria-label="Global Nikkah Home">
                    <img src="{{ asset('images/logo.png') }}" alt="Global Nikkah" class="w-7 h-7 rounded-lg object-cover shadow-2xs border border-[#d4af37]/30">
                    <span class="font-serif text-lg font-bold text-white tracking-tight">Global Nikkah</span>
                </a>
                <p class="text-sm sm:text-[15px] text-[#D8E6DF] max-w-md font-normal leading-normal">
                    Dignified matrimonial matching for practicing Muslims and their families.
                </p>
            </div>

            <!-- 2. Middle: Single Row of 6 Links (15px, font-weight 500, #D8E6DF, hover #E2B96B) -->
            <nav class="flex flex-wrap items-center gap-x-6 gap-y-1 text-[15px] font-medium" aria-label="Footer navigation">
                <a href="{{ route('about') }}" class="text-[#D8E6DF] hover:text-[#E2B96B] hover:underline underline-offset-4 transition min-h-[44px] flex items-center focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#d4af37] rounded">
                    About
                </a>
                <a href="{{ route('how-it-works') }}" class="text-[#D8E6DF] hover:text-[#E2B96B] hover:underline underline-offset-4 transition min-h-[44px] flex items-center focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#d4af37] rounded">
                    How it works
                </a>
                <a href="{{ route('subscription.pricing') }}" class="text-[#D8E6DF] hover:text-[#E2B96B] hover:underline underline-offset-4 transition min-h-[44px] flex items-center focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#d4af37] rounded">
                    Pricing
                </a>
                <a href="{{ route('marriages.celebration') }}" class="text-[#D8E6DF] hover:text-[#E2B96B] hover:underline underline-offset-4 transition min-h-[44px] flex items-center focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#d4af37] rounded">
                    Success stories
                </a>
                <a href="{{ route('contact') }}" class="text-[#D8E6DF] hover:text-[#E2B96B] hover:underline underline-offset-4 transition min-h-[44px] flex items-center focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#d4af37] rounded">
                    Help
                </a>
                <a href="{{ route('contact') }}" class="text-[#D8E6DF] hover:text-[#E2B96B] hover:underline underline-offset-4 transition min-h-[44px] flex items-center focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#d4af37] rounded">
                    Contact
                </a>
            </nav>

        </div>

        <!-- 3. Thin 1px Gold-Tinted Divider -->
        <div class="border-t border-[#d4af37]/20 my-5"></div>

        <!-- 4. Bottom Row: Copyright Left, Legal & Cookie Settings Right -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 text-sm text-[#A9BDB3]">
            <p>&copy; {{ date('Y') }} Global Nikkah. All rights reserved.</p>
            <div class="flex flex-wrap items-center gap-4 sm:gap-6 font-normal">
                <a href="{{ route('privacy') }}" class="hover:text-white hover:underline underline-offset-4 transition min-h-[44px] sm:min-h-0 flex items-center focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#d4af37]">
                    Privacy
                </a>
                <a href="{{ route('terms') }}" class="hover:text-white hover:underline underline-offset-4 transition min-h-[44px] sm:min-h-0 flex items-center focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#d4af37]">
                    Terms
                </a>
                <a href="{{ route('cookie-policy') }}" class="hover:text-white hover:underline underline-offset-4 transition min-h-[44px] sm:min-h-0 flex items-center focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#d4af37]">
                    Cookie Policy
                </a>
                @guest
                    <button type="button"
                            id="footer-cookie-settings"
                            data-open-cookie-settings
                            class="hover:text-white hover:underline underline-offset-4 transition min-h-[44px] sm:min-h-0 flex items-center cursor-pointer focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#d4af37]">
                        Cookie settings
                    </button>
                @endguest
            </div>
        </div>
    </div>
</footer>
