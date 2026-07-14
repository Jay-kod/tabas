<script setup>
import { computed, onMounted, ref } from 'vue';

const props = defineProps({
    message: {
        type: String,
        default: '',
    },
    title: {
        type: String,
        default: 'Notification',
    },
    variant: {
        type: String,
        default: 'success',
    },
});

const visible = ref(Boolean(props.message));

const displayMessage = computed(() => {
    if (props.message === 'verification-link-sent') {
        return 'A fresh verification link has been sent to your inbox.';
    }

    return props.message;
});

const palette = computed(() => {
    if (props.variant === 'danger') {
        return {
            ring: 'ring-critical-bg',
            title: 'text-critical-text',
            dot: 'bg-critical-text',
        };
    }

    if (props.variant === 'warning') {
        return {
            ring: 'ring-urgent-bg',
            title: 'text-urgent-text',
            dot: 'bg-urgent-text',
        };
    }

    return {
        ring: 'ring-nonurgent-bg',
        title: 'text-brand-800',
        dot: 'bg-brand-400',
    };
});

onMounted(() => {
    if (visible.value) {
        window.setTimeout(() => {
            visible.value = false;
        }, 4500);
    }
});
</script>

<template>
    <Transition
        enter-active-class="transition duration-300 ease-out"
        enter-from-class="translate-y-2 opacity-0"
        enter-to-class="translate-y-0 opacity-100"
        leave-active-class="transition duration-200 ease-in"
        leave-from-class="translate-y-0 opacity-100"
        leave-to-class="translate-y-2 opacity-0"
    >
        <div
            v-if="visible && displayMessage"
            class="fixed right-4 top-4 z-50 w-[min(24rem,calc(100vw-2rem))] rounded-2xl border border-white/70 bg-white/95 p-4 shadow-2xl ring-1 backdrop-blur"
            :class="palette.ring"
        >
            <div class="flex items-start gap-3">
                <span class="mt-1 h-3 w-3 rounded-full" :class="palette.dot" />
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold tracking-wide" :class="palette.title">{{ title }}</p>
                    <p class="mt-1 text-sm leading-6 text-slate-700">{{ displayMessage }}</p>
                </div>
                <button
                    type="button"
                    class="rounded-full px-2 py-1 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700"
                    @click="visible = false"
                    aria-label="Dismiss notification"
                >
                    ×
                </button>
            </div>
        </div>
    </Transition>
</template>
