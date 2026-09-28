<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    wards: {
        type: Array,
        required: true,
    },
    pendingAllocations: {
        type: Array,
        required: true,
    },
});

const createForm = useForm({
    ward_id: props.wards[0]?.id ?? '',
    bed_number: '',
    status: 'vacant',
});

const bedForms = {};
props.wards.forEach((ward) => {
    ward.beds.forEach((bed) => {
        bedForms[bed.id] = useForm({ status: bed.status });
    });
});

const bedSummary = computed(() => {
    const beds = props.wards.flatMap((ward) => ward.beds);

    return [
        { label: 'Total beds', value: beds.length, tone: 'border-brand-600' },
        { label: 'Vacant', value: beds.filter((bed) => bed.status === 'vacant').length, tone: 'border-nonurgent-text' },
        { label: 'Occupied', value: beds.filter((bed) => bed.status === 'occupied').length, tone: 'border-critical-text' },
        { label: 'Reserved', value: beds.filter((bed) => bed.status === 'reserved').length, tone: 'border-urgent-text' },
    ];
});

const bedTone = (status) => {
    const map = {
        vacant: 'bg-nonurgent-bg text-nonurgent-text ring-nonurgent-bg',
        occupied: 'bg-critical-bg text-critical-text ring-critical-bg',
        reserved: 'bg-urgent-bg text-urgent-text ring-urgent-bg',
    };

    return map[status] ?? map.vacant;
};

const submitCreate = () => {
    createForm.post(route('bed-manager.beds.store'), {
        preserveScroll: true,
        onSuccess: () => createForm.reset('bed_number'),
    });
};

const updateBed = (bedId) => {
    bedForms[bedId].patch(route('bed-manager.beds.update', bedId), { preserveScroll: true });
};

const deleteBed = (bedId) => {
    if (confirm('Delete this bed?')) {
        createForm.delete(route('bed-manager.beds.destroy', bedId), { preserveScroll: true });
    }
};
</script>

