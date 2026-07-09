import './bootstrap';
import { createApp, h } from 'vue';
import { createInertiaApp, Link } from '@inertiajs/vue3';
import ShopLayout from './Layouts/ShopLayout.vue';

const appName = import.meta.env.VITE_APP_NAME || 'Shop';

createInertiaApp({
    title: (title) => (title ? `${title} — ${appName}` : appName),
    progress: { color: '#f97316' },
    resolve: (name) => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true });
        const page = pages[`./Pages/${name}.vue`];
        if (!page) {
            throw new Error(`Storefront page not found: ${name}`);
        }
        // Persistent shop layout unless a page opts out.
        page.default.layout = page.default.layout === undefined ? ShopLayout : page.default.layout;
        return page;
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .component('Link', Link)
            .mount(el);
    },
});
