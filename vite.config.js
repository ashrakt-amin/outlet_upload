import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [
        react(),
        laravel({
            input: [
                'resources/js/app.jsx',
                'resources/js/index.css',
            ],
            refresh: true,
        }),

    ],
    outDir: 'public/build',
    build: {
        chunkSizeWarningLimit: 1600,
        // generate manifest.json in outDir
        manifest: true,
        rollupOptions: {
            external: ['react'],
            output:{
                globals: {
                    react: 'React'
                  }
            },
          // overwrite default .html entry
          input: '/resources/js/app.jsx'
        }
      }
});
