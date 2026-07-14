<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import PasswordInput from '@/Components/PasswordInput.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    roleContext: {
        type: Object,
        default: null,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <AuthLayout
        :panel-badge="roleContext?.badge ?? 'Secure access'"
        :panel-title="roleContext?.title ?? 'One workspace for every TABAS role.'"
        :panel-description="roleContext?.description ?? 'Sign in with your role account, keep the remember-me option on for trusted devices, and use the same login area for all system users.'"
        :panel-points="roleContext?.points ?? ['Role-aware access', 'Fast, secure sign in', 'Works well on mobile and desktop']"
        :panel-callout="roleContext ? 'Use the login link for your role. Admin access remains separate from the public role cards.' : 'Demo accounts are seeded with the same password: password.'"
    >
        <Head :title="roleContext?.label ? `${roleContext.label} Login` : 'Log in'" />

        <div class="space-y-3">
            <p class="text-sm font-semibold uppercase tracking-[0.35em] text-brand-800">Welcome back</p>
            <h1 class="text-3xl font-semibold text-slate-900">{{ roleContext?.label ? `${roleContext.label} sign in` : 'Sign in to TABAS' }}</h1>
            <p class="max-w-2xl text-sm leading-6 text-slate-600">
                {{ roleContext?.description ?? 'Use your role account to continue into the emergency workflow.' }}
            </p>
        </div>

        <div v-if="status" class="mt-6 rounded-2xl border border-nonurgent-bg bg-nonurgent-bg px-4 py-3 text-sm text-nonurgent-text">
            {{ status }}
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
                    autocomplete="current-password"
                    class="mt-1 block w-full"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <label class="flex items-center gap-3 text-sm text-slate-600">
                    <Checkbox id="remember" name="remember" v-model:checked="form.remember" />
                    <span>Remember me on this device</span>
                </label>

                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="text-sm font-medium text-brand-800 underline-offset-4 transition hover:text-brand-900 hover:underline focus:outline-none focus:ring-2 focus:ring-brand-400 focus:ring-offset-2"
                >
                    Forgot your password?
                </Link>
            </div>

            <PrimaryButton
                class="w-full justify-center rounded-2xl py-3"
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
            >
                Log in
            </PrimaryButton>
        </form>
    </AuthLayout>
</template>