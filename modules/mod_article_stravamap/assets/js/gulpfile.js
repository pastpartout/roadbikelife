var path = require('path'),
    gulp = require('gulp'),
    watch = require('gulp-watch'),
    sourcemaps = require('gulp-sourcemaps'),
    concat = require('gulp-concat'),
    uglify = require('gulp-uglify'),
    notify = require('gulp-notify'),
    plumber = require('gulp-plumber'),
    sass = require('gulp-sass'),
    rename = require("gulp-rename"),
    browserify = require('gulp-browserify'),
    autoprefixer = require('gulp-autoprefixer');
    http = require('http');
    ecstatic = require('ecstatic');
    htmlToJs = require('gulp-html-to-js');
    html2js = require('gulp-html2js');
    eslint = require('gulp-eslint');

var config = {
    javascript: {
        path: {
            src: path.join('src'),
            dist: path.join('dist')
        }
    },
};

var onJSError = function (err) {
    notify({
        message: err
    });
};


gulp.task('javascript_map', function () {
    return gulp.src([
         config.javascript.path.src+'/init_flot.js',
        config.javascript.path.src+'/init_map.js',
    ])
        .pipe(sourcemaps.init())
        .pipe(concat('init.js'))
        .pipe(uglify())
        .pipe(sourcemaps.write("./"))
        .pipe(gulp.dest(config.javascript.path.dist))
});

gulp.task('javascript_flotaddons', function () {
    return gulp.src(['' +
        'node_modules/flot/source/jquery.flot.hover.js',
        'node_modules/jquery.flot.tooltip/js/jquery.flot.tooltip.js',
        'node_modules/flot/source/jquery.flot.touch.js',
        config.javascript.path.src+'/curvedLines.js'
    ])
        .pipe(sourcemaps.init())
        .pipe(concat('flot_addons.js'))
        .pipe(uglify())
        .pipe(sourcemaps.write("./"))
        .pipe(gulp.dest(config.javascript.path.dist))
});






gulp.task("watch:javascript", function () {
    return watch(path.join(config.javascript.path.src, '**', '*.js'), function () {
        return gulp.start(['javascript_map','javascript_flotaddons']);
    }, {read: false});
});

gulp.task('default', ['lint'], function () {
    http.createServer(
      ecstatic({ root: __dirname })
    ).listen(3000);

    console.log('Listening on :3000');

    gulp.start('watch:javascript');
});
