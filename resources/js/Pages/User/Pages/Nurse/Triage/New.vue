<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const page = usePage();
const triageResult = computed(() => page.props.flash?.triageResult ?? null);
const triageAllocation = computed(() => page.props.flash?.triageAllocation ?? null);
const showSampleResult = ref(false);
const sampleResult = { score: 6, category: 'Urgent' };
const sampleAllocation = { recommendedBed: { ward: { name: 'General Ward' }, bed_number: '01' } };
const displayedResult = computed(() => triageResult.value ?? (showSampleResult.value ? sampleResult : null));
const displayedAllocation = computed(() => triageAllocation.value ?? (showSampleResult.value ? sampleAllocation : null));

const resultTone = computed(() => {
    const tones = {
        Critical: 'border-critical-text bg-critical-bg text-critical-text',
        Urgent: 'border-urgent-text bg-urgent-bg text-urgent-text',
        Standard: 'border-standard-text bg-standard-bg text-standard-text',
        'Non-urgent': 'border-nonurgent-text bg-nonurgent-bg text-nonurgent-text',
    };

    return tones[displayedResult.value?.category] ?? 'border-brand-400 bg-brand-50 text-brand-800';
});

const form = useForm({
    patient_name: '',
    patient_age: '',
    patient_sex: '',
    patient_hospital_id: '',
    patient_contact: '',
    presenting_complaint: '',
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
        <div class="grid gap-8 xl:grid-cols-[minmax(0,1fr)_300px] xl:items-start">
                            <form class="min-w-0" @submit.prevent="submit">
                                <section class="border-b border-[#c9ddd4] pb-7" aria-labelledby="patient-details-title">
                                    <div class="mb-5 flex items-start gap-3">
                                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-md bg-brand-900 text-xs font-semibold text-white">01</span>
                                        <div>
                                            <h3 id="patient-details-title" class="text-lg font-semibold text-slate-950">Patient details</h3>
                                            <p class="mt-1 text-sm text-slate-500">Identify the patient and record the reason for assessment.</p>
                                        </div>
                                    </div>

                                    <div class="grid gap-x-5 gap-y-4 sm:grid-cols-2 xl:grid-cols-3">
                                        <div class="sm:col-span-2 xl:col-span-2">
                                            <InputLabel for="patient_name" value="Patient name *" class="text-xs font-semibold text-slate-600" />
                                            <TextInput id="patient_name" v-model="form.patient_name" autocomplete="off" class="mt-1.5 block min-h-11 w-full rounded-md border-[#c9ddd4] focus:border-brand-600 focus:ring-brand-600" required />
                                            <InputError class="mt-1" :message="form.errors.patient_name" />
                                        </div>
                                        <div>
                                            <InputLabel for="patient_age" value="Age" class="text-xs font-semibold text-slate-600" />
                                            <TextInput id="patient_age" v-model="form.patient_age" type="number" min="0" max="120" class="mt-1.5 block min-h-11 w-full rounded-md border-[#c9ddd4] focus:border-brand-600 focus:ring-brand-600" />
                                            <InputError class="mt-1" :message="form.errors.patient_age" />
                                        </div>
                                        <div>
                                            <InputLabel for="patient_sex" value="Sex" class="text-xs font-semibold text-slate-600" />
                                            <TextInput id="patient_sex" v-model="form.patient_sex" class="mt-1.5 block min-h-11 w-full rounded-md border-[#c9ddd4] focus:border-brand-600 focus:ring-brand-600" />
                                        </div>
                                        <div>
                                            <InputLabel for="patient_hospital_id" value="Hospital ID" class="text-xs font-semibold text-slate-600" />
                                            <TextInput id="patient_hospital_id" v-model="form.patient_hospital_id" class="mt-1.5 block min-h-11 w-full rounded-md border-[#c9ddd4] focus:border-brand-600 focus:ring-brand-600" />
                                        </div>
                                        <div>
                                            <InputLabel for="patient_contact" value="Contact number" class="text-xs font-semibold text-slate-600" />
                                            <TextInput id="patient_contact" v-model="form.patient_contact" type="tel" class="mt-1.5 block min-h-11 w-full rounded-md border-[#c9ddd4] focus:border-brand-600 focus:ring-brand-600" />
                                        </div>
                                        <div class="sm:col-span-2 xl:col-span-3">
                                            <InputLabel for="presenting_complaint" value="Presenting complaint *" class="text-xs font-semibold text-slate-600" />
                                            <textarea id="presenting_complaint" v-model="form.presenting_complaint" rows="3" maxlength="1000" required class="mt-1.5 block w-full resize-y rounded-md border-[#c9ddd4] text-sm shadow-sm focus:border-brand-600 focus:ring-brand-600"></textarea>
                                            <InputError class="mt-1" :message="form.errors.presenting_complaint" />
                                        </div>
                                        <div>
                                            <InputLabel for="ward_specialization" value="Ward specialization" class="text-xs font-semibold text-slate-600" />
                                            <TextInput id="ward_specialization" v-model="form.ward_specialization" class="mt-1.5 block min-h-11 w-full rounded-md border-[#c9ddd4] focus:border-brand-600 focus:ring-brand-600" />
                                        </div>
                                    </div>
                                </section>

                                <section class="pt-7" aria-labelledby="vitals-title">
                                    <div class="mb-5 flex items-start gap-3">
                                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-md bg-brand-900 text-xs font-semibold text-white">02</span>
                                        <div>
                                            <h3 id="vitals-title" class="text-lg font-semibold text-slate-950">Initial observations</h3>
                                            <p class="mt-1 text-sm text-slate-500">Enter the measured values for this assessment.</p>
                                        </div>
                                    </div>

                                    <div class="grid gap-x-5 gap-y-4 sm:grid-cols-2 xl:grid-cols-3">
                                        <div>
                                            <InputLabel for="resp_rate" value="Respiratory rate" class="text-xs font-semibold text-slate-600" />
                                            <div class="relative mt-1.5">
                                                <TextInput id="resp_rate" v-model="form.resp_rate" type="number" min="0" max="100" class="block min-h-11 w-full rounded-md border-[#c9ddd4] pr-16 focus:border-brand-600 focus:ring-brand-600" required />
                                                <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-xs text-slate-400">/min</span>
                                            </div>
                                            <InputError class="mt-1" :message="form.errors.resp_rate" />
                                        </div>
                                        <div>
                                            <InputLabel for="spo2" value="Oxygen saturation" class="text-xs font-semibold text-slate-600" />
                                            <div class="relative mt-1.5">
                                                <TextInput id="spo2" v-model="form.spo2" type="number" min="0" max="100" class="block min-h-11 w-full rounded-md border-[#c9ddd4] pr-12 focus:border-brand-600 focus:ring-brand-600" required />
                                                <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-xs text-slate-400">% SpO2</span>
                                            </div>
                                            <InputError class="mt-1" :message="form.errors.spo2" />
                                        </div>
                                        <div>
                                            <InputLabel for="systolic_bp" value="Systolic blood pressure" class="text-xs font-semibold text-slate-600" />
                                            <div class="relative mt-1.5">
                                                <TextInput id="systolic_bp" v-model="form.systolic_bp" type="number" min="0" max="300" class="block min-h-11 w-full rounded-md border-[#c9ddd4] pr-12 focus:border-brand-600 focus:ring-brand-600" required />
                                                <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-xs text-slate-400">mmHg</span>
                                            </div>
                                            <InputError class="mt-1" :message="form.errors.systolic_bp" />
                                        </div>
                                        <div>
                                            <InputLabel for="heart_rate" value="Heart rate" class="text-xs font-semibold text-slate-600" />
                                            <div class="relative mt-1.5">
                                                <TextInput id="heart_rate" v-model="form.heart_rate" type="number" min="0" max="250" class="block min-h-11 w-full rounded-md border-[#c9ddd4] pr-16 focus:border-brand-600 focus:ring-brand-600" required />
                                                <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-xs text-slate-400">bpm</span>
                                            </div>
                                            <InputError class="mt-1" :message="form.errors.heart_rate" />
                                        </div>
                                        <div>
                                            <InputLabel for="temperature" value="Temperature" class="text-xs font-semibold text-slate-600" />
                                            <div class="relative mt-1.5">
                                                <TextInput id="temperature" v-model="form.temperature" type="number" min="30" max="45" step="0.1" class="block min-h-11 w-full rounded-md border-[#c9ddd4] pr-12 focus:border-brand-600 focus:ring-brand-600" required />
                                                <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-xs text-slate-400">°C</span>
                                            </div>
                                            <InputError class="mt-1" :message="form.errors.temperature" />
                                        </div>
                                        <div>
                                            <InputLabel for="consciousness" value="Consciousness (AVPU)" class="text-xs font-semibold text-slate-600" />
                                            <select id="consciousness" v-model="form.consciousness" required class="mt-1.5 block min-h-11 w-full rounded-md border-[#c9ddd4] text-sm focus:border-brand-600 focus:ring-brand-600">
                                                <option value="A">A - Alert</option>
                                                <option value="V">V - Responds to voice</option>
                                                <option value="P">P - Responds to pain</option>
                                                <option value="U">U - Unresponsive</option>
                                            </select>
                                            <InputError class="mt-1" :message="form.errors.consciousness" />
                                        </div>
                                    </div>

                                    <div class="mt-7 flex flex-col gap-3 border-t border-[#c9ddd4] pt-5 sm:flex-row sm:items-center sm:justify-between">
                                        <p class="max-w-lg text-xs leading-5 text-slate-500">This score is a simplified teaching model and is not a validated clinical decision system.</p>
                                        <PrimaryButton class="min-h-11 justify-center rounded-md px-5 text-xs" :disabled="form.processing">
                                            {{ form.processing ? 'Saving assessment...' : 'Submit assessment' }}
                                        </PrimaryButton>
                                    </div>
                                </section>
                            </form>

                            <aside class="xl:sticky xl:top-24" aria-labelledby="result-title">
                                <div class="border-t-4 border-brand-600 bg-brand-900 p-5 text-white shadow-sm sm:p-6">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-brand-50/70">Assessment output</p>
                                            <h3 id="result-title" class="mt-2 text-xl font-semibold">Triage result</h3>
                                        </div>
                                        <button
                                            v-if="!triageResult"
                                            type="button"
                                            class="shrink-0 rounded-md border border-brand-50/25 px-2.5 py-2 text-xs font-semibold text-brand-50 transition hover:bg-white/10 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-50"
                                            @click="showSampleResult = !showSampleResult"
                                        >
                                            {{ showSampleResult ? 'Clear sample' : 'Show sample' }}
                                        </button>
                                    </div>

                                    <div v-if="displayedResult" class="mt-6">
                                        <p v-if="!triageResult && showSampleResult" class="mb-4 border-l-2 border-brand-400 bg-white/5 px-3 py-2 text-[10px] font-semibold uppercase tracking-[0.12em] text-brand-50/80">Sample preview · not saved</p>
                                        <div class="flex items-end gap-2">
                                            <p class="text-5xl font-semibold tabular-nums">{{ displayedResult.score }}</p>
                                            <p class="pb-1 text-sm text-brand-50/70">score</p>
                                        </div>
                                        <div :class="['mt-4 inline-flex border-l-2 px-3 py-2 text-sm font-semibold', resultTone]">{{ displayedResult.category }}</div>

                                        <div class="mt-6 border-t border-white/15 pt-5">
                                            <p class="text-[11px] font-semibold uppercase tracking-[0.15em] text-brand-50/60">Recommended placement</p>
                                            <p class="mt-2 font-semibold">{{ displayedAllocation?.recommendedBed?.ward?.name ?? 'No vacant matching bed found' }}</p>
                                            <p class="mt-1 text-sm text-brand-50/70">{{ displayedAllocation?.recommendedBed?.bed_number ? `Bed ${displayedAllocation.recommendedBed.bed_number}` : 'Review availability with the bed manager' }}</p>
                                        </div>
                                    </div>

                                    <div v-else class="mt-6 border-t border-white/15 pt-5">
                                        <div class="flex h-10 w-10 items-center justify-center rounded-md border border-brand-50/20 text-brand-50/70" aria-hidden="true">
                                            <svg viewBox="0 0 24 24" class="h-5 w-5 fill-none stroke-current stroke-1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l2.5 2.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                        </div>
                                        <p class="mt-4 text-sm font-medium">No result yet</p>
                                        <p class="mt-2 text-sm leading-6 text-brand-50/70">Complete the observations and submit the assessment to calculate the score and suggested placement.</p>
                                    </div>
                                </div>

                                <p class="mt-4 border-l-2 border-brand-400 pl-3 text-xs leading-5 text-slate-500">Use clinical judgment and local protocols. This system supports workflow; it does not replace professional assessment.</p>
                            </aside>
                        </div>
                    </AuthenticatedLayout>
                </template>