<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';

const props = defineProps({
    auditLogs: {
        type: Array,
        required: true,
    },
    summary: {
        type: Object,
        required: true,
    },
    filters: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    action: props.filters.action ?? '',
    search: props.filters.search ?? '',
});

const submit = () => {
    router.get(route('admin.audit'), form.data(), { preserveState: true, preserveScroll: true, replace: true });
};
</script>

<template>
    <Head title="Audit Log" />

    <AuthenticatedLayout>
        <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
            <div class="mb-6 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Matching logs</p>
                    <p class="mt-2 text-3xl font-semibold text-slate-900">{{ props.summary.matchingTotal }}</p>
                </div>
                <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Visible logs</p>
                    <p class="mt-2 text-3xl font-semibold text-slate-900">{{ props.summary.visibleTotal }}</p>
                </div>
                <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Allocation events</p>
                    <p class="mt-2 text-3xl font-semibold text-slate-900">{{ props.summary.allocationEvents }}</p>
                </div>
                <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Triage events</p>
                    <p class="mt-2 text-3xl font-semibold text-slate-900">{{ props.summary.triageEvents }}</p>
                </div>
            </div>

            <div class="mb-6 grid gap-4 lg:grid-cols-2">
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Top actions</p>
                    <div class="mt-4 space-y-3">
                        <div v-for="item in props.summary.topActions" :key="item.label" class="flex items-center justify-between rounded-2xl bg-slate-50 px-4 py-3">
                            <span class="text-sm font-medium text-slate-700">{{ item.label }}</span>
                            <span class="rounded-full bg-brand-900 px-3 py-1 text-xs font-semibold text-white">{{ item.count }}</span>
                        </div>
                        <p v-if="!props.summary.topActions.length" class="text-sm text-slate-500">No actions to report yet.</p>
                    </div>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Subject types</p>
                    <div class="mt-4 space-y-3">
                        <div v-for="item in props.summary.subjectTypes" :key="item.label" class="flex items-center justify-between rounded-2xl bg-slate-50 px-4 py-3">
                            <span class="text-sm font-medium text-slate-700">{{ item.label }}</span>
                            <span class="rounded-full bg-nonurgent-text px-3 py-1 text-xs font-semibold text-white">{{ item.count }}</span>
                        </div>
                        <p v-if="!props.summary.subjectTypes.length" class="text-sm text-slate-500">No subject types to report yet.</p>
                    </div>
                </div>
            </div>

            <div class="mb-6 grid gap-4 md:grid-cols-3">
                <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">System events</p>
                    <p class="mt-2 text-3xl font-semibold text-slate-900">{{ props.summary.systemEvents }}</p>
                </div>
                <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Unique users</p>
                    <p class="mt-2 text-3xl font-semibold text-slate-900">{{ props.summary.uniqueUsers }}</p>
                </div>
                <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Filters active</p>
                    <p class="mt-2 text-3xl font-semibold text-slate-900">{{ [form.action, form.search].filter(Boolean).length }}</p>
                </div>
            </div>

            <form class="mb-6 grid gap-4 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm md:grid-cols-3" @submit.prevent="submit">
                <input v-model="form.action" type="text" placeholder="Filter by action" class="rounded-lg border-slate-300" />
                <input v-model="form.search" type="text" placeholder="Search subject or action" class="rounded-lg border-slate-300" />
                <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white">Filter</button>
            </form>

            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
                        <tr>
                            <th class="px-6 py-4">Action</th>
                            <th class="px-6 py-4">Subject</th>
                            <th class="px-6 py-4">User</th>
                            <th class="px-6 py-4">Time</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr v-for="row in props.auditLogs" :key="row.id">
                            <td class="px-6 py-4 font-medium text-slate-900">{{ row.action }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ row.subject_type }} #{{ row.subject_id }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ row.user?.name ?? 'System' }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ row.created_at }}</td>
                        </tr>
                        <tr v-if="!props.auditLogs.length">
                            <td colspan="4" class="px-6 py-10 text-center text-sm text-slate-500">No audit entries match the current filters.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>