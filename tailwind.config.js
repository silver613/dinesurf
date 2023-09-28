const defaultTheme = require('tailwindcss/defaultTheme');
const colors = require('tailwindcss/colors')


module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                yellow: colors.yellow,
                'bg-semi-75': 'rgba(0, 0, 0, 0.75)',
                'dinesurf-gray':{

                },
                'dinesurf-green': {
                    100: '#e7f3d8',
                    200: '#d0e8b0',
                    300: '#b8dc89',
                    400: '#a0d062',
                    500: '#8cc63f',
                    600: '#7bb135',
                    700: '#608929',
                    800: '#44621d',
                    900: '#293b12',
                },
                'dinesurf-red': {
                    100: '#f7d4d4',
                    200: '#efa9a9',
                    300: '#e77e7e',
                    400: '#df5353',
                    500: '#d82b2b',
                    600: '#c22424',
                    700: '#971c1c',
                    800: '#6c1414',
                    900: '#410c0c',
                    0: '#B72F2F',
                },
                'dinesurf-orange': {
                    100: '#fcdacf',
                    200: '#f9b69f',
                    300: '#f6916f',
                    400: '#f36c3f',
                    500: '#f15824',
                    600: '#d8400e',
                    700: '#a8320b',
                    800: '#782408',
                    900: '#481505',
                },
                'dinesurf-black': {
                    100: '#808080',
                    200: '#666666',
                    300: '#4d4d4d',
                    500: '#363636',
                    600: '#262626',
                    700: '#0d0d0d',
                    800: '#383838'
                }
            },
            spacing: {
                "85": "350px"
            },
            maxWidth: {
                '90': '90%',
                '80': '80%',
                '40': '40%',
                '50': '50%',
            },
            minWidth: {
                '60': '60%',
                '14': '14%',
            },
            minHeight: {
                '40': '10rem',
            },
            zIndex: {
                '1024': '1024',
            },
            boxShadow: {
                'menu': '0px 46px  130px 0px   #00000026',
                'side-menu': '14px  0px 60px 0px #2C30400D',
                'settings-page': '4.21148681640625px 0px 52.643585205078125px 0px #0000000D'
            }

        },
    },

    variants: {
        extend: {
            opacity: ['disabled'],
        },
    },

    plugins: [require('@tailwindcss/forms'), require('@tailwindcss/typography')],
};
