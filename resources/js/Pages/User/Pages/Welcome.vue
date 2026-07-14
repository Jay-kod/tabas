<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

const portals = [
    {
        title: 'Triage Nurse',
        description: 'Open the triage workspace, record vitals, and generate a score.',
        href: route('login.role', 'triage-nurse'),
        accent: 'from-sky-500 to-cyan-500',
    },
    {
        title: 'Bed Manager',
        description: 'Manage beds, accept placements, and keep ward capacity current.',
        href: route('login.role', 'bed-manager'),
        accent: 'from-emerald-500 to-teal-500',
    },
    {
        title: 'Doctor',
        description: 'Review the pending queue and follow up on allocations.',
        href: route('login.role', 'doctor'),
        accent: 'from-amber-500 to-orange-500',
    },
];
</script>

<template>
    <Head title="TABAS" />

    <div class="min-h-screen bg-brand-900 text-white">
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute left-0 top-0 h-80 w-80 rounded-full bg-brand-400/20 blur-3xl"></div>
            <div class="absolute right-0 top-24 h-96 w-96 rounded-full bg-brand-50/10 blur-3xl"></div>
        </div>

        <div class="relative mx-auto flex min-h-screen max-w-7xl flex-col px-4 py-6 sm:px-6 lg:px-8">
            <header class="flex flex-col gap-4 rounded-[2rem] border border-white/10 bg-white/5 px-6 py-5 backdrop-blur-xl md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.4em] text-brand-50">TABAS</p>
                    <h1 class="mt-1 text-3xl font-semibold sm:text-4xl">Triage And Bed Allocation System</h1>
                    <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-300">
                        A clear entry point for role-based hospital workflows, built for triage, allocation, and bed management.
                    </p>
                </div>

                <div class="flex flex-wrap gap-3">
                    <Link
                        v-if="canLogin"
                        :href="route('login')"
                        class="rounded-2xl border border-white/15 px-4 py-2 text-sm font-semibold text-white transition hover:bg-white/10"
                    >
                        Log in
                    </Link>
                    <Link
                        v-if="canRegister"
                        :href="route('register')"
                        class="rounded-2xl bg-brand-400 px-4 py-2 text-sm font-semibold text-brand-900 transition hover:bg-brand-600"
                    >
                        Register
                    </Link>
                </div>
            </header>

            <main class="flex-1 py-8 lg:py-10">
                <section class="grid gap-8 lg:grid-cols-[1.1fr_0.9fr] lg:items-center">
                    <div class="space-y-6">
                        <p class="text-sm font-semibold uppercase tracking-[0.35em] text-brand-50">Hospital workflow, simplified</p>
                        <h2 class="max-w-3xl text-4xl font-semibold leading-tight sm:text-5xl lg:text-6xl">
                            A role-based workspace for triage, allocation, and bed control.
                        </h2>
                        <p class="max-w-2xl text-base leading-7 text-slate-300">
                            TABAS brings the nurse, doctor, bed manager, and admin workflow together so patient intake moves faster and every placement remains visible.
                        </p>

                        <div class="flex flex-wrap gap-3">
                            <Link :href="route('login')" class="rounded-2xl bg-white px-5 py-3 text-sm font-semibold text-slate-950 transition hover:bg-slate-100">
                                Open workspace
                            </Link>
                            <a href="#role-portals" class="rounded-2xl border border-white/15 px-5 py-3 text-sm font-semibold text-white transition hover:bg-white/10">
                                See role portals
                            </a>
                        </div>
                    </div>

                    <div class="rounded-[2rem] border border-white/10 bg-white/5 p-5 shadow-2xl shadow-slate-950/30 backdrop-blur-xl sm:p-6">
                        <div class="rounded-3xl bg-white p-5 text-slate-900">
                            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-brand-800">What TABAS does</p>
                            <div class="mt-4 grid gap-3 sm:grid-cols-2">
                                <div class="rounded-2xl bg-slate-50 p-4">
                                    <p class="text-sm font-semibold">Patient intake</p>
                                    <p class="mt-1 text-sm text-slate-600">Capture demographics and vitals quickly.</p>
                                </div>
                                <div class="rounded-2xl bg-slate-50 p-4">
                                    <p class="text-sm font-semibold">Scoring</p>
                                    <p class="mt-1 text-sm text-slate-600">Compute urgency from the triage model.</p>
                                </div>
                                <div class="rounded-2xl bg-slate-50 p-4">
                                    <p class="text-sm font-semibold">Bed recommendation</p>
                                    <p class="mt-1 text-sm text-slate-600">Match patients to suitable vacant beds.</p>
                                </div>
                                <div class="rounded-2xl bg-slate-50 p-4">
                                    <p class="text-sm font-semibold">Audit trail</p>
                                    <p class="mt-1 text-sm text-slate-600">Keep actions visible for review.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="role-portals" class="mt-10">
                    <div class="mb-5 flex items-end justify-between gap-4">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-[0.35em] text-brand-50">Role portals</p>
                            <h3 class="mt-2 text-2xl font-semibold">Choose the login page for your role</h3>
                        </div>
                        <p class="hidden max-w-xl text-sm leading-6 text-slate-400 md:block">
                            Admin login is intentionally excluded from this public role list.
                        </p>
                    </div>

                    <div class="grid gap-4 lg:grid-cols-3">
                        <Link
                            v-for="portal in portals"
                            :key="portal.title"
                            :href="portal.href"
                            class="group rounded-[1.75rem] border border-white/10 bg-white/5 p-6 transition hover:-translate-y-1 hover:bg-white/10"
                        >
                            <div :class="['h-1.5 w-20 rounded-full bg-gradient-to-r', portal.accent]"></div>
                            <h4 class="mt-5 text-xl font-semibold">{{ portal.title }}</h4>
                            <p class="mt-2 text-sm leading-6 text-slate-300">{{ portal.description }}</p>
                            <p class="mt-4 text-sm font-semibold text-brand-50 transition group-hover:text-white">Open login</p>
                        </Link>
                    </div>
                </section>
            </main>

            <footer class="rounded-[2rem] border border-white/10 bg-white/5 px-6 py-5 text-sm leading-6 text-slate-300 backdrop-blur-xl">
                <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <p>TABAS is a final-year academic prototype for hospital triage and bed allocation.</p>
                    <p>Laravel {{ laravelVersion }} · PHP {{ phpVersion }}</p>
                </div>
            </footer>
        </div>
    </div>
</template>