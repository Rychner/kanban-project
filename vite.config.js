import react from '@vitejs/plugin-react';
import laravel from 'laravel-vite-plugin';
import { defineConfig } from 'vite';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.jsx',
            ssr: 'resources/js/ssr.jsx',
            refresh: true,
        }),
        react(),
    ],
    server: {
        watch: {
            ignored: [
                '**/vendor/**', // ✅ Abaikan folder vendor
                '**/storage/**', // Opsional: storage juga bisa diabaikan
                '**/node_modules/**' // Biasanya diabaikan otomatis
            ],
        },
    },
});