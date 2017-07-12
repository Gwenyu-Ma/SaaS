/*
    msg
*/
let msg = {
    err: function (title, content, time, callback) {
        title = title ? title : '错误'
        content = content ? content : ''
        time = time ? time : 4000
        this.run('error', title, content, time, callback)
    },
    info: function (title, content, time, callback) {
        title = title ? title : '提示'
        content = content ? content : ''
        time = time ? time : 4000
        this.run('info', title, content, time, callback)
    },
    success: function (title, content, time, callback) {
        title = title ? title : '成功'
        content = content ? content : ''
        time = time ? time : 4000
        this.run('success', title, content, time, callback)
    },
    run: function (type, title, content, time, callback) {
        let html = `<div class="ui modal small ${type} message msg-box"><div class="header">${title}</div><div class="content">${content}</div></div>`
        let dom = $(html)
        let self = this
        $('body').append(dom)
        dom.modal('show', function () {
            let autohide = callback && callback(dom) || true
            if (autohide) {
                window
                    .setTimeout(function () {
                        dom
                            .modal('hide', function () {
                                dom.remove()
                            })
                    }, self.time)
            }
        })
    }
}

module.exports = {
    msg
}
