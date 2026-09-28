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
const currentRole = page.props.auth.user?.role?.name ?? 'Unassigned';
const currentUser = page.props.auth.user?.name ?? 'Staff account';
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

        <div class="grid gap-10 lg:grid-cols-[minmax(0,1.35fr)_minmax(240px,0.65fr)]">
            <section aria-labelledby="quick-access-title">
                <div class="flex items-baseline justify-between border-b border-[#c9ddd4] pb-3">
                    <h3 id="quick-access-title" class="text-sm font-semibold uppercase tracking-[0.2em] text-brand-800">Quick access</h3>
                    <span class="text-xs text-slate-500">{{ roleShortcuts.length + shortcuts.length }} links</span>
                </div>
                <nav class="mt-1" aria-label="Workspace shortcuts">
                    <Link
                        v-for="item in [...roleShortcuts, ...shortcuts]"
                        :key="item.label"
                        :href="item.href"
                        class="group flex items-center justify-between gap-4 border-b border-[#dce9e3] px-3 py-4 transition hover:bg-white focus:bg-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-brand-400"
                    >
                        <span>
                            <span class="block font-semibold text-slate-900">{{ item.label }}</span>
                            <span class="mt-1 block text-sm text-slate-500">{{ item.description }}</span>
                        </span>
                        <span class="shrink-0 text-xs font-semibold text-brand-700 group-hover:text-brand-900">Open</span>
                    </Link>
                </nav>
            </section>

            <aside class="border-t-2 border-brand-600 pt-4" aria-labelledby="account-summary-title">
                <h3 id="account-summary-title" class="text-sm font-semibold uppercase tracking-[0.2em] text-brand-800">Signed-in account</h3>
                <p class="mt-5 text-2xl font-semibold text-slate-950">{{ currentUser }}</p>
                <p class="mt-1 text-sm text-slate-500">{{ currentRole }}</p>
                <p class="mt-6 border-l-2 border-brand-400 pl-3 text-sm leading-6 text-slate-600">
                    Your workspace links are tailored to your assigned role. Use the navigation to move between tasks.
                </p>
            </aside>
        </div>
    </AuthenticatedLayout>
</template>