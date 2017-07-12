define(function(require) {
    var tpl = require('text!log/pf_bus.html');
    require('selectric');
    require('css!selectric');
    require('datetimepicker');
    require('css!datetimepicker');
    require('table');
    require('css!table');
    //require('echarts/echarts.amd');
    var chartExt = require('echartsExtend');
    //var echart = require('echarts');

    var op = {
        search: {
            params: undefined, //查询条件缓存
            get_params: function(view) {
                return {
                    time: view.find('#selTime').val(),
                    type: view.find('#selType').val(),
                    group: view.find('#selGroup').val()
                };
            },
            init: function(view) {
                var dtd = $.Deferred();
                RsCore.ajax('Group/groupList', function(data) {
                    var arr = [];
                    $.each(data, function(i, group) {
                        arr.push('<option value="' + group.id + '">' + group.groupname + '</option>');
                    });
                    view.find('#selGroup').append(arr.join(''));
                    view.find('select').selectric({
                        inheritOriginalWidth: true
                    });
                    dtd.resolve();
                });
                view.find('#btnSearch').on('click', function() {
                    op.search.params = op.search.get_params(view); // 从新获取查询条件并刷新缓存变量!
                    $('#tb-pf-bus').bootstrapTable('selectPage', 1);
                    //$('#table').bootstrapTable('refresh');
                    //$('#table').bootstrapTable('refresh', {query:{name:$('#txtName').val()}});

                    /*RsCore.ajax('url', op.search.params, function(data) {
            var optionLine = chartExt.Options.Lines(data, 'Y轴 °C', false);
            chartExt.RefreshData(op.chart.self, optionLine);
          });*/
                    if (op.chart.self) {
                        var data = [{
                            name: '星期一',
                            group: '最高气温',
                            value: 11
                        }, {
                            name: '星期二',
                            group: '最高气温',
                            value: 11
                        }, {
                            name: '星期三',
                            group: '最高气温',
                            value: 15
                        }, {
                            name: '星期一',
                            group: '最低气温',
                            value: 1
                        }, {
                            name: '星期二',
                            group: '最低气温',
                            value: 4
                        }, {
                            name: '星期三',
                            group: '最低气温',
                            value: 2
                        }];
                        var optionLine = chartExt.Options.Lines(data, 'Y轴 °C', false);
                        chartExt.RefreshData(op.chart.self, optionLine);
                    }
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
                view.find('#tb-pf-bus').bootstrapTable({
                    url: 'public/test-data/table.json', //RsCore.ajaxPath+'m=home&c=Group&a=groupList',
                    method: 'get',
                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                    queryParams: function(params) {
                        // 改变查询条件时,只要不点查询按钮,无论翻页还是排序,查询条件都不变!
                        //if(!op.search.params) alert(1);
                        !op.search.params && (op.search.params = op.search.get_params(view));
                        $.extend(params, op.search.params);
                        return params;
                    },
                    //striped: true,
                    columns: [{
                        field: 'id',
                        title: 'ID',
                        align: 'left',
                        sortable: true
                    }, {
                        field: 'name',
                        title: 'Name',
                        align: 'left',
                        sortable: true
                    }, {
                        field: 'price',
                        title: 'Price',
                        align: 'left',
                        sortable: true
                        /*,
            formatter: function(value, row, index) {
              return RsCore.assist.unixToDate(row.edate.sec, 'yyyy-MM-dd hh:mm:ss');
            }*/
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
            }
        },
        chart: {
            self: undefined,
            init: function() {
                require(
                    [
                        'echarts',
                        'echarts/chart/bar',
                        'echarts/chart/line'
                    ],
                    function(ec) {
                        var domLine = document.getElementById('chartLine');
                        if (!op.search.params) {
                            op.search.params = op.search.get_params();
                        }
                        /*RsCore.ajax('url', op.search.params, function(data) {
              var optionLine = chartExt.Options.Lines(data, 'Y轴 °C', false);
              chartExt.Render(domLine, ec, optionLine);
            });*/
                        var data = [{
                            name: '星期一',
                            group: '最高气温',
                            value: 11
                        }, {
                            name: '星期二',
                            group: '最高气温',
                            value: 11
                        }, {
                            name: '星期三',
                            group: '最高气温',
                            value: 15
                        }, {
                            name: '星期四',
                            group: '最高气温',
                            value: 13
                        }, {
                            name: '星期五',
                            group: '最高气温',
                            value: 12
                        }, {
                            name: '星期六',
                            group: '最高气温',
                            value: 13
                        }, {
                            name: '星期日',
                            group: '最高气温',
                            value: 10
                        }, {
                            name: '星期一',
                            group: '最低气温',
                            value: 1
                        }, {
                            name: '星期二',
                            group: '最低气温',
                            value: -2
                        }, {
                            name: '星期三',
                            group: '最低气温',
                            value: 2
                        }, {
                            name: '星期四',
                            group: '最低气温',
                            value: 5
                        }, {
                            name: '星期五',
                            group: '最低气温',
                            value: 3
                        }, {
                            name: '星期六',
                            group: '最低气温',
                            value: 2
                        }, {
                            name: '星期日',
                            group: '最低气温',
                            value: 0
                        }];
                        var optionLine = chartExt.Options.Lines(data, 'Y轴 °C', false);
                        op.chart.self = chartExt.Render(domLine, ec, optionLine);
                    }
                );
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
                    //初始化统计图
                    var chartInit = false;
                    view.find('a[data-toggle="tab"]').on('shown', function(e) {
                        if (e.target.hash == '#tab-chart' && !chartInit) {
                            chartInit = true;
                            op.chart.init();
                        }
                    });
                });
        },
        destroy: function() {
            op.search.params = undefined;
            if (op.chart.self) {
                op.chart.self.clear();
                op.chart.self.dispose();
                op.chart.self = undefined;
            };
            $(this.container).find('#date_timepicker_start,#date_timepicker_end').datetimepicker('destroy');
            $(this.container).find('#tb-pf-bus').bootstrapTable('destroy');
            $(this.container).off().empty();
            console.log('destroy pf_bus page');
        }
    }
});