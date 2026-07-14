<script setup>
import AuthLayout from '@/Layouts/AuthLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import PasswordInput from '@/Components/PasswordInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    password: '',
});

const submit = () => {
    form.post(route('password.confirm'), {
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <AuthLayout
        panel-badge="Secure action"
        panel-title="Confirm your password"
        panel-description="This is a protected step used to keep sensitive actions behind a fresh password check."
        :panel-points="['Fresh verification', 'Protects sensitive settings', 'Keeps secure areas locked']"
        panel-callout="After confirmation, you will return to the requested secure page."
    >
        <Head title="Confirm Password" />

        <div class="space-y-3">
            <p class="text-sm font-semibold uppercase tracking-[0.35em] text-brand-800">Secure action</p>
            <h1 class="text-3xl font-semibold text-slate-900">Confirm your password</h1>
        </div>

        <div class="mt-4 text-sm leading-6 text-slate-600">
            This is a secure area of the application. Please confirm your password before continuing.
        </div>

        <form class="mt-8 space-y-5" @submit.prevent="submit">
            <div>
                <InputLabel for="password" value="Password" />
                <PasswordInput
                    id="password"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                    autofocus
                    class="mt-1 block w-full"
                />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="flex justify-end">
                <PrimaryButton
                    class="rounded-2xl px-6 py-3"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Confirm
                </PrimaryButton>
            </div>
        </form>
    </AuthLayout>
</template>