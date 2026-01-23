/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          50: '#eff4ff',
          100: '#dbeafe',
          200: '#bfdbfe',
          300: '#93c5fd',
          400: '#60a5fa',
          500: '#3b82f6',
          600: '#0E7490', // Couleur primaire - Bleu Aqua
          700: '#0c5f7a',
          800: '#0a4f63',
          900: '#1e3a8a',
        },
        secondary: {
          50: '#fef6f0',
          100: '#fdeae0',
          200: '#fad2c0',
          300: '#f7b391',
          400: '#f38a60',
          500: '#f08224', // Nouveau orange
          600: '#e06a1c',
          700: '#ba5118',
          800: '#944119',
          900: '#773617',
        },
        // Couleurs utilitaires selon votre palette
        white: '#ffffff',
        black: '#1d1d1b',
        gray: {
          50: '#f9f9f9',
          100: '#f3f3f3',
          200: '#e7e7e7',
          300: '#d1d1d1',
          400: '#b4b4b4',
          500: '#878787', // Votre gris
          600: '#737373',
          700: '#525252',
          800: '#404040',
          900: '#1d1d1b', // Votre noir
        },
      },
      fontFamily: {
        'sans': ['Poppins', 'system-ui', '-apple-system', 'sans-serif'],
        'script': ['Pacifico', 'cursive'],
      },
    },
    borderRadius: {
      'none': '0',
      'sm': '0.375rem',
      DEFAULT: '0.5rem',
      'md': '0.625rem',
      'lg': '1rem',
      'xl': '1.25rem',
      '2xl': '1.5rem',
      'full': '9999px',
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}
