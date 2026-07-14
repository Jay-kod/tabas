import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    900: '#04342C',
                    800: '#085041',
                    600: '#0F6E56',
                    400: '#1D9E75',
                    50: '#E1F5EE',
                },
                critical: {
                    text: '#791F1F',
                    bg: '#FCEBEB',
                },
                urgent: {
                    text: '#633806',
                    bg: '#FAEEDA',
                },
                standard: {
                    text: '#0C447C',
                    bg: '#E6F1FB',
                },
                nonurgent: {
                    text: '#27500A',
                    bg: '#EAF3DE',
                },
            },
        },
    },

    plugins: [forms],
};
