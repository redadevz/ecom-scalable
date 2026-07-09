<template>
    <Head title="Order confirmed" />

    <div class="mx-auto max-w-2xl px-4 py-16 text-center">
        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-green-100 text-green-600">
            <CheckCircleIcon class="h-10 w-10" />
        </div>
        <h1 class="mt-6 text-3xl font-bold tracking-tight text-gray-900">Thank you{{ order.name ? `, ${order.name.split(' ')[0]}` : '' }}!</h1>
        <p class="mt-2 text-gray-500">Your order has been placed and is being prepared.</p>

        <div class="mt-8 rounded-2xl border border-gray-100 bg-white p-6 text-left shadow-sm">
            <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                <div>
                    <p class="text-xs uppercase tracking-wide text-gray-400">Order number</p>
                    <p class="text-lg font-bold text-gray-900">{{ order.order_no }}</p>
                </div>
                <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700">Pay on pickup</span>
            </div>
            <dl class="mt-4 space-y-3 text-sm">
                <div class="flex justify-between"><dt class="text-gray-500">Items</dt><dd class="font-medium text-gray-900">{{ order.items }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Fulfilment</dt><dd class="font-medium text-gray-900">{{ order.delivery }}</dd></div>
                <div v-if="order.email" class="flex justify-between"><dt class="text-gray-500">Email</dt><dd class="font-medium text-gray-900">{{ order.email }}</dd></div>
                <div class="flex items-baseline justify-between border-t border-gray-100 pt-3"><dt class="text-base font-semibold text-gray-900">Total</dt><dd class="text-xl font-bold text-brand-600">{{ money(order.total) }}</dd></div>
            </dl>
        </div>

        <p class="mt-6 text-sm text-gray-500">We'll have your order ready shortly. Please pay when you collect it in store.</p>

        <div class="mt-8 flex justify-center gap-3">
            <Link href="/products" class="rounded-full bg-brand-500 px-6 py-3 text-sm font-semibold text-white transition hover:bg-brand-600">Continue shopping</Link>
            <Link href="/" class="rounded-full px-6 py-3 text-sm font-semibold text-gray-600 ring-1 ring-gray-200 transition hover:bg-gray-50">Back home</Link>
        </div>
    </div>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { CheckCircleIcon } from '@heroicons/vue/24/solid';

const props = defineProps({
    order: { type: Object, required: true },
    currency: { type: String, default: 'DH' },
});

const money = (v) => Number(v ?? 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' ' + props.currency;
</script>
