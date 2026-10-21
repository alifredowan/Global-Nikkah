@extends('layouts.app')

@section('title', 'Contact & Safety Centre - Global Nikkah')
@section('meta_description', 'Contact Global Nikkah for account support, privacy requests, safety concerns, verification questions, and matrimonial platform assistance.')
@section('canonical_url', route('contact'))
@section('robots', 'index, follow')

@section('content')
<div class="min-h-screen bg-slate-50 py-10 text-slate-800 dark:bg-slate-950 dark:text-slate-100 sm:py-16">
    <div class="mx-auto max-w-6xl space-y-10 px-4 sm:px-6 lg:px-8">
        <section class="relative overflow-hidden rounded-3xl bg-emerald-950 px-6 py-12 text-white shadow-2xl sm:px-12 sm:py-16">
            <div class="absolute -right-20 -top-24 h-72 w-72 rounded-full bg-emerald-400/10 blur-3xl"></div>
            <div class="absolute -bottom-32 -left-20 h-72 w-72 rounded-full bg-amber-400/10 blur-3xl"></div>
            <div class="relative max-w-3xl space-y-5">
                <span class="inline-flex items-center gap-2 rounded-full border border-emerald-700/70 bg-emerald-900/70 px-3.5 py-1.5 text-xs font-bold uppercase tracking-wider text-emerald-200">
                    <span class="text-amber-300">✦</span> Support with amanah
                </span>
                <h1 class="font-heading text-3xl font-extrabold tracking-tight sm:text-5xl">We are here to help you move forward with confidence.</h1>
                <p class="max-w-2xl text-sm leading-7 text-emerald-100/80 sm:text-base">
                    Whether you need help with your account, have a privacy request, or want to report a safety concern, our team is ready to listen and respond with discretion.
                </p>
            </div>
        </section>

        <section class="grid gap-5 md:grid-cols-3" aria-label="Contact options">
            <a href="mailto:support@globalnikkah.com" class="group rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:border-emerald-300 hover:shadow-md dark:border-slate-800 dark:bg-slate-900 dark:hover:border-emerald-700">
                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-100 text-xl text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300">✉</span>
                <h2 class="mt-5 font-heading text-lg font-extrabold text-slate-900 dark:text-white">General support</h2>
                <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-400">Questions about registration, profiles, subscriptions, or using the platform.</p>
                <span class="mt-5 inline-block text-xs font-extrabold text-emerald-700 group-hover:underline dark:text-emerald-400">support@globalnikkah.com</span>
            </a>

            <a href="mailto:privacy@globalnikkah.com" class="group rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:border-amber-300 hover:shadow-md dark:border-slate-800 dark:bg-slate-900 dark:hover:border-amber-700">
                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-100 text-xl text-amber-700 dark:bg-amber-950 dark:text-amber-300">🔒</span>
                <h2 class="mt-5 font-heading text-lg font-extrabold text-slate-900 dark:text-white">Privacy requests</h2>
                <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-400">Request data access, correction, deletion, or help with photo and account privacy.</p>
                <span class="mt-5 inline-block text-xs font-extrabold text-amber-700 group-hover:underline dark:text-amber-400">privacy@globalnikkah.com</span>
            </a>

            <a href="mailto:safety@globalnikkah.com" class="group rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:border-rose-300 hover:shadow-md dark:border-slate-800 dark:bg-slate-900 dark:hover:border-rose-700">
                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-rose-100 text-xl text-rose-700 dark:bg-rose-950 dark:text-rose-300">🛡</span>
                <h2 class="mt-5 font-heading text-lg font-extrabold text-slate-900 dark:text-white">Safety concerns</h2>
                <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-400">Report harassment, impersonation, suspicious activity, or a violation of our code of conduct.</p>
                <span class="mt-5 inline-block text-xs font-extrabold text-rose-700 group-hover:underline dark:text-rose-400">safety@globalnikkah.com</span>
            </a>
        </section>

        <section class="grid gap-6 lg:grid-cols-[1.15fr_.85fr]">
            <div class="rounded-3xl border border-slate-200 bg-white p-7 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-9">
                <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-emerald-700 dark:text-emerald-400">Before you contact us</p>
                <h2 class="mt-3 font-heading text-2xl font-extrabold text-slate-900 dark:text-white">Use the quickest safety control first</h2>
                <div class="mt-6 space-y-4 text-sm leading-6 text-slate-600 dark:text-slate-400">
                    <div class="flex gap-3"><span class="mt-1 text-emerald-600">✓</span><p><strong class="text-slate-900 dark:text-white">Block</strong> a profile immediately if you do not want further contact.</p></div>
                    <div class="flex gap-3"><span class="mt-1 text-emerald-600">✓</span><p><strong class="text-slate-900 dark:text-white">Report</strong> harassment, fraud, inappropriate content, or attempts to move conversations off-platform.</p></div>
                    <div class="flex gap-3"><span class="mt-1 text-emerald-600">✓</span><p><strong class="text-slate-900 dark:text-white">Protect evidence</strong> by keeping relevant messages and the profile link when reporting an incident.</p></div>
                </div>
                <p class="mt-7 border-t border-slate-100 pt-5 text-xs leading-5 text-slate-500 dark:border-slate-800 dark:text-slate-500">For immediate danger or a criminal matter, contact local emergency services first. Global Nikkah is not an emergency response service.</p>
            </div>

            <div class="rounded-3xl border border-emerald-200 bg-emerald-50 p-7 shadow-sm dark:border-emerald-900/60 dark:bg-emerald-950/30 sm:p-9">
                <span class="text-2xl">🕊️</span>
                <h2 class="mt-4 font-heading text-2xl font-extrabold text-slate-900 dark:text-white">A respectful response process</h2>
                <p class="mt-3 text-sm leading-6 text-slate-700 dark:text-slate-300">Please include the email connected to your account, a short description, and any relevant profile or conversation link. Do not send passwords, payment card details, or identity documents by email.</p>
                <div class="mt-6 rounded-2xl border border-emerald-200 bg-white/70 p-4 text-xs font-semibold leading-5 text-emerald-900 dark:border-emerald-900/70 dark:bg-slate-900/60 dark:text-emerald-200">We aim to acknowledge support and safety requests within one business day.</div>
            </div>
        </section>
    </div>
</div>
@endsection
