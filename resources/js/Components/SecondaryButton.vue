<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
});
</script>

<template>
    <button
        :type="type"
        class="inline-flex items-center rounded-2xl border border-brand-200 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-widest text-brand-900 shadow-sm transition duration-150 ease-in-out hover:bg-brand-50 focus:outline-none focus:ring-2 focus:ring-brand-400 focus:ring-offset-2 disabled:opacity-25"
    >
        <slot />
    </button>
</template>