<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    triageRecords: {
        type: Array,
        required: true,
    },
});

const urgencyTone = (urgency) => {
    const map = {
        Critical: 'bg-critical-bg text-critical-text',
        Urgent: 'bg-urgent-bg text-urgent-text',
        Standard: 'bg-standard-bg text-standard-text',
        'Non-urgent': 'bg-nonurgent-bg text-nonurgent-text',
    };

    return map[urgency] ?? 'bg-brand-50 text-brand-800';
};
</script>

<template>
    <Head title="Today's Patients" />

    <AuthenticatedLayout>
        <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
            <div v-if="props.triageRecords.length" class="grid gap-4">
                <div v-for="record in props.triageRecords" :key="record.id" class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900">{{ record.patient.name }}</h3>
                            <p class="text-sm text-slate-500">{{ record.created_at }}</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="rounded-full bg-standard-bg px-3 py-1 text-xs font-semibold text-standard-text">Score {{ record.computed_score }}</span>
                            <span class="rounded-full px-3 py-1 text-xs font-semibold" :class="urgencyTone(record.urgency_level)">{{ record.urgency_level }}</span>
                        </div>
                    </div>

                    <div class="mt-4 grid gap-3 text-sm text-slate-600 md:grid-cols-2 xl:grid-cols-4">
                        <div>Resp rate: {{ record.resp_rate }}</div>
                        <div>SpO2: {{ record.spo2 }}</div>
                        <div>BP: {{ record.systolic_bp }}</div>
                        <div>Heart rate: {{ record.heart_rate }}</div>
                    </div>

                    <div class="mt-4 text-sm text-slate-600">
                        Allocation status: {{ record.allocation ? record.allocation.status : 'pending' }}
                    </div>
                    <div v-if="record.allocation?.recommendedBed" class="mt-1 text-sm text-slate-600">
                        Recommended bed: {{ record.allocation.recommendedBed.ward.name }} / {{ record.allocation.recommendedBed.bed_number }}
                    </div>
                </div>
            </div>

            <p v-else class="rounded-3xl border border-dashed border-slate-300 bg-white p-8 text-center text-sm text-slate-500">
                No triage records created today yet.
            </p>
        </div>
    </AuthenticatedLayout>
</template>