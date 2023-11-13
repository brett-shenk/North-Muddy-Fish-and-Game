/**
 * Requires
**/
var gulp = require('gulp');
var notify = require('gulp-notify');
var rename = require('gulp-rename');

// CSS
var sass = require('gulp-sass')(require('node-sass'));
var sourcemaps = require('gulp-sourcemaps');
var autoprefixer = require('autoprefixer');
var postcss = require('gulp-postcss');

// js
var uglify = require('gulp-uglify');


/**
 * Default Task + Settings
**/
var defaultBuild = 'prod';

var files = {
    css: [
        "assets/*.scss",
        "assets/sass/*.scss",
        "assets/sass/**/*.scss",
        "sass/*.scss",
        "*.scss"
    ],
    js: [
        "assets/*.js",
        "assets/js/*.js",
        "assets/js/**/*.js",
        "js/*.js",
        "*.js",
        "!gulpfile.js"
    ]
}
var dist = [ "dist/" ]


/**
 * Functions
**/
var sassTask = function( compression ) {
    return gulp.src( files.css )
        .pipe( sourcemaps.init({ largeFile: true }) )
        .pipe( sass({
            errLogToConsole: true,
            outputStyle: compression
        }).on('error', notify.onError({
            title: "SASS",
            subtitle: "Failure!",
            message: "Error: <%= error.message %>",
        })) )
        .pipe(postcss([autoprefixer({
            flexbox: "no-2009",
        })]))
        .pipe( rename({
			suffix: '.min'
		}) )
        .pipe( sourcemaps.write( '.' ) )
        .pipe( gulp.dest( dist ) );
}

var jsTask = function( compressed ) {
    return gulp.src( files.js )
        .pipe( sourcemaps.init({ loadMaps: true }) )
        .pipe( uglify({
            output: {
                beautify: compressed,
                comments: compressed,
            }
        }).on('error', notify.onError({
            title: "JS",
            subtitle: "Failure!",
            message: "Error: <%= error.message %>",
        })) )
        .pipe( rename({
            suffix: '.min'
        }) )
        .pipe( sourcemaps.write( '.' ) )
        .pipe( gulp.dest( dist ) );
}


/**
 * SASS Tasks
**/
gulp.task( 'sass:dev', function(done) {
    var sassCompression = 'compact';
    sassTask( sassCompression );

    done();
}); // gulp sass:dev

gulp.task( 'sass:prod', function(done) {
    var sassCompression = 'compressed';
    sassTask( sassCompression );

    done();
}); // gulp sass:prod


/**
 * JS Tasks
**/
gulp.task( 'js:dev', function(done) {
    var jsBeautify = true;
    jsTask(jsBeautify);

    done();
}); // gulp js:dev

gulp.task( 'js:prod', function(done) {
    var jsBeautify = false;
    jsTask(jsBeautify);

    done();
}); // gulp js:prod


/**
 * Watch Tasks
**/
gulp.task( 'default', function(done){
    gulp.watch( files.css, gulp.series('sass:' + defaultBuild));        // sass
    gulp.watch( files.js, gulp.series('js:' + defaultBuild));           // js
    done();
}); // gulp

gulp.task( 'dev', gulp.series('sass:dev', 'js:dev'));               // gulp dev
gulp.task( 'prod', gulp.series('sass:prod', 'js:prod'));            // gulp prod
