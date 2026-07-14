<script setup>
import { ref } from 'vue';
import TextInput from '@/Components/TextInput.vue';

defineOptions({ inheritAttrs: false });

const model = defineModel({
    type: String,
    required: true,
});

const visible = ref(false);

const toggleVisibility = () => {
    visible.value = !visible.value;
};
</script>

<template>
    <div class="relative">
        <TextInput
            v-bind="$attrs"
            v-model="model"
            :type="visible ? 'text' : 'password'"
            class="block w-full rounded-2xl border-brand-200 bg-white px-4 py-3 pr-12 text-slate-900 shadow-sm focus:border-brand-400 focus:ring-brand-400"
        />

        <button
            type="button"
            class="absolute inset-y-0 right-0 flex items-center px-3 text-brand-800 transition hover:text-brand-900"
            :aria-label="visible ? 'Hide password' : 'Show password'"
            :title="visible ? 'Hide password' : 'Show password'"
            @click="toggleVisibility"
        >
            <svg v-if="visible" viewBox="0 0 24 24" class="h-5 w-5 fill-none stroke-current stroke-2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.58 10.58A2 2 0 0012 14a2 2 0 001.42-.58" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.88 5.09A10.94 10.94 0 0112 5c5 0 9.27 3.11 10.95 7.5a11.45 11.45 0 01-3.09 4.45" />
            </svg>
            <svg v-else viewBox="0 0 24 24" class="h-5 w-5 fill-none stroke-current stroke-2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.88 12c1.68-4.39 5.95-7.5 10.95-7.5S22.13 7.61 23.81 12c-1.68 4.39-5.95 7.5-10.95 7.5S4.56 16.39 2.88 12Z" />
                <circle cx="12" cy="12" r="3" />
            </svg>
        </button>
    </div>
</template>