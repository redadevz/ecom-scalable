import { reactive } from 'vue';
import { useToast } from './toast';

// Singleton client cart store — snappy mini-cart synced with the server via
// JSON, initialised from the SSR-shared cart count on first load.
const state = reactive({
    count: 0,
    lines: [],
    summary: { subtotal: 0, total: 0, count: 0 },
    currency: 'DH',
    open: false,
    loading: false,
    loaded: false,
});

function csrf() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';
}

async function request(url, options = {}) {
    const res = await fetch(url, {
        headers: {
            Accept: 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf(),
            'X-Requested-With': 'XMLHttpRequest',
        },
        ...options,
    });
    if (!res.ok) throw new Error('Cart request failed');
    return res.json();
}

function apply(data) {
    if (data.lines) state.lines = data.lines;
    if (data.summary) state.summary = data.summary;
    if (data.count != null) state.count = data.count;
    state.loaded = true;
}

async function refresh() {
    try { apply(await request('/cart/data')); } catch (e) { /* keep last known */ }
}

async function add(itemId, qty = 1, { openDrawer = true } = {}) {
    state.loading = true;
    try {
        apply(await request('/cart', { method: 'POST', body: JSON.stringify({ item_id: itemId, quantity: qty }) }));
        if (openDrawer) state.open = true;
        useToast().show('Added to cart');
    } catch (e) {
        useToast().show('Could not add item', 'error');
    } finally {
        state.loading = false;
    }
}

async function setQty(itemId, qty) {
    apply(await request(`/cart/${itemId}`, { method: 'PATCH', body: JSON.stringify({ quantity: Math.max(0, qty) }) }));
}

async function remove(itemId) {
    apply(await request(`/cart/${itemId}`, { method: 'DELETE' }));
    useToast().show('Item removed');
}

function open() {
    state.open = true;
    refresh();
}
function close() { state.open = false; }

function init({ count = 0, currency = 'DH' } = {}) {
    state.count = count;
    state.currency = currency;
}

export function useCart() {
    return { state, refresh, add, setQty, remove, open, close, init };
}
