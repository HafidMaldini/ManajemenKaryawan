import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            keyframes: {
                fadeIn: { from: { opacity: 0 }, to: { opacity: 1 } },
                fadeOut: { from: { opacity: 1 }, to: { opacity: 0 } }
            },
            animation: {
                fadeIn: "fadeIn 0.3s ease-out",
                fadeOut: "fadeOut 0.3s ease-out"
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
