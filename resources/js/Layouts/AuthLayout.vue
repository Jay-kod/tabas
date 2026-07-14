<script setup>
import { computed } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import FlashAlert from '@/Components/FlashAlert.vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const flashStatus = computed(() => page.props.flash?.status ?? '');

const roleCards = [
    {
        title: 'Admin',
        description: 'Manage users, wards, audits, and the overall system flow.',
    },
    {
        title: 'Triage Nurse',
        description: 'Record vitals, score patients, and trigger allocation recommendations.',
    },
    {
        title: 'Bed Manager',
        description: 'Accept or override bed placements and keep ward capacity accurate.',
    },
    {
        title: 'Doctor',
        description: 'Review the pending allocation queue and follow up on cases.',
    },
];
</script>

<template>
    <div class="relative min-h-screen overflow-hidden bg-brand-900 text-white">
        <div class="pointer-events-none absolute inset-0">
            <div class="absolute left-0 top-0 h-80 w-80 rounded-full bg-brand-400/20 blur-3xl"></div>
            <div class="absolute bottom-0 right-0 h-96 w-96 rounded-full bg-brand-50/10 blur-3xl"></div>
        </div>

        <FlashAlert :message="flashStatus" />

        <div class="relative mx-auto grid min-h-screen max-w-7xl gap-8 px-4 py-6 lg:grid-cols-[1.05fr_0.95fr] lg:px-8">
            <div class="flex min-h-[calc(100vh-3rem)] flex-col justify-center">
                <div class="mb-6 flex items-center justify-between gap-4">
                    <Link href="/" class="inline-flex items-center gap-3">
                        <ApplicationLogo class="h-12 w-12 fill-current text-brand-50" />
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-brand-50">TABAS</p>
                            <p class="text-sm text-slate-400">Triage and Bed Allocation System</p>
                        </div>
                    </Link>
                </div>

                <div class="rounded-[2rem] border border-brand-50/70 bg-white p-6 text-slate-900 shadow-2xl shadow-slate-950/30 sm:p-8 lg:p-10">
                    <slot />
                </div>
            </div>

            <aside class="flex min-h-[calc(100vh-3rem)] items-center">
                <div class="w-full rounded-[2rem] border border-white/10 bg-white/10 p-6 shadow-2xl shadow-slate-950/30 backdrop-blur-xl sm:p-8 lg:p-10">
                    <p class="text-sm font-semibold uppercase tracking-[0.35em] text-brand-50">Secure access</p>
                    <h1 class="mt-4 max-w-xl text-4xl font-semibold leading-tight sm:text-5xl">One workspace for every TABAS role.</h1>
                    <p class="mt-5 max-w-2xl text-base leading-7 text-slate-300">
                        Sign in with your role account, keep the remember-me option on for trusted devices, and use the same login area for all system users.
                    </p>

                    <div class="mt-8 grid gap-4 sm:grid-cols-2">
                        <div
                            v-for="card in roleCards"
                            :key="card.title"
                            class="rounded-2xl border border-white/10 bg-white/5 p-4"
                        >
                            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-400">{{ card.title }}</p>
                            <p class="mt-2 text-sm leading-6 text-slate-200">{{ card.description }}</p>
                        </div>
                    </div>

                    <div class="mt-8 rounded-2xl border border-brand-400/20 bg-brand-400/10 p-4 text-sm leading-6 text-brand-50">
                        Demo accounts are seeded with the same password: password.
                    </div>
                </div>
            </aside>
        </div>
    </div>
</template>