'use strict';

var gulp = require('gulp');
var resources = require("../resources");
var utils = require("../util");

gulp.task('modal', ['modal-css', 'modal-js']);


gulp.task('modal-css', function() {
    return utils.minifyCssFiles('modal.css', resources.modalCss);
});

gulp.task('modal-js', function() {
    return utils.minifyJavaScripts('modal.js', resources.modalJs);
});
