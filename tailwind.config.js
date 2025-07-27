export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        './resources/**/*.ts',
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                brand: {
                    50: 'var(--color-brand-50, #F5F3FF)',
                    100: 'var(--color-brand-100, #EDE9FE)',
                    200: 'var(--color-brand-200, #DDD6FE)',
                    300: 'var(--color-brand-300, #C4B5FD)',
                    400: 'var(--color-brand-400, #A78BFA)',
                    500: 'var(--color-brand-500, #8B5CF6)',
                    600: 'var(--color-brand-600, #7C3AED)',
                    700: 'var(--color-brand-700, #6D28D9)',
                    800: 'var(--color-brand-800, #5B21B6)',
                    900: 'var(--color-brand-900, #4C1D95)',
                }
            },
        },
    },
};
