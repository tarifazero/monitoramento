module.exports = {
    purge: [
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],
    darkMode: false,
    theme: {
        colors: {
            transparent: 'transparent',
            current: 'currentColor',
            black: '#000',
            white: '#fff',
            yellow: {
                light: '#FFF9AE',
                medium: '#FFEB6B',
                dark: '#FFCA44',
                DEFAULT: '#FFF200',
            },
            pink: {
                light: '#F8C1D8',
                medium: '#E887B7',
                DEFAULT: '#EC008C',
            },
            beige: {
                light: '#F4F1ED',
                DEFAULT: '#E9E0D2',
            },
            gray: '#231F20',
        },
        container: {
            center: true,
            padding: '1rem',
        },
        fontFamily: {
            'helsinki': ['Helsinki', 'sans-serif'],
            'work': ['Work\\ Sans', 'sans-serif'],
            'dm': ['DM\\ Sans', 'sans-serif'],
        },
        extend: {
            padding: {
                '9/16': '56.25%',
                '2/3': '66.67%',
                'full': '100%',
            },
            borderWidth: {
                '11': '11px',
            },
            outline: {
                pink: ['2px dotted #EC008C', '1px'],
                yellow: ['2px dotted #FFF200', '1px'],
            },
        },
    },
    variants: {
        extend: {
            outline: ['focus-visible'],
            borderWidth: ['hover', 'focus', 'focus-within'],
            margin: ['hover', 'focus', 'focus-within'],
        },
    },
    plugins: [],
}
