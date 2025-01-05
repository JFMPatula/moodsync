import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    base: '/build/', // Ensure assets are served from the /build/ folder
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'], // Main input files
            refresh: true, // Enable automatic browser refresh
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
        hmr: {
            host: 'moodsync-eosin.vercel.app', // Host for Vite HMR
            protocol: 'wss', // WebSocket Secure protocol for HMR
        },
        watch: {
            usePolling: true, // Fix for file system watchers on some systems
        },
    },
    build: {
        outDir: 'public/build', // Ensure the build output is in the correct folder
        assetsDir: '', // Keep all assets flat in the /build/ folder
        manifest: true, // Generate a manifest for Laravel's asset helper
        rollupOptions: {
            input: ['resources/css/app.css', 'resources/js/app.js'], // Entry points
        },
    },
});
