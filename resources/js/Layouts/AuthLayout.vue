<script setup>
import { computed } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import FlashAlert from '@/Components/FlashAlert.vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const flashStatus = computed(() => page.props.flash?.status ?? '');

defineProps({
    panelBadge: { type: String, default: 'Secure access' },
    panelTitle: { type: String, default: 'One workspace for every TABAS role.' },
    panelDescription: { type: String, default: 'A clearer view of patient flow, from first assessment to the right bed.' },
    panelPoints: { type: Array, default: () => [] },
    panelCallout: { type: String, default: '' },
});
</script>

<template>
    <div class="min-h-screen bg-[#f4f8f6] text-slate-900 lg:grid lg:grid-cols-[minmax(360px,0.88fr)_1.12fr]">
        <FlashAlert :message="flashStatus" />

        <aside class="relative isolate overflow-hidden bg-brand-900 px-6 py-7 text-white sm:px-10 sm:py-9 lg:flex lg:min-h-screen lg:flex-col lg:justify-between lg:px-14 lg:py-12">
            <div class="pointer-events-none absolute inset-0 -z-10 opacity-[0.12]" style="background-image: linear-gradient(rgba(225,245,238,0.28) 1px, transparent 1px), linear-gradient(90deg, rgba(225,245,238,0.28) 1px, transparent 1px); background-size: 48px 48px;"></div>
            <div class="pointer-events-none absolute inset-x-0 bottom-0 -z-10 h-1/2 bg-gradient-to-t from-brand-800/80 to-transparent"></div>

            <div class="flex items-center gap-3">
                <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-white p-1.5 shadow-sm">
                    <ApplicationLogo class="h-full w-full" />
                </span>
                <div>
                    <p class="text-lg font-bold leading-tight tracking-wide">TABAS</p>
                    <p class="mt-0.5 text-xs text-brand-50/75">Emergency care coordination</p>
                </div>
            </div>

            <div class="my-10 max-w-xl lg:my-auto lg:py-16">
                <p class="inline-flex items-center gap-2 rounded-full border border-brand-50/25 bg-brand-50/10 px-3 py-1.5 text-xs font-semibold uppercase tracking-[0.16em] text-brand-50">
                    <span class="h-1.5 w-1.5 rounded-full bg-[#8CE0B9]"></span>
                    {{ panelBadge }}
                </p>
                <h2 class="mt-7 max-w-lg text-4xl font-semibold leading-[1.08] text-white sm:text-5xl" style="font-family: Georgia, 'Times New Roman', serif;">
                    {{ panelTitle }}
                </h2>
                <p class="mt-5 max-w-md text-base leading-7 text-brand-50/80">
                    {{ panelDescription }}
                </p>

                <ul v-if="panelPoints.length" class="mt-9 grid gap-3 sm:grid-cols-2 lg:grid-cols-1">
                    <li v-for="(point, index) in panelPoints" :key="point" class="flex items-center gap-3 text-sm text-brand-50/90">
                        <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-md border border-brand-50/30 text-[11px] font-semibold text-brand-50">0{{ index + 1 }}</span>
                        {{ point }}
                    </li>
                </ul>
            </div>

            <div v-if="panelCallout" class="max-w-md border-l-2 border-brand-400 pl-4 text-sm leading-6 text-brand-50/75">
                {{ panelCallout }}
            </div>
        </aside>

        <main class="flex min-h-[70vh] items-center justify-center px-6 py-12 sm:px-10 lg:min-h-screen lg:px-12 xl:px-20">
            <div class="w-full max-w-[460px]">
                <slot />
            </div>
        </main>
    </div>
</template>