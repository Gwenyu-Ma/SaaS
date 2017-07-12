define(function(require) {
    var tpl = require('text!manage-client.html');
    var mustache = require('mustache');
    require('table');
    require('css!table');
    return {
        container: undefined,
        render: function(container, paramStr) {
            this.container = container;
            var view = $(container);
            if (paramStr) {
                var id = paramStr.split('&')[0];
                var name = RsCore.cache.group.list[id];
                var html = '';
                if (id == 0) {
                    name = "全网计算机";
                    html = mustache.render(tpl, {
                        groupId: '',
                        groupName: name,
                        showHeader: false
                    });
                    view.append(html);
                } else {
                    if (name) {
                        html = mustache.render(tpl, {
                            groupId: id,
                            groupName: name,
                            group: true,
                            remove: true,
                            showHeader: true
                        });
                        view.append(html);
                        //如果当前组没有策略,则点击组策略按钮直接跳转到新增组策略页面,否则跳转到组策略列表页面
                        RsCore.ajax('Policy/hasPolicy', {
                            groupid: id
                        }, function(count) {
                            if (count == 0) {
                                view.find('.content-header a:eq(0)').prop('href', function(i, val) {
                                    return val.replace('groupPolicy', 'policy');
                                });
                            }
                        });
                    } else {
                        return false;
                    }
                }

            } else {
                return false;
            }

            view.find('#tbClient').bootstrapTable({
                url: RsCore.ajaxPath + 'Group/getgroupComputer',
                method: 'post',
                contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                queryParams: function(params) {
                    $.extend(params, {
                        groupid: id
                    });
                    return params;
                },
                responseHandler: function(res) {
                    return res.data || {
                        total: 0,
                        rows: []
                    };
                },
                //striped: true,
                columns: [{
                    field: 'state',
                    checkbox: true
                }, {
                    field: 'computername',
                    title: '计算机名称',
                    align: 'left',
                    sortable: true,
                    formatter: function(value, row, index) {
                        return '<a href="#manage/clientDetail?' + row.sguid + '&'+id+'">' + (value ? value : '未知') + '</a>';
                    }
                }, {
                    field: 'ip',
                    title: 'IP地址',
                    align: 'left',
                    sortable: true
                }, {
                    field: 'version',
                    title: '版本',
                    align: 'left',
                    sortable: true
                }, {
                    field: 'os',
                    title: '操作系统',
                    align: 'left',
                    sortable: true,
                    formatter: function(value, row, index) {
                        if (value) {
                            if (value.length > 30) {
                                value = value.substring(0, 30) + ' ...';
                            }
                        } else {
                            value = "未知";
                        }

                        return value;
                    }
                }, {
                    field: 'groupname',
                    title: '所在组',
                    align: 'left',
                    sortable: true
                }, {
                    field: 'op',
                    title: '操作',
                    align: 'right',
                    formatter: function(value, row, index) {
                        //return '<button class="btn btn-mini btn-success"><i class="icon-edit icon-white"></i></button> <button class="btn btn-mini btn-danger"><i class="icon-trash icon-white"></i></button>';
                        //return '<a href="#manage/clientPolicy?'+row.sguid+'">策略</a> <a href="#">命令</a>';
                        return '<a href="#" name="btnPolicy">策略</a> <a href="#manage/clientCmd?' + row.sguid + '">命令</a>';
                    },
                    events: {
                        'click a[name=btnPolicy]': function(e, value, row, index) {
                            //如果当前组没有策略,则点击组策略按钮直接跳转到新增组策略页面,否则跳转到组策略列表页面
                            RsCore.ajax('Policy/hasPolicy', {
                                sguid: row.sguid
                            }, function(count) {
                                if (count == 0) {
                                    window.location.hash = '#manage/policy?&&' + row.sguid;
                                } else {
                                    window.location.hash = '#manage/clientPolicy?' + row.sguid;
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

            view.on('click', '#btnMoveTo', function() {
                //alert(JSON.stringify($('#table').bootstrapTable('getSelections')));
                var clients = [],
                    ids = [];
                $(view.find('#tbClient').bootstrapTable('getSelections')).each(function(i, item) {
                    clients.push(item.computername);
                    ids.push(item.sguid);
                });
                if (ids.length == 0) {
                    bootbox.alert('请先选择要移动的计算机 !');
                    return false;
                }
                view.find('#txtClient').val(clients.join(', '));
                view.find('#btnSaveMoveTo').data('ids', ids);
                RsCore.ajax('Group/groupListAll', {
                    t: 1
                }, function(groups) {
                    var html = [];
                    var rows = groups.rows;
                    $(rows).each(function(i, row) {
                        html.push('<label class="radio"><input type="radio" name="radioGroup" value="' + row.id + '">' + row.groupname + '</label>');
                    });
                    view.find('#targetGroup').html(html.join(''));
                });
                view.find('#mMoveTo').modal();
            });
            view.on('click', '#btnRemove', function() {
                var clients = view.find('#tbClient').bootstrapTable('getSelections');
                if (clients.length == 0) {
                    bootbox.alert('请先选择要移除的终端 !')
                    return false;
                }
                var arr = [];
                $.each(clients, function(i, item) {
                    arr.push(item.sguid);
                });
                //var ids = arr.join(',');
                RsCore.ajax('Group/delClient', {
                    clients: arr,
                    group: RsCore.cache.group.nogroup
                }, function() {
                    RsCore.msg.success('终端移除成功 !');
                    $(container).find('#tbClient').bootstrapTable('refresh');
                });
            });
            view.on('click', '#btnSaveMoveTo', function() {
                //var ids = $(this).data('ids').join(','),
                groupId = $(container).find(':radio[name=radioGroup]:checked').val();
                if (groupId) {
                    RsCore.ajax('Group/moveComputer', {
                        clients: $(this).data('ids'),
                        group: groupId
                    }, function() {
                        RsCore.msg.success('终端移动成功 !');
                        $(container).find('#tbClient').bootstrapTable('refresh');
                        $('#mMoveTo').modal('hide');
                    });
                } else {
                    bootbox.alert('请先选择目标组 !');
                }
            });
            view.on('click', '#btnClientPolicy', function() {
                var arr = $('#tbClient').bootstrapTable('getSelections');
                if (arr.length == 0) {
                    bootbox.alert('请先选择要下发命令的终端 !')
                    return false;
                }
                var clients = [];
                $.each(arr, function(i, client) {
                    clients.push({
                        cName: client.computername,
                        cId: client.sguid
                    });
                });
                RsCore.cache.params.clients = clients;
            });

            /*搜索*/
            view.on('click', '#tableSearch', function() {
                var params = {
                    name: view.find('#search_name').val() || '',
                    ip: view.find('#search_ip').val() || '',
                    mac: view.find('#search_mac').val() || '',
                    sys: view.find('#search_sys').val() || '',
                    version: view.find('#search_ver').val() || ''
                };
                $(container).find('#tbClient').bootstrapTable('refresh', {
                    query: params
                });
            })

        },
        destroy: function() {
            $(this.container).find('#tbClient').bootstrapTable('destroy');
            $(this.container).off().empty();
            console.log('destroy manage-client page');
        }
    }
});
