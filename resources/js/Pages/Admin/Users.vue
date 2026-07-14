<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    users: {
        type: Array,
        required: true,
    },
    roles: {
        type: Array,
        required: true,
    },
});

const form = useForm({
    name: '',
    email: '',
    password: '',
    role_id: props.roles[0]?.id ?? '',
});

const userForms = {};
props.users.forEach((user) => {
    userForms[user.id] = useForm({
        name: user.name,
        email: user.email,
        role_id: user.role_id,
    });
});

const submit = () => {
    form.post(route('admin.users.store'), {
        preserveScroll: true,
        onSuccess: () => form.reset('name', 'email', 'password'),
    });
};

const destroyUser = (user) => {
    if (confirm(`Delete ${user.name}?`)) {
        form.delete(route('admin.users.destroy', user.id), { preserveScroll: true });
    }
};

const saveUser = (userId) => {
    userForms[userId].patch(route('admin.users.update', userId), { preserveScroll: true });
};
</script>

<template>
    <Head title="User Management" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-2xl font-semibold leading-tight text-slate-900">User Management</h2>
        </template>

        <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
            <div class="grid gap-8 xl:grid-cols-[0.9fr_1.1fr]">
                <form class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm" @submit.prevent="submit">
                    <h3 class="text-lg font-semibold text-slate-900">Create user</h3>
                    <div class="mt-5 space-y-4">
                        <div>
                            <InputLabel for="name" value="Name" />
                            <TextInput id="name" v-model="form.name" class="mt-1 block w-full" required />
                            <InputError class="mt-2" :message="form.errors.name" />
                        </div>
                        <div>
                            <InputLabel for="email" value="Email" />
                            <TextInput id="email" v-model="form.email" type="email" class="mt-1 block w-full" required />
                            <InputError class="mt-2" :message="form.errors.email" />
                        </div>
                        <div>
                            <InputLabel for="password" value="Password" />
                            <TextInput id="password" v-model="form.password" type="password" class="mt-1 block w-full" required />
                            <InputError class="mt-2" :message="form.errors.password" />
                        </div>
                        <div>
                            <InputLabel for="role_id" value="Role" />
                            <select id="role_id" v-model="form.role_id" class="mt-1 block w-full rounded-lg border-slate-300">
                                <option v-for="role in props.roles" :key="role.id" :value="role.id">{{ role.name }}</option>
                            </select>
                            <InputError class="mt-2" :message="form.errors.role_id" />
                        </div>
                    </div>
                    <PrimaryButton class="mt-6" :disabled="form.processing">Create user</PrimaryButton>
                </form>

                <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
                            <tr>
                                <th class="px-6 py-4">Name</th>
                                <th class="px-6 py-4">Email</th>
                                <th class="px-6 py-4">Role</th>
                                <th class="px-6 py-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr v-for="user in props.users" :key="user.email">
                                <td class="px-6 py-4">
                                    <TextInput v-model="userForms[user.id].name" class="w-full" />
                                </td>
                                <td class="px-6 py-4">
                                    <TextInput v-model="userForms[user.id].email" type="email" class="w-full" />
                                </td>
                                <td class="px-6 py-4">
                                    <select v-model="userForms[user.id].role_id" class="w-full rounded-lg border-slate-300">
                                        <option v-for="role in props.roles" :key="role.id" :value="role.id">{{ role.name }}</option>
                                    </select>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-3">
                                        <button type="button" class="text-sm font-semibold text-slate-700 hover:text-slate-900" @click="saveUser(user.id)">Save</button>
                                        <button type="button" class="text-sm font-semibold text-rose-600 hover:text-rose-700" @click="destroyUser(user)">Delete</button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
