<template>
    <Head title="Home" />

    <!-- HERO -->
    <section class="bg-gray-50">
        <div class="mx-auto max-w-7xl px-4 py-8">
            <div class="relative overflow-hidden rounded-[2rem] bg-gradient-to-br from-brand-900 via-brand-700 to-orange-500">
                <div class="pointer-events-none absolute -right-24 -top-24 h-96 w-96 rounded-full bg-white/10 blur-3xl"></div>
                <div class="pointer-events-none absolute -bottom-32 left-10 h-80 w-80 rounded-full bg-amber-300/20 blur-3xl"></div>
                <div class="relative grid items-center gap-8 p-8 sm:p-12 lg:grid-cols-2 lg:p-16">
                    <div class="text-white">
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-white/15 px-3 py-1 text-xs font-semibold uppercase tracking-wide ring-1 ring-white/25">
                            <SparklesIcon class="h-3.5 w-3.5" /> Fresh daily
                        </span>
                        <h1 class="mt-5 text-4xl font-extrabold leading-[1.1] tracking-tight sm:text-5xl">Your one-stop<br />local market.</h1>
                        <p class="mt-4 max-w-md text-base text-white/85">Groceries, drinks and everyday essentials — fresh stock, fair prices, ready to pick up.</p>
                        <div class="mt-8 flex flex-wrap items-center gap-3">
                            <Link href="/products" class="inline-flex items-center gap-2 rounded-full bg-white px-6 py-3 text-sm font-semibold text-brand-700 shadow-lg transition hover:bg-brand-50">
                                Shop now <ArrowRightIcon class="h-4 w-4" />
                            </Link>
                            <a href="#featured" class="inline-flex items-center rounded-full px-6 py-3 text-sm font-semibold text-white ring-1 ring-white/40 transition hover:bg-white/10">Browse featured</a>
                        </div>
                        <div class="mt-8 flex items-center gap-6 text-sm text-white/80">
                            <span class="flex items-center gap-2"><CheckCircleIcon class="h-5 w-5" /> Fresh stock</span>
                            <span class="flex items-center gap-2"><CheckCircleIcon class="h-5 w-5" /> Fair prices</span>
                            <span class="flex items-center gap-2"><CheckCircleIcon class="h-5 w-5" /> Fast pickup</span>
                        </div>
                    </div>
                    <!-- product collage -->
                    <div class="hidden grid-cols-2 gap-4 lg:grid">
                        <Link v-for="(p, i) in featured.slice(0, 4)" :key="p.id" :href="`/products/${p.id}`"
                            class="group overflow-hidden rounded-2xl bg-white shadow-xl ring-1 ring-white/20 transition hover:-translate-y-1" :class="i % 2 ? 'mt-8' : ''">
                            <div class="aspect-square overflow-hidden bg-gray-50">
                                <img v-if="p.image" :src="p.image" :alt="p.name" class="h-full w-full object-cover transition duration-300 group-hover:scale-105" />
                                <span v-else class="flex h-full w-full items-center justify-center bg-gradient-to-br from-brand-100 to-orange-50 text-3xl font-bold text-brand-300">{{ p.name.charAt(0) }}</span>
                            </div>
                            <div class="flex items-center justify-between px-3 py-2.5">
                                <span class="truncate text-xs font-medium text-gray-700">{{ p.name }}</span>
                                <span class="flex-shrink-0 text-xs font-bold text-brand-600">{{ money(p.price) }}</span>
                            </div>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FEATURE STRIP -->
    <section class="border-y border-gray-100 bg-white">
        <div class="mx-auto grid max-w-7xl grid-cols-2 gap-6 px-4 py-8 lg:grid-cols-4">
            <div v-for="f in features" :key="f.title" class="flex items-center gap-3">
                <span class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-full bg-brand-50 text-brand-500"><component :is="f.icon" class="h-6 w-6" /></span>
                <div>
                    <p class="text-sm font-semibold text-gray-900">{{ f.title }}</p>
                    <p class="text-xs text-gray-500">{{ f.text }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FEATURED -->
    <section id="featured" class="mx-auto max-w-7xl px-4 py-12">
        <SectionHeading title="Featured Products" subtitle="Handpicked from our shelves" />
        <div v-if="featured.length" class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5">
            <ProductCard v-for="p in featured.slice(0, 5)" :key="p.id" :product="p" :currency="currency" />
        </div>
    </section>

    <!-- PROMO BANNERS -->
    <section class="mx-auto max-w-7xl px-4 py-4">
        <div class="grid gap-4 md:grid-cols-3">
            <div class="flex items-center gap-4 rounded-2xl bg-amber-50 p-6">
                <div>
                    <h3 class="font-bold text-gray-900">Free Shipping</h3>
                    <p class="mt-1 text-sm text-gray-500">On orders over {{ currency }} 500.</p>
                </div>
                <TruckIcon class="ml-auto h-12 w-12 text-amber-400" />
            </div>
            <div class="flex items-center gap-4 rounded-2xl bg-gray-900 p-6 text-white">
                <div>
                    <h3 class="font-bold">Clearance Sale</h3>
                    <p class="mt-1 text-sm text-white/70">Up to 30% off.</p>
                </div>
                <BoltIcon class="ml-auto h-12 w-12 text-brand-400" />
            </div>
            <div class="flex items-center gap-4 rounded-2xl bg-brand-50 p-6">
                <div>
                    <h3 class="font-bold text-gray-900">First Order −20%</h3>
                    <p class="mt-1 text-sm text-gray-500">Use code <b class="text-brand-600">FIRST20</b></p>
                </div>
                <GiftIcon class="ml-auto h-12 w-12 text-brand-400" />
            </div>
        </div>
    </section>

    <!-- SHOP BY CATEGORY -->
    <section class="mx-auto max-w-7xl px-4 py-12">
        <SectionHeading title="Shop by Category" subtitle="Find what you need, faster" />
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">
            <Link v-for="c in categories" :key="c.id" :href="`/products?category=${c.id}`"
                class="group flex flex-col items-center gap-3 rounded-2xl border border-gray-100 bg-white p-6 text-center transition hover:border-brand-200 hover:shadow-lg">
                <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-brand-50 to-orange-50 text-xl font-bold text-brand-500 transition group-hover:from-brand-500 group-hover:to-orange-500 group-hover:text-white">
                    {{ c.name.charAt(0) }}
                </span>
                <div>
                    <p class="text-sm font-semibold text-gray-800 group-hover:text-brand-600">{{ c.name }}</p>
                    <p v-if="c.items_count != null" class="text-xs text-gray-400">{{ c.items_count }} items</p>
                </div>
            </Link>
        </div>
    </section>

    <!-- NEW PRODUCTS -->
    <section class="mx-auto max-w-7xl px-4 py-12">
        <SectionHeading title="New Products" subtitle="Just arrived in store" />
        <div v-if="newProducts.length" class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
            <ProductCard v-for="p in newProducts" :key="p.id" :product="p" :currency="currency" />
        </div>
    </section>

    <!-- FAQ -->
    <section class="bg-gray-50">
        <div class="mx-auto max-w-3xl px-4 py-14">
            <h2 class="text-center text-2xl font-bold tracking-tight text-gray-900">Frequently Asked Questions</h2>
            <div class="mt-8 space-y-3">
                <div v-for="(f, i) in faqs" :key="i" class="overflow-hidden rounded-2xl border border-gray-100 bg-white">
                    <button type="button" @click="openFaq = openFaq === i ? -1 : i" class="flex w-full items-center justify-between px-5 py-4 text-left text-sm font-semibold text-gray-900">
                        {{ f.q }}
                        <ChevronDownIcon class="h-5 w-5 text-gray-400 transition" :class="openFaq === i ? 'rotate-180' : ''" />
                    </button>
                    <div v-if="openFaq === i" class="px-5 pb-4 text-sm leading-relaxed text-gray-500">{{ f.a }}</div>
                </div>
            </div>
        </div>
    </section>

    <!-- NEWSLETTER -->
    <section class="mx-auto max-w-7xl px-4 py-14">
        <div class="overflow-hidden rounded-3xl bg-gradient-to-br from-brand-900 via-brand-700 to-orange-500 px-8 py-12 text-center text-white sm:px-16">
            <h2 class="text-2xl font-bold sm:text-3xl">Get our updates</h2>
            <p class="mx-auto mt-2 max-w-md text-sm text-white/85">Fresh deals and new arrivals, straight to your inbox.</p>
            <form class="mx-auto mt-6 flex max-w-md overflow-hidden rounded-full bg-white p-1.5 shadow-lg" @submit.prevent>
                <input type="email" placeholder="Enter your email" class="w-full border-0 bg-transparent px-4 text-sm text-gray-900 focus:ring-0" />
                <button type="submit" class="flex-shrink-0 rounded-full bg-brand-500 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-600">Subscribe</button>
            </form>
        </div>
    </section>
</template>

<script setup>
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import { ArrowRightIcon, TruckIcon, ShieldCheckIcon, ArrowPathIcon, ChatBubbleLeftRightIcon, ChevronDownIcon, BoltIcon, GiftIcon, SparklesIcon, CheckCircleIcon } from '@heroicons/vue/24/outline';
import ProductCard from '@/Components/ProductCard.vue';
import SectionHeading from '@/Components/SectionHeading.vue';

const props = defineProps({
    featured: { type: Array, default: () => [] },
    newProducts: { type: Array, default: () => [] },
    categories: { type: Array, default: () => [] },
    currency: { type: String, default: 'DH' },
});

const openFaq = ref(0);
const money = (v) => Number(v ?? 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' ' + props.currency;

const features = [
    { icon: ChatBubbleLeftRightIcon, title: 'Responsive', text: 'Support available 24/7' },
    { icon: ShieldCheckIcon, title: 'Secure', text: 'Trusted since 2017' },
    { icon: TruckIcon, title: 'Fast Shipping', text: 'Reliable worldwide' },
    { icon: ArrowPathIcon, title: 'Easy Returns', text: 'Hassle-free policy' },
];
const faqs = [
    { q: 'What payment methods do you accept?', a: 'Cash, card and store credit at the counter; online payments are coming soon.' },
    { q: 'How long does pickup take?', a: 'Orders are usually ready within 30 minutes during opening hours.' },
    { q: 'Do you offer delivery?', a: 'In-store pickup today; local delivery is on the roadmap.' },
    { q: 'Can I track my order?', a: 'Yes — once accounts are enabled you can view order status in your profile.' },
];
</script>
