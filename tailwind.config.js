const colors = require('tailwindcss/colors')
 
module.exports = {
    content: [
        './resources/**/*.blade.php',
        './vendor/filament/**/*.blade.php', 
    ],
    theme: {
        extend: {
            colors: { 
                primary: colors.cyan,
                danger: colors.rose,
                success: colors.green,
                warning: colors.yellow,
            }, 
        },
    },
    plugins: [
        require('@tailwindcss/forms'), 
        require('@tailwindcss/typography'), 
    ],
}