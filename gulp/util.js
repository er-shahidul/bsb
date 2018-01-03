'use strict';
var gulp = require('gulp');
var concat = require('gulp-concat');
var resolveRelativeUrls = require('gulp-css-resolve-relative-urls');
var uglify = require('gulp-uglify');
var cleanCSS = require('gulp-clean-css');

module.exports = {
    minifyJavaScripts: function (file, src) {
        return gulp.src(src)
            .pipe(concat(file))
            .pipe(uglify())
            .pipe(gulp.dest('web/js/'));
    },

    minifyCssFiles: function (file, src) {
        var dest = 'web/css/';
        return gulp.src(src)
            .pipe(resolveRelativeUrls(dest))
            .pipe(concat(file))
            .pipe(cleanCSS())
            .pipe(gulp.dest(dest));
    }
};