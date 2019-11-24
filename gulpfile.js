'use strict';


var gulp = require('gulp'),

    elixir = require('laravel-mix'),
    env     = require('dotenv').load();

    uglify = require('gulp-uglify'),
    plumber = require('gulp-plumber'),
    autoprefixer = require('gulp-autoprefixer'),
    cleanCSS = require('gulp-clean-css'),
    // browserSync = require('browser-sync').create(),
    // concatCss = require('gulp-concat-css'),
    useref = require('gulp-useref'),
    gulpif = require('gulp-if'),
    sass = require('gulp-sass');





 elixir(function(mix) {
     // mix.sass("**/*.scss");

     mix.sass('**/*.scss', null, {
       includePaths: [
         elixir.config.assetsPath + '/sass'
       ]
     });

     mix.browserSync({
         proxy:  'localhost:8000',
         notify: false
     });

 });



// gulp.task('style', function() {
//    return gulp.src('resources/assets/sass/**/*.scss')
//        .pipe(plumber())
//        .pipe(sass().on('error', sass.logError))
//        // .pipe(gulpif('*.css', autoprefixer()))
//        // .pipe(gulp.dest('app/css'))
//        //.pipe(rename('style.min.css'))
//        //.pipe(cleanCSS())
//        //.pipe(autoprefixer('last 2 version', 'safari 5', 'ie 8', 'ie 9'))
//        //.pipe(gulp.dest('dist/styles'))
//        //.pipe(gulp.dest('../../../dist/maps'))
//        .pipe(browserSync.reload({
//            stream: true
//        }));
// });


/*gulp.task('browserSync', function() {
    browserSync.init({
        proxy: 'http://localhost/leck/',
        port: 8000,
        open: true,
        notify: false
    });
});*/


// gulp.task('end', function() {
//     return gulp.src('app/*.php')
//         .pipe(useref())
//         .pipe(gulpif('*.js', uglify()))
//         .pipe(gulpif('*.css', autoprefixer()))
//         .pipe(gulpif('*.css', cleanCSS()))
//         .pipe(gulp.dest('dist'));
// });
//

//  gulp.task('default', ['styles', 'browserSync'], function() {
//      gulp.watch('resources/assets/sass/**/*.scss', ['style', browserSync.reload]);
//      gulp.watch('app/**/*.php', browserSync.reload);
//      gulp.watch('app/**/*.html', browserSync.reload);
//      gulp.watch('app/**/*.js', browserSync.reload);
// });