<template>
    <Head title="Bed Board" />

    <AuthenticatedLayout>
        <div class="space-y-8">
            <section class="grid grid-cols-2 gap-3 xl:grid-cols-4" aria-label="Bed capacity summary">
                <div v-for="metric in bedSummary" :key="metric.label" class="rounded-md border border-slate-200 border-l-4 bg-white px-4 py-3 shadow-sm" :class="metric.tone">
                    <p class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-500">{{ metric.label }}</p>
                    <p class="mt-2 text-2xl font-semibold tabular-nums text-slate-950">{{ metric.value }}</p>
                </div>
            </section>

            <section aria-labelledby="inventory-title">
                <div class="mb-4 flex flex-wrap items-end justify-between gap-3 border-b border-[#c9ddd4] pb-3">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.16em] text-brand-700">Capacity management</p>
                        <h3 id="inventory-title" class="mt-1 text-xl font-semibold text-slate-950">Ward inventory</h3>
                    </div>
                    <p class="text-sm text-slate-500">{{ props.wards.length }} wards <span class="mx-1 text-brand-400">·</span> {{ bedSummary[0].value }} beds</p>
                </div>

                <form class="mb-7 rounded-md border border-[#c9ddd4] bg-white p-4 shadow-sm sm:p-5" @submit.prevent="submitCreate">
                    <div class="mb-4 flex items-center justify-between gap-3">
                        <div>
                            <h4 class="font-semibold text-slate-900">Add a bed</h4>
                            <p class="mt-1 text-xs text-slate-500">Add capacity to a ward.</p>
                        </div>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-[minmax(180px,1fr)_minmax(150px,0.8fr)_minmax(150px,0.8fr)_auto] xl:items-end">
                        <div>
                            <InputLabel for="ward_id" value="Ward" class="text-xs font-semibold text-slate-600" />
                            <select id="ward_id" v-model="createForm.ward_id" class="mt-1 block min-h-10 w-full rounded-md border-[#c9ddd4] text-sm focus:border-brand-600 focus:ring-brand-600">
                                <option v-for="ward in props.wards" :key="ward.id" :value="ward.id">{{ ward.name }}</option>
                            </select>
                            <InputError class="mt-1" :message="createForm.errors.ward_id" />
                        </div>
                        <div>
                            <InputLabel for="bed_number" value="Bed number" class="text-xs font-semibold text-slate-600" />
                            <TextInput id="bed_number" v-model="createForm.bed_number" class="mt-1 block min-h-10 w-full rounded-md border-[#c9ddd4] text-sm focus:border-brand-600 focus:ring-brand-600" required />
                            <InputError class="mt-1" :message="createForm.errors.bed_number" />
                        </div>
                        <div>
                            <InputLabel for="status" value="Initial status" class="text-xs font-semibold text-slate-600" />
                            <select id="status" v-model="createForm.status" class="mt-1 block min-h-10 w-full rounded-md border-[#c9ddd4] text-sm focus:border-brand-600 focus:ring-brand-600">
                                <option value="vacant">Vacant</option>
                                <option value="occupied">Occupied</option>
                                <option value="reserved">Reserved</option>
                            </select>
                            <InputError class="mt-1" :message="createForm.errors.status" />
                        </div>
                        <PrimaryButton class="min-h-10 justify-center rounded-md px-5 text-xs" :disabled="createForm.processing">
                            {{ createForm.processing ? 'Adding...' : 'Add bed' }}
                        </PrimaryButton>
                    </div>
                </form>

                <div v-if="props.wards.length" class="space-y-6">
                    <section v-for="ward in props.wards" :key="ward.id" class="border-t border-[#c9ddd4] pt-4">
                        <div class="mb-3 flex flex-wrap items-baseline justify-between gap-2">
                            <div class="flex flex-wrap items-baseline gap-x-3 gap-y-1">
                                <h4 class="text-lg font-semibold text-slate-950">{{ ward.name }}</h4>
                                <span class="text-sm text-slate-500">{{ ward.specialization ?? 'General' }}</span>
                            </div>
                            <span class="text-xs font-medium text-slate-500">{{ ward.beds.length }} {{ ward.beds.length === 1 ? 'bed' : 'beds' }}</span>
                        </div>

                        <div v-if="ward.beds.length" class="grid gap-2 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">
                            <article v-for="bed in ward.beds" :key="bed.id" class="min-w-0 rounded-md border border-slate-200 bg-white p-3 shadow-sm">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-semibold text-slate-900">Bed {{ bed.bed_number }}</p>
                                        <p class="mt-0.5 text-[11px] text-slate-500">ID {{ bed.id }}</p>
                                    </div>
                                    <span :class="['shrink-0 rounded px-2 py-1 text-[10px] font-semibold uppercase', bedTone(bed.status)]">{{ bed.status }}</span>
                                </div>
                                <div class="mt-3 flex items-center gap-2">
                                    <select v-model="bedForms[bed.id].status" :aria-label="`Status for bed ${bed.bed_number}`" class="min-h-9 min-w-0 flex-1 rounded-md border-[#c9ddd4] py-1.5 text-xs focus:border-brand-600 focus:ring-brand-600">
                                        <option value="vacant">Vacant</option>
                                        <option value="occupied">Occupied</option>
                                        <option value="reserved">Reserved</option>
                                    </select>
                                    <button type="button" class="min-h-9 rounded-md bg-brand-900 px-3 text-xs font-semibold text-white transition hover:bg-brand-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-400 disabled:opacity-50" :disabled="bedForms[bed.id].processing" @click="updateBed(bed.id)">
                                        {{ bedForms[bed.id].processing ? 'Saving' : 'Save' }}
                                    </button>
                                </div>
                                <button type="button" class="mt-2 text-xs font-medium text-critical-text underline-offset-2 hover:underline focus:outline-none focus-visible:ring-2 focus-visible:ring-critical-text" @click="deleteBed(bed.id)">Remove bed</button>
                            </article>
                        </div>
                        <p v-else class="rounded-md border border-dashed border-[#c9ddd4] px-4 py-5 text-sm text-slate-500">No beds in this ward yet.</p>
                    </section>
                </div>
                <p v-else class="rounded-md border border-dashed border-[#c9ddd4] bg-white px-4 py-8 text-center text-sm text-slate-500">No wards are configured yet.</p>
            </section>

            <section aria-labelledby="recommendations-title">
                <div class="mb-3 flex items-end justify-between gap-3 border-b border-[#c9ddd4] pb-3">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.16em] text-brand-700">Allocation queue</p>
                        <h3 id="recommendations-title" class="mt-1 text-xl font-semibold text-slate-950">Pending recommendations</h3>
                    </div>
                    <span class="text-sm text-slate-500">{{ props.pendingAllocations.length }} waiting</span>
                </div>
                <div v-if="props.pendingAllocations.length" class="divide-y divide-[#dce9e3]">
                    <article v-for="allocation in props.pendingAllocations" :key="allocation.id" class="grid gap-2 py-3 sm:grid-cols-[minmax(0,1fr)_minmax(0,1fr)_minmax(0,1.3fr)] sm:items-center sm:gap-4">
                        <div class="min-w-0">
                            <p class="truncate font-semibold text-slate-900">{{ allocation.triage_record.patient.name }}</p>
                            <p class="text-xs text-slate-500">Score {{ allocation.triage_record.computed_score }} · {{ allocation.triage_record.urgency_level }}</p>
                        </div>
                        <span :class="['w-fit rounded px-2 py-1 text-xs font-semibold', bedTone(allocation.triage_record.urgency_level === 'Non-urgent' ? 'vacant' : allocation.triage_record.urgency_level === 'Critical' ? 'occupied' : 'reserved')]">{{ allocation.triage_record.urgency_level }}</span>
                        <p class="text-sm text-slate-600">{{ allocation.recommended_bed?.ward?.name ?? 'No ward matched' }} <span class="text-slate-400">/</span> {{ allocation.recommended_bed?.bed_number ?? 'No bed assigned' }}</p>
                    </article>
                </div>
                <p v-else class="py-5 text-sm text-slate-500">No pending recommendations right now.</p>
            </section>
        </div>
    </AuthenticatedLayout>
</template>