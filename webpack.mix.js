// webpack.mix.js
const mix = require('laravel-mix');



mix.js('resources/js/app.js', 'public/js')
.react()
.postCss('resources/css/app.css', 'public/css', [
    require('tailwindcss'),
])

mix.browserSync({
    watch: true,
    files: [
      'public/js/**/*',
      'public/css/**/*',
      'public/**/*.+(html|php)',
      'resources/views/**/*.php'
    ],
    open: "http://127.0.0.1:8000/",
    browser: "google chrome",
    reloadDelay: 1000,
    proxy: {
      target: "http://127.0.0.1:8000/",
      ws: true,
    },
  });

// mix.setPublicPath('/')
// .webpackConfig({
//   module: {
//     rules: [
//       {
//         test: /\.(jsx|js|vue)$/,
//         loader: 'eslint-loader',
//         enforce: 'pre',
//         exclude: /(node_modules)/,
//       }
//     ]
//   },
// })
// .options({
//   hmrOptions: {
//       port: 3000
//   }
// })
// .react('src/index.js', '/dist/app.js');