/** @type {import('tailwindcss').Config} */
export default {
  darkMode: 'class',
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        'brand-brown':  '#5C4033',
        'brand-cream':  '#FAFAF8',
        'brand-forest': '#228B22',
        'brand-gold':   '#D4A853',
        'brand-gold-light': '#E8C878',
        'brand-ink':    '#0D0D14',
        'brand-ink-2':  '#16161F',
        'brand-ink-3':  '#1E1E2A',
        'brand-muted':  '#6B7280',
      },
      fontFamily: {
        sans:    ['Inter', 'sans-serif'],
        display: ['Outfit', 'sans-serif'],
        serif:   ['"Playfair Display"', 'Georgia', 'serif'],
      },
      animation: {
        'fade-up':   'fadeUp 0.7s ease both',
        'fade-in':   'fadeIn 0.5s ease both',
        'shimmer':   'shimmer 2s linear infinite',
        'float':     'float 6s ease-in-out infinite',
      },
      keyframes: {
        fadeUp:  { '0%': { opacity: '0', transform: 'translateY(24px)' }, '100%': { opacity: '1', transform: 'translateY(0)' } },
        fadeIn:  { '0%': { opacity: '0' },  '100%': { opacity: '1' } },
        shimmer: { '0%': { backgroundPosition: '-200% 0' }, '100%': { backgroundPosition: '200% 0' } },
        float:   { '0%, 100%': { transform: 'translateY(0)' }, '50%': { transform: 'translateY(-10px)' } },
      },
      backgroundImage: {
        'gold-gradient': 'linear-gradient(135deg, #D4A853, #E8C878, #D4A853)',
      },
    },
  },
  plugins: [],
}
