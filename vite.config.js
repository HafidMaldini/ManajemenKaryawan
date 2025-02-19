// import { defineConfig } from 'vite';
// import laravel from 'laravel-vite-plugin';

// export default defineConfig({
//     plugins: [
//         laravel({
//             input: ['resources/css/app.css', 'resources/js/app.js'],
//             refresh: true,
//         }),
//     ],
// });


import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
  plugins: [vue()],
  server: {
    host: true, // Izinkan akses dari luar
    port: 5173, // Pastikan sesuai dengan Railway
  },
  build: {
    outDir: 'dist', // Pastikan output berada di dist
  }
});
