<template>
    <Head :title="filters.q ? `Search: ${filters.q}` : 'Shop'" />

    <div class="mx-auto max-w-7xl px-4 py-8">
        <!-- heading -->
        <div class="flex flex-wrap items-end justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900">
                    {{ activeCategory ? activeCategory.name : 'All products' }}
                </h1>
                <p class="mt-1 text-sm text-gray-500">
                    {{ products.total }} {{ products.total === 1 ? 'product' : 'products' }}
                    <span v-if="filters.q"> matching “{{ filters.q }}”</span>
                </p>
            </div>
            <Link v-if="filters.q || filters.category" href="/products" class="text-sm font-medium text-brand-600 hover:text-brand-700">Clear filters</Link>
        </div>

        <!-- category chips -->
        <div class="mt-5 flex flex-wrap gap-2">
            <Link href="/products"
                :class="!filters.category ? 'bg-brand-500 text-white' : 'bg-white text-gray-600 ring-1 ring-gray-200 hover:ring-brand-300'"
                class="rounded-full px-4 py-1.5 text-sm font-medium transition">All</Link>
            <Link v-for="c in categories" :key="c.id" :href="`/products?category=${c.id}`"
                :class="filters.category === c.id ? 'bg-brand-500 text-white' : 'bg-white text-gray-600 ring-1 ring-gray-200 hover:ring-brand-300'"
                class="rounded-full px-4 py-1.5 text-sm font-medium transition">{{ c.name }}</Link>
        </div>

        <!-- grid -->
        <div v-if="products.data.length" class="mt-8 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
            <ProductCard v-for="p in products.data" :key="p.id" :product="p" :currency="currency" />
        </div>
        <div v-else class="mt-8 rounded-2xl border border-dashed border-gray-200 py-20 text-center">
            <p class="text-gray-500">No products found.</p>
            <Link href="/products" class="mt-3 inline-block text-sm font-semibold text-brand-600 hover:text-brand-700">Browse everything →</Link>
        </div>

        <!-- pagination -->
        <nav v-if="products.last_page > 1" class="mt-10 flex flex-wrap justify-center gap-1">
            <template v-for="(link, i) in products.links" :key="i">
                <Link v-if="link.url" :href="link.url"
                    :class="link.active ? 'bg-brand-500 text-white' : 'bg-white text-gray-600 ring-1 ring-gray-200 hover:ring-brand-300'"
                    class="min-w-[2.25rem] rounded-lg px-3 py-2 text-center text-sm font-medium transition" v-html="link.label" />
                <span v-else class="min-w-[2.25rem] rounded-lg px-3 py-2 text-center text-sm text-gray-300" v-html="link.label" />
            </template>
        </nav>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import ProductCard from '@/Components/ProductCard.vue';

const props = defineProps({
    products: { type: Object, required: true },
    filters: { type: Object, default: () => ({}) },
    currency: { type: String, default: 'DH' },
});

const categories = computed(() => usePage().props.categories ?? []);
const activeCategory = computed(() => categories.value.find((c) => c.id === props.filters.category) ?? null);
</script>
