const elixir = require('laravel-elixir');

require('laravel-elixir-vue');

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

elixir(mix =>
{
	mix.sass('app.scss')
		.copy('./resources/assets/js/app.js', 'public/js')
		.copy('./resources/assets/sass/bootstrap.superhero.min.css', 'public/css')
		.copy('./resources/assets/js/loading-bar.js', 'public/js')
		.copy('./resources/assets/js/ngStorage.js', 'public/js')
		.copy('./resources/assets/js/controllers/*.js', 'public/js/controllers')
		.copy('./resources/assets/js/services.js', 'public/js')
		.copy('./bower_components/angular-animate/angular-animate.min.js', 'public/js')
	;
});
