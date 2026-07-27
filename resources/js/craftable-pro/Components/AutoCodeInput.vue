<template>
    <div>
        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">{{ label }}</label>
        <input :value="modelValue" readonly :placeholder="placeholder" :class="codeCls" />
        <p class="mt-1 text-xs text-gray-400">{{ hint }}</p>
    </div>
</template>

<script setup lang="ts">
withDefaults(defineProps<{
    modelValue?: string | number | null;
    label: string;
    hint?: string;
    placeholder?: string;
}>(), {
    hint: "System-generated — not editable.",
    placeholder: "Generated on save",
});

const codeCls = "w-full cursor-not-allowed select-all rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm font-medium text-gray-700 focus:outline-none dark:border-[#2c2f3d] dark:bg-[#1a1c27] dark:text-gray-200";
</script>
