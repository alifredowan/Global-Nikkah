@extends('layouts.app')

@section('title', 'Platform Audit Logs - Admin')
@section('page_title', 'Audit Logs')

@section('content')
<div class="py-6 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-6">

        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white font-heading">Platform Immutable Audit Logs</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Tamper-evident logs of security events, administrative decisions, and moderation actions.</p>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden">
            <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                <thead class="bg-slate-50 dark:bg-slate-800/80 text-slate-700 dark:text-slate-300 uppercase font-bold text-[10px] border-b border-slate-200 dark:border-slate-800">
                    <tr>
                        <th class="p-4">Timestamp (UTC)</th>
                        <th class="p-4">Actor</th>
                        <th class="p-4">Action Event</th>
                        <th class="p-4">Target Entity</th>
                        <th class="p-4">Client IP</th>
                        <th class="p-4">Metadata Payload</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 font-mono text-[11px]">
                    @forelse($logs as $log)
                        <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/50 transition">
                            <td class="p-4 text-slate-500 dark:text-slate-400 whitespace-nowrap">{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                            <td class="p-4 font-sans font-bold text-slate-900 dark:text-white">{{ $log->user?->name ?? 'Guest / System' }}</td>
                            <td class="p-4">
                                <span class="px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200 border border-slate-200 dark:border-slate-700 font-bold">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="p-4 font-sans text-slate-700 dark:text-slate-300">{{ $log->target_type }} #{{ $log->target_id ?? '-' }}</td>
                            <td class="p-4 text-slate-400 dark:text-slate-500">{{ $log->ip_address ?? '127.0.0.1' }}</td>
                            <td class="p-4 max-w-sm truncate text-slate-500 dark:text-slate-400 font-mono text-[10px]">
                                {{ $log->details ? json_encode($log->details) : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-400 dark:text-slate-500 font-sans">No audit events logged yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-4 border-t border-slate-100 dark:border-slate-800">
                {{ $logs->links() }}
            </div>
        </div>

    </div>
@endsection
