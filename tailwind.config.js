/** @type {import('tailwindcss').Config} */
//
// Les couleurs du projet vivent dans resources/css/theme.css (3 couches).
// Ce fichier expose les ROLES a Tailwind pour pouvoir ecrire bg-surface,
// text-secondary, border-default, etc. Il ne definit aucune valeur hex,
// sauf dans le bloc de compat, qui disparaitra apres migration des vues.
//
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        // ---- Roles (basculent automatiquement en mode sombre) ----
        surface: {
          DEFAULT:   'var(--surface)',
          raised:    'var(--surface-raised)',
          secondary: 'var(--surface-secondary)',
          hover:     'var(--surface-hover)',
          overlay:   'var(--surface-overlay)',
          inverse:   'var(--surface-inverse)',
        },
        content: {
          DEFAULT:   'var(--text-primary)',
          secondary: 'var(--text-secondary)',
          muted:     'var(--text-muted)',
          'on-accent': 'var(--text-on-accent)',
          'on-color':  'var(--text-on-color)',
        },
        line: {
          DEFAULT: 'var(--border-default)',
          strong:  'var(--border-strong)',
        },
        accent: {
          DEFAULT:   'var(--accent)',
          2:         'var(--accent-2)',
          highlight: 'var(--accent-highlight)',
          surface:   'var(--accent-surface)',
          border:    'var(--accent-border)',
        },
        state: {
          success: 'var(--success)',
          warning: 'var(--warning)',
          error:   'var(--error)',
          info:    'var(--info)',
        },

        // ---- Compat : anciennes classes des 25 vues non migrees. ----
        // A supprimer une fois toutes les vues passees aux roles.
        primary: {
          50: '#EEF7FB', 100: '#D3EBF5', 200: '#A8D6EA', 300: '#6FBADB',
          400: '#3B9AC7', 500: '#1B7BA8', 600: '#0E7490', 700: '#134E6E',
          800: '#143F58', 900: '#14344A',
        },
        secondary: {
          50: '#FEF6E7', 100: '#FCE8BF', 200: '#F9D183', 300: '#F7BC4C',
          400: '#F5A524', 500: '#f08224', 600: '#B96A08', 700: '#94510C',
          800: '#7A4110', 900: '#663710',
        },
      },
      fontFamily: {
        sans:    ['Inter', 'system-ui', '-apple-system', 'sans-serif'],
        display: ['"Inter Tight"', 'Inter', 'system-ui', 'sans-serif'],
      },
      borderRadius: {
        card:   'var(--radius-card)',
        button: 'var(--radius-button)',
        input:  'var(--radius-input)',
        chip:   'var(--radius-chip)',
        pill:   'var(--radius-pill)',
      },
      boxShadow: {
        soft:    'var(--shadow-soft)',
        card:    'var(--shadow-card)',
        raised:  'var(--shadow-raised)',
        overlay: 'var(--shadow-overlay)',
      },
      transitionTimingFunction: {
        DEFAULT: 'var(--easing)',
        out:     'var(--easing-out)',
      },
      zIndex: {
        dropdown: 'var(--z-dropdown)',
        sticky:   'var(--z-sticky)',
        overlay:  'var(--z-overlay)',
        modal:    'var(--z-modal)',
        toast:    'var(--z-toast)',
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}
