/**
 * FILE PURPOSE: Configure Vite with React plugin and educational aliases.
 * LEARNING NOTE: Vite is a lightning-fast dev server that bundles your app for production.
 * TRY THIS: Add an alias for '@/components' to simplify imports.
 */

import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';

export default defineConfig({
  plugins: [react()],
  server: {
    port: 5173,
  },
});
