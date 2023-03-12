const {series, parallel, src, dest, watch} = require('gulp');
const browserSync = require('browser-sync');
const sass = require('gulp-sass');
const autoprefixer = require('gulp-autoprefixer');
const sourcemaps = require('gulp-sourcemaps');
const rename = require('gulp-rename');
const csso = require('gulp-csso');
const gulpClone = require("gulp-clone");
const touch = require('gulp-touch-fd');
const eventStream = require('event-stream');

const config = {

    /** Browser Sync URL */
    proxyUrl: 'http://roadbikelife.sr',

    /** Changes to those files trigger browser sync to refresh page */
    browserSyncWatchedFiles: [
        './**/template.css'
    ],

    /** Changes to those files trigger gulp task */
    gulpWatchedFiles: [
        './templates/**/*.scss'
    ],

    /** The path of the SCSS file to compile via gulp */
    scssRootFilePath: './templates/custom/sass/template.scss',
    scssAboveFoldRootFilePath: './templates/custom/sass/template_abovefold.scss',

    /** Production CSS file props */
    prodCss: {
        filename: 'template.css',
        basePath: './templates/custom/css'
    },
    /** Production CSS file props */
    prodCssAboveFold: {
        filename: 'template_abovefold.css',
        basePath: './templates/custom/css'
    },

    /** Debug CSS file props */
    debugCss: {
        filename: 'template_debug.css',
        basePath: './templates/custom/css'
    }
};

config.default = config;



// DON'T CHANGE ANYTHING FROM HERE UNLESS NECESSARY
let initBrowserSync = function (done) {
    browserSync.init(config.default.browserSyncWatchedFiles, {
        proxy: config.default.proxyUrl,
        host: '192.168.178.32',
        notify: false,
        online: false,
        ghostMode: {
            clicks: false,
            forms: false,
            scroll: false
        },
        open: false,
        reloadOnRestart: true
    });
    done();
};

let compile = function (done) {
    const css = src(config.default.scssRootFilePath)
        .pipe(sourcemaps.init())
        .pipe(sass({
            onError: browserSync.notify
        }).on('error', sass.logError))
        .pipe(autoprefixer())
        .pipe(csso())
    ;

    const prod = css
        .pipe(gulpClone())
        .pipe(rename(config.default.prodCss.filename))
        .pipe(dest(config.default.prodCss.basePath))
        .pipe(touch())
    ;

    // const debug = css
    //     .pipe(gulpClone())
    //     .pipe(rename(config.default.debugCss.filename))
    //     .pipe(sourcemaps.write('./'))
    //     .pipe(dest(config.default.debugCss.basePath))
    //     .pipe(touch())
    // ;
    // const above_fold = src(config.default.scssRootFilePath)
    //     .pipe(gulpClone())
    //     .pipe(rename(config.default.prodCssAboveFold.filename))
    //     .pipe(sourcemaps.write('./'))
    //     .pipe(dest(config.default.prodCssAboveFold.basePath))
    //     .pipe(touch())
    // ;



    eventStream.merge(prod);
    done();
};

let initWatch = function (done) {
    watch(config.default.gulpWatchedFiles, compile);
    done();
};

exports.default = series(compile, parallel(initBrowserSync, initWatch));
