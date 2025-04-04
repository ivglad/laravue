import { defineConfig } from 'vite'
import Vue from '@vitejs/plugin-vue'
import path from 'path'
import { ViteImageOptimizer } from 'vite-plugin-image-optimizer'
// ----------------------------------------------------------------------------
// Unplugin auto-import
// ----------------------------------------------------------------------------
import AutoImport from 'unplugin-auto-import/vite'
import Components from 'unplugin-vue-components/vite'
// ----------------------------------------------------------------------------

// ----------------------------------------------------------------------------
// Unplugin icons
// ----------------------------------------------------------------------------
import Icons from 'unplugin-icons/vite'
import { FileSystemIconLoader } from 'unplugin-icons/loaders'
import IconsResolver from 'unplugin-icons/resolver'
// ----------------------------------------------------------------------------

// ----------------------------------------------------------------------------
// PrimeVue
// ----------------------------------------------------------------------------
import { PrimeVueResolver } from '@primevue/auto-import-resolver'
// ----------------------------------------------------------------------------

// ----------------------------------------------------------------------------
// Motion
// ----------------------------------------------------------------------------
import MotionResolver from 'motion-v/resolver'
// ----------------------------------------------------------------------------

// ----------------------------------------------------------------------------
// PWA
// ----------------------------------------------------------------------------
import { VitePWA } from 'vite-plugin-pwa'
// ----------------------------------------------------------------------------

export default defineConfig({
  server: {
    host: '0.0.0.0',
    port: 5173,
    strictPort: true,
  },
  plugins: [
    Vue(),
    Icons({
      defaultClass: 'icon',
      compiler: 'vue3',
      customCollections: {
        custom: FileSystemIconLoader('src/assets/svg/icons'),
      },
      iconCustomizer(collection, icon, props) {
        if (collection === 'custom') {
          props.class = `icon icon-${icon}`
          props.width = '1em'
          props.height = '1em'
        }
      },
      transform(svg, collection, icon) {
        if (collection === 'custom') {
          // Transform svg width and height
          // svg = svg.replace(/(width|height)="([\d.]+)"/g, '$1="2rem"')
          // Transform svg fill/stroke color
          svg = svg.replace(/(fill|stroke)="#715F92"/g, '$1="currentColor"')
        }
        return svg
      },
    }),
    AutoImport({
      include: [/\.[tj]sx?$/, /\.vue$/, /\.vue\?vue/, /\.md$/],
      dirs: ['src/helpers/**', 'src/store/**'],
      imports: [
        'vue',
        'vue-router',
        {
          axios: [['default', 'axios']],
        },
        {
          zod: ['z'],
        },
        {
          motion: ['motion-v'],
        },
      ],
      packagePresets: [
        'primevue',
        '@primeuix/themes',
        '@primevue/core/api',
        '@primevue/forms/resolvers/zod',
        '@tanstack/vue-table',
        '@tanstack/vue-query',
        '@vueuse/core',
      ],
      vueTemplate: true,
      vueDirectives: undefined,
      viteOptimizeDeps: true,
      injectAtEnd: true,
      dts: true,
    }),
    Components({
      dirs: ['src'],
      deep: true,
      directives: true,
      resolvers: [
        IconsResolver({
          customCollections: ['custom'],
        }),
        PrimeVueResolver(),
        MotionResolver(),
      ],
      dts: true,
    }),
    ViteImageOptimizer(),
    // TODO: реализовать активацию PWA по значению в .env
    // VitePWA({
    //   registerType: 'autoUpdate',
    //   workbox: {
    //     cleanupOutdatedCaches: true,
    //     skipWaiting: true,
    //     globPatterns: ['**/*.{js,css,html,ico,png,svg,woff2}'],
    //   },
    //   // PWA in dev mode
    //   devOptions: {
    //     enabled: false,
    //   },
    // }),
  ],
  css: {
    preprocessorOptions: {
      scss: {
        api: 'modern-compiler',
        additionalData: `@use '@/assets/styles/_index' as *;`,
      },
    },
  },
  resolve: {
    alias: [
      {
        find: '@',
        replacement: path.resolve(__dirname, 'src'),
      },
    ],
  },
})
