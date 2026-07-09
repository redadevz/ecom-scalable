<template>
    <Head :title="filters.q ? `Search: ${filters.q}` : (activeCategory ? activeCategory.name : 'Shop')" />

    <!-- page header -->
    <div class="border-b border-gray-100 bg-gray-50">
        <div class="mx-auto max-w-7xl px-4 py-6">
            <nav class="flex items-center gap-1.5 text-sm text-gray-500">
                <Link href="/" class="hover:text-brand-600">Home</Link>
                <ChevronRightIcon class="h-4 w-4 text-gray-300" />
                <span class="text-gray-700">{{ activeCategory ? activeCategory.name : 'All products' }}</span>
            </nav>
            <h1 class="mt-2 text-2xl font-bold tracking-tight text-gray-900">
                {{ filters.q ? `Results for “${filters.q}”` : (activeCategory ? activeCategory.name : 'All products') }}
            </h1>
        </div>
    </div>

    <div class="mx-auto max-w-7xl gap-8 px-4 py-8 lg:flex">
        <!-- sidebar -->
        <aside class="mb-6 lg:mb-0 lg:w-64 lg:flex-shrink-0">
            <div class="lg:sticky lg:top-28">
                <div class="rounded-2xl border border-gray-100 bg-white p-5">
                    <h3 class="text-sm font-semibold text-gray-900">Categories</h3>
                    <ul class="mt-3 space-y-1">
                        <li>
                            <Link href="/products" class="flex items-center justify-between rounded-lg px-3 py-2 text-sm transition"
                                :class="!filters.category ? 'bg-brand-50 font-semibold text-brand-600' : 'text-gray-600 hover:bg-gray-50'">
                                All products
                            </Link>
                        </li>
                        <li v-for="c in categories" :key="c.id">
                            <Link :href="`/products?category=${c.id}`" class="flex items-center justify-between rounded-lg px-3 py-2 text-sm transition"
                                :class="filters.category === c.id ? 'bg-brand-50 font-semibold text-brand-600' : 'text-gray-600 hover:bg-gray-50'">
                                {{ c.name }}
                                <span class="text-xs text-gray-400">{{ c.items_count }}</span>
                            </Link>
                        </li>
                    </ul>
                </div>
                <div class="mt-4 hidden rounded-2xl bg-gradient-to-br from-brand-500 to-orange-600 p-6 text-white lg:block">
                    <p class="text-sm font-semibold">Need help choosing?</p>
                    <p class="mt-1 text-xs text-white/85">Visit us in store — our team is happy to help.</p>
                </div>
            </div>
        </aside>

        <!-- results -->
        <div class="min-w-0 flex-1">
            <!-- toolbar -->
            <div class="mb-5 flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-gray-100 bg-white px-4 py-3">
                <p class="text-sm text-gray-500">
                    <span class="font-semibold text-gray-900">{{ products.total }}</span> {{ products.total === 1 ? 'product' : 'products' }}
                    <Link v-if="filters.q || filters.category" href="/products" class="ml-2 text-brand-600 hover:underline">Clear</Link>
                </p>
                <div class="flex items-center gap-2">
                    <label class="text-sm text-gray-500">Sort by</label>
                    <select :value="filters.sort" @change="changeSort($event.target.value)"
                        class="rounded-lg border border-gray-200 bg-white py-1.5 pl-3 pr-8 text-sm font-medium text-gray-700 focus:border-brand-500 focus:ring-brand-500">
                        <option value="newest">Newest</option>
                        <option value="name">Name (A–Z)</option>
                        <option value="price_low">Price: Low to High</option>
                        <option value="price_high">Price: High to Low</option>
                    </select>
                </div>
            </div>

            <!-- grid -->
            <div v-if="products.data.length" class="grid grid-cols-2 gap-4 sm:grid-cols-3">
                <ProductCard v-for="p in products.data" :key="p.id" :product="p" :currency="currency" />
            </div>
            <div v-else class="rounded-2xl border border-dashed border-gray-200 py-20 text-center">
                <MagnifyingGlassIcon class="mx-auto h-8 w-8 text-gray-300" />
                <p class="mt-3 text-gray-500">No products found.</p>
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
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { ChevronRightIcon, MagnifyingGlassIcon } from '@heroicons/vue/24/outline';
import ProductCard from '@/Components/ProductCard.vue';

const props = defineProps({
    products: { type: Object, required: true },
    categories: { type: Array, default: () => [] },
    filters: { type: Object, default: () => ({}) },
    currency: { type: String, default: 'DH' },
});

const activeCategory = computed(() => props.categories.find((c) => c.id === props.filters.category) ?? null);

function changeSort(sort) {
    router.get('/products', { ...props.filters, sort }, { preserveScroll: true, preserveState: true });
}
</script>
