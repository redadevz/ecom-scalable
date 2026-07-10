<template>
    <div class="flex min-h-screen flex-col bg-white">
        <!-- utility bar -->
        <div class="hidden border-b border-gray-100 bg-white text-xs text-gray-500 lg:block">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-2">
                <div class="flex items-center gap-5">
                    <span class="flex items-center gap-1.5"><MapPinIcon class="h-4 w-4 text-brand-500" /> 123 Main Street, Anytown</span>
                    <span class="flex items-center gap-1.5"><PhoneIcon class="h-4 w-4 text-brand-500" /> +1 (555) 123-4567</span>
                </div>
                <div class="flex items-center gap-4">
                    <span>{{ currency }}</span>
                    <span class="text-gray-300">|</span>
                    <span>English</span>
                    <span class="text-gray-300">|</span>
                    <div class="flex items-center gap-3 text-gray-400">
                        <a href="#" class="hover:text-brand-500"><svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12a10 10 0 10-11.6 9.9v-7H7.9V12h2.5V9.8c0-2.5 1.5-3.9 3.8-3.9 1.1 0 2.2.2 2.2.2v2.5h-1.3c-1.2 0-1.6.8-1.6 1.6V12h2.8l-.4 2.9h-2.4v7A10 10 0 0022 12z"/></svg></a>
                        <a href="#" class="hover:text-brand-500"><svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.9 1.2h3.7l-8 9.2 9.4 12.4h-7.4l-5.8-7.6-6.6 7.6H.5l8.6-9.8L0 1.2h7.6l5.2 6.9zm-1.3 19.5h2L6.5 3.3H4.4z"/></svg></a>
                        <a href="#" class="hover:text-brand-500"><svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.2c3.2 0 3.6 0 4.9.1 3.3.1 4.8 1.7 4.9 4.9.1 1.3.1 1.6.1 4.8s0 3.6-.1 4.8c-.1 3.2-1.7 4.8-4.9 4.9-1.3.1-1.6.1-4.9.1s-3.6 0-4.8-.1c-3.3-.1-4.8-1.7-4.9-4.9-.1-1.3-.1-1.6-.1-4.8s0-3.6.1-4.8C2.3 4 3.9 2.4 7.1 2.3 8.4 2.2 8.8 2.2 12 2.2zm0 3.4a6.4 6.4 0 100 12.8 6.4 6.4 0 000-12.8zm0 10.5a4.1 4.1 0 110-8.2 4.1 4.1 0 010 8.2zm6.6-10.8a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/></svg></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- main header -->
        <header class="sticky top-0 z-40 border-b border-gray-100 bg-white/95 backdrop-blur">
            <div class="mx-auto flex max-w-7xl items-center gap-4 px-4 py-4">
                <button type="button" @click="mobileOpen = true" class="-ml-1 rounded-lg p-2 text-gray-600 transition hover:bg-gray-100 lg:hidden" aria-label="Menu">
                    <Bars3Icon class="h-6 w-6" />
                </button>
                <Link href="/" class="flex items-center gap-2 text-2xl font-extrabold tracking-tight text-gray-900">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-brand-500 to-orange-600 text-white shadow-sm">
                        <ShoppingBagIcon class="h-5 w-5" />
                    </span>
                    <span class="hidden sm:inline">{{ appName }}<span class="text-brand-500">.</span></span>
                </Link>

                <!-- search -->
                <form class="relative mx-auto hidden w-full max-w-2xl md:block" @submit.prevent="search">
                    <input v-model="q" type="text" placeholder="Search products…"
                        class="w-full rounded-full border border-gray-200 bg-gray-50 py-2.5 pl-5 pr-24 text-sm text-gray-900 transition focus:border-brand-500 focus:bg-white focus:ring-2 focus:ring-brand-500/20" />
                    <button type="submit" class="absolute right-1.5 top-1/2 flex -translate-y-1/2 items-center gap-1.5 rounded-full bg-brand-500 px-4 py-1.5 text-sm font-semibold text-white transition hover:bg-brand-600">
                        <MagnifyingGlassIcon class="h-4 w-4" /> <span class="hidden lg:inline">Search</span>
                    </button>
                </form>

                <div class="ml-auto flex items-center gap-2 sm:gap-5">
                    <button type="button" @click="cart.open()" class="flex items-center gap-2 text-gray-700 transition hover:text-brand-600">
                        <span class="relative">
                            <ShoppingCartIcon class="h-6 w-6" />
                            <span v-if="cart.state.count" class="absolute -right-1.5 -top-1.5 flex h-4 min-w-[1rem] items-center justify-center rounded-full bg-brand-500 px-1 text-[10px] font-bold text-white">{{ cart.state.count }}</span>
                        </span>
                        <span class="hidden text-left sm:block">
                            <span class="block text-[11px] text-gray-400">Cart</span>
                            <span class="block text-sm font-semibold">{{ cart.state.count }} items</span>
                        </span>
                    </button>
                    <div v-if="customer" class="relative" @mouseenter="acctOpen = true" @mouseleave="acctOpen = false">
                        <Link href="/account" class="flex items-center gap-2 text-gray-700 transition hover:text-brand-600">
                            <UserCircleIcon class="h-6 w-6" />
                            <span class="hidden text-left sm:block">
                                <span class="block text-[11px] text-gray-400">Account</span>
                                <span class="block max-w-[7rem] truncate text-sm font-semibold">{{ customer.first_name }}</span>
                            </span>
                        </Link>
                        <transition enter-active-class="transition duration-100" enter-from-class="opacity-0 -translate-y-1">
                            <div v-if="acctOpen" class="absolute right-0 top-full z-50 w-44 overflow-hidden rounded-xl bg-white py-1 shadow-xl ring-1 ring-black/5">
                                <Link href="/account" class="block px-4 py-2.5 text-sm text-gray-700 transition hover:bg-brand-50 hover:text-brand-600">My account</Link>
                                <button type="button" @click="logout" class="block w-full px-4 py-2.5 text-left text-sm text-red-600 transition hover:bg-red-50">Sign out</button>
                            </div>
                        </transition>
                    </div>
                    <Link v-else href="/account/login" class="flex items-center gap-2 text-gray-700 transition hover:text-brand-600">
                        <UserCircleIcon class="h-6 w-6" />
                        <span class="hidden text-left sm:block">
                            <span class="block text-[11px] text-gray-400">Account</span>
                            <span class="block text-sm font-semibold">Sign in</span>
                        </span>
                    </Link>
                </div>
            </div>

            <!-- category nav -->
            <div class="bg-brand-500 text-white">
                <div class="mx-auto flex max-w-7xl items-center px-4">
                    <div class="relative" @mouseenter="catOpen = true" @mouseleave="catOpen = false">
                        <button type="button" class="flex items-center gap-2 bg-brand-600 px-4 py-3 text-sm font-semibold">
                            <Bars3Icon class="h-5 w-5" /> All Categories <ChevronDownIcon class="h-4 w-4" />
                        </button>
                        <transition enter-active-class="transition duration-100" enter-from-class="opacity-0 -translate-y-1">
                            <div v-if="catOpen" class="absolute left-0 top-full z-50 w-60 overflow-hidden rounded-b-xl bg-white py-1 text-gray-700 shadow-xl ring-1 ring-black/5">
                                <Link v-for="c in categories" :key="c.id" :href="`/products?category=${c.id}`"
                                    class="flex items-center justify-between px-4 py-2.5 text-sm transition hover:bg-brand-50 hover:text-brand-600">
                                    {{ c.name }}
                                    <ChevronRightIcon class="h-4 w-4 text-gray-300" />
                                </Link>
                            </div>
                        </transition>
                    </div>

                    <nav class="hidden items-center gap-1 px-2 text-sm font-medium md:flex">
                        <Link href="/products" class="rounded-md px-3 py-3 transition hover:bg-white/10">Products</Link>
                        <a href="#featured" class="rounded-md px-3 py-3 transition hover:bg-white/10">Blog</a>
                        <a href="#footer" class="rounded-md px-3 py-3 transition hover:bg-white/10">Contact</a>
                    </nav>

                    <div class="ml-auto hidden items-center gap-4 text-sm font-semibold lg:flex">
                        <Link href="/products" class="py-3 transition hover:text-white/80">Best Seller</Link>
                        <Link href="/products" class="py-3 transition hover:text-white/80">New Arrival</Link>
                    </div>
                </div>
            </div>
        </header>

        <!-- page -->
        <main class="flex-1">
            <slot />
        </main>

        <!-- footer -->
        <footer id="footer" class="mt-20 border-t border-gray-100 bg-gray-50">
            <div class="mx-auto grid max-w-7xl grid-cols-2 gap-8 px-4 py-14 md:grid-cols-4 lg:grid-cols-5">
                <div class="col-span-2 lg:col-span-1">
                    <div class="flex items-center gap-2 text-xl font-extrabold text-gray-900">
                        <ShoppingBagIcon class="h-6 w-6 text-brand-500" /> {{ appName }}<span class="text-brand-500">.</span>
                    </div>
                    <ul class="mt-4 space-y-2.5 text-sm text-gray-500">
                        <li class="flex items-center gap-2"><PhoneIcon class="h-4 w-4 text-brand-500" /> +1 (555) 123-4567</li>
                        <li class="flex items-center gap-2"><EnvelopeIcon class="h-4 w-4 text-brand-500" /> hello@{{ appName.toLowerCase() }}.com</li>
                        <li class="flex items-center gap-2"><MapPinIcon class="h-4 w-4 text-brand-500" /> 123 Main Street, Anytown</li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-gray-900">Links</h4>
                    <ul class="mt-4 space-y-2.5 text-sm text-gray-500">
                        <li><Link href="/products" class="hover:text-brand-600">Products List</Link></li>
                        <li><a href="#" class="hover:text-brand-600">Order Tracking</a></li>
                        <li><a href="#" class="hover:text-brand-600">Shopping Cart</a></li>
                        <li><a href="#" class="hover:text-brand-600">Wishlist</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-gray-900">Support</h4>
                    <ul class="mt-4 space-y-2.5 text-sm text-gray-500">
                        <li><a href="#" class="hover:text-brand-600">About Us</a></li>
                        <li><a href="#" class="hover:text-brand-600">Return Policy</a></li>
                        <li><a href="#" class="hover:text-brand-600">Help Centre</a></li>
                        <li><a href="#" class="hover:text-brand-600">Careers</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-gray-900">Categories</h4>
                    <ul class="mt-4 space-y-2.5 text-sm text-gray-500">
                        <li v-for="c in categories.slice(0, 5)" :key="c.id"><Link :href="`/products?category=${c.id}`" class="hover:text-brand-600">{{ c.name }}</Link></li>
                    </ul>
                </div>
                <div class="col-span-2 lg:col-span-1">
                    <h4 class="text-sm font-semibold text-gray-900">Get updates</h4>
                    <form class="mt-4 flex overflow-hidden rounded-full border border-gray-200 bg-white" @submit.prevent>
                        <input type="email" placeholder="Your email" class="w-full border-0 bg-transparent px-4 py-2 text-sm focus:ring-0" />
                        <button type="submit" class="bg-brand-500 px-4 text-sm font-semibold text-white transition hover:bg-brand-600">Join</button>
                    </form>
                    <div class="mt-4 flex flex-wrap gap-2">
                        <span v-for="pm in ['Visa', 'Mastercard', 'Cash', 'Card']" :key="pm" class="rounded-md border border-gray-200 bg-white px-2.5 py-1 text-xs font-medium text-gray-500">{{ pm }}</span>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-200">
                <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-2 px-4 py-5 text-xs text-gray-500 sm:flex-row">
                    <p>© {{ year }} {{ appName }}. All rights reserved.</p>
                    <div class="flex gap-4">
                        <a href="#" class="hover:text-brand-600">Privacy Policy</a>
                        <a href="#" class="hover:text-brand-600">Terms &amp; Conditions</a>
                        <a href="/admin" class="hover:text-brand-600">Staff login</a>
                    </div>
                </div>
            </div>
        </footer>

        <!-- mobile menu -->
        <Transition enter-active-class="transition-opacity duration-200" enter-from-class="opacity-0" leave-active-class="transition-opacity duration-200" leave-to-class="opacity-0">
            <div v-if="mobileOpen" class="fixed inset-0 z-[60] lg:hidden">
                <div class="absolute inset-0 bg-black/40" @click="mobileOpen = false"></div>
                <Transition appear enter-active-class="transition-transform duration-300 ease-out" enter-from-class="-translate-x-full" leave-active-class="transition-transform duration-200 ease-in" leave-to-class="-translate-x-full">
                    <aside v-if="mobileOpen" class="absolute left-0 top-0 flex h-full w-80 max-w-[85%] flex-col bg-white shadow-2xl">
                        <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4">
                            <span class="text-lg font-extrabold text-gray-900">{{ appName }}<span class="text-brand-500">.</span></span>
                            <button type="button" @click="mobileOpen = false" class="rounded-full p-1.5 text-gray-400 hover:bg-gray-100"><XMarkIcon class="h-5 w-5" /></button>
                        </div>
                        <form class="border-b border-gray-100 p-4" @submit.prevent="search">
                            <div class="relative">
                                <MagnifyingGlassIcon class="pointer-events-none absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400" />
                                <input v-model="q" type="text" placeholder="Search products…" class="w-full rounded-full border border-gray-200 bg-gray-50 py-2.5 pl-10 pr-4 text-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20" />
                            </div>
                        </form>
                        <nav class="flex-1 overflow-y-auto p-2">
                            <Link href="/products" @click="mobileOpen = false" class="block rounded-lg px-3 py-2.5 text-sm font-semibold text-gray-900 hover:bg-brand-50">All products</Link>
                            <p class="px-3 pb-1 pt-4 text-xs font-semibold uppercase tracking-wide text-gray-400">Categories</p>
                            <Link v-for="c in categories" :key="c.id" :href="`/products?category=${c.id}`" @click="mobileOpen = false" class="flex items-center justify-between rounded-lg px-3 py-2.5 text-sm text-gray-600 hover:bg-brand-50 hover:text-brand-600">
                                {{ c.name }} <ChevronRightIcon class="h-4 w-4 text-gray-300" />
                            </Link>
                        </nav>
                        <div class="border-t border-gray-100">
                            <Link v-if="customer" href="/account" @click="mobileOpen = false" class="block px-5 py-3.5 text-sm font-medium text-gray-700 hover:text-brand-600">My account</Link>
                            <Link v-else href="/account/login" @click="mobileOpen = false" class="block px-5 py-3.5 text-sm font-medium text-gray-700 hover:text-brand-600">Sign in</Link>
                            <a href="/admin" class="block px-5 py-3.5 text-sm font-medium text-gray-500 hover:text-brand-600">Staff login →</a>
                        </div>
                    </aside>
                </Transition>
            </div>
        </Transition>

        <CartDrawer />
        <ToastContainer />
    </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import { router, usePage, Link } from '@inertiajs/vue3';
