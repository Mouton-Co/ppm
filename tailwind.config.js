const defaultTheme = require('tailwindcss/defaultTheme');

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
            colors: {
                'light-gray': '#818285',
                'dark-gray': '#3a3a3b',
                'dark-field': '#2a3441',
                'dark-field-border': '#404954',
                'feedback-success': '#545d6a',
                'nav-link': '#9ca3a3',
            }
        },
        screens: {
            'smaller-than-380': {'min': '0px', 'max': '380px'},
            'smaller-than-520': {'min': '0px', 'max': '520px'},
            'smaller-than-572': {'min': '0px', 'max': '572px'},
            'smaller-than-740': {'min': '0px', 'max': '740px'},
            'smaller-than-928': {'min': '0px', 'max': '928px'},
            ...defaultTheme.screens,
        },
    },

    plugins: [require('@tailwindcss/forms')],
};
