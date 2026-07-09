<template>
    <div class="group relative flex flex-col overflow-hidden rounded-2xl border border-gray-100 bg-white transition duration-200 hover:-translate-y-1 hover:border-brand-200 hover:shadow-xl">
        <Link :href="`/products/${product.id}`" class="relative block aspect-square overflow-hidden bg-gray-50">
            <img v-if="product.image" :src="product.image" :alt="product.name" class="h-full w-full object-cover transition duration-300 group-hover:scale-105" />
            <span v-else class="flex h-full w-full items-center justify-center bg-gradient-to-br from-brand-100 to-orange-50 text-4xl font-bold text-brand-300">{{ product.name.charAt(0) }}</span>
            <span v-if="!product.in_stock" class="absolute left-3 top-3 rounded-full bg-gray-900/80 px-2.5 py-1 text-[11px] font-semibold text-white">Sold out</span>
        </Link>

        <!-- quick actions -->
        <div class="pointer-events-none absolute right-3 top-3 flex flex-col gap-2 opacity-0 transition group-hover:pointer-events-auto group-hover:opacity-100">
            <button type="button" @click="addToCart" :disabled="!product.in_stock" class="flex h-9 w-9 items-center justify-center rounded-full bg-white text-gray-600 shadow-md transition hover:bg-brand-500 hover:text-white disabled:opacity-50" title="Add to cart">
                <ShoppingCartIcon class="h-4 w-4" />
            </button>
            <Link :href="`/products/${product.id}`" class="flex h-9 w-9 items-center justify-center rounded-full bg-white text-gray-600 shadow-md transition hover:bg-brand-500 hover:text-white" title="View">
                <EyeIcon class="h-4 w-4" />
            </Link>
        </div>

        <div class="flex flex-1 flex-col p-4">
            <Link :href="`/products/${product.id}`" class="line-clamp-2 text-sm font-semibold text-gray-800 transition hover:text-brand-600">{{ product.name }}</Link>
            <!-- rating -->
            <div class="mt-1.5 flex items-center gap-1">
                <div class="flex text-amber-400">
                    <StarIcon v-for="n in 5" :key="n" class="h-3.5 w-3.5" :class="n <= rating ? '' : 'text-gray-200'" />
                </div>
                <span class="text-xs text-gray-400">({{ reviews }})</span>
            </div>
            <div class="mt-auto pt-3">
                <span class="text-lg font-bold text-gray-900">{{ money(product.price) }}</span>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { ShoppingCartIcon, EyeIcon } from '@heroicons/vue/24/outline';
import { StarIcon } from '@heroicons/vue/24/solid';
import { useCart } from '@/stores/cart';

const props = defineProps({
    product: { type: Object, required: true },
    currency: { type: String, default: 'DH' },
});

const money = (v) => Number(v ?? 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' ' + props.currency;

function addToCart() {
    useCart().add(props.product.id, 1);
}
// Decorative, stable per-product rating (no reviews table wired yet).
const rating = computed(() => 4 + (props.product.id % 2));
const reviews = computed(() => 10 + ((props.product.id * 7) % 90));
</script>
