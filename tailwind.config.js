/** @type {import('tailwindcss').Config} */
export default {
  // Aquí activamos tu prefijo
  prefix: 'tw', 

  // Desactivamos el reset para no romper Bootstrap
  corePlugins: {
    preflight: false,
  },

  // Rutas donde Tailwind buscará tus clases
  content: [
    ".storage/framework/views/*.php",
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],

  theme: {
    extend: {},
  },
  plugins: [],
}