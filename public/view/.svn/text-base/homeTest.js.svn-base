define(function(require) {
    var tpl = require('text!homeTest.html');
    require('table');
    require('css!table');
    var echarts = require('echarts3');

    var op = {
        chart: {
            self: undefined,
            init: function(view) {
                //操作系统
                var osObj = view.find('#osStack')[0];
                var osChart = echarts.init(osObj);
                //osChart.showLoading();
                $.get('/public/test-data/os.json').done(function(data){
                    osChart.hideLoading();
                    osChart.setOption(data);
                }).fail(function(){
                    console.log('loaded error');
                })
            }
        },
        table: {
            init: function(view) {
                
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
            //op.table.init(view);
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