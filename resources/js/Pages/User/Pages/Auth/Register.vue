<script setup>
import AuthLayout from '@/Layouts/AuthLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import PasswordInput from '@/Components/PasswordInput.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <AuthLayout
        panel-badge="Create account"
        panel-title="Join the TABAS workspace"
        panel-description="Register with your name and email address so the hospital team can assign the correct workflow role."
        :panel-points="['Role assignment ready', 'Secure onboarding', 'One account per staff member']"
        panel-callout="After registration you will be logged in automatically and taken to the dashboard."
    >
        <Head title="Register" />

        <div class="space-y-3">
            <p class="text-sm font-semibold uppercase tracking-[0.35em] text-brand-800">Create account</p>
            <h1 class="text-3xl font-semibold text-slate-900">Join the TABAS workspace</h1>
            <p class="max-w-2xl text-sm leading-6 text-slate-600">
                Register with your name and email address so the hospital team can assign the correct workflow role.
            </p>
        </div>

        <form class="mt-8 space-y-5" @submit.prevent="submit">
            <div>
                <InputLabel for="name" value="Name" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div>
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
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

            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <Link
                    :href="route('login')"
                    class="text-sm font-medium text-brand-800 underline-offset-4 transition hover:text-brand-900 hover:underline focus:outline-none focus:ring-2 focus:ring-brand-400 focus:ring-offset-2"
                >
                    Already registered?
                </Link>
            </div>

            <PrimaryButton class="w-full justify-center rounded-2xl py-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Register
            </PrimaryButton>
        </form>
    </AuthLayout>
</template>