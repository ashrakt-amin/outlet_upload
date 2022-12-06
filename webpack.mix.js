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
      'resources/views/**/*.php',
      'app/Http/**/*/.php',
      'database/**/*/.php',
      'route/*/.php', 
    ],
    open: "http://127.0.0.1:8000/",
    browser: "google chrome",
    reloadDelay: 1000,
    proxy: {
      target: "http://127.0.0.1:8000/",
      ws: true,
    },
  });
