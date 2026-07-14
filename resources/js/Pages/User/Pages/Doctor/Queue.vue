<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    pendingAllocations: {
        type: Array,
        required: true,
    },
});

const selectedAllocation = ref(null);
const showOverrideModal = ref(false);
const form = useForm({
    status: 'accepted',
    reason: '',
});

const accept = (allocation) => {
    form.status = 'accepted';
    form.reason = '';
    form.patch(route('allocations.update', allocation.id), {
        preserveScroll: true,
    });
};

const openOverride = (allocation) => {
    selectedAllocation.value = allocation;
    form.status = 'overridden';
    form.reason = '';
    showOverrideModal.value = true;
};

const submitOverride = () => {
    form.patch(route('allocations.update', selectedAllocation.value.id), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
    });
};

const closeModal = () => {
    showOverrideModal.value = false;
    selectedAllocation.value = null;
    form.reset('reason');
};
</script>

<template>
    <Head title="Doctor Queue" />

    <AuthenticatedLayout>
        <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
            <div class="grid gap-4">
                <article v-for="item in props.pendingAllocations" :key="item.id" class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900">{{ item.triage_record.patient.name }}</h3>
                            <p class="text-sm text-slate-500">Recommended bed: {{ item.recommended_bed?.ward?.name ?? 'No ward matched' }} / {{ item.recommended_bed?.bed_number ?? '—' }}</p>
                            <p class="mt-1 text-sm text-slate-500">Vitals score: {{ item.triage_record.computed_score }} · {{ item.triage_record.urgency_level }}</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="rounded-full bg-standard-bg px-3 py-1 text-xs font-semibold text-standard-text">Score {{ item.triage_record.computed_score }}</span>
                            <PrimaryButton @click="accept(item)">Accept</PrimaryButton>
                            <button type="button" class="rounded-lg border border-urgent-bg px-4 py-2 text-sm font-semibold text-urgent-text hover:bg-urgent-bg" @click="openOverride(item)">Override</button>
                        </div>
                    </div>
                </article>

                <p v-if="!props.pendingAllocations.length" class="rounded-3xl border border-dashed border-slate-300 bg-white p-8 text-center text-sm text-slate-500">
                    No pending allocations right now.
                </p>
            </div>
        </div>

        <Modal :show="showOverrideModal" maxWidth="lg" @close="closeModal">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-slate-900">Override recommendation</h3>
                <p class="mt-1 text-sm text-slate-500">A reason is required to override the recommended bed.</p>

                <div class="mt-5">
                    <label class="text-sm font-medium text-slate-700">Reason</label>
                    <TextInput v-model="form.reason" class="mt-1 block w-full" placeholder="Explain the clinical or operational reason" />
                    <p v-if="form.errors.reason" class="mt-2 text-sm text-red-600">{{ form.errors.reason }}</p>
                </div>

                <div class="mt-6 flex items-center justify-end gap-3">
                    <button type="button" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50" @click="closeModal">Cancel</button>
                    <PrimaryButton :disabled="form.processing" @click="submitOverride">Override</PrimaryButton>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>