var gulp = require('gulp'),
    watch = require('gulp-watch'),
    sftp = require('gulp-sftp');

var needwait = false,
    tick = null;

function upload(){
    return gulp.src('./views/**')
        .pipe(sftp({
            host: '192.168.20.171',
            user: 'root',
            pass: 'rising',
            port: '22',
            remotePath: '/rsroot/liuqiang/mgr/public/views',
        }))
}

gulp.task('default',function(){
    console.log('watch views start')
    return watch('./views/**',function(e,s){
        console.log(e,s)
        if(!tick){
            tick = setTimeout(function(){
                upload()
            },10000)
        }else{
            clearTimeout(tick);
            tick = null;
            tick = setTimeout(function(){
                upload()
            },10000)
        }
    })

})
// gulp.watch('./views/**',function(e,s){
//     console.log(e,s)
//     if(!tick){
//         tick = setTimeout(function(){
//             upload()
//         },2000)
//     }else{
//         clearTimeout(tick);
//         tick = null;
//         tick = setTimeout(function(){
//             upload()
//         },2000)
//     }
// }).on('change',function(e){
//     console.log(e)
// })
