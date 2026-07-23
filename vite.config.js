import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import tailwindcss from 'tailwindcss';
import { resolve } from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            buildDirectory: 'build-shop',
            // Own hot file: Laravel defaults both apps to `public/hot`, so running
            // the admin dev server used to hijack the storefront's assets (it would
            // fetch them from the admin's Vite server, which doesn't serve them, and
            // the store rendered unstyled). Separate files = the two never collide.
            hotFile: 'public/hot-shop',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    css: {
        postcss: {
            plugins: [
                tailwindcss({ config: './tailwind.config.js' }),
            ],
        },
    },
    resolve: {
        alias: {
            '@': resolve(__dirname, './resources/js'),
        },
    },
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
