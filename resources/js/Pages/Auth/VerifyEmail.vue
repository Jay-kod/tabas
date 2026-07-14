<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    status: {
        type: String,
    },
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

</script>

<template>
    <GuestLayout>
        <Head title="Verify Email" />

        <div class="grid min-h-[calc(100vh-4rem)] gap-8 rounded-[2rem] border border-white/70 bg-gradient-to-br from-slate-950 via-slate-900 to-sky-900 p-6 text-white shadow-2xl sm:p-10 lg:grid-cols-[1.1fr_0.9fr]">
            <div class="flex flex-col justify-between gap-8">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.35em] text-sky-300">TABAS</p>
                    <h1 class="mt-4 max-w-xl text-4xl font-semibold leading-tight sm:text-5xl">
                        Verify your email before you enter the triage workspace.
                    </h1>
                    <p class="mt-5 max-w-2xl text-base leading-7 text-slate-300">
                        This extra step keeps every patient record and bed allocation tied to a verified account. If the email is missing, resend the link and check the inbox again.
                    </p>
                </div>

                <div class="grid gap-4 sm:grid-cols-3">
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                        <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Step 1</p>
                        <p class="mt-2 text-sm text-slate-200">Open the verification email.</p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                        <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Step 2</p>
                        <p class="mt-2 text-sm text-slate-200">Confirm your address.</p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                        <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Step 3</p>
                        <p class="mt-2 text-sm text-slate-200">Return to the workflow dashboard.</p>
                    </div>
                </div>
            </div>

            <div class="rounded-[1.75rem] border border-white/10 bg-white/10 p-6 backdrop-blur-xl sm:p-8">
                <div class="rounded-3xl bg-white p-6 text-slate-900 shadow-xl">
                    <div class="inline-flex rounded-full bg-sky-50 px-3 py-1 text-xs font-semibold uppercase tracking-[0.25em] text-sky-700">
                        Email verification required
                    </div>

                    <p class="mt-5 text-sm leading-6 text-slate-600">
                        Resend the verification message if needed, then click the link from your inbox to continue.
                    </p>

                    <form class="mt-6" @submit.prevent="submit">
                        <div class="flex flex-col gap-3 sm:flex-row">
                            <PrimaryButton
                                class="justify-center"
                                :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing"
                            >
                                Resend verification email
                            </PrimaryButton>

                            <Link
                                :href="route('logout')"
                                method="post"
                                as="button"
                                class="inline-flex items-center justify-center rounded-2xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50"
                            >
                                Log out
                            </Link>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>
