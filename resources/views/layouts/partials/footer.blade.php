<!-- Sleek & Minimal Unified Halal Platform Footer -->
<footer class="bg-slate-900 dark:bg-slate-950 text-slate-400 text-sm border-t border-slate-800 transition-colors">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-6">
            <!-- Brand Logo & Copyright -->
            <div class="flex flex-col sm:flex-row items-center gap-3 sm:gap-4 text-center sm:text-left">
                <a href="{{ route('landing') }}" class="flex items-center gap-2.5 group">
                    <div class="w-8 h-8 rounded-lg bg-emerald-600 flex items-center justify-center text-white shadow-sm shadow-emerald-900/30 group-hover:scale-105 transition transform">
                        <span class="font-bold text-amber-300 text-sm">☪</span>
                    </div>
                    <span class="font-heading font-extrabold text-lg text-white tracking-tight">Global Nikkah</span>
                </a>
                <span class="hidden sm:inline text-slate-700">|</span>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    &copy; {{ date('Y') }} Global Nikkah. All rights reserved.
                </p>
            </div>

            <!-- Policy & Essential Links -->
            <div class="flex items-center gap-4 sm:gap-6 text-xs text-slate-300 font-medium">
                <a href="{{ route('privacy') }}" class="hover:text-emerald-400 transition">Privacy Policy</a>
                <span class="text-slate-700">•</span>
                <a href="{{ route('terms') }}" class="hover:text-emerald-400 transition">Terms of Service</a>
                <span class="text-slate-700">•</span>
                <a href="{{ route('about') }}" class="hover:text-emerald-400 transition">About Us</a>
            </div>
        </div>
    </div>
</footer>
