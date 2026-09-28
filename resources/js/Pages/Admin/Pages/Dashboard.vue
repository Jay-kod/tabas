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
    { label: 'Patients today', value: props.metrics.patientsToday, tone: 'border-brand-600 text-brand-800' },
    { label: 'Beds occupied', value: props.metrics.bedsOccupied, tone: 'border-standard-text text-standard-text' },
    { label: 'Beds vacant', value: props.metrics.bedsVacant, tone: 'border-nonurgent-text text-nonurgent-text' },
    { label: 'Pending allocations', value: props.metrics.pendingAllocations, tone: 'border-urgent-text text-urgent-text' },
];
</script>

<template>
    <Head title="Admin Dashboard" />

    <AuthenticatedLayout>
        <div>
            <section class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4" aria-label="Current system metrics">
                <div v-for="metric in metricCards" :key="metric.label" class="rounded-md border border-slate-200 border-l-4 bg-white px-4 py-4 shadow-sm" :class="metric.tone">
                    <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">{{ metric.label }}</p>
                    <p class="mt-3 text-3xl font-semibold tabular-nums text-slate-950">{{ metric.value }}</p>
                </div>
            </section>

            <div class="mt-8 grid gap-8 xl:grid-cols-[minmax(0,1.6fr)_minmax(260px,0.7fr)]">
                <section class="min-w-0" aria-labelledby="pending-title">
                    <div class="flex flex-wrap items-end justify-between gap-3 border-b border-[#c9ddd4] pb-3">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.16em] text-brand-700">Needs attention</p>
                            <h3 id="pending-title" class="mt-1 text-xl font-semibold text-slate-950">Pending recommendations</h3>
                        </div>
                        <span class="text-sm text-slate-500">{{ props.latestPendingAllocations.length }} waiting</span>
                    </div>

                    <div v-if="props.latestPendingAllocations.length" class="divide-y divide-[#dce9e3]">
                        <div v-for="allocation in props.latestPendingAllocations" :key="allocation.id" class="grid gap-2 py-4 sm:grid-cols-[minmax(0,1fr)_minmax(0,1fr)_auto] sm:items-center sm:gap-4">
                            <div class="min-w-0">
                                <p class="truncate font-semibold text-slate-900">{{ allocation.triage_record.patient.name }}</p>
                                <p class="mt-1 text-xs uppercase tracking-wide text-slate-500">Patient</p>
                            </div>
                            <div class="min-w-0">
                                <p class="truncate text-sm text-slate-700">{{ allocation.recommended_bed?.ward?.name ?? 'No ward matched' }}</p>
                                <p class="mt-1 text-xs text-slate-500">Bed {{ allocation.recommended_bed?.bed_number ?? 'Not assigned' }}</p>
                            </div>
                            <span class="w-fit border-l-2 border-urgent-text bg-urgent-bg px-2.5 py-1 text-xs font-semibold text-urgent-text">Pending</span>
                        </div>
                    </div>
                    <p v-else class="py-6 text-sm text-slate-500">No pending recommendations right now.</p>
                </section>

                <aside class="space-y-8">
                    <section class="rounded-md bg-brand-900 p-5 text-white" aria-labelledby="placement-time-title">
                        <p class="text-xs font-semibold uppercase tracking-[0.16em] text-brand-50/75">Today's throughput</p>
                        <h3 id="placement-time-title" class="mt-3 text-sm font-medium text-brand-50">Average time to placement</h3>
                        <p class="mt-1 text-3xl font-semibold tabular-nums">{{ props.metrics.averagePlacementMinutes }} <span class="text-base font-medium text-brand-50/80">min</span></p>
                        <p class="mt-3 text-xs leading-5 text-brand-50/70">Based on accepted allocations made today.</p>
                    </section>

                    <section aria-labelledby="admin-links-title">
                        <h3 id="admin-links-title" class="border-b border-[#c9ddd4] pb-3 text-sm font-semibold uppercase tracking-[0.16em] text-brand-800">Administration</h3>
                        <nav class="mt-1" aria-label="Administrative links">
                            <Link v-for="shortcut in shortcuts" :key="shortcut.title" :href="shortcut.href" class="block border-b border-[#dce9e3] px-2 py-3 transition hover:bg-white focus:bg-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-brand-400">
                                <span class="block font-semibold text-slate-900">{{ shortcut.title }}</span>
                                <span class="mt-1 block text-sm text-slate-500">{{ shortcut.description }}</span>
                            </Link>
                        </nav>
                    </section>
                </aside>
            </div>
        </div>
    </AuthenticatedLayout>
</template>