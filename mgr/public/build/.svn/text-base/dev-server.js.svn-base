require('./check-versions')()
var config = require('../config')
if (!process.env.NODE_ENV) process.env.NODE_ENV = JSON.parse(config.dev.env.NODE_ENV)
var path = require('path')
var express = require('express')
var webpack = require('webpack')
var opn = require('opn')
var proxyMiddleware = require('http-proxy-middleware')
var webpackConfig = process.env.NODE_ENV === 'testing' ?
    require('./webpack.prod.conf') :
    require('./webpack.dev.conf')
var bodyParser = require('body-parser')

// default port where dev server listens for incoming traffic
var port = process.env.PORT || config.dev.port
    // Define HTTP proxies to your custom API backend
    // https://github.com/chimurai/http-proxy-middleware
var proxyTable = config.dev.proxyTable

var app = express()
var compiler = webpack(webpackConfig)

var devMiddleware = require('webpack-dev-middleware')(compiler, {
    publicPath: webpackConfig.output.publicPath,
    stats: {
        colors: true,
        chunks: false
    }
})

var hotMiddleware = require('webpack-hot-middleware')(compiler)
    // force page reload when html-webpack-plugin template changes
compiler.plugin('compilation', function(compilation) {
    console.log('compilation done!')
    compilation.plugin('html-webpack-plugin-after-emit', function(data, cb) {
        console.log('html-webpack-plugin-after-emit done')
        hotMiddleware.publish({ action: 'reload' })
        cb()
    })
})

// proxy api requests
Object.keys(proxyTable).forEach(function(context) {
    var options = proxyTable[context]
    if (typeof options === 'string') {
        options = { target: options }
    }
    app.use(proxyMiddleware(context, options))
})

// handle fallback for HTML5 history API
app.use(require('connect-history-api-fallback')())

// serve webpack bundle output
app.use(devMiddleware)

// enable hot-reload and state-preserving
// compilation error display
app.use(hotMiddleware)

app.use(bodyParser.json({
    limit: 'lmb'
}))
app.use(bodyParser.urlencoded({
    extended: true
}))

// serve pure static assets
var staticPath = path.posix.join(config.dev.assetsPublicPath, config.dev.assetsSubDirectory)
app.use(staticPath, express.static('./static'))


app.post('/getData', function(req, res) {
    console.log(req.body.type)
    let obj = {};
    if (req.body.type === 'Manage') {
        obj = {
            datas: [{
                num: 0,
                eid: 123,
                name: '11',
                uname: '1-1',
                online: 20,
                total: 30,
                joindate: '1997',
                lastdate: '2010'

            }, {
                num: 1,
                eid: 456,
                name: '11',
                uname: '1-1',
                online: 20,
                total: 30,
                joindate: '1997',
                lastdate: '2010'

            }]
        }
    }
    if (req.body.type === 'Term') {
        obj = {
            datas: [{
                num: 0,
                eid: 123,
                name: '11',
                online: 20,
                ip: 30,
                mac: '1997',
                ber: '2010',
                sguid: '123465798',
                logdate: '2000',
                date: '30'

            }, {
                num: 0,
                eid: 123,
                name: '11',
                online: 20,
                ip: 30,
                mac: '1997',
                ber: '2010',
                sguid: '123465798',
                logdate: '2000',
                date: '30'
            }]
        }
    }

    res.send(JSON.stringify(obj));
})

module.exports = app.listen(port, function(err) {
    if (err) {
        console.log(err)
        return
    }
    var uri = 'http://localhost:' + port
    console.log('Listening at ' + uri + '\n')

    // when env is testing, don't need open it
    if (process.env.NODE_ENV !== 'testing') {
        opn(uri)
    }
})
