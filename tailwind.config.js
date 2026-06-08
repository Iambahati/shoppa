import defaultTheme from 'tailwindcss/defaultTheme'

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './app/View/Components/**/*.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                // Inter for UI, fallback to system sans
                sans: ['Nunito', 'Nunito Variable', ...defaultTheme.fontFamily.sans],
                mono: ['JetBrains Mono', ...defaultTheme.fontFamily.mono],
            },

            colors: {
                // Shoppa brand — emerald-based, stone neutrals
                // These complement Tailwind's built-ins; no overrides needed.
                shoppa: {
                    50:  '#ecfdf5',
                    100: '#d1fae5',
                    500: '#10b981',
                    600: '#059669',
                    700: '#047857',
                    900: '#064e3b',
                },
            },

            borderRadius: {
                // Slightly rounder than Tailwind default for a modern feel
                'xl':  '0.875rem',
                '2xl': '1.25rem',
            },
        },
    },

    plugins: [
        require('@tailwindcss/forms'),
        
    ],
}