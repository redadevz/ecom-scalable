<template>
    <div>
        <label class="mb-1.5 block text-sm font-medium text-gray-700">{{ label }}</label>
        <slot />
        <p v-if="error" class="mt-1 text-xs text-red-500">{{ error }}</p>
    </div>
</template>

<script setup>
defineProps({
    label: { type: String, default: '' },
    error: { type: String, default: '' },
});
</script>
