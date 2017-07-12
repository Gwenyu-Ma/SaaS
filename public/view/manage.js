define(function(require) {
    var tpl = require('text!manage.html');
    require('css!sidemenu');
    var sidemenu = require('sidemenu');
    require('slimscroll'); //side-menu
    var mustache = require('mustache');

    var op = {
        //获取组列表
        getGroupList: function(opt) {
            if (opt && opt.sync) {
                RsCore.ajaxSync('Group/groupListAll', function(data) {
                    callback(data);
                });
            } else {
                RsCore.ajax('Group/groupListAll', function(data) {
                    callback(data);
                });
            };

            function callback(data) {
                $('.side-menu .client-manage').empty();
                var list = [];
                RsCore.cache.group = {};
                //全网ID
                RsCore.cache.group.all = data.rows[0].id;
                RsCore.cache.group.eid = data.rows[0].eid
                RsCore.cache.group.list = {};
                list.push('<li><a href="#manage/client?0">全网计算机</a></li>');
                $(data.rows).each(function(i, group) {
                    RsCore.cache.group.list[group.id] = group.groupname;
                    if (i < 1) {
                        list.push(mustache.render('<li><a href="#manage/client?{{id}}">{{groupname}}</a></li>', group));
                    } else {
                        list.push(mustache.render('<li><a href="#manage/client?{{id}}"><i class="fa fa-edit pull-right"></i> <i class="fa fa-trash-o pull-right"></i><span>{{groupname}}</span></a></li>', group));
                    }
                });
                list.push('<li><a href="#" class="group-add"><i class="fa fa-plus-square"></i> 添加组</a></li>');
                $('.side-menu .client-manage').html(list.join(''));
            };
        }
    };

    return {
        container: undefined,
        render: function(container) {
            this.container = container;
            $(container).append(tpl);
            //初始化左侧菜单
            sidemenu.init($('.side-menu'), '');

            function setScroll() {
                $('.side-menu-scroll').slimScroll({ height: 'auto' });
            };
            setScroll();
            var resizeId;
            $(window).on('resize', function() {
                clearTimeout(resizeId);
                resizeId = setTimeout(function() {
                    setScroll();
                }, 300);
            });
            //同步获取组信息,防止后续页面找不到缓存中的groupList!!
            op.getGroupList({ sync: true });
            //组操作绑定
            $('.client-manage').on('click', 'i.fa-trash-o', function(e) {
                var href = e.target.parentElement.href;
                //var id = href.substring(href.indexOf('?')+1).split('&')[0];
                var hash = href.substring(href.indexOf('#') + 1);
                var id = hash.substring(hash.indexOf('?') + 1).split('&')[0];
                bootbox.confirm('是否删除 [' + RsCore.cache.group.list[id] + '] 组', function(r) {
                    if (r) {
                        RsCore.ajax('Group/delGroup', { id: id }, function() {
                            //window.location.href=$('ul.client-manage>li:eq(0)>a').prop('href');
                            var currentPath = RsCore.getCurrentPath();
                            if (currentPath) {
                                if (/^#manage\/client$/.test(currentPath)) {
                                    window.location.hash = $('ul.client-manage>li:eq(0)>a').attr('href');
                                } else if (/^#manage\/group$/.test(currentPath)) {
                                    $('#c-manage').find('#tbGroup').bootstrapTable('refresh');
                                }
                            }
                            op.getGroupList();
                        });
                    }
                });
                return false;
            }).on('click', 'i.fa-edit', function(e) {
                var href = e.target.parentElement.href;
                //var id = href.substring(href.indexOf('?')+1).split('&')[0];
                var hash = href.substring(href.indexOf('#') + 1);
                var id = hash.substring(hash.indexOf('?') + 1).split('&')[0];
                $('#mGroup #btnSaveGroup').data('id', id);
                RsCore.ajax('Group/getGroup', { id: id }, function(data) {
                    $('#mGroup #mGroupTitle').text('修改组');
                    $('#mGroup modal-body input:text').val('');
                    $('#mGroup .modal-body textarea').val('');
                    $('#mGroup #txtGroupName').val(data.groupname);
                    $('#mGroup #txtGroupDesp').val(data.description);
                    $('#mGroup').modal();
                });
                return false;
            }).on('click', 'a.group-add', function() {
                $('#mGroup #btnSaveGroup').removeData('id');
                $('#mGroup #mGroupTitle').text('添加新组');
                $('#mGroup .modal-body input:text').val('');
                $('#mGroup .modal-body textarea').val('');
                $('#mGroup').modal();
                return false;
            });
            $('#mGroup').on('click', '#btnSaveGroup', function() {
                var id = $(this).data('id');
                if (id) {
                    //编辑
                    var info = {
                        id: id,
                        name: $('#mGroup #txtGroupName').val(),
                        desp: $('#mGroup #txtGroupDesp').val()
                    };
                    RsCore.ajax('Group/editGroup', info, function() {
                        RsCore.msg.success('组信息修改成功');
                        $('#mGroup').modal('hide');
                        var currentPath = RsCore.getCurrentPath();
                        if (currentPath) {
                            if (/^#manage\/group$/.test(currentPath)) {
                                $('#c-manage').find('#tbGroup').bootstrapTable('refresh');
                            }
                        }
                        op.getGroupList();
                    });
                } else {
                    //新增
                    var info = {
                        name: $('#mGroup #txtGroupName').val(),
                        desp: $('#mGroup #txtGroupDesp').val()
                    };
                    RsCore.ajax('Group/editGroup', info, function() {
                        RsCore.msg.success('新增分组成功');
                        $('#mGroup').modal('hide');
                        var currentPath = RsCore.getCurrentPath();
                        if (currentPath) {
                            if (/^#manage\/group$/.test(currentPath)) {
                                $('#c-manage').find('#tbGroup').bootstrapTable('refresh');
                            }
                        }
                        op.getGroupList();
                    });
                }
            });
        },
        destroy: function() {
            $('.client-manage').off();
            $(this.container).off().empty();
            console.log('destroy manage page');
        }
    }
});
