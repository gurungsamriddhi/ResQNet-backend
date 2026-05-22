import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],

    theme: {
        extend: {
            colors: {
                admin: {
                    // Primary Brand Colors
                    primary: '#3D5A4E',
                    primaryPress: '#2F453C',
                    navActive: '#FCC234',

                    // Background & Surfaces
                    bg: '#F4F7F6',
                    surface: '#FFFFFF',
                    border: '#E0E0E0',

                    // Action Button States
                    btnGreen: '#34C759',
                    btnGreenPress: '#2AA54A',
                    btnRed: '#FF3B30',
                    btnRedPress: '#E02A20',

                    // Text Colors
                    textPrimary: '#1A1A1A',
                    textSecondary: '#666666',
                    textOnPrimary: '#FFFFFF',
                    textOnActiveNav: '#1A1A1A',

                    // Status Badges
                    successBg: '#D4EDDA',
                    successText: '#28A745',
                    errorBg: '#F8D7DA',
                    errorText: '#DC3545',
                }
            },
        },
    },
    plugins: [],
}
