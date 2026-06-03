import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { globSync } from 'glob';


export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css',
                'resources/js/app.js',
                ...globSync('resources/plugins/js/**/*.js'),
                ...globSync('resources/plugins/css/**/*.css'),
                ...globSync('resources/custom/js/**/*.js')
            ],
            refresh: true,
        })
    ],
    build: {
        commonjsOptions: {
            transformMixedEsModules: true,
        },
    },
    logLevel: "info",
});
