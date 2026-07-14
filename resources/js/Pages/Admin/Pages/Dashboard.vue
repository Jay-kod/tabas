<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    metrics: {
        type: Object,
        required: true,
    },
    latestPendingAllocations: {
        type: Array,
        required: true,
    },
});

const shortcuts = [
    { title: 'Users', href: route('admin.users'), description: 'Manage staff accounts and access levels.' },
    { title: 'Wards & Beds', href: route('admin.wards'), description: 'Maintain hospital capacity and specialization.' },
    { title: 'Audit Log', href: route('admin.audit'), description: 'Review all recommendations and overrides.' },
];

const metricCards = [
    { label: 'Patients today', value: props.metrics.patientsToday, tone: 'bg-brand-50 text-brand-800 ring-brand-100' },
    { label: 'Beds occupied', value: props.metrics.bedsOccupied, tone: 'bg-standard-bg text-standard-text ring-standard-bg' },
    { label: 'Beds vacant', value: props.metrics.bedsVacant, tone: 'bg-nonurgent-bg text-nonurgent-text ring-nonurgent-bg' },
    { label: 'Pending allocations', value: props.metrics.pendingAllocations, tone: 'bg-urgent-bg text-urgent-text ring-urgent-bg' },
];
</script>

<template>
    <Head title="Admin Dashboard" />

    <AuthenticatedLayout>
        <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <div v-for="metric in metricCards" :key="metric.label" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div :class="['inline-flex rounded-full px-3 py-1 text-xs font-semibold ring-1 ring-inset', metric.tone]">
                        {{ metric.label }}
                    </div>
                    <div class="mt-4 text-3xl font-semibold text-slate-900">{{ metric.value }}</div>
                </div>
            </div>

            <div class="mt-8 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-slate-900">Average time to placement</h3>
                <p class="mt-2 text-3xl font-semibold text-slate-900">{{ props.metrics.averagePlacementMinutes }} min</p>
                <p class="mt-1 text-sm text-slate-500">Based on accepted allocations made today.</p>
            </div>

            <div class="mt-8 grid gap-6 lg:grid-cols-3">
                <Link v-for="shortcut in shortcuts" :key="shortcut.title" :href="shortcut.href" class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Navigate</p>
                    <h3 class="mt-2 text-xl font-semibold text-slate-900">{{ shortcut.title }}</h3>
                    <p class="mt-2 text-sm text-slate-600">{{ shortcut.description }}</p>
                </Link>
            </div>

            <div class="mt-8 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-slate-900">Pending recommendations</h3>
                <div v-if="props.latestPendingAllocations.length" class="mt-4 divide-y divide-slate-100">
                    <div v-for="allocation in props.latestPendingAllocations" :key="allocation.id" class="flex flex-wrap items-center justify-between gap-4 py-4">
                        <div>
                            <p class="font-medium text-slate-900">{{ allocation.triage_record.patient.name }}</p>
                            <p class="text-sm text-slate-500">{{ allocation.recommended_bed?.ward?.name ?? 'No ward matched' }} · Bed {{ allocation.recommended_bed?.bed_number ?? '—' }}</p>
                        </div>
                        <span class="rounded-full bg-urgent-bg px-3 py-1 text-xs font-semibold text-urgent-text">Pending</span>
                    </div>
                </div>
                <p v-else class="mt-4 text-sm text-slate-500">No pending recommendations right now.</p>
            </div>
        </div>
    </AuthenticatedLayout>
</template>