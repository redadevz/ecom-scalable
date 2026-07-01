<template>
    <PageHeader :title="$t('craftable-pro', 'Dashboard')" />

    <PageContent>
        <!-- Greeting -->
        <div class="mb-6">
            <p class="text-sm text-gray-500">{{ greeting }}</p>
            <h2 class="mt-0.5 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">
                Welcome back
            </h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Manage your catalog, orders, stock and customers — all from one place.
            </p>
        </div>

        <!-- Quick actions -->
        <h3 class="mb-3 text-xs font-semibold uppercase tracking-wide text-gray-400">
            Quick access
        </h3>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <Link
                v-for="card in cards"
                :key="card.label"
                :href="card.href"
                class="group flex items-start gap-4 rounded-xl border border-gray-200 bg-white p-5 transition-colors hover:border-gray-300 hover:bg-gray-50/50 dark:border-gray-800 dark:bg-gray-900 dark:hover:bg-gray-800/50"
            >
                <span
                    class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-500 transition-colors group-hover:bg-emerald-50 group-hover:text-emerald-600 dark:bg-gray-800"
                >
                    <component :is="card.icon" class="h-5 w-5" />
                </span>
                <div class="min-w-0">
                    <p class="font-medium text-gray-900 dark:text-white">
                        {{ card.label }}
                    </p>
                    <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">
                        {{ card.description }}
                    </p>
                </div>
                <ArrowUpRightIcon
                    class="absolute right-4 top-4 h-4 w-4 text-gray-300 transition-all group-hover:right-3.5 group-hover:text-primary-500 dark:text-gray-600"
                />
            </Link>
        </div>
    </PageContent>
</template>

<script setup lang="ts">
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";
import { PageHeader, PageContent } from "craftable-pro/Components";
import {
    CubeIcon,
    ShoppingCartIcon,
    UsersIcon,
    TagIcon,
    TruckIcon,
    ArchiveBoxIcon,
    ArrowUpRightIcon,
} from "@heroicons/vue/24/outline";

const greeting = computed(() => {
    const h = new Date().getHours();
    if (h < 12) return "Good morning";
    if (h < 18) return "Good afternoon";
    return "Good evening";
});

const cards = [
    {
        label: "Items",
        description: "Manage your product catalog",
        icon: CubeIcon,
        href: route("craftable-pro.items.index"),
    },
    {
        label: "Orders",
        description: "View and process sales orders",
        icon: ShoppingCartIcon,
        href: route("craftable-pro.order-headers.index"),
    },
    {
        label: "Customers",
        description: "Customer records & loyalty",
        icon: UsersIcon,
        href: route("craftable-pro.customers.index"),
    },
    {
        label: "Prices",
        description: "Pricing, tax & markups",
        icon: TagIcon,
        href: route("craftable-pro.prices.index"),
    },
    {
        label: "Purchases",
        description: "Restock from suppliers",
        icon: TruckIcon,
        href: route("craftable-pro.purchases.index"),
    },
    {
        label: "Stock history",
        description: "Audit every stock movement",
        icon: ArchiveBoxIcon,
        href: route("craftable-pro.stock-histories.index"),
    },
];
</script>
