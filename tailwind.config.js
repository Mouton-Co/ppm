const defaultTheme = require('tailwindcss/defaultTheme');

const dotenv = require('dotenv');
dotenv.config();

const colors = {
    'light-gray': '#818285',
    'dark-gray': '#3a3a3b',
    'dark-field': '#2a3441',
    'dark-field-border': '#404954',
    'feedback-success': '#545d6a',
    'nav-link': '#9ca3a3',
};

// staging styling overrides
if (process.env.APP_ENV === 'staging') {
    // simply changes navbar on the left to a whiter color so you can identify when on staging
    colors['gray-900'] = '#e5e7eb';
}

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter var', ...defaultTheme.fontFamily.sans],
            },
            colors: colors
        },
        screens: {
            'smaller-than-380': {'min': '0px', 'max': '380px'},
            'smaller-than-520': {'min': '0px', 'max': '520px'},
            'smaller-than-572': {'min': '0px', 'max': '572px'},
            'smaller-than-711': {'min': '0px', 'max': '711px'},
            'smaller-than-740': {'min': '0px', 'max': '740px'},
            'smaller-than-928': {'min': '0px', 'max': '928px'},
            'smaller-than-1090': {'min': '0px', 'max': '1090px'},
            'smaller-than-1457': {'min': '0px', 'max': '1457px'},
            ...defaultTheme.screens,
        },
    },

    safelist: [
        'bg-green-300', 'text-green-800',
        'bg-cyan-300', 'text-cyan-800',
        'bg-zinc-300', 'text-zinc-800',
        'bg-orange-300', 'text-orange-800',
        'bg-transparent' , 'text-gray-400',
    ],

    plugins: [require('@tailwindcss/forms')],
};
