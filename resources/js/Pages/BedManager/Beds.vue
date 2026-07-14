<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

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

const bedTone = (status) => {
    const map = {
        vacant: 'bg-emerald-50 text-emerald-700 ring-emerald-200',
        occupied: 'bg-slate-200 text-slate-700 ring-slate-300',
        reserved: 'bg-amber-50 text-amber-700 ring-amber-200',
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
        <template #header>
            <h2 class="text-2xl font-semibold leading-tight text-slate-900">Bed Board</h2>
        </template>

        <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
            <div class="grid gap-8 xl:grid-cols-[0.9fr_1.1fr]">
                <form class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm" @submit.prevent="submitCreate">
                    <h3 class="text-lg font-semibold text-slate-900">Create bed</h3>
                    <div class="mt-5 space-y-4">
                        <div>
                            <InputLabel for="ward_id" value="Ward" />
                            <select id="ward_id" v-model="createForm.ward_id" class="mt-1 block w-full rounded-lg border-slate-300">
                                <option v-for="ward in props.wards" :key="ward.id" :value="ward.id">{{ ward.name }}</option>
                            </select>
                            <InputError class="mt-2" :message="createForm.errors.ward_id" />
                        </div>
                        <div>
                            <InputLabel for="bed_number" value="Bed number" />
                            <TextInput id="bed_number" v-model="createForm.bed_number" class="mt-1 block w-full" required />
                            <InputError class="mt-2" :message="createForm.errors.bed_number" />
                        </div>
                        <div>
                            <InputLabel for="status" value="Status" />
                            <select id="status" v-model="createForm.status" class="mt-1 block w-full rounded-lg border-slate-300">
                                <option value="vacant">Vacant</option>
                                <option value="occupied">Occupied</option>
                                <option value="reserved">Reserved</option>
                            </select>
                            <InputError class="mt-2" :message="createForm.errors.status" />
                        </div>
                    </div>
                    <PrimaryButton class="mt-6" :disabled="createForm.processing">Create bed</PrimaryButton>
                </form>

                <div class="grid gap-4 lg:grid-cols-2">
                    <section v-for="ward in props.wards" :key="ward.id" class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h3 class="text-xl font-semibold text-slate-900">{{ ward.name }}</h3>
                                <p class="mt-1 text-sm text-slate-500">{{ ward.specialization ?? 'General' }}</p>
                            </div>
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">{{ ward.beds_count }} beds</span>
                        </div>

                        <div class="mt-4 space-y-3">
                            <article v-for="bed in ward.beds" :key="bed.id" class="rounded-2xl border border-slate-200 p-4">
                                <div class="flex flex-wrap items-center justify-between gap-3">
                                    <div>
                                        <p class="font-medium text-slate-900">Bed {{ bed.bed_number }}</p>
                                        <p class="text-xs text-slate-500">ID {{ bed.id }}</p>
                                    </div>
                                    <span :class="['rounded-full px-3 py-1 text-xs font-semibold ring-1 ring-inset', bedTone(bed.status)]">{{ bed.status }}</span>
                                </div>
                                <div class="mt-3 flex flex-wrap items-center gap-3">
                                    <select v-model="bedForms[bed.id].status" class="rounded-lg border-slate-300 text-sm">
                                        <option value="vacant">Vacant</option>
                                        <option value="occupied">Occupied</option>
                                        <option value="reserved">Reserved</option>
                                    </select>
                                    <button type="button" class="rounded-lg bg-slate-900 px-3 py-2 text-sm font-semibold text-white" @click="updateBed(bed.id)">Save</button>
                                    <button type="button" class="rounded-lg border border-rose-300 px-3 py-2 text-sm font-semibold text-rose-600" @click="deleteBed(bed.id)">Delete</button>
                                </div>
                            </article>
                        </div>
                    </section>
                </div>
            </div>

            <aside class="mt-8 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-slate-900">Pending recommendations</h3>
                <div v-if="props.pendingAllocations.length" class="mt-4 space-y-4">
                    <article v-for="allocation in props.pendingAllocations" :key="allocation.id" class="rounded-2xl bg-slate-50 p-4">
                        <p class="font-medium text-slate-900">{{ allocation.triage_record.patient.name }}</p>
                        <p class="mt-1 text-sm text-slate-500">Score {{ allocation.triage_record.computed_score }} · {{ allocation.triage_record.urgency_level }}</p>
                        <p class="mt-1 text-sm text-slate-500">Recommended bed: {{ allocation.recommended_bed?.ward?.name ?? 'No ward matched' }} / {{ allocation.recommended_bed?.bed_number ?? '—' }}</p>
                    </article>
                </div>
                <p v-else class="mt-4 text-sm text-slate-500">No pending recommendations right now.</p>
            </aside>
        </div>
    </AuthenticatedLayout>
</template>
