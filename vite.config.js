import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/login.css',
                'resources/css/student.css',
                'resources/js/app.js',
                'resources/js/admin.js',
                'resources/js/student.js',
            ],
            refresh: true,
        }),
    ],
});
