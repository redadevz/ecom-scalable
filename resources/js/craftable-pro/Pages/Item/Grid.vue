<template>
    <PageHeader :title="$t('craftable-pro', 'Items')">
        <div class="flex items-center gap-2">
            <div class="flex rounded-lg border border-gray-200 p-0.5 dark:border-[#2c2f3d]">
                <Link :href="route('craftable-pro.items.index')" class="flex h-8 w-8 items-center justify-center rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-white/5">
                    <ListBulletIcon class="h-5 w-5" />
                </Link>
                <span class="flex h-8 w-8 items-center justify-center rounded-md bg-primary-500 text-white">
                    <Squares2X2Icon class="h-5 w-5" />
                </span>
            </div>
            <Button :leftIcon="PlusIcon" :as="Link" :href="route('craftable-pro.items.create')" v-can="'craftable-pro.items.create'">
                {{ $t("craftable-pro", "New Item") }}
            </Button>
        </div>
    </PageHeader>

    <PageContent>
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-4">
            <!-- Filter sidebar -->
            <aside class="lg:col-span-1">
                <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-[#2c2f3d] dark:bg-[#212330]">
                    <div class="relative mb-4">
                        <MagnifyingGlassIcon class="pointer-events-none absolute left-3 top-2.5 h-4 w-4 text-gray-400" />
                        <input v-model="search" @keyup.enter="applyFilters" type="text" placeholder="Search..."
                            class="w-full rounded-lg border-gray-200 pl-9 text-sm dark:border-[#2c2f3d] dark:bg-[#1a1c27] dark:text-white" />
                    </div>

                    <h3 class="mb-2 text-sm font-semibold text-gray-900 dark:text-white">Categories</h3>
                    <label class="flex cursor-pointer items-center gap-2 rounded-lg px-2 py-1.5 text-sm hover:bg-gray-50 dark:hover:bg-white/5">
                        <input type="radio" :checked="!filters.category" @change="filterBy(null)" class="text-primary-500 focus:ring-primary-500" />
                        <span :class="!filters.category ? 'font-medium text-primary-600 dark:text-primary-400' : 'text-gray-600 dark:text-gray-300'">All categories</span>
                    </label>
                    <label v-for="c in categories" :key="c.id" class="flex cursor-pointer items-center gap-2 rounded-lg px-2 py-1.5 text-sm hover:bg-gray-50 dark:hover:bg-white/5">
                        <input type="radio" :checked="filters.category === c.id" @change="filterBy(c.id)" class="text-primary-500 focus:ring-primary-500" />
                        <span class="flex-1" :class="filters.category === c.id ? 'font-medium text-primary-600 dark:text-primary-400' : 'text-gray-600 dark:text-gray-300'">{{ c.name }}</span>
                        <span class="text-xs text-gray-400">({{ c.items_count }})</span>
                    </label>
                </div>
            </aside>

            <!-- Product grid -->
            <div class="lg:col-span-3">
                <div class="mb-4 flex items-center justify-between">
                    <p class="text-sm text-gray-500">
                        <span class="text-gray-400">Catalog</span> <span class="mx-1 text-gray-300">/</span> All Items ·
                        Showing <span class="font-semibold text-gray-700 dark:text-gray-300">{{ items.total }}</span> results
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-3">
                    <div v-for="item in items.data" :key="item.id"
                        class="group rounded-xl border border-gray-200 bg-white p-4 transition-shadow hover:shadow-md dark:border-[#2c2f3d] dark:bg-[#212330]">
                        <div class="mb-4 h-36 overflow-hidden rounded-lg bg-gray-50 dark:bg-white/5">
                            <img v-if="item.images_url || item.image" :src="item.images_url || item.image" :alt="item.name" class="h-full w-full object-cover" />
                            <div v-else class="flex h-full items-center justify-center text-4xl font-bold uppercase text-gray-300 dark:text-gray-600">
                                {{ (item.name || '?').slice(0, 2) }}
                            </div>
                        </div>
                        <div class="flex items-start justify-between gap-2">
                            <div class="min-w-0">
                                <h3 class="truncate font-medium text-gray-900 dark:text-white">{{ item.name }}</h3>
                                <p class="truncate text-xs text-gray-400">{{ item.item_category?.name ?? '—' }} · {{ item.sku_code }}</p>
                            </div>
                            <span class="flex-shrink-0 rounded-full px-2 py-0.5 text-xs font-semibold tabular-nums"
                                :class="isLow(item) ? 'bg-red-50 text-red-600 dark:bg-red-500/10 dark:text-red-400' : 'bg-gray-100 text-gray-600 dark:bg-white/5 dark:text-gray-300'">
                                {{ item.current_stock_quantity ?? 0 }}
                            </span>
                        </div>
                        <div class="mt-3 flex items-center justify-between">
                            <span class="text-lg font-semibold text-gray-900 dark:text-white">{{ price(item) }}</span>
                            <span class="rounded-full px-2 py-0.5 text-xs font-medium"
                                :class="item.is_active ? 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400' : 'bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400'">
                                {{ item.is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        <div class="mt-4 flex gap-2">
                            <Link :href="route('craftable-pro.items.edit', item.id)" v-can="'craftable-pro.items.edit'"
                                class="flex flex-1 items-center justify-center gap-2 rounded-lg bg-primary-500 px-3 py-2 text-sm font-medium text-white hover:bg-primary-600">
                                <PencilSquareIcon class="h-4 w-4" /> Edit
                            </Link>
                            <Link :href="route('craftable-pro.items.edit', item.id)"
                                class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 dark:border-[#2c2f3d] dark:text-gray-300 dark:hover:bg-white/5">
                                <EyeIcon class="h-4 w-4" />
                            </Link>
                        </div>
                    </div>
                </div>

                <p v-if="!items.data.length" class="rounded-xl border border-dashed border-gray-300 py-16 text-center text-sm text-gray-400 dark:border-[#2c2f3d]">
                    No items found
                </p>

                <div v-if="items.last_page > 1" class="mt-6 flex flex-wrap items-center justify-center gap-1">
                    <template v-for="(link, i) in items.links" :key="i">
                        <Link v-if="link.url" :href="link.url"
                            class="flex h-9 min-w-9 items-center justify-center rounded-lg px-3 text-sm"
                            :class="link.active ? 'bg-primary-500 font-medium text-white' : 'border border-gray-200 text-gray-600 hover:bg-gray-50 dark:border-[#2c2f3d] dark:text-gray-300 dark:hover:bg-white/5'"
                            v-html="link.label" />
                        <span v-else class="flex h-9 min-w-9 items-center justify-center px-3 text-sm text-gray-300" v-html="link.label" />
                    </template>
                </div>
            </div>
        </div>
    </PageContent>
</template>

<script setup lang="ts">
import { ref } from "vue";
import { Link, router } from "@inertiajs/vue3";
import { PlusIcon, PencilSquareIcon, EyeIcon, ListBulletIcon, Squares2X2Icon, MagnifyingGlassIcon } from "@heroicons/vue/24/outline";
import { PageHeader, PageContent, Button } from "craftable-pro/Components";

interface Props {
    items: any;
    categories: Array<{ id: number; name: string; items_count: number }>;
    filters: { category: number | null; search: string };
}
const props = defineProps<Props>();

const search = ref(props.filters.search ?? "");

const money = (v: number) =>
    Number(v ?? 0).toLocaleString("en-US", { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + " DH";

const price = (item: any) => {
    const p = item.prices?.[0]?.sale_price;
    return p != null ? money(p) : "—";
};

const isLow = (item: any) =>
    Number(item.current_stock_quantity ?? 0) <= Number(item.low_stock_quantity ?? 0);

const query = () => {
    const q: Record<string, any> = {};
    if (props.filters.category) q.category = props.filters.category;
    if (search.value) q.search = search.value;
    return q;
};

const applyFilters = () => {
    router.get(route("craftable-pro.items.grid"), query(), { preserveScroll: true });
};

const filterBy = (category: number | null) => {
    const q: Record<string, any> = {};
    if (category) q.category = category;
    if (search.value) q.search = search.value;
    router.get(route("craftable-pro.items.grid"), q, { preserveScroll: true });
};
</script>
