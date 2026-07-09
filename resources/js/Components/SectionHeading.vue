<template>
    <div class="mb-6 flex items-end justify-between">
        <div>
            <h2 class="text-2xl font-bold tracking-tight text-gray-900">{{ title }}</h2>
            <p v-if="subtitle" class="mt-1 text-sm text-gray-500">{{ subtitle }}</p>
        </div>
        <Link :href="href" class="flex items-center gap-1 text-sm font-semibold text-brand-600 transition hover:text-brand-700">
            View all <ArrowRightIcon class="h-4 w-4" />
        </Link>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { ArrowRightIcon } from '@heroicons/vue/24/outline';

defineProps({
    title: { type: String, required: true },
    subtitle: { type: String, default: '' },
    href: { type: String, default: '/products' },
});
</script>
