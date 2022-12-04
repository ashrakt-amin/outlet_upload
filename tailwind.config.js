/** @type {import('tailwindcss').Config} */
module.exports = {
  darkMode: "class",
  content: [
    './storage/framework/views/*.php',
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.jsx',
],
  theme: {
    extend: {},
  },
  plugins: [require("flowbite/plugin")],
};
