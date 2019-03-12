'use strict';

var gulp = require('gulp');
var sass = require('gulp-sass');
var sourcemaps = require('gulp-sourcemaps');
var uglify = require('gulp-uglify');
var rename = require('gulp-rename');
var merge = require('merge-stream');


gulp.task('sass:dev', function() {
  return gulp.src(['./scss/admin.scss', './scss/login.scss'])
    .pipe(sourcemaps.init({
        loadMaps: false,
        debug: true
    }))
    .pipe(sass({ outputStyle: 'nested' }).on('error', sass.logError))
    .pipe(sourcemaps.write('.', {
        includeContent: false,
        sourceRoot: '/'
    }))
    .pipe(gulp.dest('../assets/css'));
});

gulp.task('sass:dist', function() {
  return gulp.src(['./scss/admin.scss', './scss/login.scss'])
    .pipe(sourcemaps.init({
        loadMaps: false,
        debug: false
    }))
    .pipe(sass({ outputStyle: 'compressed' }).on('error', sass.logError))
    .pipe(rename({ suffix: '.min' }))
    .pipe(sourcemaps.write('.', {
        includeContent: false,
        sourceRoot: '/'
    }))
    .pipe(gulp.dest('../assets/css'));
});

gulp.task('copy:scripts', function() {
  var bootstrap = gulp.src('node_modules/bootstrap/dist/js/*.js*')
    .pipe(gulp.dest('../assets/js/lib/bootstrap'));

  var jquery = gulp.src('node_modules/jquery/dist/jquery.*')
    .pipe(gulp.dest('../assets/js/lib/jquery'));

  var js_cookie = gulp.src('node_modules/js-cookie/src/*.js*')
    .pipe(gulp.dest('../assets/js/lib/js-cookie'));

  var owl_carousel = gulp.src('node_modules/owl.carousel/dist/owl.carousel.*js*')
    .pipe(gulp.dest('../assets/js/lib/owl.carousel'));

  return merge(bootstrap, jquery, js_cookie, owl_carousel);
});

gulp.task('copy:styles', function() {
  var bootstrap = gulp.src('node_modules/bootstrap/dist/css/*.css*')
    .pipe(gulp.dest('../assets/css/lib/bootstrap'));

  var owl_carousel = gulp.src('node_modules/owl.carousel/dist/assets/owl.carousel.*css*')
    .pipe(gulp.dest('../assets/css/lib/owl.carousel'));

  return merge(bootstrap, owl_carousel);
});

gulp.task('serve', gulp.series([ 'sass:dev', 'sass:dist' ], function() {
    gulp.watch('scss/**/*.scss', gulp.series('sass:dev'));
    gulp.watch('js/**/*.js').on('change', browserSync.reload);
}));


gulp.task('default', gulp.parallel([ 'serve', 'copy:scripts', 'copy:styles' ]));
