<template>
    <PageContent>
        <div class="mx-auto px-1 py-2" :class="maxWidthCls">
            <!-- Stepper -->
            <div class="mb-6 flex items-center">
                <template v-for="(s, i) in steps" :key="s.key">
                    <button type="button" @click="go(i)" class="flex items-center gap-2 focus:outline-none">
                        <span class="flex h-7 w-7 items-center justify-center rounded-full text-xs font-bold transition-all" :class="dotCls(i)">
                            <CheckIcon v-if="i < current" class="h-4 w-4" />
                            <span v-else>{{ i + 1 }}</span>
                        </span>
                        <span class="text-sm font-semibold transition-colors"
                            :class="i === current ? 'text-primary-600 dark:text-primary-400' : (i < current ? 'text-gray-700 dark:text-gray-300' : 'text-gray-400')">
                            {{ s.label }}
                        </span>
                    </button>
                    <span v-if="i < steps.length - 1" class="mx-3 h-px flex-1 transition-colors"
                        :class="i < current ? 'bg-primary-400' : 'bg-gray-200 dark:bg-[#2c2f3d]'"></span>
                </template>
            </div>

            <!-- No overflow-hidden: a clipped card would cut off Multiselect/DatePicker dropdowns -->
            <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-[#2c2f3d] dark:bg-[#1e2029]">
                <!-- All steps stay mounted (v-show) so field state never remounts -->
                <div v-for="(s, i) in steps" :key="s.key" v-show="current === i">
                    <slot :name="s.key" :active="current === i" />
                </div>

                <!-- Footer nav -->
                <div class="flex items-center justify-between border-t border-gray-100 px-6 py-4 dark:border-[#2a2d38]">
                    <button type="button" @click="back" :disabled="current === 0"
                        class="inline-flex items-center gap-1.5 rounded-xl px-4 py-2 text-sm font-medium text-gray-500 transition hover:text-gray-800 disabled:opacity-0 dark:hover:text-gray-200">
                        <ArrowLeftIcon class="h-4 w-4" /> Back
                    </button>
                    <button v-if="current < steps.length - 1" type="button" @click="next"
                        class="inline-flex items-center gap-1.5 rounded-xl bg-primary-500 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-600">
                        Continue <ArrowRightIcon class="h-4 w-4" />
                    </button>
                    <button v-else type="button" @click="emit('submit')" :disabled="processing"
                        class="inline-flex items-center gap-1.5 rounded-xl bg-primary-500 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-600 disabled:opacity-60">
                        <CheckIcon class="h-4 w-4" /> {{ processing ? 'Saving…' : submitLabel }}
                    </button>
                </div>
            </div>
        </div>
    </PageContent>
</template>

<script setup lang="ts">
import { computed, ref, watch } from "vue";
import { PageContent } from "craftable-pro/Components";
import { CheckIcon, ArrowLeftIcon, ArrowRightIcon } from "@heroicons/vue/24/outline";

interface Step { key: string; label: string }

const props = withDefaults(defineProps<{
    steps: Step[];
    processing?: boolean;
    submitLabel?: string;
    /** Reactive Inertia errors object — when it changes, jump to the step holding the first error. */
    errors?: Record<string, string>;
    /** Maps a field name to its step index, used for the error jump. */
    fieldStep?: Record<string, number>;
    maxWidth?: "xl" | "2xl" | "3xl" | "4xl" | "5xl";
}>(), { processing: false, submitLabel: "Save", maxWidth: "3xl" });

const emit = defineEmits<{ submit: [] }>();

const current = ref(0);
const go = (i: number) => { current.value = i; };
const next = () => { if (current.value < props.steps.length - 1) current.value++; };
const back = () => { if (current.value > 0) current.value--; };

const maxWidthCls = computed(() => ({ xl: "max-w-xl", "2xl": "max-w-2xl", "3xl": "max-w-3xl", "4xl": "max-w-4xl", "5xl": "max-w-5xl" }[props.maxWidth]));

const dotCls = (i: number) =>
    i < current.value ? "bg-primary-500 text-white"
    : i === current.value ? "bg-primary-500 text-white ring-4 ring-primary-500/20"
    : "bg-gray-100 text-gray-400 dark:bg-[#2c2f3d]";

// On validation failure, jump to the earliest step that has an error.
watch(() => props.errors, (errs) => {
    const keys = Object.keys(errs ?? {});
    if (!keys.length || !props.fieldStep) return;
    const target = Math.min(...keys.map((k) => props.fieldStep![k] ?? 0));
    if (Number.isFinite(target)) current.value = target;
}, { deep: true });
</script>
