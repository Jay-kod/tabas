<script setup>
import { computed, ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import FlashAlert from '@/Components/FlashAlert.vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const flashStatus = computed(() => page.props.flash?.status ?? '');
const currentUserRole = computed(() => page.props.auth.user?.role?.name ?? 'Guest');
const mobileSidebarOpen = ref(false);

const pageMeta = computed(() => {
    const routeName = route().current() ?? 'dashboard';

    const meta = {
        dashboard: { title: 'Dashboard', description: 'Overview of your current workflow and pending work.' },
        'admin.dashboard': { title: 'Admin Dashboard', description: 'System health, capacity, and operations.' },
        'admin.users': { title: 'User Management', description: 'Create, update, and assign roles to users.' },
        'admin.wards': { title: 'Wards', description: 'Maintain ward capacity and bed records.' },
        'admin.audit': { title: 'Audit Log', description: 'Review recommendations, overrides, and system events.' },
        'nurse.triage.new': { title: 'New Triage Intake', description: 'Capture vitals and generate a triage score.' },
        'nurse.patients': { title: 'Today\'s Triage List', description: 'Review patients triaged today and their allocation status.' },
        'bed-manager.beds': { title: 'Bed Board', description: 'Manage bed availability and pending recommendations.' },
        'doctor.queue': { title: 'Doctor Queue', description: 'Review waiting allocations and make decisions.' },
        'profile.edit': { title: 'Profile', description: 'Update account details and security settings.' },
    };

    return meta[routeName] ?? meta.dashboard;
});

const navigation = computed(() => {
    const commonLinks = [
        { label: 'Dashboard', href: route('dashboard'), active: 'dashboard', icon: 'home' },
        { label: 'Profile', href: route('profile.edit'), active: 'profile.*', icon: 'user' },
    ];

    const roleLinks = {
        Admin: [
            { label: 'Dashboard', href: route('admin.dashboard'), active: 'admin.dashboard', icon: 'grid' },
            { label: 'Users', href: route('admin.users'), active: 'admin.users*', icon: 'users' },
            { label: 'Wards', href: route('admin.wards'), active: 'admin.wards*', icon: 'building' },
            { label: 'Audit Log', href: route('admin.audit'), active: 'admin.audit*', icon: 'shield' },
        ],
        'Triage Nurse': [
            { label: 'Triage Intake', href: route('nurse.triage.new'), active: 'nurse.triage.new', icon: 'clipboard' },
            { label: 'Patients', href: route('nurse.patients'), active: 'nurse.patients', icon: 'heart' },
        ],
        'Bed Manager': [
            { label: 'Beds', href: route('bed-manager.beds'), active: 'bed-manager.beds*', icon: 'bed' },
        ],
        Doctor: [
            { label: 'Queue', href: route('doctor.queue'), active: 'doctor.queue*', icon: 'queue' },
        ],
    };

    return [
        ...(roleLinks[currentUserRole.value] ?? []),
        ...commonLinks,
    ];
});

const isActive = (pattern) => {
    const currentRoute = route().current() ?? '';

    if (pattern.endsWith('.*')) {
        return currentRoute.startsWith(pattern.slice(0, -2));
    }

    if (pattern.includes('*')) {
        return currentRoute.startsWith(pattern.replace('*', ''));
    }

    return currentRoute === pattern;
};

const iconClasses = (name) => {
    const base = 'h-4 w-4';
    return `${base} ${name === 'shield' ? 'stroke-current fill-none' : 'fill-current'}`;
};
</script>

<template>
    <div class="min-h-screen bg-brand-50 text-slate-900">
        <FlashAlert :message="flashStatus" />

        <div class="lg:flex lg:min-h-screen">
            <aside class="hidden w-80 shrink-0 border-r border-brand-900 bg-brand-900 text-white lg:flex lg:flex-col">
                <div class="flex h-20 items-center gap-3 border-b border-white/10 px-6">
                    <Link :href="route('dashboard')" class="inline-flex items-center gap-3">
                        <ApplicationLogo class="h-10 w-10 fill-current text-brand-50" />
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-brand-50">TABAS</p>
                            <p class="text-sm text-slate-400">Workflow shell</p>
                        </div>
                    </Link>
                </div>

                <div class="flex-1 px-4 py-6">
                    <div class="rounded-[1.75rem] border border-white/10 bg-white/5 p-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-400">Current role</p>
                        <p class="mt-2 text-lg font-semibold text-white">{{ currentUserRole }}</p>
                        <p class="mt-1 text-sm text-slate-300">{{ $page.props.auth.user?.name }}</p>
                    </div>

                    <nav class="mt-6 space-y-2">
                        <Link
                            v-for="item in navigation"
                            :key="item.label"
                            :href="item.href"
                            class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium transition"
                            :class="isActive(item.active) ? 'bg-brand-400 text-brand-900 shadow-lg shadow-brand-400/20' : 'text-slate-300 hover:bg-white/8 hover:text-white'"
                        >
                            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-white/10" :class="isActive(item.active) ? 'bg-slate-950/10' : ''">
                                <svg viewBox="0 0 24 24" :class="iconClasses(item.icon)" aria-hidden="true">
                                    <path v-if="item.icon === 'home'" d="M12 3.2 3.5 10v10.5h6.5v-6.5h4v6.5h6.5V10L12 3.2Z" />
                                    <path v-else-if="item.icon === 'grid'" d="M4 4h7v7H4V4Zm9 0h7v7h-7V4ZM4 13h7v7H4v-7Zm9 0h7v7h-7v-7Z" />
                                    <path v-else-if="item.icon === 'users'" d="M9 12a4 4 0 1 0-4-4 4 4 0 0 0 4 4Zm8 1a3 3 0 1 0-3-3 3 3 0 0 0 3 3ZM3 20a6 6 0 0 1 12 0Zm12.5-4a5.5 5.5 0 0 1 5.5 5.5V20h-4.2a8 8 0 0 0-1.3-4Z" />
                                    <path v-else-if="item.icon === 'building'" d="M4 21V3h12v18H4Zm14 0V9h2v12h-2ZM7 6h2v2H7V6Zm4 0h2v2h-2V6Zm-4 4h2v2H7v-2Zm4 0h2v2h-2v-2Zm-4 4h2v2H7v-2Zm4 0h2v2h-2v-2Z" />
                                    <path v-else-if="item.icon === 'shield'" stroke-linecap="round" stroke-linejoin="round" d="M12 3 5 6v5c0 4.9 3.1 8.8 7 10 3.9-1.2 7-5.1 7-10V6l-7-3Z" />
                                    <path v-else-if="item.icon === 'clipboard'" d="M8 3h8a2 2 0 0 1 2 2v1h-2V5H8v1H6V5a2 2 0 0 1 2-2Zm-2 4h12v14H6V7Zm3 3h6v2H9V10Zm0 4h6v2H9v-2Z" />
                                    <path v-else-if="item.icon === 'heart'" d="M12 21s-7.5-4.6-9.5-9.3C1 7.7 3.4 5 6.5 5c1.7 0 3.1.8 4.1 2 1-1.2 2.4-2 4.1-2 3.1 0 5.5 2.7 4 6.7C19.5 16.4 12 21 12 21Z" />
                                    <path v-else-if="item.icon === 'bed'" d="M4 7h2v5h6.5a3.5 3.5 0 0 1 3.5 3.5V17h1v-3h2v7h-2v-2H6v2H4V7Zm3 1h5a2 2 0 0 1 2 2v2H7V8Z" />
                                    <path v-else-if="item.icon === 'queue'" d="M4 6h16v2H4V6Zm0 5h16v2H4v-2Zm0 5h10v2H4v-2Z" />
                                    <path v-else d="M5 5h14v2H5V5Zm0 6h14v2H5v-2Zm0 6h10v2H5v-2Z" />
                                </svg>
                            </span>
                            <span>{{ item.label }}</span>
                        </Link>
                    </nav>
                </div>

                <div class="border-t border-white/10 p-4">
                    <Link :href="route('profile.edit')" class="block rounded-2xl border border-white/10 px-4 py-3 text-sm text-slate-300 transition hover:bg-white/8 hover:text-white">
                        Profile settings
                    </Link>
                    <Link :href="route('logout')" method="post" as="button" class="mt-3 block w-full rounded-2xl bg-brand-50 px-4 py-3 text-sm font-semibold text-brand-900 transition hover:bg-brand-50/90">
                        Log out
                    </Link>
                </div>
            </aside>

            <div class="flex min-h-screen flex-1 flex-col">
                <header class="sticky top-0 z-20 border-b border-brand-50 bg-white/90 backdrop-blur">
                    <div class="flex items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
                        <div class="flex items-center gap-3 lg:hidden">
                            <Link :href="route('dashboard')" class="inline-flex items-center gap-2">
                                <ApplicationLogo class="h-9 w-9 fill-current text-brand-800" />
                                <span class="text-sm font-semibold text-slate-900">TABAS</span>
                            </Link>
                        </div>

                        <div class="hidden lg:block">
                            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-brand-800">{{ pageMeta.title }}</p>
                            <h1 class="mt-1 text-2xl font-semibold text-slate-950">{{ pageMeta.title }}</h1>
                        </div>

                        <div class="flex items-center gap-3 lg:hidden">
                            <button type="button" class="rounded-xl border border-slate-200 px-3 py-2 text-sm font-medium text-slate-700" @click="mobileSidebarOpen = !mobileSidebarOpen">
                                Menu
                            </button>
                        </div>
                    </div>

                    <div class="border-t border-brand-50 px-4 py-3 sm:px-6 lg:hidden">
                        <p class="text-xs font-semibold uppercase tracking-[0.35em] text-brand-800">{{ pageMeta.title }}</p>
                        <h1 class="mt-1 text-xl font-semibold text-slate-950">{{ pageMeta.title }}</h1>
                    </div>

                    <div v-if="mobileSidebarOpen" class="border-t border-brand-900 bg-brand-900 px-4 py-4 text-white lg:hidden">
                        <div class="space-y-2">
                            <Link
                                v-for="item in navigation"
                                :key="`mobile-${item.label}`"
                                :href="item.href"
                                class="block rounded-2xl px-4 py-3 text-sm font-medium"
                                :class="isActive(item.active) ? 'bg-brand-400 text-brand-900' : 'text-slate-300 hover:bg-white/8 hover:text-white'"
                            >
                                {{ item.label }}
                            </Link>
                        </div>
                    </div>
                </header>

                <main class="flex-1">
                    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
                        <div class="mb-6 rounded-[1.75rem] border border-brand-50 bg-white p-5 shadow-sm">
                            <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                                <div>
                                    <p class="text-sm font-semibold uppercase tracking-[0.3em] text-brand-800">{{ pageMeta.title }}</p>
                                    <h2 class="mt-1 text-2xl font-semibold text-slate-950">{{ pageMeta.title }}</h2>
                                </div>
                                <p class="max-w-2xl text-sm leading-6 text-slate-500">{{ pageMeta.description }}</p>
                            </div>
                        </div>

                        <slot />
                    </div>
                </main>

                <footer class="border-t border-slate-200 bg-white px-6 py-4 text-center text-xs text-slate-500">
                    TABAS is a final-year academic prototype. The triage score is a simplified teaching model and is not a validated clinical decision system.
                </footer>
            </div>
        </div>
    </div>
</template>
