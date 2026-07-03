const defaultTheme = require("tailwindcss/defaultTheme");
const colors = require("tailwindcss/colors");

/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/views/craftable-pro.blade.php",
    "./resources/js/craftable-pro/**/*.vue",
    "./vendor/brackets/craftable-pro/resources/js/**/*.vue"
  ],
  darkMode: 'class',
  theme: {
    extend: {
      colors: {
        // Larkon-style warm orange accent
        primary: {
          50:  "#fff4ed",
          100: "#ffe6d5",
          200: "#feccaa",
          300: "#fdac74",
          400: "#fb8b3c",
          500: "#f97316",
          600: "#ea6a25",
          700: "#c2410c",
          800: "#9a3412",
          900: "#7c2d12",
        },
        secondary: colors.amber,
        gray: colors.slate,
        warning: colors.amber,
        danger: colors.red,
        success: colors.green,
        info: colors.sky,
      },
      fontFamily: {
        sans: ["Inter", "Nunito", ...defaultTheme.fontFamily.sans],
      },
      screens: {
        '3xl': '1800px',
      },
    },
  },

  plugins: [require("@tailwindcss/typography"), require("@tailwindcss/forms")],
};
