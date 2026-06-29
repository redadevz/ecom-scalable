<template>
    <PageHeader :title="$t('craftable-pro', 'Dashboard')" />

    <PageContent>
        <!-- Hero -->
        <div
            class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-primary-600 via-primary-600 to-teal-500 px-8 py-10 text-white shadow-lg"
        >
            <div class="relative z-10 max-w-2xl">
                <p class="text-sm font-medium uppercase tracking-wider text-white/70">
                    {{ greeting }}
                </p>
                <h2 class="mt-1 text-3xl font-semibold tracking-tight">
                    Welcome back 👋
                </h2>
                <p class="mt-2 text-base text-white/80">
                    Manage your catalog, orders, stock and customers — all from one place.
                    Pick up where you left off below.
                </p>
            </div>
            <!-- decorative -->
            <div class="pointer-events-none absolute -right-16 -top-16 h-64 w-64 rounded-full bg-white/10 blur-2xl" />
            <div class="pointer-events-none absolute -bottom-24 right-24 h-72 w-72 rounded-full bg-white/10 blur-3xl" />
        </div>

        <!-- Quick actions -->
        <h3 class="mb-4 mt-8 text-sm font-semibold uppercase tracking-wider text-gray-400">
            Quick access
        </h3>
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
            <Link
                v-for="card in cards"
                :key="card.label"
                :href="card.href"
                class="group relative flex items-start gap-4 overflow-hidden rounded-2xl border border-gray-100 bg-white p-5 shadow-sm transition-all hover:-translate-y-0.5 hover:shadow-lg dark:border-gray-800 dark:bg-gray-900"
            >
                <span
                    class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-primary-50 text-primary-600 transition-colors group-hover:bg-primary-600 group-hover:text-white dark:bg-primary-500/10"
                >
                    <component :is="card.icon" class="h-6 w-6" />
                </span>
                <div class="min-w-0">
                    <p class="font-semibold text-gray-900 dark:text-white">
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
