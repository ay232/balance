const mix = require('laravel-mix');
/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .postCss('resources/css/tailwind.css', 'public/css/tailwind.css', [
    require('tailwindcss'),
    require('autoprefixer')
]);
mix.styles(['node_modules/bootstrap/dist/css/bootstrap.css',
    'node_modules/bootstrap/dist/css/bootstrap-grid.css',
    'node_modules/bootstrap/dist/css/bootstrap-utilities.css'],
    'public/css/bootstrap.css');
mix.js('node_modules/bootstrap/dist/js/bootstrap.js', 'public/js/bootstrap.js');

mix.copy('node_modules/jquery/dist/jquery.js', 'public/js/jquery.js');

mix.copy('node_modules/datatables.net-bs5/css/dataTables.bootstrap5.css', 'public/css/datatables.css');
mix.copy('node_modules/datatables.net/js/jquery.dataTables.js', 'public/js/datatables.js');
mix.copy('node_modules/datatables.net-bs5/js/dataTables.bootstrap5.js', 'public/js/dt-bs-5.js');
mix.css('resources/css/custom.css', 'public/css/app.css');
