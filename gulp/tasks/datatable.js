'use strict';

var gulp = require('gulp');
var resources = require("../resources");
var utils = require("../util");

gulp.task('datatable', ['datatable-css', 'datatable-js']);


gulp.task('datatable-css', function() {
    return utils.minifyCssFiles('datatable.css', resources.datatableCss);
});

gulp.task('datatable-js', function() {
    return utils.minifyJavaScripts('datatable.js', resources.datatableJs);
});
