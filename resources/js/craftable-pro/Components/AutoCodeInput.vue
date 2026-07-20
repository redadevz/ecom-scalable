<template>
    <div>
        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">{{ label }}</label>

        <!-- existing record: show the assigned reference, read-only -->
        <div v-if="modelValue"
            class="flex items-center gap-2 rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 dark:border-[#2c2f3d] dark:bg-[#1a1c27]">
            <LockClosedIcon class="h-4 w-4 flex-shrink-0 text-gray-400" />
            <span class="font-mono text-sm font-medium text-gray-700 dark:text-gray-200">{{ modelValue }}</span>
        </div>

        <!-- new record: nothing to show yet, it's assigned on save -->
        <div v-else
            class="flex items-center gap-2 rounded-lg border border-dashed border-gray-300 bg-transparent px-3 py-2 dark:border-[#3a3e4d]">
            <SparklesIcon class="h-4 w-4 flex-shrink-0 text-primary-500" />
            <span class="text-sm text-gray-400">Generated automatically on save</span>
        </div>

        <p class="mt-1 text-xs text-gray-400">{{ hint }}</p>
    </div>
</template>

<script setup lang="ts">
import { LockClosedIcon, SparklesIcon } from "@heroicons/vue/24/outline";

withDefaults(defineProps<{
    modelValue?: string | number | null;
    label: string;
    hint?: string;
}>(), { hint: "System-generated — not editable." });
</script>
