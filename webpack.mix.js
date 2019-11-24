'use strict';

const { mix } = require('laravel-mix');



/*const UglifyJSPlugin = require('uglifyjs-webpack-plugin');

module.exports = {
  plugins: [
    new UglifyJSPlugin({
      include: ['resources/assets/js/d3/d3graph.js'],
      compress: {
        warnings: false,
        screw_ie8: true,
        conditionals: true,
        unused: true,
        comparisons: true,
        sequences: true,
        dead_code: true,
        evaluate: true,
        join_vars: true,
        if_return: true
      },
      output: {
        comments: false,
        path: 'public/js'
      }
    })
  ]
}*/


/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */


 // mix.sass("**/*.scss");

mix.sass('resources/assets/sass/app.scss', 'public/css')
  .sass('resources/assets/sass/sections/home/home.scss', 'public/css')
  .sass('resources/assets/sass/sections/editor/writing.scss', 'public/css')
  .sass('resources/assets/sass/sections/signs.scss', 'public/css')
  .sass('resources/assets/sass/sections/feed.scss', 'public/css')
  .sass('resources/assets/sass/sections/profile.scss', 'public/css')
  .sass('resources/assets/sass/sections/popular.scss', 'public/css');



  mix.js('resources/assets/js/app.js', 'public/js');
mix.js('resources/assets/js/profile.js', 'public/js');

// mix.js('resources/assets/js/d3/d3graph.js', 'public/js');
mix.combine(['resources/assets/js/d3/*'], 'public/js/d3graph.js');
mix.combine(['resources/assets/js/editor/main/*'], 'public/js/editor.js');
mix.combine(['resources/assets/js/writing/*'], 'public/js/writing.js');


 // mix.js('resources/assets/js/**/*.js', 'public/js')
 //   .sass('resources/assets/sass/**/*.scss', 'public/css');



// mix.js('resources/assets/js/app.js', 'public/js')
//   .sass('resources/assets/sass/app.scss', 'public/css');




mix.browserSync();



  // Full API
  // mix.extract(vendorLibs);
  // mix.sass(src, output);
  // mix.less(src, output);
  // mix.browserSync('my-site.dev');
  // mix.combine(files, destination);
  // mix.copy(from, to);
  // mix.minify(file);
  // mix.sourceMaps(); // Enable sourcemaps
  // mix.version(); // Enable versioning.
  mix.disableNotifications();
  // mix.setPublicPath('path/to/public');
  // mix.autoload({}); <-- Will be passed to Webpack's ProvidePlugin.
  // mix.webpackConfig({}); <-- Override webpack.config.js, without editing the file directly.
  // mix.then(function () {}) <-- Will be triggered each time Webpack finishes building.
  // mix.options({
  //   extractVueStyles: false // default
  // });
