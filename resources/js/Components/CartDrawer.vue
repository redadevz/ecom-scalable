<template>
    <Transition enter-active-class="transition-opacity duration-200" enter-from-class="opacity-0"
        leave-active-class="transition-opacity duration-200" leave-to-class="opacity-0">
        <div v-if="cart.state.open" class="fixed inset-0 z-[60]" role="dialog" aria-modal="true">
            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="cart.close()"></div>

            <Transition appear enter-active-class="transition-transform duration-300 ease-out" enter-from-class="translate-x-full"
                leave-active-class="transition-transform duration-200 ease-in" leave-to-class="translate-x-full">
                <aside v-if="cart.state.open" class="absolute right-0 top-0 flex h-full w-full max-w-md flex-col bg-white shadow-2xl">
                    <!-- header -->
                    <header class="flex items-center justify-between border-b border-gray-100 px-5 py-4">
                        <h2 class="flex items-center gap-2 text-lg font-semibold text-gray-900">
                            <ShoppingBagIcon class="h-5 w-5 text-brand-500" /> Your cart
                            <span v-if="s.count" class="rounded-full bg-brand-50 px-2 py-0.5 text-xs font-bold text-brand-600">{{ s.count }}</span>
                        </h2>
                        <button type="button" @click="cart.close()" class="rounded-full p-1.5 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600"><XMarkIcon class="h-5 w-5" /></button>
                    </header>

                    <!-- lines -->
                    <div class="flex-1 overflow-y-auto px-5">
                        <div v-if="s.lines.length" class="divide-y divide-gray-100">
                            <div v-for="line in s.lines" :key="line.item_id" class="flex gap-3 py-4">
                                <Link :href="`/products/${line.item_id}`" @click="cart.close()" class="h-20 w-20 flex-shrink-0 overflow-hidden rounded-xl bg-gray-50">
                                    <img v-if="line.image" :src="line.image" :alt="line.name" class="h-full w-full object-cover" />
                                    <span v-else class="flex h-full w-full items-center justify-center bg-gradient-to-br from-brand-100 to-orange-50 text-xl font-bold text-brand-300">{{ line.name.charAt(0) }}</span>
                                </Link>
                                <div class="flex min-w-0 flex-1 flex-col">
                                    <div class="flex items-start justify-between gap-2">
                                        <Link :href="`/products/${line.item_id}`" @click="cart.close()" class="line-clamp-2 text-sm font-semibold text-gray-900 hover:text-brand-600">{{ line.name }}</Link>
                                        <button type="button" @click="cart.remove(line.item_id)" class="flex-shrink-0 text-gray-300 hover:text-red-500"><TrashIcon class="h-4 w-4" /></button>
                                    </div>
                                    <p class="text-xs text-brand-600">{{ money(line.unit_price) }}</p>
                                    <div class="mt-auto flex items-center justify-between pt-2">
                                        <div class="flex items-center rounded-full border border-gray-200">
                                            <button type="button" @click="cart.setQty(line.item_id, line.quantity - 1)" class="flex h-7 w-7 items-center justify-center text-gray-500 hover:text-brand-600"><MinusIcon class="h-3.5 w-3.5" /></button>
                                            <span class="w-7 text-center text-sm font-semibold tabular-nums">{{ line.quantity }}</span>
                                            <button type="button" @click="cart.setQty(line.item_id, line.quantity + 1)" class="flex h-7 w-7 items-center justify-center text-gray-500 hover:text-brand-600"><PlusIcon class="h-3.5 w-3.5" /></button>
                                        </div>
                                        <span class="text-sm font-bold text-gray-900">{{ money(line.line_total) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- empty -->
                        <div v-else class="flex flex-col items-center justify-center py-20 text-center">
                            <ShoppingCartIcon class="h-12 w-12 text-gray-200" />
                            <p class="mt-3 text-sm text-gray-500">Your cart is empty.</p>
                            <button type="button" @click="cart.close()" class="mt-4 rounded-full bg-brand-500 px-5 py-2 text-sm font-semibold text-white transition hover:bg-brand-600">Browse products</button>
                        </div>
                    </div>

                    <!-- footer -->
                    <footer v-if="s.lines.length" class="border-t border-gray-100 p-5">
                        <div class="flex items-baseline justify-between">
                            <span class="text-sm text-gray-500">Subtotal</span>
                            <span class="text-xl font-bold text-gray-900">{{ money(s.summary.total) }}</span>
                        </div>
                        <p class="mt-1 text-xs text-gray-400">Tax included · pay on pickup</p>
                        <div class="mt-4 flex gap-3">
                            <Link href="/cart" @click="cart.close()" class="flex-1 rounded-full border border-gray-200 py-3 text-center text-sm font-semibold text-gray-700 transition hover:bg-gray-50">View cart</Link>
                            <Link href="/checkout" @click="cart.close()" class="flex-1 rounded-full bg-brand-500 py-3 text-center text-sm font-semibold text-white shadow-lg shadow-brand-500/25 transition hover:bg-brand-600">Checkout</Link>
                        </div>
                    </footer>
                </aside>
            </Transition>
        </div>
    </Transition>
</template>

<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { ShoppingBagIcon, ShoppingCartIcon, XMarkIcon, TrashIcon, PlusIcon, MinusIcon } from '@heroicons/vue/24/outline';
import { useCart } from '@/stores/cart';

const cart = useCart();
const s = cart.state;
const money = (v) => Number(v ?? 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' ' + s.currency;
</script>
