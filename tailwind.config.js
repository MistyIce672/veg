/** @type {import('tailwindcss').Config} */
module.exports = {
    content: ["./*.{html,js,php}", "./**/*.{html,js,php}"],
    mode: "jit",
    theme: {
      extend: {
        colors: {
          primary: "#131313",
          secondary: "#00f6ff",
          dimWhite: "rgba(255, 255, 255, 0.7)",
          dimBlue: "rgba(9, 151, 124, 0.1)",
          ogDark: "#eb2d07",
        },
        boxShadow:{
          og : "0 0 10px #f35a0a",
        },
      },
      screens: {
        xs: "480px",
        ss: "620px",
        sm: "768px",
        md: "1060px",
        lg: "1200px",
        xl: "1700px",
      },
    },
    plugins: [],
  };