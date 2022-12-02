import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
    build: {
        chunkSizeWarningLimit: 1600,
        // generate manifest.json in outDir
        manifest: true,
        rollupOptions: {
          input: '/resources/js/app.jsx'
        }
      }
});
