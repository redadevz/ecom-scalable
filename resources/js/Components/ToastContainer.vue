<template>
    <div class="pointer-events-none fixed bottom-6 right-6 z-[70] flex flex-col gap-2">
        <TransitionGroup
            enter-active-class="transition duration-200 ease-out" enter-from-class="translate-y-3 opacity-0"
            leave-active-class="transition duration-150 ease-in" leave-to-class="translate-y-1 opacity-0">
            <div v-for="t in toast.state.items" :key="t.id"
                class="pointer-events-auto flex items-center gap-3 rounded-xl bg-white px-4 py-3 shadow-xl ring-1 ring-black/5">
                <span class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-full"
                    :class="t.type === 'error' ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600'">
                    <CheckIcon v-if="t.type !== 'error'" class="h-4 w-4" />
                    <XMarkIcon v-else class="h-4 w-4" />
                </span>
                <span class="text-sm font-medium text-gray-800">{{ t.message }}</span>
            </div>
        </TransitionGroup>
    </div>
</template>

<script setup>
import { useToast } from '@/stores/toast';
import { CheckIcon, XMarkIcon } from '@heroicons/vue/24/solid';

const toast = useToast();
</script>
