let mix = require('laravel-mix');

// RESOURCE CLIENT
mix.js('resources/js/app.js', 'public/js');
mix.sass('resources/scss/app.scss', 'public/css');
