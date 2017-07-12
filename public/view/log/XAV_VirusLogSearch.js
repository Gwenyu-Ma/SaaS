define(function(require) {
    var tpl = require('text!log/XAV_VirusLogSearch.html');
    require('selectric');
    require('css!selectric');
    require('datetimepicker');
    require('css!datetimepicker');
    require('table');
    require('css!table');

    var op = {
        search: {
            params: undefined, //查询条件缓存
            get_params: function(view) {
                var json = {
                    time: view.find('#selTime').val(),
                    virusclass: view.find('#selVirusClass').val(),
                    taskname: view.find('#selVirusSource').val(),
                    treatmethod: view.find('#selProcMode').val(),
                    state: view.find('#selVirusStatus').val(),
                    client: view.find('#txtClient').val(),
                    group: view.find('#selGroup').val()
                };
                if (json.time == 4) {
                    json.starttime = $('#date_timepicker_start').val();
                    json.endtime = $('#date_timepicker_end').val();
                }
                return json;
            },
            init: function(view) {
                var dtd = $.Deferred();
                RsCore.ajax('XAVLog/getResult', function(data) {
                    var arr = [];
                    $.each(data.virusclass, function(i, item) {
                        arr.push('<option value="' + item.value + '">' + item.text + '</option>');
                    });
                    view.find('#selVirusClass').append(arr.join(''));
                    arr = [];
                    $.each(data.taskname, function(i, item) {
                        arr.push('<option value="' + item.value + '">' + item.text + '</option>');
                    });
                    view.find('#selVirusSource').append(arr.join(''));
                    arr = [];
                    $.each(data.treatmethod, function(i, item) {
                        arr.push('<option value="' + item.value + '">' + item.text + '</option>');
                    });
                    view.find('#selProcMode').append(arr.join(''));
                    arr = [];
                    $.each(data.state, function(i, item) {
                        arr.push('<option value="' + item.value + '">' + item.text + '</option>');
                    });
                    view.find('#selVirusStatus').append(arr.join(''));
                    arr = [];
                    $.each(data.group, function(i, item) {
                        arr.push('<option value="' + item.value + '">' + item.text + '</option>');
                    });
                    view.find('#selGroup').append(arr.join(''));
                    view.find('.bar-search select').selectric({
                        inheritOriginalWidth: false
                    });
                    dtd.resolve();
                });
                view.find('#btnSearch').on('click', function() {
                    op.search.params = op.search.get_params(view); // 从新获取查询条件并刷新缓存变量!
                    $('#tb-vlog').bootstrapTable('search');
                    //$('#tb-vlog').bootstrapTable('selectPage', 1);
                    //$('#table').bootstrapTable('refresh');
                    //$('#table').bootstrapTable('refresh', {query:{name:$('#txtName').val()}});

                });
                view.find('#date_timepicker_start,#date_timepicker_end').val(new Date().Format('yyyy-MM-dd hh:mm'));
                var dateInit = false
                view.find('#selTime').on('change', function() {
                    if (this.value == 4) {
                        if (!dateInit) {
                            view.find('#date_timepicker_start,#date_timepicker_end').datetimepicker({
                                format: 'Y-m-d H:i',
                                timepicker: true
                            });
                            dateInit = true;
                        }
                        view.find('#panelDate').css('display', '');
                    } else {
                        view.find('#panelDate').css('display', 'none');
                    }
                });
                return dtd.promise();
            }
        },
        table: {
            init: function(view) {
                view.find('#tb-vlog').bootstrapTable({
                    url: RsCore.ajaxPath + 'Xavlog/ScanResultList', //RsCore.ajaxPath+'m=home&c=Group&a=groupList',
                    method: 'post',
                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                    queryParams: function(params) {
                        // 改变查询条件时,只要不点查询按钮,无论翻页还是排序,查询条件都不变!
                        //if(!op.search.params) alert(1);
                        !op.search.params && (op.search.params = op.search.get_params(view));
                        $.extend(params, op.search.params);
                        return params;
                    },
                    //striped: true,
                    columns: [
                        /*{
            field: 'time',
            title: '时间',
            align: 'left',
            sortable: true,
            formatter: function(value, row, index) {
              return RsCore.assist.unixToDate(row.edate.sec, 'yyyy-MM-dd hh:mm:ss');
            }
          }, */
                        {
                            field: 'virusname',
                            title: '病毒名称',
                            align: 'left',
                            sortable: true
                        }, {
                            field: 'virusclass',
                            title: '病毒分类',
                            align: 'left',
                            sortable: true
                        }, {
                            field: 'filepath',
                            title: '病毒文件',
                            align: 'left',
                            sortable: true
                        }, {
                            field: 'taskname',
                            title: '病毒来源',
                            align: 'left',
                            sortable: true
                        }, {
                            field: 'treatmethod',
                            title: '处理方式',
                            align: 'left',
                            sortable: true
                        }, {
                            field: 'state',
                            title: '病毒状态',
                            align: 'left',
                            sortable: true
                        }
                    ],
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
            }
        }
    };

    return {
        container: undefined,
        render: function(container) {
            this.container = container;
            var view = $(container);
            view.append(tpl);

            $.when(op.search.init(view))
                .done(function() {
                    //初始化表格
                    op.table.init(view);
                });
        },
        destroy: function() {
            op.search.params = undefined;
            $(this.container).find('#date_timepicker_start,#date_timepicker_end').datetimepicker('destroy');
            $(this.container).find('#tb-vlog').bootstrapTable('destroy');
            $(this.container).off().empty();
        }
    }
});