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
        primary: colors.emerald,
        secondary: colors.teal,
        gray: colors.slate,
        warning: colors.amber,
        danger: colors.rose,
        success: colors.emerald,
        info: colors.sky,
      },
      fontFamily: {
        sans: ["Inter", "Nunito", ...defaultTheme.fontFamily.sans],
      },
      // Softer, more modern corner rounding across every card / input / button
      borderRadius: {
        DEFAULT: "0.5rem",
        md: "0.625rem",
        lg: "0.875rem",
        xl: "1.125rem",
        "2xl": "1.375rem",
      },
      // Layered, low-contrast shadows — the hallmark of a premium UI
      boxShadow: {
        sm: "0 1px 2px 0 rgb(15 23 42 / 0.04)",
        DEFAULT:
          "0 1px 3px 0 rgb(15 23 42 / 0.06), 0 1px 2px -1px rgb(15 23 42 / 0.05)",
        md: "0 4px 14px -3px rgb(15 23 42 / 0.08), 0 2px 6px -2px rgb(15 23 42 / 0.05)",
        lg: "0 14px 32px -8px rgb(15 23 42 / 0.12), 0 6px 14px -6px rgb(15 23 42 / 0.07)",
        xl: "0 24px 48px -12px rgb(15 23 42 / 0.18)",
      },
      screens: {
        '3xl': '1800px',
      },
    },
  },

  plugins: [require("@tailwindcss/typography"), require("@tailwindcss/forms")],
};
