<template>
    <Head :title="`Order ${order.order_no}`" />

    <div class="mx-auto max-w-3xl px-4 py-8">
        <nav class="flex items-center gap-1.5 text-sm text-gray-500">
            <Link href="/account" class="hover:text-brand-600">My account</Link>
            <ChevronRightIcon class="h-4 w-4 text-gray-300" />
            <span class="text-gray-700">{{ order.order_no }}</span>
        </nav>

        <div class="mt-2 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900">Order {{ order.order_no }}</h1>
                <p class="mt-1 text-sm text-gray-500">Placed {{ order.date }}</p>
            </div>
            <span class="rounded-full px-3 py-1 text-xs font-semibold"
                :class="order.is_paid ? 'bg-green-50 text-green-700' : 'bg-amber-50 text-amber-700'">
                {{ order.is_paid ? 'Paid' : 'Pay on pickup' }}
            </span>
        </div>

        <!-- Cancelled -->
        <div v-if="order.canceled" class="mt-6 flex items-center gap-3 rounded-2xl border border-red-100 bg-red-50 px-5 py-4 text-sm text-red-700">
            <XCircleIcon class="h-5 w-5 flex-shrink-0" />
            This order was cancelled. If you have questions, please contact us.
        </div>

        <!-- Status timeline -->
        <section v-else class="mt-6 rounded-2xl border border-gray-100 bg-white p-6">
            <h2 class="text-lg font-semibold text-gray-900">Tracking</h2>
            <ol class="mt-6 space-y-0">
                <li v-for="(s, i) in steps" :key="s.name" class="relative flex gap-4 pb-8 last:pb-0">
                    <!-- connector -->
                    <span v-if="i < steps.length - 1" class="absolute left-[15px] top-8 h-full w-0.5"
                        :class="s.done ? 'bg-brand-500' : 'bg-gray-200'"></span>
                    <!-- dot -->
                    <span class="relative z-10 flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full ring-4 ring-white"
                        :class="s.done ? 'bg-brand-500 text-white' : (s.current ? 'bg-brand-100 text-brand-600' : 'bg-gray-100 text-gray-400')">
                        <CheckIcon v-if="s.done" class="h-4 w-4" />
                        <span v-else class="h-2 w-2 rounded-full" :class="s.current ? 'bg-brand-500' : 'bg-gray-300'"></span>
                    </span>
                    <div class="pt-1">
                        <p class="text-sm font-semibold" :class="s.done || s.current ? 'text-gray-900' : 'text-gray-400'">{{ s.label }}</p>
                        <p v-if="s.at" class="text-xs text-gray-400">{{ s.at }}</p>
                        <p v-else-if="s.current" class="text-xs text-brand-600">In progress</p>
                    </div>
                </li>
            </ol>
        </section>

        <!-- Items -->
        <section class="mt-6 rounded-2xl border border-gray-100 bg-white">
            <header class="border-b border-gray-100 px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-900">Items</h2>
            </header>
            <div class="divide-y divide-gray-100">
                <div v-for="(l, i) in order.lines" :key="i" class="flex items-center justify-between px-6 py-3 text-sm">
                    <span class="text-gray-700"><span class="font-medium text-gray-900">{{ l.qty }}×</span> {{ l.name }}</span>
                    <span class="font-medium tabular-nums text-gray-900">{{ money(l.total) }}</span>
                </div>
            </div>
            <div class="flex items-center justify-between border-t border-gray-100 px-6 py-4">
                <span class="text-sm font-semibold text-gray-900">Total</span>
                <span class="text-lg font-bold text-brand-600">{{ money(order.total) }}</span>
            </div>
        </section>

        <p class="mt-6 text-center text-xs text-gray-400">Collect your order at the store · Pay on pickup</p>
    </div>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ChevronRightIcon, CheckIcon, XCircleIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    order: { type: Object, required: true },
    steps: { type: Array, default: () => [] },
    currency: { type: String, default: 'DH' },
});

const money = (v) => Number(v ?? 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' ' + props.currency;
</script>
