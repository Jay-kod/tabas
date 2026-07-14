<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const triageResult = computed(() => page.props.flash?.triageResult ?? null);
const triageAllocation = computed(() => page.props.flash?.triageAllocation ?? null);

const form = useForm({
    patient_name: '',
    patient_age: '',
    patient_sex: '',
    patient_hospital_id: '',
    patient_contact: '',
    ward_specialization: 'General',
    resp_rate: '',
    spo2: '',
    systolic_bp: '',
    heart_rate: '',
    consciousness: 'A',
    temperature: '',
});

const submit = () => {
    form.post(route('triage-records.store'), { preserveScroll: true });
};
</script>

<template>
    <Head title="New Triage" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-2xl font-semibold leading-tight text-slate-900">New Triage Intake</h2>
        </template>

        <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
            <div class="grid gap-8 lg:grid-cols-[1.3fr_0.7fr]">
                <form class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm" @submit.prevent="submit">
                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <InputLabel for="patient_name" value="Patient name" />
                            <TextInput id="patient_name" v-model="form.patient_name" class="mt-1 block w-full" required />
                            <InputError class="mt-2" :message="form.errors.patient_name" />
                        </div>
                        <div>
                            <InputLabel for="patient_age" value="Age" />
                            <TextInput id="patient_age" v-model="form.patient_age" type="number" class="mt-1 block w-full" />
                            <InputError class="mt-2" :message="form.errors.patient_age" />
                        </div>
                        <div>
                            <InputLabel for="patient_sex" value="Sex" />
                            <TextInput id="patient_sex" v-model="form.patient_sex" class="mt-1 block w-full" />
                        </div>
                        <div>
                            <InputLabel for="patient_hospital_id" value="Hospital ID" />
                            <TextInput id="patient_hospital_id" v-model="form.patient_hospital_id" class="mt-1 block w-full" />
                        </div>
                        <div>
                            <InputLabel for="patient_contact" value="Contact" />
                            <TextInput id="patient_contact" v-model="form.patient_contact" class="mt-1 block w-full" />
                        </div>
                        <div>
                            <InputLabel for="ward_specialization" value="Ward specialization" />
                            <TextInput id="ward_specialization" v-model="form.ward_specialization" class="mt-1 block w-full" />
                        </div>
                        <div>
                            <InputLabel for="resp_rate" value="Respiratory rate" />
                            <TextInput id="resp_rate" v-model="form.resp_rate" type="number" class="mt-1 block w-full" required />
                        </div>
                        <div>
                            <InputLabel for="spo2" value="SpO2" />
                            <TextInput id="spo2" v-model="form.spo2" type="number" class="mt-1 block w-full" required />
                        </div>
                        <div>
                            <InputLabel for="systolic_bp" value="Systolic BP" />
                            <TextInput id="systolic_bp" v-model="form.systolic_bp" type="number" class="mt-1 block w-full" required />
                        </div>
                        <div>
                            <InputLabel for="heart_rate" value="Heart rate" />
                            <TextInput id="heart_rate" v-model="form.heart_rate" type="number" class="mt-1 block w-full" required />
                        </div>
                        <div>
                            <InputLabel for="consciousness" value="Consciousness (AVPU)" />
                            <TextInput id="consciousness" v-model="form.consciousness" class="mt-1 block w-full" required />
                        </div>
                        <div>
                            <InputLabel for="temperature" value="Temperature" />
                            <TextInput id="temperature" v-model="form.temperature" type="number" step="0.1" class="mt-1 block w-full" required />
                        </div>
                    </div>

                    <div class="mt-6 flex items-center gap-4">
                        <PrimaryButton :disabled="form.processing">{{ form.processing ? 'Saving…' : 'Submit triage' }}</PrimaryButton>
                        <p class="text-sm text-slate-500">The score is computed immediately after submission.</p>
                    </div>
                </form>

                <aside class="rounded-3xl border border-slate-200 bg-slate-900 p-6 text-white shadow-sm">
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-sky-300">Live result</p>
                    <div v-if="triageResult" class="mt-4 space-y-3">
                        <div class="text-4xl font-semibold">{{ triageResult.score }}</div>
                        <div class="inline-flex rounded-full bg-white/10 px-3 py-1 text-sm font-semibold">{{ triageResult.category }}</div>
                        <div v-if="triageAllocation" class="rounded-2xl bg-white/10 p-3 text-sm text-slate-200">
                            Recommended bed: {{ triageAllocation.recommendedBed?.ward?.name ?? 'No vacant matching bed found' }} / {{ triageAllocation.recommendedBed?.bed_number ?? '—' }}
                        </div>
                    </div>
                    <div v-else class="mt-4 text-sm text-slate-300">
                        Submit vitals to generate the computed score and recommended placement.
                    </div>
                </aside>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
