import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './app/Filament/**/*.php',
        './vendor/filament/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Healthcare/Medical color palette
                primary: {
                    50: '#e6f2ff',
                    100: '#cce5ff',
                    200: '#99cbff',
                    300: '#66b1ff',
                    400: '#3397ff',
                    500: '#007dff', // Primary blue for healthcare
                    600: '#0064cc',
                    700: '#004b99',
                    800: '#003266',
                    900: '#001933',
                },
                medical: {
                    // Medical status colors
                    success: '#10b981', // Green for healthy/cleared
                    warning: '#f59e0b', // Amber for caution
                    danger: '#ef4444', // Red for urgent/critical
                    info: '#3b82f6', // Blue for information
                    // Healthcare specific
                    triage: {
                        immediate: '#dc2626', // Red - immediate care
                        urgent: '#f59e0b', // Amber - urgent
                        standard: '#3b82f6', // Blue - standard
                        nonurgent: '#10b981', // Green - non-urgent
                    },
                },
                clinic: {
                    // Clinic-specific colors
                    background: '#f8fafc',
                    surface: '#ffffff',
                    border: '#e2e8f0',
                    text: {
                        primary: '#1e293b',
                        secondary: '#64748b',
                        muted: '#94a3b8',
                    },
                },
            },
            spacing: {
                '18': '4.5rem',
                '88': '22rem',
                '128': '32rem',
            },
            borderRadius: {
                'xl': '0.75rem',
                '2xl': '1rem',
                '3xl': '1.5rem',
            },
            boxShadow: {
                'soft': '0 2px 15px -3px rgba(0, 0, 0, 0.07), 0 10px 20px -2px rgba(0, 0, 0, 0.04)',
                'medical': '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)',
            },
            animation: {
                'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                'fade-in': 'fadeIn 0.5s ease-in',
                'slide-up': 'slideUp 0.3s ease-out',
            },
            keyframes: {
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                slideUp: {
                    '0%': { transform: 'translateY(10px)', opacity: '0' },
                    '100%': { transform: 'translateY(0)', opacity: '1' },
                },
            },
        },
    },

    plugins: [forms],
};
