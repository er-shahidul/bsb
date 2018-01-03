'use strict';

var gulp = require('gulp');

gulp.task('watch', function () {
    gulp.watch([
        'src/*/Resources/assets/css/*.css',
        'src/*/Resources/assets/js/*.js',
        'web/assets/layouts/layout3/css/*',
        'web/js/fos_js_routes.js',
        'web/assets/global/scripts/*'
    ], ['default']);
});
