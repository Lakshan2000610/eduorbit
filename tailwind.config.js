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
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },

            colors: {
                // 🌐 Global Brand Colors (used for welcome page + admin default)
                primary: "#2F6BFF",
                accent: "#FFC83D",
                secondary: "#7AB8FF",
                "text-primary": "#1E1E1E",
                "text-secondary": "#595959",
                background: "#FAFAFA",

                // 🧑‍💼 Admin Theme
                admin: {
                    primary: "#2F6BFF",
                    secondary: "#7AB8FF",
                    accent: "#FFC83D",
                    background: "#F4F7FF",
                    text: "#1E1E1E",
                },

                // 👩‍🏫 Teacher Theme
                teacher: {
                    primary: "#34B27B",
                    secondary: "#A1E3C4",
                    accent: "#FFD56B",
                    background: "#F8FFF9",
                    text: "#1E1E1E",
                },

                // 👨‍🎓 Student Theme
                student: {
                    primary: "#9B51E0",
                    secondary: "#E2C7FF",
                    accent: "#FF7AA2",
                    background: "#FCFAFF",
                    text: "#1E1E1E",
                },
            },
        },
    },

    plugins: [forms],
};
