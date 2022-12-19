// webpack.mix.js
const mix = require('laravel-mix');



mix.js('resources/js/app.js', 'public/js')
.version()
.react()
.sourceMaps()
.postCss('resources/css/app.css', 'public/css', [
    require('tailwindcss'),
])

if (mix.inProduction()) {
  mix.version();
}

mix.browserSync({
    watch: true,
    files: [
      'public/js/**/*',
      'public/css/**/*',
      'public/**/*.+(html|php)',
      'resources/views/**/*.php',
      'app/Http/Controllers/Api/*/.php',
      'database/**/*/.php',
      'route/*/.php',
    ],
    open: "http://127.0.0.1:8000/",
    browser: "chrome",
    reloadDelay: 100,
    proxy: {
      target: "http://127.0.0.1:8000/",
      ws: true,
    },
  });
