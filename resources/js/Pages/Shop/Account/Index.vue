<template>
    <Head title="My account" />

    <div class="mx-auto max-w-6xl px-4 py-8">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900">My account</h1>
                <p class="mt-1 text-sm text-gray-500">Hi {{ customer.first_name }} — manage your details and orders.</p>
            </div>
            <button type="button" @click="logout" class="inline-flex items-center gap-2 rounded-full border border-gray-200 px-4 py-2 text-sm font-medium text-gray-600 transition hover:border-red-200 hover:text-red-600">
                <ArrowRightOnRectangleIcon class="h-4 w-4" /> Sign out
            </button>
        </div>

        <div class="mt-6 grid gap-8 lg:grid-cols-3">
            <!-- profile -->
            <section class="lg:col-span-1">
                <div class="rounded-2xl border border-gray-100 bg-white p-6">
                    <h2 class="text-lg font-semibold text-gray-900">Profile</h2>
                    <form @submit.prevent="saveProfile" class="mt-4 space-y-4">
                        <div class="grid grid-cols-2 gap-3">
                            <Field label="First name" :error="form.errors.first_name"><input v-model="form.first_name" type="text" :class="input" /></Field>
                            <Field label="Last name" :error="form.errors.last_name"><input v-model="form.last_name" type="text" :class="input" /></Field>
                        </div>
                        <Field label="Email"><input :value="customer.email" type="email" disabled :class="[input, 'bg-gray-50 text-gray-400']" /></Field>
                        <Field label="Phone" :error="form.errors.phone"><input v-model="form.phone" type="tel" :class="input" /></Field>
                        <Field label="Address" :error="form.errors.billing_address"><input v-model="form.billing_address" type="text" :class="input" /></Field>
                        <Field label="Postal code" :error="form.errors.postal_code"><input v-model="form.postal_code" type="text" :class="input" /></Field>
                        <button type="submit" :disabled="form.processing" class="w-full rounded-full bg-brand-500 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-600 disabled:opacity-60">
                            {{ form.processing ? 'Saving…' : 'Save changes' }}
                        </button>
                    </form>
                </div>
            </section>

            <!-- orders -->
            <section class="lg:col-span-2">
                <div class="rounded-2xl border border-gray-100 bg-white">
                    <header class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-900">Order history</h2>
                        <span class="text-sm text-gray-400">{{ orders.length }} order{{ orders.length === 1 ? '' : 's' }}</span>
                    </header>

                    <div v-if="orders.length" class="divide-y divide-gray-100">
                        <div v-for="o in orders" :key="o.order_no" class="flex flex-wrap items-center justify-between gap-3 px-6 py-4">
                            <div>
                                <p class="font-semibold text-gray-900">{{ o.order_no }}</p>
                                <p class="text-xs text-gray-400">{{ o.date }}</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="rounded-full px-2.5 py-0.5 text-xs font-semibold"
                                    :class="o.is_paid ? 'bg-green-50 text-green-700' : 'bg-amber-50 text-amber-700'">
                                    {{ o.is_paid ? 'Paid' : (o.status || 'Processing') }}
                                </span>
                                <span class="w-24 text-right font-bold text-gray-900">{{ money(o.total) }}</span>
                            </div>
                        </div>
                    </div>
                    <div v-else class="px-6 py-16 text-center">
                        <ShoppingBagIcon class="mx-auto h-10 w-10 text-gray-200" />
                        <p class="mt-3 text-sm text-gray-500">No orders yet.</p>
                        <Link href="/products" class="mt-3 inline-block text-sm font-semibold text-brand-600 hover:text-brand-700">Start shopping →</Link>
                    </div>
                </div>
            </section>
        </div>
    </div>
</template>

<script setup>
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { ArrowRightOnRectangleIcon, ShoppingBagIcon } from '@heroicons/vue/24/outline';
import Field from '@/Components/Field.vue';

const props = defineProps({
    customer: { type: Object, required: true },
    orders: { type: Array, default: () => [] },
    currency: { type: String, default: 'DH' },
});

const input = 'w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20';
const money = (v) => Number(v ?? 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' ' + props.currency;

const form = useForm({
    first_name: props.customer.first_name ?? '',
    last_name: props.customer.last_name ?? '',
    phone: props.customer.phone ?? '',
    billing_address: props.customer.billing_address ?? '',
    postal_code: props.customer.postal_code ?? '',
});

function saveProfile() {
    form.patch('/account', { preserveScroll: true });
}
function logout() {
    router.post('/account/logout');
}
</script>
