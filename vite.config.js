import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    base: process.env.NODE_ENV === 'production' ? '/' : '/', // Adjust base URL if needed
    build: {
        outDir: 'public/build',  // Output to the public/build directory
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
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
    server: {
        hmr: false, // Disable HMR in production
    },
});
