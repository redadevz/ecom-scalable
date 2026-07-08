<template>
    <PageHeader sticky title="Cash Register">
        <Button color="gray" variant="outline" :as="Link" :href="route('craftable-pro.pos')" :leftIcon="ArrowLeftIcon">
            Back to POS
        </Button>
    </PageHeader>

    <PageContent>
        <div class="mx-auto max-w-5xl space-y-6">
            <!-- status banner -->
            <section class="flex items-center gap-4 rounded-2xl border p-5 shadow-sm"
                :class="session ? 'border-green-200 bg-green-50 dark:border-green-500/20 dark:bg-green-500/5' : 'border-gray-200 bg-white dark:border-[#2c2f3d] dark:bg-[#1e2029]'">
                <span class="flex h-12 w-12 items-center justify-center rounded-2xl"
                    :class="session ? 'bg-green-500/15 text-green-600 dark:text-green-400' : 'bg-gray-500/10 text-gray-500'">
                    <LockOpenIcon v-if="session" class="h-6 w-6" /><LockClosedIcon v-else class="h-6 w-6" />
                </span>
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ session ? 'Register open' : 'Register closed' }}</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        <template v-if="session">Opened {{ fmt(session.opened_at) }} · float {{ money(session.opening_float) }}</template>
                        <template v-else>Open the register with a starting float to begin taking cash sales.</template>
                    </p>
                </div>
            </section>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <!-- OPEN form -->
                <section v-if="!session" :class="cardCls">
                    <header :class="headerCls"><span :class="iconBadge"><BanknotesIcon class="h-5 w-5" /></span><h3 :class="titleCls">Open register</h3></header>
                    <div class="space-y-5 p-6">
                        <div>
                            <label :class="labelCls">Opening float (cash in drawer)</label>
                            <div class="relative max-w-xs">
                                <input v-model.number="openForm.opening_float" type="number" min="0" step="0.01" :class="[inputCls, 'pr-14 text-right font-semibold tabular-nums']" />
                                <span class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-sm font-medium text-gray-400">{{ currency }}</span>
                            </div>
                            <p v-if="openForm.errors.opening_float" class="mt-1 text-xs text-red-500">{{ openForm.errors.opening_float }}</p>
                        </div>
                        <Button :leftIcon="LockOpenIcon" @click="submitOpen" :loading="openForm.processing">Open register</Button>
                    </div>
                </section>

                <!-- X report (live) -->
                <section v-if="session && summary" :class="cardCls">
                    <header :class="headerCls"><span :class="iconBadge"><ChartBarIcon class="h-5 w-5" /></span><div><h3 :class="titleCls">Current session (X report)</h3><p :class="subCls">Live totals since open.</p></div></header>
                    <dl class="divide-y divide-gray-100 dark:divide-[#2a2d38]">
                        <div class="flex justify-between px-6 py-3 text-sm"><dt class="text-gray-500 dark:text-gray-400">Opening float</dt><dd class="tabular-nums text-gray-900 dark:text-white">{{ money(summary.opening_float) }}</dd></div>
                        <div class="flex justify-between px-6 py-3 text-sm"><dt class="text-gray-500 dark:text-gray-400">Cash sales</dt><dd class="tabular-nums text-gray-900 dark:text-white">{{ money(summary.cash_sales) }}</dd></div>
                        <div class="flex justify-between px-6 py-3 text-sm"><dt class="text-gray-500 dark:text-gray-400">Card / other sales</dt><dd class="tabular-nums text-gray-900 dark:text-white">{{ money(summary.other_sales) }}</dd></div>
                        <div class="flex justify-between px-6 py-3 text-sm"><dt class="text-gray-500 dark:text-gray-400">Total sales</dt><dd class="tabular-nums font-medium text-gray-900 dark:text-white">{{ money(summary.total_sales) }}</dd></div>
                        <div class="flex justify-between bg-primary-500/5 px-6 py-3.5 text-sm"><dt class="font-medium text-gray-900 dark:text-white">Expected in drawer</dt><dd class="tabular-nums text-lg font-bold text-primary-600 dark:text-primary-400">{{ money(summary.expected_cash) }}</dd></div>
                    </dl>
                </section>

                <!-- CLOSE form -->
                <section v-if="session && summary" :class="cardCls">
                    <header :class="headerCls"><span :class="iconBadge"><LockClosedIcon class="h-5 w-5" /></span><div><h3 :class="titleCls">Close register (Z report)</h3><p :class="subCls">Count the drawer and reconcile.</p></div></header>
                    <div class="space-y-5 p-6">
                        <div>
                            <label :class="labelCls">Counted cash</label>
                            <div class="relative max-w-xs">
                                <input v-model.number="closeForm.counted_cash" type="number" min="0" step="0.01" :class="[inputCls, 'pr-14 text-right font-semibold tabular-nums']" />
                                <span class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-sm font-medium text-gray-400">{{ currency }}</span>
                            </div>
                            <p v-if="closeForm.errors.counted_cash" class="mt-1 text-xs text-red-500">{{ closeForm.errors.counted_cash }}</p>
                        </div>
                        <div v-if="closeForm.counted_cash !== null && closeForm.counted_cash !== ''" class="flex items-center justify-between rounded-xl px-4 py-3 text-sm"
                            :class="variance === 0 ? 'bg-green-50 dark:bg-green-500/10' : 'bg-amber-50 dark:bg-amber-500/10'">
                            <span class="font-medium text-gray-700 dark:text-gray-200">{{ variance === 0 ? 'Balanced' : (variance > 0 ? 'Over' : 'Short') }}</span>
                            <span class="tabular-nums font-bold" :class="variance === 0 ? 'text-green-600 dark:text-green-400' : 'text-amber-600 dark:text-amber-400'">{{ money(variance) }}</span>
                        </div>
                        <div>
                            <label :class="labelCls">Notes (optional)</label>
                            <textarea v-model="closeForm.notes" rows="2" :class="inputCls" placeholder="e.g. counted twice, till drop at 3pm…"></textarea>
                        </div>
                        <Button color="danger" :leftIcon="LockClosedIcon" @click="submitClose" :loading="closeForm.processing">Close register</Button>
                    </div>
                </section>
            </div>

            <!-- recent sessions -->
            <section v-if="recent.length" :class="cardCls">
                <header :class="headerCls"><span :class="iconBadge"><ClockIcon class="h-5 w-5" /></span><h3 :class="titleCls">Recent sessions</h3></header>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-100 text-left text-xs uppercase tracking-wide text-gray-400 dark:border-[#2a2d38]">
                                <th class="px-6 py-3 font-medium">Opened</th><th class="px-6 py-3 font-medium">Closed</th>
                                <th class="px-6 py-3 text-right font-medium">Sales</th><th class="px-6 py-3 text-right font-medium">Expected</th>
                                <th class="px-6 py-3 text-right font-medium">Counted</th><th class="px-6 py-3 text-right font-medium">Variance</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-[#2a2d38]">
                            <tr v-for="s in recent" :key="s.id">
                                <td class="px-6 py-3 text-gray-500 dark:text-gray-400">{{ fmt(s.opened_at) }}</td>
                                <td class="px-6 py-3 text-gray-500 dark:text-gray-400">{{ fmt(s.closed_at) }}</td>
                                <td class="px-6 py-3 text-right tabular-nums text-gray-900 dark:text-white">{{ money(Number(s.cash_sales) + Number(s.other_sales)) }}</td>
                                <td class="px-6 py-3 text-right tabular-nums text-gray-900 dark:text-white">{{ money(s.expected_cash) }}</td>
                                <td class="px-6 py-3 text-right tabular-nums text-gray-900 dark:text-white">{{ money(s.counted_cash) }}</td>
                                <td class="px-6 py-3 text-right">
                                    <span class="rounded-full px-2 py-0.5 text-xs font-semibold tabular-nums"
                                        :class="Number(s.variance) === 0 ? 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400' : 'bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400'">
                                        {{ money(s.variance) }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </PageContent>
</template>

<script setup lang="ts">
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";
import { PageHeader, PageContent, Button } from "craftable-pro/Components";
import { useForm } from "craftable-pro/hooks/useForm";
import { ArrowLeftIcon, BanknotesIcon, ChartBarIcon, LockOpenIcon, LockClosedIcon, ClockIcon } from "@heroicons/vue/24/outline";
import dayjs from "dayjs";

interface Session { id: number; opening_float: number; opened_at: string; }
interface Summary { opening_float: number; cash_sales: number; other_sales: number; total_sales: number; expected_cash: number; }

interface Props {
    session: Session | null;
    summary: Summary | null;
    currency: string;
    recent: Array<Record<string, any>>;
}
const props = defineProps<Props>();

const labelCls = "mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300";
const inputCls = "w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 shadow-sm transition placeholder:text-gray-400 focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:border-[#2c2f3d] dark:bg-[#151720] dark:text-white";
const cardCls = "rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-[#2c2f3d] dark:bg-[#1e2029]";
const headerCls = "flex items-center gap-3 border-b border-gray-100 px-6 py-4 dark:border-[#2a2d38]";
const iconBadge = "flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-primary-500/10 text-primary-500";
const titleCls = "text-[15px] font-semibold text-gray-900 dark:text-white";
const subCls = "text-xs text-gray-400";

const money = (v: any) => Number(v ?? 0).toLocaleString("en-US", { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + " " + props.currency;
const fmt = (d: any) => (d ? dayjs(d).format("DD MMM, HH:mm") : "—");

const { form: openForm, submit: submitOpen } = useForm({ opening_float: 0 }, route("craftable-pro.pos.till.open"), "post");
const { form: closeForm, submit: submitClose } = useForm({ counted_cash: null as number | null, notes: "" }, route("craftable-pro.pos.till.close"), "post");

const variance = computed(() => {
    if (!props.summary || closeForm.counted_cash === null) return 0;
    return +(Number(closeForm.counted_cash) - props.summary.expected_cash).toFixed(2);
});
</script>
