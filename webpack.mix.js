// webpack.mix.js
const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
.react()

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