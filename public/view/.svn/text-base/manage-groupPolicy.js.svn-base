define(function(require) {
    var tpl = require('text!manage-groupPolicy.html');
    var mustache = require('mustache');
    require('table');
    require('css!table');

    return {
        container: undefined,
        render: function(container, paramStr) {
            this.container = container;
            view = $(container);
            if (paramStr) {
                var id = paramStr.split('&')[0];
                var name = RsCore.cache.group.list[id];
                var html = '';
                if (name) {
                    /*if(id==RsCore.cache.group.all) {
            html = mustache.render(tpl, {groupId: id,groupName: name});
          } else if(id!=RsCore.cache.group.nogroup) {
            html = mustache.render(tpl, {groupId: id,groupName: name,group: true});
          }*/
                    html = mustache.render(tpl, {
                        groupId: id,
                        groupName: name,
                        group: true
                    });
                    /*
          if(id==RsCore.cache.group.all) {
            html = mustache.render(tpl, {groupId: id,groupName: name});
          } else {
            html = mustache.render(tpl, {groupId: id,groupName: name,group: true});
          }
          */
                    view.append(html);
                } else {
                    return false;
                }
            } else {
                return false;
            }

            RsCore.ajax('Policy/getDisplayProduct', {
                objid: ''+id,
                eid:''+RsCore.cache.group.eid,
                groupType:1
            }, function(r) {
                if (!r) view.find('#btnAddGroupPolicy').css('display', 'none');
            });

            view.find('#tbGroupPolicy').bootstrapTable({
                url: RsCore.ajaxPath + 'Policy/getPolicyList',
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
                    field: 'productname',
                    title: '产品',
                    align: 'left',
                    sortable: true
                }, {
                    field: 'edata',
                    title: '修改时间',
                    align: 'left',
                    sortable: true,
                    formatter: function(value, row, index) {
                        return RsCore.assist.unixToDate(row.edata, 'yyyy-MM-dd hh:mm:ss');
                        //return new Date(parseInt(row.edate.sec) * 1000).toLocaleString().substr(0,17);
                    }
                }, {
                    field: 'op',
                    title: '操作',
                    align: 'right',
                    formatter: function(value, row, index) {
                        return '<a href="#manage/policy?' + id + '&' + row.productid + '_' + row.policytype + '"><i class="fa fa-edit"></i></a> <a name="btnDel" href="#"><i class="fa fa-remove"></i></a>';
                    },
                    events: {
                        'click a[name=btnDel]': function(e, value, row, index) {
                            bootbox.confirm("是否要删除当前策略 ?", function(result) {
                                if (result) {
                                    RsCore.ajax('Policy/delPolicy', {
                                        eid: row.eid,
                                        id: row._id.$id
                                    }, function() {
                                        RsCore.msg.success('策略删除成功 !');
                                        view.find('#tbGroupPolicy').bootstrapTable('refresh');
                                        view.find('#btnAddGroupPolicy').css('display', '');
                                    });
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
            console.log('destroy manage-groupPolicy page');
        }
    }
});