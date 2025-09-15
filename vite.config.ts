import { wayfinder } from '@laravel/vite-plugin-wayfinder';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import { defineConfig } from 'vite';
import { wayfinder } from '@laravel/vite-plugin-wayfinder';
import { run } from 'vite-plugin-run';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.ts'],
            ssr: 'resources/js/ssr.ts',
            refresh: true,
        }),
        tailwindcss(),
        wayfinder({
            formVariants: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        run([
            {
                name: "wayfinder",
                run: ["sail", "artisan", "wayfinder:generate"],
                pattern: ["routes/**/*.php", "app/**/Http/**/*.php"],
            }
        ]),
    ],
});
