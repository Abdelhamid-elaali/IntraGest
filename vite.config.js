import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/search.js',
                // 'resources/js/candidates/criteria-handler.js',
                'resources/js/candidates/dynamic-criteria.js'
            ],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '@': '/resources/js',
            '~': '/resources/css',
            'jquery': 'jquery/dist/jquery.min.js',
            'select2': 'select2/dist/js/select2.min.js',
        },
    },
    optimizeDeps: {
        include: ['jquery', 'select2']
    }
});
