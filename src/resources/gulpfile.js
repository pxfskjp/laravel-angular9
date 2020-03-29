var gulp = require('gulp');
var sass = require('gulp-sass');
var exec = require('child_process').exec;

//style paths
var sassFile = './sass/app.scss',
    cssDest = '../../public/css';
    materialTheme = './node_modules/@angular/material/prebuilt-themes/indigo-pink.css';

gulp.task('styles', function(){
    var sassGlob = require('gulp-sass-glob');
    return gulp
        .src(sassFile)
        .pipe(sassGlob())
        .pipe(sass({outputStyle: 'compressed'}).on('error', sass.logError))
        .pipe(gulp.dest(cssDest));
});

gulp.task('material', function(){
    return gulp.src(materialTheme)
        .pipe(gulp.dest(cssDest));
});

gulp.task('ngbuild', function (cb) {
    return exec('ng build --prod --localize', function (err, stdout, stderr) {
      console.log(stdout);
      console.log(stderr);
      cb(err);
    });
});

gulp.task('ngbuilddev', function (cb) {
    exec('ng build', function (err, stdout, stderr) {
      console.log(stdout);
      console.log(stderr);
      cb(err);
    });
});

gulp.task('indexCopy', function() {
    return gulp.src('../../public/js/index.blade.php')
        .pipe(gulp.dest('./views'));
});

gulp.task('build', gulp.series(gulp.parallel('styles', 'material', 'ngbuild'), 'indexCopy'));
gulp.task('builddev',  gulp.series(gulp.parallel('styles', 'material', 'ngbuilddev'), 'indexCopy'));
