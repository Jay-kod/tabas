<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import PasswordInput from '@/Components/PasswordInput.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
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
    demoAccounts: {
        type: Object,
        default: () => ({})
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const demoEntries = computed(() => Object.entries(props.demoAccounts ?? {}).map(([key, value]) => ({
    key,
    label: value.label,
    email: value.email,
    password: value.password,
})));

const useDemoAccount = (account) => {
    form.email = account.email;
    form.password = account.password;
};

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
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-600">{{ roleContext?.label ?? 'Secure staff access' }}</p>
            <h1 class="text-4xl font-semibold leading-tight text-brand-900" style="font-family: Georgia, 'Times New Roman', serif;">Welcome back<span class="text-brand-400">.</span></h1>
            <p class="max-w-md text-sm leading-6 text-slate-600">
                Sign in to continue to your {{ roleContext?.label ? `${roleContext.label.toLowerCase()} workspace` : 'emergency care workspace' }}.
            </p>
        </div>

        <div v-if="status" class="mt-6 border-l-4 border-nonurgent-text bg-nonurgent-bg px-4 py-3 text-sm text-nonurgent-text" role="status">
            {{ status }}
        </div>

        <form class="mt-9 space-y-6" @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Work email" class="text-sm font-semibold text-slate-700" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-2 block w-full rounded-lg border-[#c9ddd4] bg-white px-4 py-3 text-brand-900 shadow-sm shadow-brand-900/[0.03] placeholder:text-slate-400 focus:border-brand-600 focus:ring-brand-600"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div>
                <div class="flex items-center justify-between">
                    <InputLabel for="password" value="Password" class="text-sm font-semibold text-slate-700" />
                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="text-xs font-semibold text-brand-700 underline-offset-4 transition hover:text-brand-900 hover:underline focus:outline-none focus:ring-2 focus:ring-brand-400 focus:ring-offset-2"
                    >
                        Forgot password?
                    </Link>
                </div>

                <PasswordInput
                    id="password"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                    class="mt-2 block w-full rounded-lg border-[#c9ddd4] bg-white px-4 py-3 text-brand-900 shadow-sm shadow-brand-900/[0.03] placeholder:text-slate-400 focus:border-brand-600 focus:ring-brand-600"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <label class="flex items-center gap-3 text-sm text-slate-600">
                <Checkbox id="remember" name="remember" v-model:checked="form.remember" class="rounded border-brand-300 text-brand-700 focus:ring-brand-500" />
                <span>Keep me signed in on this device</span>
            </label>

            <PrimaryButton
                class="w-full justify-center rounded-lg bg-brand-800 py-3.5 text-sm font-semibold normal-case tracking-normal shadow-lg shadow-brand-900/10 hover:bg-brand-600 focus:bg-brand-600"
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
            >
                {{ form.processing ? 'Signing in...' : 'Sign in to workspace' }}
            </PrimaryButton>
        </form>

        <details v-if="demoEntries.length" class="mt-8 border-t border-[#dce9e3] pt-5">
            <summary class="flex cursor-pointer list-none items-center justify-between text-sm font-semibold text-brand-800 marker:hidden">
                <span>Use a demo account</span>
                <span class="text-lg font-normal text-brand-600" aria-hidden="true">+</span>
            </summary>
            <div class="mt-3 grid gap-2">
                <button
                    v-for="account in demoEntries"
                    :key="account.key"
                    type="button"
                    @click="useDemoAccount(account)"
                    class="flex min-w-0 items-center justify-between gap-3 rounded-lg border border-[#dce9e3] bg-white px-3 py-2.5 text-left transition hover:border-brand-400 hover:bg-brand-50 focus:outline-none focus:ring-2 focus:ring-brand-400"
                >
                    <span class="shrink-0 text-sm font-semibold text-brand-900">{{ account.label }}</span>
                    <span class="min-w-0 truncate text-right text-xs text-slate-500">{{ account.email }}</span>
                </button>
            </div>
        </details>

        <p class="mt-8 text-center text-xs leading-5 text-slate-500">
            Authorized staff only <span class="mx-1 text-brand-400">•</span> Your access is protected and role-based.
        </p>
    </AuthLayout>
</template>