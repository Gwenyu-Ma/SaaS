define(function(require) {
    var tpl = require('text!manage-group.html');
    var mustache = require('mustache');
    require('table');
    require('css!table');
    return {
        container: undefined,
        render: function(container) {
            this.container = container;
            var view = $(container);
            view.append(tpl);

            var op = {
                //刷新终端管理菜单下的组列表
                refreshGroupList: function() {
                    RsCore.ajax('Group/groupListAll', function(data) {
                        $('.side-menu .client-manage').empty();
                        var list = [];
                        RsCore.cache.group = {};
                        //全网ID
                        RsCore.cache.group.all = data.rows[0].id; //data[0].id;
                        RsCore.cache.group.list = {};
                        list.push('<li><a href="#manage/client?{{id}}">全网计算机</a></li>');
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
                    });
                }
            };

            view.on('click', '#btnAdd', function() {
                view.find('#mManageGroup #btnSaveManageGroup').removeData('id');
                view.find('#mManageGroup #mManageGroupTitle').text('添加新组');
                view.find('#mManageGroup .modal-body input:text').val('');
                view.find('#mManageGroup .modal-body textarea').val('');
                view.find('#mManageGroup').modal();
            }).on('click', '#btnSaveManageGroup', function() {
                var id = $(this).data('id');
                if (id) {
                    //编辑
                    var info = {
                        id: id,
                        name: $('#mManageGroup #txtManageGroupName').val(),
                        desp: $('#mManageGroup #txtManageGroupDesp').val()
                    };
                    RsCore.ajax('Group/editGroup', info, function() {
                        RsCore.msg.success('组信息修改成功');
                        $('#mManageGroup').modal('hide');
                        view.find('#tbGroup').bootstrapTable('refresh');
                        op.refreshGroupList();
                    });
                } else {
                    //新增
                    var info = {
                        name: $('#mManageGroup #txtManageGroupName').val(),
                        desp: $('#mManageGroup #txtManageGroupDesp').val()
                    };
                    RsCore.ajax('Group/editGroup', info, function() {
                        RsCore.msg.success('新增分组成功');
                        $('#mManageGroup').modal('hide');
                        view.find('#tbGroup').bootstrapTable('refresh');
                        op.refreshGroupList();
                    });
                }
            });


            view.find('#tbGroup').bootstrapTable({
                url: RsCore.ajaxPath + 'Group/groupList',
                method: 'post',
                contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                responseHandler: function(res) {
                    return res.data || {
                        total: 0,
                        rows: []
                    };
                },
                /*queryParams: function(params) {
          //$.extend(params, {groupid: id});
          return params;
        },*/
                //striped: true,
                columns: [{
                    field: 'groupname',
                    title: '分组',
                    align: 'left',
                    sortable: true,
                    formatter: function(value, row, index) {
                        return '<div>' + value + '</div>';
                    }
                }, {
                    field: 'description',
                    title: '描述',
                    align: 'left',
                    sortable: true,
                    formatter: function(value, row, index) {
                        return value ? value : '';
                    }
                }, {
                    field: 'edate',
                    title: '修改时间',
                    align: 'left',
                    sortable: true,
                    formatter: function(value, row, index) {
                        return RsCore.assist.unixToDate(row.edate, 'yyyy-MM-dd hh:mm:ss');
                        //return new Date(parseInt(row.edate.sec) * 1000).toLocaleString().substr(0,17);
                    }
                }, {
                    field: 'op',
                    title: '操作',
                    align: 'right',
                    formatter: function(value, row, index) {
                        var arr = [];
                        if (row.id == RsCore.cache.group.all) {
                            arr.push(' <a name="btnPolicy" href="#">策略</a>');
                            arr.push(' <a href="#manage/groupCmd?' + row.id + '">命令</a>');
                            return arr.join('');
                        }

                        arr.push(' <a name="btnDel" href="#del" data-toggle="modal">删除</a>');
                        arr.push(' <a name="btnEdit" href="#" data-toggle="modal">编辑</a>');
                        arr.push(' <a name="btnPolicy" href="#">策略</a>');
                        arr.push(' <a href="#manage/groupCmd?' + row.id + '">命令</a>');
                        return arr.join('');
                    },
                    events: {
                        'click a[name=btnDel]': function(e, value, row, index) {
                            bootbox.confirm('是否删除 [' + RsCore.cache.group.list[row.id] + '] 组', function(r) {
                                if (r) {
                                    RsCore.ajax('Group/delGroup', {
                                        id: row.id
                                    }, function() {
                                        view.find('#tbGroup').bootstrapTable('refresh');
                                        op.refreshGroupList();
                                    });
                                }
                            });
                            e.stopPropagation(); //防止事件向上冒泡导致行选中
                            e.preventDefault(); //阻止a标签的默认行为
                        },
                        'click a[name=btnEdit]': function(e, value, row, index) {
                            view.find('#mManageGroup #btnSaveManageGroup').data('id', row.id);
                            RsCore.ajax('Group/getGroup', {
                                id: row.id
                            }, function(data) {
                                $('#mManageGroup #mManageGroupTitle').text('修改组');
                                $('#mManageGroup modal-body input:text').val('');
                                $('#mManageGroup .modal-body textarea').val('');
                                $('#mManageGroup #txtManageGroupName').val(data.groupname);
                                $('#mManageGroup #txtManageGroupDesp').val(data.description);
                                $('#mManageGroup').modal();
                            });
                            e.stopPropagation(); //防止事件向上冒泡导致行选中
                            e.preventDefault(); //阻止a标签的默认行为
                        },
                        'click a[name=btnPolicy]': function(e, value, row, index) {
                            //如果当前组没有策略,则点击组策略按钮直接跳转到新增组策略页面,否则跳转到组策略列表页面
                            RsCore.ajax('Policy/hasPolicy', {
                                groupid: row.id
                            }, function(count) {
                                if (count == 0) {
                                    window.location.hash = '#manage/policy?' + row.id;
                                } else {
                                    window.location.hash = '#manage/groupPolicy?' + row.id;
                                }
                            });
                            e.stopPropagation(); //防止事件向上冒泡导致行选中
                            e.preventDefault(); //阻止a标签的默认行为
                        }
                    }
                }],
                cache: false,
                search: false,
                showToggle: true,
                showRefresh: true,
                showColumns: true,
                pagination: true,
                sidePagination: 'server',
                showPaginationSwitch: false,
                clickToSelect: false,
                onLoadError: function(status) {
                    RsCore.reqTableError(status);
                }
            });
        },
        destroy: function() {
            $(this.container).off().empty();
            console.log('destroy manage-group page');
        }
    }
});