var gulp = require('gulp');
var watch = require('gulp-watch');
var batch = require('gulp-batch');
var rollup = require('rollup').rollup;
var nodeResolve = require('rollup-plugin-node-resolve');

gulp.task('script', function () {
    return rollup({
        entry: 'src/js/main.js',
        plugins: [
            nodeResolve({ jsnext: true })
        ]
    }).then(function (bundle) {
        return bundle.write({
            format: 'iife',
            dest: 'public/assets/bundle.js'
        });
    });
});

gulp.task('watch', function () {
    return gulp.src('src/js/*.js')
        .pipe(watch('src/js/*.js'))
        .pipe(gulp.start('script'));
});
