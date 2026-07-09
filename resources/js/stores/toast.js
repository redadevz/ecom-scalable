import { reactive } from 'vue';

let seq = 0;
const state = reactive({ items: [] });

function remove(id) {
    const i = state.items.findIndex((t) => t.id === id);
    if (i > -1) state.items.splice(i, 1);
}

function show(message, type = 'success', timeout = 2600) {
    const id = ++seq;
    state.items.push({ id, message, type });
    if (timeout) setTimeout(() => remove(id), timeout);
    return id;
}

export function useToast() {
    return { state, show, remove };
}
