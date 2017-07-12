define(function(require) {
    var tpl = require('text!manage-groupCmd.html');
    var mustache = require('mustache');

    var op = {
        xav: {
            pid: 'D49170C0-B076-4795-B079-0F97560485AF',
            init: function(view, id) {
                var cmdName = '命令成功';
                view.on('click', '#xav .dropdown-layer li', function(e) {
                    cmdName = $(this).closest('li.dropdown-layer').find('>span').text();
                    var btns = $(this).parent();
                    btns.css('display', 'none');
                    RsCore.ajax('Cmd/editCmd', {
                        groupid: id,
                        productid: op.xav.pid,
                        grouptype: id == 1 ? 0 : 1,
                        //type: $(this).data('type'),
                        //status: $(this).data('status'),
                        json: getXmlJson($(this).data('type'), $(this).data('status')),
                        cmdid: $(this).data('cmdid')
                    }, function(r) {
                        btns.css('display', '');
                        RsCore.msg.notice(cmdName, '命令已发送至业务中心执行');
                    });

                    function getXmlJson(type, status) {
                        var json = {};
                        switch (type) {
                            case 1:
                                json.scan = {};
                                json.scan.taskname = {};
                                json.scan.taskname["@value"] = 'quickscan';
                                json.scan.control = {};
                                json.scan.control["@value"] = status;
                                break;
                            case 2:
                                json.scan = {};
                                json.scan.taskname = {};
                                json.scan.taskname["@value"] = 'allscan';
                                json.scan.control = {};
                                json.scan.control["@value"] = status;
                                break;
                            case 3:
                                json.monctrl = {};
                                json.monctrl["@value"] = 'filemon:' + status;
                                break;
                            case 4:
                                json.monctrl = {};
                                json.monctrl["@value"] = 'mailmon:' + status;
                                break;
                                break;
                        }
                        return JSON.stringify(json);
                    }
                });
            }
        },
        android:{
            pid: '74F2C5FD-2F95-46be-B67C-FFA200D69012',
            getClients: function(view) {
                var arr = [];
                view.find(':checkbox[name=chkClient]:checked').each(function() {
                    arr.push(this.value)
                });
                if (arr.length == 0) {
                    bootbox.alert('请先选择要下发命令的终端 !');
                    return false;
                }
                return arr;
            },
            init: function(view, ids) {
                var cmdName = '命令成功';
                view.on('click', '#android .dropdown-layer li', function() {
                    cmdName = $(this).closest('li.dropdown-layer').find('>span').text();
                    var cids = ids;
                    if (!cids) {
                        cids = op.android.getClients(view);
                        if (!cids) {
                            return;
                        }
                    }
                    var btns = $(this).parent();
                    btns.css('display', 'none');
                    RsCore.ajax('Cmd/editCmd', {
                        sguid: cids,
                        productid: op.android.pid,
                        grouptype: 2,
                        //type: $(this).data('type'),
                        //status: $(this).data('status'),
                        json: '',
                        cmdid: $(this).data('cmdid')
                    }, function(r) {
                        btns.css('display', '');
                        RsCore.msg.notice(cmdName, '命令已发送至业务中心执行');
                    });
                    
                });
            }
        }
    }

    return {
        container: undefined,
        render: function(container, paramStr) {
            this.container = container;
            var view = $(container);
            var id = paramStr.split('&')[0],
                name = RsCore.cache.group.list[id],
                html = '';
            if (name) {
                html = mustache.render(tpl, {
                    groupName: name
                });
                view.append(html);
            } else {
                return false;
            }

            op.xav.init(view, id);
            op.android.init(view, id);

        },
        destroy: function() {
            $(this.container).off().empty();
        }
    }
});