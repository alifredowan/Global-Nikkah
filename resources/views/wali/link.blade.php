@extends('layouts.app')

@section('title', 'Guardian (Wali) Settings - Global Nikkah')

@section('content')
<div class="py-10 bg-slate-50 dark:bg-slate-950 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xs flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white font-heading">Guardian (Wali) Linkage</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Configure guardian involvement to safeguard your marriage search with Islamic wisdom (FR-4.3, 6.2).</p>
            </div>
            <span class="text-xl">🛡️</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Left Form -->
            <div class="md:col-span-2 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 shadow-xs">
                <h2 class="text-base font-bold text-slate-900 dark:text-white font-heading mb-2">Link a Registered Guardian</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">
                    The guardian must already have a registered account on Global Nikkah to be linked.
                </p>

                @if(session('error'))
                    <div class="mb-4 p-3.5 rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-xs text-rose-800 dark:text-rose-300">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('wali.link.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">Guardian's Full Name</label>
                        <input type="text" name="wali_name" required value="{{ old('wali_name') }}" placeholder="Guardian legal name"
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder:text-slate-400 dark:placeholder:text-slate-500 text-xs focus:ring-2 focus:ring-emerald-500 outline-none">
                        @error('wali_name')
                            <p class="text-rose-600 dark:text-rose-400 text-xs mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">Relationship to You</label>
                            <select name="relationship_type" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs focus:ring-2 focus:ring-emerald-500 outline-none">
                                <option value="father" {{ old('relationship_type') === 'father' ? 'selected' : '' }}>Father</option>
                                <option value="brother" {{ old('relationship_type') === 'brother' ? 'selected' : '' }}>Brother</option>
                                <option value="uncle" {{ old('relationship_type') === 'uncle' ? 'selected' : '' }}>Uncle (Paternal / Maternal)</option>
                                <option value="grandfather" {{ old('relationship_type') === 'grandfather' ? 'selected' : '' }}>Grandfather</option>
                                <option value="other_mahram" {{ old('relationship_type') === 'other_mahram' ? 'selected' : '' }}>Other Mahram</option>
                            </select>
                            @error('relationship_type')
                                <p class="text-rose-600 dark:text-rose-400 text-xs mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">Permission Level (FR-6.2)</label>
                            <select name="permission_level" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs focus:ring-2 focus:ring-emerald-500 outline-none">
                                <option value="approve_required" {{ old('permission_level') === 'approve_required' ? 'selected' : '' }}>Approval Required (Chat locked until Wali confirms)</option>
                                <option value="view_only" {{ old('permission_level') === 'view_only' ? 'selected' : '' }}>View-Only (Observer in chats & requests)</option>
                                <option value="full_proxy" {{ old('permission_level') === 'full_proxy' ? 'selected' : '' }}>Full Proxy (Manages profile communications)</option>
                            </select>
                            @error('permission_level')
                                <p class="text-rose-600 dark:text-rose-400 text-xs mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                                Guardian's Account Email <span class="text-rose-500">*</span>
                            </label>
                            <input type="email" name="wali_email" required value="{{ old('wali_email') }}" placeholder="wali.account@domain.com"
                                   class="w-full px-4 py-2.5 rounded-xl border @error('wali_email') border-rose-500 ring-1 ring-rose-500 @else border-slate-300 dark:border-slate-700 @enderror bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder:text-slate-400 dark:placeholder:text-slate-500 text-xs focus:ring-2 focus:ring-emerald-500 outline-none">
                            @error('wali_email')
                                <p class="text-rose-600 dark:text-rose-400 text-xs mt-1 font-semibold">{{ $message }}</p>
                            @else
                                <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">Must be an existing registered account on Global Nikkah.</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">Guardian's Phone / WhatsApp</label>
                            <input type="tel" name="wali_phone" value="{{ old('wali_phone') }}" placeholder="+1 555 123 4567"
                                   class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder:text-slate-400 dark:placeholder:text-slate-500 text-xs focus:ring-2 focus:ring-emerald-500 outline-none">
                            @error('wali_phone')
                                <p class="text-rose-600 dark:text-rose-400 text-xs mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <button type="submit" class="w-full py-3 rounded-xl bg-purple-700 hover:bg-purple-800 text-white font-bold text-xs shadow-xs transition mt-2 cursor-pointer">
                        Save Guardian Link
                    </button>
                </form>
            </div>

            <!-- Right Info / Current Links -->
            <div class="space-y-6">
                <div class="bg-purple-50 dark:bg-purple-950/40 rounded-2xl border border-purple-200 dark:border-purple-900/60 p-5 text-xs text-purple-900 dark:text-purple-300 leading-relaxed">
                    <h3 class="font-bold text-sm text-purple-950 dark:text-purple-200 mb-2 font-heading">Why Involve a Wali?</h3>
                    <p class="mb-2">In Islam, the guardian acts as an advocate and protector, providing wisdom, vetting prospective suitors, and ensuring respect throughout.</p>
                    <p>When "Approval Required" is selected, suitors cannot message you until your guardian has reviewed their profile and approved the introduction.</p>
                </div>

                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 shadow-xs">
                    <h3 class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-3">Linked Guardians</h3>
                    @forelse($existingLinks as $link)
                        <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700/60 mb-2 text-xs">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-slate-900 dark:text-white">{{ $link->wali_name ?? ($link->wali?->name ?? 'Guardian') }}</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-purple-100 dark:bg-purple-950/70 text-purple-800 dark:text-purple-300 border border-purple-200 dark:border-purple-800/60">{{ $link->status }}</span>
                            </div>
                            <p class="text-slate-500 dark:text-slate-400 mt-1 capitalize">{{ $link->relationship_type }} • {{ str_replace('_', ' ', $link->permission_level) }}</p>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 dark:text-slate-500">No guardian currently linked.</p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
