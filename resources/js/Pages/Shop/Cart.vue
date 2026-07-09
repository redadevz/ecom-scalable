<template>
    <Head title="Cart" />

    <div class="mx-auto max-w-7xl px-4 py-8">
        <h1 class="text-2xl font-bold tracking-tight text-gray-900">Your Cart</h1>

        <div v-if="lines.length" class="mt-6 grid gap-8 lg:grid-cols-3">
            <!-- items -->
            <div class="lg:col-span-2">
                <div class="divide-y divide-gray-100 rounded-2xl border border-gray-100 bg-white">
                    <div v-for="line in lines" :key="line.item_id" class="flex items-center gap-4 p-4">
                        <Link :href="`/products/${line.item_id}`" class="h-20 w-20 flex-shrink-0 overflow-hidden rounded-xl bg-gray-50">
                            <img v-if="line.image" :src="line.image" :alt="line.name" class="h-full w-full object-cover" />
                            <span v-else class="flex h-full w-full items-center justify-center bg-gradient-to-br from-brand-100 to-orange-50 text-xl font-bold text-brand-300">{{ line.name.charAt(0) }}</span>
                        </Link>
                        <div class="min-w-0 flex-1">
                            <Link :href="`/products/${line.item_id}`" class="text-sm font-semibold text-gray-900 hover:text-brand-600">{{ line.name }}</Link>
                            <p class="text-xs text-gray-400">{{ line.sku }}</p>
                            <p class="mt-1 text-sm font-medium text-brand-600">{{ money(line.unit_price) }}</p>
                        </div>
                        <div class="flex items-center rounded-full border border-gray-200">
                            <button type="button" @click="setQty(line, line.quantity - 1)" class="flex h-9 w-9 items-center justify-center text-gray-500 hover:text-brand-600"><MinusIcon class="h-4 w-4" /></button>
                            <span class="w-8 text-center text-sm font-semibold tabular-nums">{{ line.quantity }}</span>
                            <button type="button" @click="setQty(line, line.quantity + 1)" class="flex h-9 w-9 items-center justify-center text-gray-500 hover:text-brand-600"><PlusIcon class="h-4 w-4" /></button>
                        </div>
                        <div class="w-24 text-right text-sm font-bold text-gray-900">{{ money(line.line_total) }}</div>
                        <button type="button" @click="remove(line)" class="text-gray-300 hover:text-red-500"><TrashIcon class="h-5 w-5" /></button>
                    </div>
                </div>
                <Link href="/products" class="mt-4 inline-flex items-center gap-1.5 text-sm font-semibold text-brand-600 hover:text-brand-700">
                    <ArrowLeftIcon class="h-4 w-4" /> Continue shopping
                </Link>
            </div>

            <!-- summary -->
            <div>
                <div class="rounded-2xl border border-gray-100 bg-white p-6 lg:sticky lg:top-28">
                    <h2 class="text-lg font-semibold text-gray-900">Order summary</h2>
                    <dl class="mt-4 space-y-3 text-sm">
                        <div class="flex justify-between text-gray-500"><dt>Subtotal ({{ summary.count }} items)</dt><dd class="tabular-nums text-gray-900">{{ money(summary.subtotal) }}</dd></div>
                        <div class="flex justify-between text-gray-500"><dt>Pickup</dt><dd class="font-medium text-green-600">Free</dd></div>
                        <div class="flex items-baseline justify-between border-t border-gray-100 pt-3">
                            <dt class="text-base font-semibold text-gray-900">Total</dt>
                            <dd class="text-xl font-bold text-brand-600">{{ money(summary.total) }}</dd>
                        </div>
                    </dl>
                    <Link href="/checkout" class="mt-5 flex w-full items-center justify-center gap-2 rounded-full bg-brand-500 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-brand-500/25 transition hover:bg-brand-600">
                        Checkout <ArrowRightIcon class="h-4 w-4" />
                    </Link>
                    <p class="mt-3 text-center text-xs text-gray-400">Tax included · Pay on pickup</p>
                </div>
            </div>
        </div>

        <!-- empty -->
        <div v-else class="mt-10 rounded-2xl border border-dashed border-gray-200 py-20 text-center">
            <ShoppingCartIcon class="mx-auto h-10 w-10 text-gray-300" />
            <p class="mt-3 text-gray-500">Your cart is empty.</p>
            <Link href="/products" class="mt-4 inline-flex items-center gap-2 rounded-full bg-brand-500 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-600">Start shopping</Link>
        </div>
    </div>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { PlusIcon, MinusIcon, TrashIcon, ArrowRightIcon, ArrowLeftIcon, ShoppingCartIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    lines: { type: Array, default: () => [] },
    summary: { type: Object, default: () => ({}) },
    currency: { type: String, default: 'DH' },
});

const money = (v) => Number(v ?? 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' ' + props.currency;

function setQty(line, qty) {
    router.patch(`/cart/${line.item_id}`, { quantity: Math.max(0, qty) }, { preserveScroll: true, preserveState: true });
}
function remove(line) {
    router.delete(`/cart/${line.item_id}`, { preserveScroll: true, preserveState: true });
}
</script>
