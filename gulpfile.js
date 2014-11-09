// Project configuration
var project   = 'myfossil';

// Initialization sequence
var gulp      = require('gulp')
  , gutil     = require('gulp-util')
  , plugins   = require('gulp-load-plugins')({ camelize: true })
  , lr        = require('tiny-lr')
  , server    = lr()
  , sass      = require('gulp-sass')
  , build     = './static/'
;

gulp.task('plugins', function() {
  return gulp.src(['assets/src/js/plugins/*.js', 'assets/src/js/plugins.js'])
      .pipe(plugins.concat(project+'-plugins.js'))
      .pipe(gulp.dest('assets/staging'))
      .pipe(plugins.rename({ suffix: '.min' }))
      .pipe(plugins.uglify())
      .pipe(plugins.livereload(server))
      .pipe(gulp.dest(build));
});

gulp.task('scripts-public-single', function() {
  return gulp.src(['assets/src/js/public/single/*.js', '!assets/src/js/plugins.js'])
      .pipe(plugins.sourcemaps.init())
      .pipe(plugins.jshint('.jshintrc'))
      .pipe(plugins.jshint.reporter('default'))
      .pipe(plugins.concat('public-single.js'))
      .pipe(gulp.dest('assets/staging'))
      .pipe(plugins.rename({ suffix: '.min' }))
      //.pipe(plugins.uglify())
      .pipe(plugins.livereload(server))
      .pipe(plugins.sourcemaps.write())
      .pipe(gulp.dest(build + '/js/'));
});

gulp.task('scripts-public-list', function() {
  return gulp.src(['assets/src/js/public/list.js', '!assets/src/js/plugins.js'])
      .pipe(plugins.sourcemaps.init())
      .pipe(plugins.jshint('.jshintrc'))
      .pipe(plugins.concat('public-list.js'))
      .pipe(gulp.dest('assets/staging'))
      .pipe(plugins.rename({ suffix: '.min' }))
      //.pipe(plugins.uglify())
      .pipe(plugins.livereload(server))
      .pipe(plugins.sourcemaps.write())
      .pipe(gulp.dest(build + '/js/'));
});

gulp.task('scripts-public-plugins', function() {
  return gulp.src(['assets/src/js/public/plugins/**/*.js', '!assets/src/js/plugins.js'])
      .pipe(plugins.sourcemaps.init())
      .pipe(plugins.jshint.reporter('default'))
      .pipe(plugins.concat('public-plugins.js'))
      .pipe(gulp.dest('assets/staging'))
      .pipe(plugins.rename({ suffix: '.min' }))
      //.pipe(plugins.uglify())
      .pipe(plugins.livereload(server))
      .pipe(plugins.sourcemaps.write())
      .pipe(gulp.dest(build + '/js/'));
});

gulp.task('scripts-public', ['scripts-public-single', 'scripts-public-list', 'scripts-public-plugins']);

gulp.task('scripts-admin', function() {
  return gulp.src(['assets/src/js/admin/*.js', '!assets/src/js/plugins.js'])
      .pipe(plugins.sourcemaps.init())
      .pipe(plugins.jshint('.jshintrc'))
      .pipe(plugins.jshint.reporter('default'))
      .pipe(plugins.concat('admin.js'))
      .pipe(gulp.dest('assets/staging'))
      .pipe(plugins.rename({ suffix: '.min' }))
      //.pipe(plugins.uglify())
      .pipe(plugins.livereload(server))
      .pipe(plugins.sourcemaps.write())
      .pipe(gulp.dest(build + '/js/'));
});


gulp.task('scripts', ['scripts-public', 'scripts-admin']);

gulp.task('images', function() {
  return gulp.src('assets/src/img/**/*')
      .pipe(plugins.imagemin({ optimizationLevel: 7, progressive: true, interlaced: true }))
      .pipe(plugins.livereload(server))
      .pipe(gulp.dest(build+'img/'));
});

gulp.task('clean', function() {
  return gulp.src(build+'**/.DS_Store', { read: false })
      .pipe(plugins.clean());
});

gulp.task('watch', function() {
  server.listen(35729, function (err) { // Listen on port 35729
    if (err) {
      return console.log(err)
    };
    gulp.watch('assets/src/js/**/*.js', ['plugins', 'scripts']);
    gulp.watch('assets/src/img/**/*', ['images']);
    gulp.watch('public');
    gulp.watch('admin');
    gulp.watch('./**/*.php').on('change', function(file) { plugins.livereload(server).changed(file.path); });
  });
});

gulp.task('build', ['plugins', 'scripts', 'images', 'clean']);
gulp.task('default', ['build', 'watch']);
