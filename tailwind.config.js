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
    },

    plugins: [require('@tailwindcss/forms')],
};
