@extends('layouts.app')

@section('title', 'Blocked Profiles - Global Nikkah')

@section('content')
<div class="min-h-screen bg-slate-50 py-8 dark:bg-slate-950">
    <div class="mx-auto max-w-4xl space-y-6 px-4 sm:px-6 lg:px-8">
        <section class="rounded-3xl border border-rose-100 bg-gradient-to-br from-rose-50 via-white to-slate-50 p-6 shadow-xs dark:border-rose-900/50 dark:from-rose-950/30 dark:via-slate-900 dark:to-slate-950 sm:p-8">
            <p class="text-[10px] font-extrabold uppercase tracking-[0.18em] text-rose-700 dark:text-rose-300">Your safety controls</p>
            <h1 class="mt-2 font-heading text-3xl font-extrabold text-slate-900 dark:text-white">Blocked profiles</h1>
            <p class="mt-2 max-w-2xl text-sm leading-relaxed text-slate-600 dark:text-slate-300">Profiles you block are removed from your discovery results and cannot be opened from your account. You can reverse the decision whenever you are ready.</p>
        </section>

        @if(session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-xs font-semibold text-emerald-800 dark:border-emerald-800 dark:bg-emerald-950/50 dark:text-emerald-200">✓ {{ session('success') }}</div>
        @endif

        <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-xs dark:border-slate-800 dark:bg-slate-900">
            <div class="flex items-center justify-between gap-4"><h2 class="font-heading text-lg font-extrabold text-slate-900 dark:text-white">Your list</h2><span class="rounded-full bg-slate-100 px-3 py-1 text-[11px] font-bold text-slate-600 dark:bg-slate-800 dark:text-slate-300">{{ $blockedUsers->count() }} {{ $blockedUsers->count() === 1 ? 'profile' : 'profiles' }}</span></div>
            <div class="mt-5 divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($blockedUsers as $blockedUser)
                    <div class="flex items-center justify-between gap-4 py-4 first:pt-0 last:pb-0">
                        <div class="flex min-w-0 items-center gap-3">
                            <div class="flex h-11 w-11 shrink-0 items-center justify-center overflow-hidden rounded-xl bg-emerald-950 font-bold text-amber-300">
                                @if($blockedUser->profile?->primaryPhoto)
                                    <img src="{{ $blockedUser->profile->primaryPhoto->displayUrl() }}" alt="{{ $blockedUser->name }}" class="photo-blur h-full w-full object-cover">
                                @else
                                    {{ substr($blockedUser->name, 0, 1) }}
                                @endif
                            </div>
                            <div class="min-w-0"><p class="truncate text-sm font-bold text-slate-900 dark:text-white">{{ $blockedUser->name }}</p><p class="text-[11px] text-slate-500 dark:text-slate-400">Blocked {{ $blockedUser->pivot->created_at?->diffForHumans() ?? 'recently' }}</p></div>
                        </div>
                        <form action="{{ route('blocked-users.destroy', $blockedUser->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="rounded-xl border border-slate-200 px-3 py-2 text-[11px] font-bold text-slate-600 transition hover:border-emerald-300 hover:text-emerald-700 dark:border-slate-700 dark:text-slate-300 dark:hover:border-emerald-700 dark:hover:text-emerald-300">Unblock</button>
                        </form>
                    </div>
                @empty
                    <div class="py-10 text-center"><div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-xl dark:bg-slate-800">🛡️</div><p class="mt-3 text-sm font-bold text-slate-900 dark:text-white">No blocked profiles</p><p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Your safety list is currently clear.</p></div>
                @endforelse
            </div>
        </section>
    </div>
</div>
@endsection
