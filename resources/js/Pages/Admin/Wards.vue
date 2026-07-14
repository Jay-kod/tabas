<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    wards: {
        type: Array,
        required: true,
    },
});

const form = useForm({
    name: '',
    specialization: '',
    total_beds: 1,
});

const wardForms = {};
props.wards.forEach((ward) => {
    wardForms[ward.id] = useForm({
        name: ward.name,
        specialization: ward.specialization ?? '',
        total_beds: ward.total_beds,
    });
});

const submit = () => {
    form.post(route('admin.wards.store'), {
        preserveScroll: true,
        onSuccess: () => form.reset('name', 'specialization', 'total_beds'),
    });
};

const destroyWard = (ward) => {
    if (confirm(`Delete ${ward.name}?`)) {
        form.delete(route('admin.wards.destroy', ward.id), { preserveScroll: true });
    }
};

const saveWard = (wardId) => {
    wardForms[wardId].patch(route('admin.wards.update', wardId), { preserveScroll: true });
};
</script>

<template>
    <Head title="Wards" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-2xl font-semibold leading-tight text-slate-900">Wards</h2>
        </template>

        <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
            <div class="grid gap-8 xl:grid-cols-[0.9fr_1.1fr]">
                <form class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm" @submit.prevent="submit">
                    <h3 class="text-lg font-semibold text-slate-900">Create ward</h3>
                    <div class="mt-5 space-y-4">
                        <div>
                            <InputLabel for="name" value="Ward name" />
                            <TextInput id="name" v-model="form.name" class="mt-1 block w-full" required />
                            <InputError class="mt-2" :message="form.errors.name" />
                        </div>
                        <div>
                            <InputLabel for="specialization" value="Specialization" />
                            <TextInput id="specialization" v-model="form.specialization" class="mt-1 block w-full" />
                        </div>
                        <div>
                            <InputLabel for="total_beds" value="Total beds" />
                            <TextInput id="total_beds" v-model="form.total_beds" type="number" min="1" class="mt-1 block w-full" required />
                            <InputError class="mt-2" :message="form.errors.total_beds" />
                        </div>
                    </div>
                    <PrimaryButton class="mt-6" :disabled="form.processing">Create ward</PrimaryButton>
                </form>

                <div class="grid gap-4 lg:grid-cols-2">
                    <div v-for="ward in props.wards" :key="ward.name" class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="space-y-3">
                            <div>
                                <InputLabel :for="`ward-name-${ward.id}`" value="Ward name" />
                                <TextInput :id="`ward-name-${ward.id}`" v-model="wardForms[ward.id].name" class="mt-1 block w-full" />
                            </div>
                            <div>
                                <InputLabel :for="`ward-spec-${ward.id}`" value="Specialization" />
                                <TextInput :id="`ward-spec-${ward.id}`" v-model="wardForms[ward.id].specialization" class="mt-1 block w-full" />
                            </div>
                            <div>
                                <InputLabel :for="`ward-beds-${ward.id}`" value="Total beds" />
                                <TextInput :id="`ward-beds-${ward.id}`" v-model="wardForms[ward.id].total_beds" type="number" class="mt-1 block w-full" />
                            </div>
                        </div>
                        <p class="mt-3 text-sm text-slate-600">{{ ward.beds_count }} beds total</p>
                        <p class="mt-1 text-sm text-slate-500">{{ ward.beds.length }} bed records created</p>
                        <div class="mt-4 flex flex-wrap gap-3">
                            <button type="button" class="text-sm font-semibold text-slate-700 hover:text-slate-900" @click="saveWard(ward.id)">Save</button>
                            <button type="button" class="text-sm font-semibold text-rose-600 hover:text-rose-700" @click="destroyWard(ward)">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
