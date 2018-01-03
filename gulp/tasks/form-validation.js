'use strict';

var gulp = require('gulp');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');

var baseDir = 'vendor/fp/jsformvalidator-bundle/Fp/JsFormValidatorBundle/Resources/public/js/';

gulp.task('form-validation', function() {
    return  gulp.src([
        baseDir + 'FpJsFormValidator.js',
        baseDir + 'constraints/*',
        '!' + baseDir + 'constraints/UniqueEntity.js',
        'web/assets/global/scripts/UniqueEntity.js',
        baseDir + 'transformers/*',
        baseDir + 'jquery.fpjsformvalidator.js',
        'web/assets/global/scripts/common_form_validation.js'
    ])
        .pipe(concat('fp_js_validator.js'))
        .pipe(uglify())
        .pipe(gulp.dest('web/js/'));
});