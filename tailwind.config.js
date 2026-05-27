import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import flowbite from 'flowbite/plugin';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',

        './node_modules/flowbite/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                amazon: {
                    dark: '#131921',
                    'dark-blue': '#232f3e',
                    yellow: '#febd69',
                    orange: '#f3a847',
                    'light-gray': '#f3f3f3',
                },
            },
        },
    },

    plugins: [
        forms,
        flowbite
    ],
};
