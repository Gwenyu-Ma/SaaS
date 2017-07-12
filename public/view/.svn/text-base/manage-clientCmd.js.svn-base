define(function(require) {
    var tpl = require('text!manage-clientCmd.html');
    var mustache = require('mustache');

    var op = {
        xav: {
            pid: 'D49170C0-B076-4795-B079-0F97560485AF',
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
                view.on('click', '#xav .dropdown-layer li', function() {
                    cmdName = $(this).closest('li.dropdown-layer').find('>span').text();
                    var cids = ids;
                    if (!cids) {
                        cids = op.xav.getClients(view);
                        if (!cids) {
                            return;
                        }
                    }
                    var btns = $(this).parent();
                    btns.css('display', 'none');
                    RsCore.ajax('Cmd/editCmd', {
                        sguid: cids,
                        productid: op.xav.pid,
                        grouptype: 2,
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
            if (paramStr) {
                RsCore.ajax('Group/getcomputerNameinfo', {
                    sguid: paramStr
                }, function(r) {
                    if (!r) {
                        bootbox.alert('终端不存在 !');
                        return false;
                    }
                    var r = r[0]
                    var id = r.sguid,
                        name = r.computername;
                    var html = mustache.render(tpl, {
                        clientName: ': ' + name
                    });
                    //var html = mustache.render(tpl, {clients: [{cName:name, cId:id}]});
                    view.append(html);
                    op.xav.init(view, [id]);
                    op.android.init(view,[id]);
                });
            } else {
                var clients = RsCore.cache.params.clients;
                delete RsCore.cache.params.clients;
                if (clients && clients.length > 0) {                   
                    var html = mustache.render(tpl, {
                        display: true,
                        clients: clients
                    });
                    view.append(html);
                    op.xav.init(view);
                    op.android.init(view);
                }
            }

        },
        destroy: function() {
            $(this.container).off().empty();
        }
    }
});