import { ShoppingBagIcon, ShoppingCartIcon, MagnifyingGlassIcon, UserCircleIcon, MapPinIcon, PhoneIcon, EnvelopeIcon, Bars3Icon, ChevronDownIcon, ChevronRightIcon, XMarkIcon } from '@heroicons/vue/24/outline';
import { useCart } from '@/stores/cart';
import { useToast } from '@/stores/toast';
import CartDrawer from '@/Components/CartDrawer.vue';
import ToastContainer from '@/Components/ToastContainer.vue';

const page = usePage();
const appName = computed(() => page.props.appName ?? 'Shop');
const categories = computed(() => page.props.categories ?? []);
const currency = computed(() => page.props.currency ?? 'USD');
const customer = computed(() => page.props.auth?.customer ?? null);
const year = new Date().getFullYear();
const catOpen = ref(false);
const acctOpen = ref(false);
const mobileOpen = ref(false);

function logout() {
    router.post('/account/logout');
}

const cart = useCart();
onMounted(() => cart.init({ count: page.props.cartCount ?? 0, currency: page.props.currency ?? 'DH' }));
// keep the badge in sync after any Inertia navigation that changed the session cart
watch(() => page.props.cartCount, (c) => { if (c != null) cart.state.count = c; });
// surface server flash messages (e.g. checkout) as toasts
watch(() => page.props.flash?.message, (m) => m && useToast().show(m));
watch(() => page.props.flash?.error, (m) => m && useToast().show(m, 'error'));

const q = ref(new URLSearchParams(window.location.search).get('q') ?? '');
function search() {
    mobileOpen.value = false;
    router.get('/products', { q: q.value || undefined }, { preserveState: false });
}
</script>
