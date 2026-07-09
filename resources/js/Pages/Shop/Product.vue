<template>
    <Head :title="product.name" />

    <div class="mx-auto max-w-7xl px-4 py-6">
        <!-- breadcrumb -->
        <nav class="flex items-center gap-1.5 text-sm text-gray-500">
            <Link href="/" class="hover:text-brand-600">Home</Link>
            <ChevronRightIcon class="h-4 w-4 text-gray-300" />
            <Link href="/products" class="hover:text-brand-600">Shop</Link>
            <ChevronRightIcon class="h-4 w-4 text-gray-300" />
            <span class="truncate text-gray-700">{{ product.name }}</span>
        </nav>

        <div class="mt-6 grid gap-8 lg:grid-cols-2">
            <!-- image -->
            <div class="overflow-hidden rounded-3xl border border-gray-100 bg-gray-50">
                <div class="aspect-square">
                    <img v-if="product.image" :src="product.image" :alt="product.name" class="h-full w-full object-cover" />
                    <span v-else class="flex h-full w-full items-center justify-center bg-gradient-to-br from-brand-100 to-orange-50 text-8xl font-bold text-brand-300">{{ product.name.charAt(0) }}</span>
                </div>
            </div>

            <!-- details -->
            <div class="flex flex-col">
                <span v-if="product.category" class="text-sm font-medium text-brand-600">{{ product.category }}</span>
                <h1 class="mt-1 text-3xl font-bold tracking-tight text-gray-900">{{ product.name }}</h1>
                <p class="mt-1 text-sm text-gray-400">SKU: {{ product.sku }}</p>

                <div class="mt-5 flex items-center gap-3">
                    <span class="text-3xl font-bold text-gray-900">{{ money(product.price) }}</span>
                    <span v-if="product.in_stock" class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-3 py-1 text-sm font-medium text-green-700">
                        <CheckCircleIcon class="h-4 w-4" /> In stock
                    </span>
                    <span v-else class="inline-flex items-center gap-1.5 rounded-full bg-red-50 px-3 py-1 text-sm font-medium text-red-600">Out of stock</span>
                </div>

                <p v-if="product.description" class="mt-6 leading-relaxed text-gray-600">{{ product.description }}</p>

                <!-- add to cart (wired in Step 24) -->
                <div class="mt-8 flex items-center gap-3">
                    <div class="flex items-center rounded-full border border-gray-200">
                        <button type="button" @click="qty = Math.max(1, qty - 1)" class="flex h-11 w-11 items-center justify-center text-gray-500 hover:text-brand-600"><MinusIcon class="h-4 w-4" /></button>
                        <span class="w-10 text-center text-sm font-semibold tabular-nums">{{ qty }}</span>
                        <button type="button" @click="qty++" class="flex h-11 w-11 items-center justify-center text-gray-500 hover:text-brand-600"><PlusIcon class="h-4 w-4" /></button>
                    </div>
                    <button type="button" :disabled="!product.in_stock"
                        class="inline-flex flex-1 items-center justify-center gap-2 rounded-full bg-brand-500 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-brand-500/25 transition hover:bg-brand-600 disabled:cursor-not-allowed disabled:opacity-50">
                        <ShoppingCartIcon class="h-5 w-5" /> Add to cart
                    </button>
                </div>
                <p class="mt-3 text-xs text-gray-400">Secure checkout · Pickup in store</p>
            </div>
        </div>

        <!-- related -->
        <section v-if="related.length" class="mt-16">
            <h2 class="mb-5 text-xl font-bold tracking-tight text-gray-900">You may also like</h2>
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                <ProductCard v-for="p in related" :key="p.id" :product="p" :currency="currency" />
            </div>
        </section>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import { ChevronRightIcon, CheckCircleIcon, ShoppingCartIcon, PlusIcon, MinusIcon } from '@heroicons/vue/24/outline';
import ProductCard from '@/Components/ProductCard.vue';

const props = defineProps({
    product: { type: Object, required: true },
    related: { type: Array, default: () => [] },
    currency: { type: String, default: 'DH' },
});

const qty = ref(1);
const money = (v) => Number(v ?? 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' ' + props.currency;
</script>
