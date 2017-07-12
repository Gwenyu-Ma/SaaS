define(function(require) {
    var tpl = require('text!home.html');
    var chartExt = require('echartsExtend');
    require('table');
    require('css!table');

    var op = {
        chart: {
            self: undefined,
            init: function(view) {
                require(
                    [
                        'echarts',
                        'echarts/chart/pie'
                    ],
                    function(ec) {
                        var domPie = view.find('#chartPie')[0];
                        var data = [{
                            name: '中毒',
                            value: 11
                        }, {
                            name: '健康',
                            value: 10
                        }];
                        // 获取饼图配置信息
                        var optionPie = chartExt.Options.Pie(data);
                        //修改饼图配置信息
                        delete optionPie.toolbox; //删除工具栏
                        //delete optionPie.tooltip;//删除浮动信息
                        optionPie.legend.orient = 'horizontal';
                        optionPie.legend.x = 'center';
                        optionPie.legend.y = 'bottom';
                        /*optionPie.title = {
              text: '最近7天健康情况',
              x: 'center'
            };*/
                        optionPie.series[0].itemStyle = {
                            normal: {
                                label: {
                                    show: true,
                                    //formatter: '{b} : {c} ({d}%)'
                                    formatter: '{b} : {c}\n百分比 : {d}%'
                                },
                                labelLine: {
                                    show: true
                                }
                            }
                        };

                        //初始化饼图
                        op.chart.self = chartExt.Render(domPie, ec, optionPie);
                        //窗口大小变化,自动重绘
                        $(window).on('resize', function() {
                            op.chart.self.resize();
                        });
                    }
                );
            }
        },
        table: {
            init: function(view) {
                view.find('#tbVirusInfo').bootstrapTable({
                    url: '/public/test-data/index-virus-info.json', //RsCore.ajaxPath+'m=home&c=Group&a=groupList',
                    method: 'get',
                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                    striped: false,
                    columns: [{
                        field: 'virusname',
                        title: '病毒名称',
                        align: 'left',
                        sortable: false
                    }, {
                        field: 'virusamount',
                        title: '感染机器数量',
                        align: 'center',
                        sortable: false
                    }],
                    cache: false,
                    search: false,
                    showToggle: false,
                    showRefresh: false,
                    showColumns: false,
                    pagination: true,
                    sidePagination: 'server',
                    showPaginationSwitch: false,
                    clickToSelect: false,
                    pageSize: 10,
                    onLoadError: function(status) {
                        RsCore.reqTableError(status);
                    }
                });

                view.find('#tbVirusScan').bootstrapTable({
                    url: '/public/test-data/index-virus-scan.json',
                    method: 'get',
                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                    striped: false,
                    columns: [{
                        field: 'clientname',
                        title: '客户端名称',
                        align: 'left',
                        sortable: false
                    }, {
                        field: 'scanaobjectnum',
                        title: '扫描对象数',
                        align: 'center',
                        sortable: false
                    }, {
                        field: 'findnum',
                        title: '发现病毒数',
                        align: 'center',
                        sortable: false
                    }, {
                        field: 'processnum',
                        title: '处理病毒数',
                        align: 'center',
                        sortable: false
                    }, {
                        field: 'filenum',
                        title: '遍历文件数',
                        align: 'center',
                        sortable: false
                    }, {
                        field: 'scanfilenum',
                        title: '扫描文件数',
                        align: 'center',
                        sortable: false
                    }],
                    cache: false,
                    search: false,
                    showToggle: false,
                    showRefresh: false,
                    showColumns: false,
                    pagination: true,
                    sidePagination: 'server',
                    showPaginationSwitch: false,
                    clickToSelect: false,
                    onLoadError: function(status) {
                        RsCore.reqTableError(status);
                    }
                });

                view.find('#tbClientList').bootstrapTable({
                    url: '/public/test-data/index-client-list.json',
                    method: 'get',
                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                    striped: false,
                    columns: [{
                        field: 'clientname',
                        title: '客户端名称',
                        align: 'left',
                        sortable: false
                    }, {
                        field: 'online',
                        title: '在线情况',
                        align: 'center',
                        sortable: false
                    }, {
                        field: 'health',
                        title: '最近7天健康情况',
                        align: 'center',
                        sortable: false
                    }],
                    cache: false,
                    search: false,
                    showToggle: false,
                    showRefresh: false,
                    showColumns: false,
                    pagination: false,
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
            //初始化饼图
            op.chart.init(view);
            //初始化表格
            op.table.init(view);
        },
        destroy: function() {
            if (op.chart.self) {
                $(window).off('resize');
                op.chart.self.clear();
                op.chart.self.dispose();
                op.chart.self = undefined;
            };
            $(this.container).off().empty();
        }
    }
});