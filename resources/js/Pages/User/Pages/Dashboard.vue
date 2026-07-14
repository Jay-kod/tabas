<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';

const page = usePage();

const shortcuts = [
    { label: 'Profile', href: route('profile.edit'), description: 'Update your account and password.' },
];

const roleShortcutMap = {
    Admin: [
        { label: 'Admin Dashboard', href: route('admin.dashboard'), description: 'Open the administrative overview.' },
        { label: 'Users', href: route('admin.users'), description: 'Manage staff accounts.' },
    ],
    'Triage Nurse': [
        { label: 'New Triage', href: route('nurse.triage.new'), description: 'Capture a new patient intake.' },
        { label: 'Patients', href: route('nurse.patients'), description: 'Review today’s triage list.' },
    ],
    'Bed Manager': [
        { label: 'Beds', href: route('bed-manager.beds'), description: 'Open the live bed board.' },
    ],
    Doctor: [
        { label: 'Queue', href: route('doctor.queue'), description: 'Review pending allocations.' },
    ],
};

const roleShortcuts = roleShortcutMap[page.props.auth.user?.role?.name] ?? [];
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.35em] text-brand-800">Workspace home</p>
                    <h2 class="text-3xl font-semibold leading-tight text-slate-900">Dashboard</h2>
                </div>
                <p class="max-w-2xl text-sm leading-6 text-slate-500">Use the sidebar to jump to your role workspace and the cards below to get to the most common actions faster.</p>
            </div>
        </template>

        <div class="grid gap-6 lg:grid-cols-2">
            <div class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-sm">
                <p class="text-sm font-semibold uppercase tracking-[0.25em] text-slate-500">Quick links</p>
                <div class="mt-5 space-y-3">
                    <Link v-for="item in [...roleShortcuts, ...shortcuts]" :key="item.label" :href="item.href" class="block rounded-2xl border border-brand-100 px-4 py-4 transition hover:border-brand-200 hover:bg-brand-50">
                        <p class="font-semibold text-slate-900">{{ item.label }}</p>
                        <p class="mt-1 text-sm text-slate-500">{{ item.description }}</p>
                    </Link>
                </div>
            </div>

            <div class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-sm">
                <p class="text-sm font-semibold uppercase tracking-[0.25em] text-slate-500">Today at a glance</p>
                <div class="mt-5 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-sm font-medium text-slate-700">Current role</p>
                        <p class="mt-1 text-xl font-semibold text-slate-900">{{ page.props.auth.user?.role?.name ?? 'Unassigned' }}</p>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-sm font-medium text-slate-700">Account</p>
                        <p class="mt-1 text-xl font-semibold text-slate-900">{{ page.props.auth.user?.name }}</p>
                    </div>
                </div>
                <p class="mt-5 text-sm leading-6 text-slate-500">This dashboard acts as the fast entry point to the correct role workspace after login.</p>
            </div>
        </div>
    </AuthenticatedLayout>
</template>