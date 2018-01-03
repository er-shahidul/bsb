'use strict';

var gulp = require('gulp');
var resources = require("../resources");
var utils = require("../util");

gulp.task('base-css', function() {
    return utils.minifyCssFiles('base.css', resources.baseCss);
});