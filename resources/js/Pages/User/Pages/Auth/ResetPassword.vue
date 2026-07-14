<script setup>
import AuthLayout from '@/Layouts/AuthLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import PasswordInput from '@/Components/PasswordInput.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <AuthLayout
        panel-badge="Reset password"
        panel-title="Set a new password"
        panel-description="Use the reset link from your email to create a new password and regain access to your account."
        :panel-points="['Secure password reset', 'Email token verified', 'Account access restored quickly']"
        panel-callout="Choose a strong password you have not used before."
    >
        <Head title="Reset Password" />

        <div class="space-y-3">
            <p class="text-sm font-semibold uppercase tracking-[0.35em] text-brand-800">Reset password</p>
            <h1 class="text-3xl font-semibold text-slate-900">Set a new password</h1>
        </div>

        <form class="mt-8 space-y-5" @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div>
                <InputLabel for="password" value="Password" />

                <PasswordInput
                    id="password"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                    class="mt-1 block w-full"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div>
                <InputLabel for="password_confirmation" value="Confirm Password" />

                <PasswordInput
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                    class="mt-1 block w-full"
                />

                <InputError class="mt-2" :message="form.errors.password_confirmation" />
            </div>

            <div class="flex items-center justify-end">
                <PrimaryButton class="rounded-2xl px-6 py-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Reset Password
                </PrimaryButton>
            </div>
        </form>
    </AuthLayout>
</template>