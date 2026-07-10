<template>
    <Head title="Checkout" />

    <div class="mx-auto max-w-7xl px-4 py-8">
        <nav class="flex items-center gap-1.5 text-sm text-gray-500">
            <Link href="/cart" class="hover:text-brand-600">Cart</Link>
            <ChevronRightIcon class="h-4 w-4 text-gray-300" />
            <span class="text-gray-700">Checkout</span>
        </nav>
        <h1 class="mt-2 text-2xl font-bold tracking-tight text-gray-900">Checkout</h1>

        <div v-if="!prefill" class="mt-4 flex flex-wrap items-center justify-between gap-2 rounded-xl bg-brand-50 px-4 py-3 text-sm text-brand-800">
            <span>Have an account? Sign in for faster checkout.</span>
            <Link href="/account/login" class="font-semibold text-brand-700 hover:underline">Sign in →</Link>
        </div>

        <form @submit.prevent="submit" class="mt-6 grid gap-8 lg:grid-cols-3">
            <!-- details -->
            <div class="space-y-6 lg:col-span-2">
                <section class="rounded-2xl border border-gray-100 bg-white p-6">
                    <h2 class="text-lg font-semibold text-gray-900">Your details</h2>
                    <div class="mt-4 grid gap-4 sm:grid-cols-2">
                        <Field label="First name" :error="form.errors.first_name"><input v-model="form.first_name" type="text" :class="input" /></Field>
                        <Field label="Last name" :error="form.errors.last_name"><input v-model="form.last_name" type="text" :class="input" /></Field>
                        <Field label="Phone" :error="form.errors.phone"><input v-model="form.phone" type="tel" :class="input" /></Field>
                        <Field label="Email (optional)" :error="form.errors.email"><input v-model="form.email" type="email" :class="input" /></Field>
                    </div>
                </section>

                <section class="rounded-2xl border border-gray-100 bg-white p-6">
                    <h2 class="text-lg font-semibold text-gray-900">Delivery</h2>
                    <div class="mt-4 space-y-4">
                        <div class="flex flex-wrap gap-2">
                            <button v-for="d in deliveryTypes" :key="d.id" type="button" @click="form.delivery_type_id = d.id"
                                :class="form.delivery_type_id === d.id ? 'border-brand-500 bg-brand-50 text-brand-600' : 'border-gray-200 text-gray-600'"
                                class="rounded-xl border px-4 py-2 text-sm font-medium transition">{{ d.name }}</button>
                        </div>
                        <Field label="Address" :error="form.errors.billing_address"><input v-model="form.billing_address" type="text" :class="input" placeholder="Street, building, etc." /></Field>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <Field label="Postal code (optional)" :error="form.errors.postal_code"><input v-model="form.postal_code" type="text" :class="input" /></Field>
                        </div>
                        <Field label="Order notes (optional)" :error="form.errors.notes"><textarea v-model="form.notes" rows="2" :class="input" placeholder="Anything we should know?"></textarea></Field>
                    </div>
                </section>
            </div>

            <!-- summary -->
            <div>
                <div class="rounded-2xl border border-gray-100 bg-white p-6 lg:sticky lg:top-28">
                    <h2 class="text-lg font-semibold text-gray-900">Your order</h2>
                    <div class="mt-4 max-h-64 space-y-3 overflow-y-auto">
                        <div v-for="line in lines" :key="line.item_id" class="flex items-center gap-3">
                            <div class="relative h-12 w-12 flex-shrink-0 overflow-hidden rounded-lg bg-gray-50">
                                <img v-if="line.image" :src="line.image" :alt="line.name" class="h-full w-full object-cover" />
                                <span v-else class="flex h-full w-full items-center justify-center bg-gradient-to-br from-brand-100 to-orange-50 text-sm font-bold text-brand-300">{{ line.name.charAt(0) }}</span>
                                <span class="absolute -right-1 -top-1 flex h-5 w-5 items-center justify-center rounded-full bg-brand-500 text-[10px] font-bold text-white">{{ line.quantity }}</span>
                            </div>
                            <span class="min-w-0 flex-1 truncate text-sm text-gray-700">{{ line.name }}</span>
                            <span class="text-sm font-medium text-gray-900">{{ money(line.line_total) }}</span>
                        </div>
                    </div>
                    <dl class="mt-4 space-y-2 border-t border-gray-100 pt-4 text-sm">
                        <div class="flex justify-between text-gray-500"><dt>Subtotal</dt><dd class="tabular-nums text-gray-900">{{ money(summary.subtotal) }}</dd></div>
                        <div class="flex justify-between text-gray-500"><dt>Pickup</dt><dd class="font-medium text-green-600">Free</dd></div>
                        <div class="flex items-baseline justify-between border-t border-gray-100 pt-2"><dt class="font-semibold text-gray-900">Total</dt><dd class="text-xl font-bold text-brand-600">{{ money(summary.total) }}</dd></div>
                    </dl>
                    <button type="submit" :disabled="form.processing" class="mt-5 flex w-full items-center justify-center gap-2 rounded-full bg-brand-500 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-brand-500/25 transition hover:bg-brand-600 disabled:opacity-60">
                        {{ form.processing ? 'Placing order…' : 'Place order' }}
                    </button>
                    <p class="mt-3 text-center text-xs text-gray-400">Pay on pickup · No card required</p>
                </div>
            </div>
        </form>
    </div>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ChevronRightIcon } from '@heroicons/vue/24/outline';
import Field from '@/Components/Field.vue';

const props = defineProps({
    lines: { type: Array, default: () => [] },
    summary: { type: Object, default: () => ({}) },
    deliveryTypes: { type: Array, default: () => [] },
    prefill: { type: Object, default: null },
    currency: { type: String, default: 'DH' },
});

const input = 'w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20';
const money = (v) => Number(v ?? 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' ' + props.currency;

const form = useForm({
    first_name: props.prefill?.first_name ?? '',
    last_name: props.prefill?.last_name ?? '',
    phone: props.prefill?.phone ?? '',
    email: props.prefill?.email ?? '',
    billing_address: props.prefill?.billing_address ?? '',
    postal_code: props.prefill?.postal_code ?? '',
    delivery_type_id: props.deliveryTypes.find((d) => d.name === 'Pickup')?.id ?? props.deliveryTypes[0]?.id ?? null,
    notes: '',
});

function submit() {
    form.post('/checkout');
}
</script>
