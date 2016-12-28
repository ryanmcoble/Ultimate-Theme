var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix.sass([
    	'app.scss'
    ], 'public/assets/css');

    mix.scripts([
    	'app.js'
    ], 'public/assets/js');

    // cache busting
    mix.version(['assets/css/app.css', 'assets/js/all.js']);
});


// HOW TO
// dev - "gulp"
// production - "gulp --production"