import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vuePlugin from '@vitejs/plugin-vue';
import path from 'path';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        tailwindcss(),
        vuePlugin(),
        laravel({
            input: ['resources/css/app.css', 'resources/ts/app.ts'],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/ts'),
        },
    },
});
