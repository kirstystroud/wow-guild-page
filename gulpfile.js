const elixir = require('laravel-elixir');

require('laravel-elixir-vue-2');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for your application as well as publishing vendor resources.
 |
 */

elixir((mix) => {
    mix.sass('app.scss')
       .webpack('app.js');
    mix.scripts([
            'auctions.js',
            'characters.js',
            'dungeons.js',
            'quests.js',
            'raids.js',
            'reputation.js',
            'stats.js',
            'tabard.js'
        ],
        'public/js/wow.js','resources/assets/js'
    );
});
