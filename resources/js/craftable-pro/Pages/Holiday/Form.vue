<template>
    <PageContent>
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- ============ MAIN COLUMN ============ -->
            <div class="space-y-6 lg:col-span-2">
                <!-- Holiday -->
                <section :class="cardCls">
                    <header :class="headerCls">
                        <span :class="iconBadge"><CalendarDaysIcon class="h-5 w-5" /></span>
                        <div>
                            <h3 :class="titleCls">Holiday</h3>
                            <p :class="subCls">Name, reason and the period it covers.</p>
                        </div>
                    </header>
                    <div class="grid grid-cols-1 gap-x-6 gap-y-5 p-6 sm:grid-cols-2">
                        <TextInput v-model="form.name" name="name" label="Name" type="text" />
                        <TextInput v-model="form.reason" name="reason" label="Reason" type="text" />
                        <DatePicker v-model="form.starts_at" name="starts_at" mode="dateTime" label="Starts At" />
                        <DatePicker v-model="form.ends_at" name="ends_at" mode="dateTime" label="Ends At" />
                    </div>
                </section>

                <!-- Notes -->
                <section :class="cardCls">
                    <header :class="headerCls">
                        <span :class="iconBadge"><PencilSquareIcon class="h-5 w-5" /></span>
                        <div>
                            <h3 :class="titleCls">Notes</h3>
                            <p :class="subCls">Internal comments about this holiday.</p>
                        </div>
                    </header>
                    <div class="p-6">
                        <TextInput v-model="form.comments" name="comments" label="Comments" type="text" />
                    </div>
                </section>
            </div>

            <!-- ============ SIDE COLUMN ============ -->
            <div class="space-y-6">
                <div class="lg:sticky lg:top-24 space-y-6">
                    <!-- Live preview -->
                    <section class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-[#2c2f3d] dark:bg-[#1e2029]">
                        <div class="relative h-28 bg-gradient-to-br from-primary-500 via-orange-500 to-amber-400">
                            <CalendarDaysIcon class="absolute -right-4 -top-4 h-32 w-32 text-white/15" />
                            <div class="absolute right-3 top-3 rounded-full bg-white/20 px-2.5 py-1 text-[11px] font-semibold uppercase tracking-wide text-white backdrop-blur">
                                Holiday
                            </div>
                        </div>
                        <div class="px-5 pb-5">
                            <div class="-mt-12 mb-3 flex h-24 w-24 items-center justify-center rounded-2xl border-4 border-white bg-gradient-to-br from-primary-500 to-orange-600 text-3xl font-bold uppercase text-white shadow-lg ring-1 ring-black/5 dark:border-[#1e2029]">
                                {{ initials }}
                            </div>
                            <h3 class="truncate text-lg font-semibold text-gray-900 dark:text-white">{{ form.name || 'New Holiday' }}</h3>
                            <p class="text-sm text-gray-400">{{ form.reason || '—' }}</p>

                            <dl class="mt-4 space-y-2 border-t border-gray-100 pt-4 text-sm dark:border-[#2a2d38]">
                                <div v-if="form.starts_at" class="flex items-center gap-2 text-gray-600 dark:text-gray-300">
                                    <CalendarDaysIcon class="h-4 w-4 flex-shrink-0 text-gray-400" /><span class="truncate">{{ formatDate(form.starts_at) }}</span>
                                </div>
                                <div v-if="form.ends_at" class="flex items-center gap-2 text-gray-600 dark:text-gray-300">
                                    <CalendarDaysIcon class="h-4 w-4 flex-shrink-0 text-gray-400" /><span class="truncate">{{ formatDate(form.ends_at) }}</span>
                                </div>
                                <p v-if="!form.starts_at && !form.ends_at" class="text-xs text-gray-400">The period appears here as you pick dates.</p>
                            </dl>
                        </div>
                    </section>

                    <!-- Relations -->
                    <section :class="cardCls">
                        <header :class="headerCls">
                            <span :class="iconBadge"><LinkIcon class="h-5 w-5" /></span>
                            <h3 :class="titleCls">Relations</h3>
                        </header>
                        <div class="space-y-4 p-5">
                            <Multiselect v-model="form.store_id" name="store_id" label="Store" mode="single"
                                :options="$page.props.stores ?? []" options-value-prop="id" options-label="name" :searchable="true" />
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </PageContent>
</template>

<script setup lang="ts">
import { computed } from "vue";
import {
    CalendarDaysIcon,
    PencilSquareIcon,
    LinkIcon,
} from "@heroicons/vue/24/outline";
import {
    TextInput,
    PageContent,
    DatePicker,
    Multiselect,
} from "craftable-pro/Components";
import { InertiaForm } from "craftable-pro/types";
import type { HolidayForm } from "./types";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";

dayjs.extend(customParseFormat);

interface Props {
    form: InertiaForm<HolidayForm>;
    submit: void;
}
const props = defineProps<Props>();

const cardCls = "rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-[#2c2f3d] dark:bg-[#1e2029]";
const headerCls = "flex items-center gap-3 border-b border-gray-100 px-6 py-4 dark:border-[#2a2d38]";
const iconBadge = "flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-primary-500/10 text-primary-500";
const titleCls = "text-[15px] font-semibold text-gray-900 dark:text-white";
const subCls = "text-xs text-gray-400";

const initials = computed(() => {
    const parts = (props.form.name || "").toString().trim().split(/\s+/).filter(Boolean);
    if (!parts.length) return "H";
    return (parts[0][0] + (parts[1]?.[0] ?? "")).toUpperCase();
});

const formatDate = (value: string) => dayjs(value).format("DD MMM YYYY");
</script>
