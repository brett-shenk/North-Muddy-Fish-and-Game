/**
 * Requires
**/
var gulp = require('gulp');
var notify = require('gulp-notify');
var rename = require('gulp-rename');
var concat = require('gulp-concat');
const { convertAllFonts } = require('@hayes0724/web-font-converter');

// CSS
var sass = require('gulp-sass');
sass.compiler = require('node-sass');
var sourcemaps = require('gulp-sourcemaps');
var autoprefixer = require('autoprefixer');
var postcss = require('gulp-postcss');

// JS
var uglify = require('gulp-uglify');
var babel = require('gulp-babel');

// Handle images
var imagemin = require('gulp-imagemin');
var del = require('del');
var iconfont = require('gulp-iconfont');
var icon_sass = require('gulp-iconfont-css');
var runTimestamp = Math.round(Date.now()/1000);


/**
 * Default Task + Settings
**/
var defaultBuild = 'dev';
const { CONFIG } = require("./gulp/config");


/**
 * Functions
**/
var sassTask = function( compression, entry ) {
    return gulp.src( entry.styles )
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
        .pipe(concat( `${entry.id}.css` ))
        .pipe( rename({
			suffix: '.min'
		}) )
        .pipe( sourcemaps.write( '.' ) )
        .pipe( gulp.dest( CONFIG.dest.styles ) );
}

var sassInlineTask = function( compression ) {
    return gulp.src( CONFIG.inlined.styles )
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
        .pipe( gulp.dest( CONFIG.dest.styles ) );
}

var jsTask = function( compressed, entry ) {
    return gulp.src( entry.js )
        .pipe( sourcemaps.init({ loadMaps: true }) )
        .pipe(babel({
            presets: ['@babel/env']
        }).on('error', notify.onError({
            title: "JS",
            subtitle: "Failure!",
            message: "Error: <%= error.message %>",
        })) )
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
        .pipe(concat( `${entry.id}.js` ))
        .pipe( rename({
            suffix: '.min'
        }) )
        .pipe( sourcemaps.write( '.' ) )
        .pipe( gulp.dest( CONFIG.dest.scripts ) );
}


/**
 * SASS Tasks
**/
gulp.task( 'sass:dev', function(done) {
    var sassCompression = 'compact';
    
    for (let entry of CONFIG.files) {
        if (entry.styles) sassTask( sassCompression, entry );
    }

    sassInlineTask( sassCompression );

    done();
}); // gulp sass:dev

gulp.task( 'sass:prod', function(done) {
    var sassCompression = 'compressed';

    for (let entry of CONFIG.files) {
        if (entry.styles) sassTask( sassCompression, entry );
    }

    sassInlineTask( sassCompression );

    done();
}); // gulp sass:prod


/**
 * JS Tasks
**/
gulp.task( 'uglify:dev', function(done) {
    var jsBeautify = true;
    
    for (let entry of CONFIG.files) {
        if (entry.js && entry.js.length) jsTask(jsBeautify, entry);
    }

    done();
}); // gulp uglify:dev

gulp.task( 'uglify:prod', function(done) {
    var jsBeautify = false;
    
    for (let entry of CONFIG.files) {
        if (entry.js && entry.js.length) jsTask(jsBeautify, entry);
    }

    done();
}); // gulp uglify:prod


/**
 * Font Conversion
**/
gulp.task( 'fonts', function(done) {
    if( CONFIG.font.pathIn.length ){
        convertAllFonts({
            pathIn:        CONFIG.font.pathIn,             // Absolute path only
            pathOut:       CONFIG.font.pathOut,
            outputFormats: CONFIG.font.outputFormats,
            inputFormats:  CONFIG.font.inputFormats,
            debug: false
        }, done());
    }
}); // gulp fonts


/**
 * Compile Icon Font
**/
gulp.task('iconfont', function(){
    return gulp.src( CONFIG.iconFont.folder )
        .pipe(icon_sass({
            fontName:   CONFIG.iconFont.name,
            path:       CONFIG.iconFont.sassInput,
            targetPath: CONFIG.iconFont.sassOutput,
            fontPath:   CONFIG.iconFont.cssPath
        }).on('error', notify.onError({
            title: "FONT",
            subtitle: "Failure!",
            message: "Error: <%= error.message %>",
        })) )
        .pipe(iconfont({
            fontName:           CONFIG.iconFont.name,
            prependUnicode:     true,
            formats:            ['woff2', 'woff', 'ttf', 'svg'],
            timestamp:          runTimestamp,
            normalize:          true,                    // Unifying SVG
            fontHeight:         1001,                    // Unifying SVG
            appendCodepoints:   true,
            copyright:          'Brett Shenk'
        }).on('error', notify.onError({
            title: "FONT",
            subtitle: "Failure!",
            message: "Error: <%= error.message %>",
        })) )
        // Output font info
        // .on('glyphs', function(glyphs, options) {
            // console.log(glyphs, options);
        // })
        .pipe(gulp.dest(CONFIG.dest.fontFolder));
}); // gulp iconfont

gulp.task('svgicon-minify', function () {
    return gulp.src( CONFIG.dest.fontFile )
        .pipe(imagemin([
            imagemin.svgo({
                plugins: [
                    {removeViewBox:     true},
                    {removeComments:    true},
                    {mergeStyles:       true},
                    {inlineStyles:      true},
                    {cleanupIDs:        true},
                    {prefixIds:         false}
                ]
            })
        ]))
        .pipe(gulp.dest(CONFIG.dest.fontFolder));
}); // gulp svgicon-minify


/**
 * Image Tasks
**/
gulp.task('CleanHouse', function(done){
    return del( 'assets/dist/**', '!assets/dist', done );
}); // gulp CleanHouse

gulp.task('removeImages', function(done) {
    return del(CONFIG.dest.images, done);
}); // gulp removeImages

var imageTask = function(entry) {
    return gulp.src( entry.images )
        .pipe(imagemin([
            imagemin.mozjpeg({
                quality: 97,
                progressive: true
            }),
            imagemin.optipng({ optimizationLevel: 1 }),
            imagemin.svgo({
                plugins: [
                    {removeViewBox:     true},
                    {removeComments:    true},
                    {mergeStyles:       true},
                    {inlineStyles:      true},
                    {cleanupIDs:        true},
                    {prefixIds:         false}
                ]
            }),
            imagemin.gifsicle({
                interlaced: false,
                optimizationLevel: 1
            })
        ]))
        .pipe(gulp.dest( CONFIG.dest.images ));
}

gulp.task('images', function(done) {
    for (let entry of CONFIG.files) {
        if (entry.images && entry.images.length) imageTask(entry);
    }

    done();
}); // gulp images


/**
 * Watch Tasks
**/
gulp.task( 'default', function(done) {
    gulp.watch( CONFIG.watch.styles, gulp.series('sass:' + defaultBuild));                      // sass
    gulp.watch( CONFIG.watch.scripts, gulp.series('uglify:' + defaultBuild));                   // js
    gulp.watch( CONFIG.watch.images, gulp.series('images'));                                    // imgs
    gulp.watch( CONFIG.iconFont.sassInput, gulp.series('iconfont'));                            // iconfont
    done();
}); // gulp

gulp.task( 'icons', gulp.series('iconfont', 'svgicon-minify'));                                 // gulp icons

gulp.task( 'dev', gulp.series('iconfont', 'sass:dev', 'uglify:dev', 'removeImages', 'images')); // gulp dev
gulp.task( 'prod', gulp.series('icons', 'sass:prod', 'uglify:prod', 'removeImages', 'images')); // gulp prod
