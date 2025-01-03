import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
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
    build: {
        outDir: 'public', // Output directly to public directory
        emptyOutDir: true, // Ensure the folder is cleared before each build
    },
    server: {
        hmr: {
            host: 'localhost',
        },
        watch: {
            usePolling: true,
        },
    },
});
