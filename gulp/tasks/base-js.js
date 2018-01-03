'use strict';

var gulp = require('gulp');
var resources = require("../resources");
var utils = require("../util");

gulp.task('base-js', function() {
    return utils.minifyJavaScripts('base.js', resources.baseJs);
});