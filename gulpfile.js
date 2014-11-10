// Project configuration
var project   = 'myfossil';

// Initialization sequence
var gulp      = require('gulp')
  , gutil     = require('gulp-util')
  , plugins   = require('gulp-load-plugins')({ camelize: true })
  , argv      = require('yargs').argv
  , gulpif    = require('gulp-if')
  , lr        = require('tiny-lr')
  , server    = lr()
  , build     = './static/'
;

gulp.task('init', function() {
    if ( ! argv.production ) {
    }
});

gulp.task('scripts-public-single', function() {
  return gulp.src(['assets/src/js/public/single/*.js', '!assets/src/js/plugins.js'])
      .pipe(gulpif( ! argv.production, plugins.sourcemaps.init()))
      .pipe(gulpif( ! argv.production, plugins.jshint('.jshintrc')))
      .pipe(plugins.concat('public-single.js'))
      .pipe(gulp.dest('assets/staging'))
      .pipe(plugins.rename({ suffix: '.min' }))
      .pipe(gulpif(argv.production, plugins.uglify()))
      .pipe(gulpif( ! argv.production, plugins.sourcemaps.write()))
      .pipe(gulp.dest(build + '/js/'));
});

gulp.task('scripts-public-list', function() {
  return gulp.src(['assets/src/js/public/list.js', '!assets/src/js/plugins.js'])
      .pipe(gulpif( ! argv.production, plugins.sourcemaps.init()))
      .pipe(gulpif( ! argv.production, plugins.jshint('.jshintrc')))
      .pipe(plugins.concat('public-list.js'))
      .pipe(gulp.dest('assets/staging'))
      .pipe(plugins.rename({ suffix: '.min' }))
      .pipe(gulpif(argv.production, plugins.uglify()))
      .pipe(gulpif( ! argv.production, plugins.sourcemaps.write()))
      .pipe(gulp.dest(build + '/js/'));
});

gulp.task('scripts-public-plugins', function() {
  return gulp.src(['assets/src/js/public/plugins/jquery-file-upload/jquery.ui.widget.js', 'assets/src/js/public/plugins/**/*.js', '!assets/src/js/plugins.js'])
      .pipe(gulpif( ! argv.production, plugins.sourcemaps.init()))
      .pipe(gulpif( ! argv.production, plugins.jshint('.jshintrc')))
      .pipe(plugins.concat('public-plugins.js'))
      .pipe(gulp.dest('assets/staging'))
      .pipe(plugins.rename({ suffix: '.min' }))
      .pipe(gulpif(argv.production, plugins.uglify()))
      .pipe(gulpif( ! argv.production, plugins.sourcemaps.write()))
      .pipe(gulp.dest(build + '/js/'));
});

gulp.task('scripts-public', ['scripts-public-single', 'scripts-public-list', 'scripts-public-plugins']);

gulp.task('scripts-admin', function() {
  return gulp.src(['assets/src/js/admin/*.js', '!assets/src/js/plugins.js'])
      .pipe(gulpif( ! argv.production, plugins.sourcemaps.init()))
      .pipe(gulpif( ! argv.production, plugins.jshint('.jshintrc')))
      .pipe(plugins.concat('admin.js'))
      .pipe(gulp.dest('assets/staging'))
      .pipe(plugins.rename({ suffix: '.min' }))
      .pipe(gulpif(argv.production, plugins.uglify()))
      .pipe(gulpif( ! argv.production, plugins.sourcemaps.write()))
      .pipe(gulp.dest(build + '/js/'));
});


gulp.task('scripts', ['scripts-public', 'scripts-admin']);

gulp.task('clean', function() {
  return gulp.src(build+'**/.DS_Store', { read: false })
      .pipe(plugins.clean());
});

gulp.task('watch', function() {
  server.listen(35729, function (err) { // Listen on port 35729
    if (err) {
      return console.log(err)
    };
    gulp.watch('assets/src/js/**/*.js', ['scripts']);
    gulp.watch('**/*.php').on('change', function(file) { 
        plugins.livereload(server).changed(file.path); 
    });
  });
});

gulp.task('build', ['init', 'scripts', 'clean']);
gulp.task('default', ['build', 'watch']);
