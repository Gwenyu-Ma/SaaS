define(function(require){
    var tpl = require('text!homeDemo.html');
    require('css!sidemenu');    
    var echarts = require('echarts3');
    var sidemenu = require('sidemenu');
    require('dep/dragsort/jquery.dragsort-0.5.2');
    require('dep/jquery.cookie');
    require('dep/echarts3/map/china');
    var opt = {
        echartObj:[],
        init:function(view){
            function saveOrder(){
                var items = [];
                view.find('.gridly > div').map(function() { items.push($(this).attr('sortNum')) });
                $.cookie('layout',items.join('-'));
            }
            view.find('.gridly').dragsort({
                dragSelector:".gridly > div .head h4",
                itemSelector:".gridly > div",
                dragBetween:true,
                dragEnd:saveOrder,
                placeHolderTemplate:"<div class='placeDrag'></div>"                
            })
            sidemenu.init($('.gridly'), '');
        },
        sort:function(view,sort){
            if(sort.length){
                var gridly= view.find('.gridly');
                var objs = gridly.find(' > div');
                gridly.empty();
                for(var i=0;i<sort.length;i++){
                    gridly.append(objs[sort[i]]);
                }
            }
            this.initChart(view);
        },
        initChart:function(view){
            var chart = view.find('#chart')[0];
            var _chart = echarts.init(chart);
            _chart.showLoading();
            $.get('/public/test-data/map.json').done(function(data){
                _chart.hideLoading();
                _chart.setOption(data);
            }).fail(function(){
                console.log('loaded error');
            })
            var chart1 = view.find('#chart1')[0];
            var _chart1 = echarts.init(chart1);
            _chart1.showLoading();
            $.get('/public/test-data/os2.json').done(function(data){
                _chart1.hideLoading();
                _chart1.setOption(data);
            }).fail(function(){
                console.log('loaded error');
            })
            var chart2 = view.find('#chart2')[0];
            var _chart2 = echarts.init(chart2);
            _chart2.showLoading();
            $.get('/public/test-data/pie.json').done(function(data){
                _chart2.hideLoading();
                _chart2.setOption(data);
            }).fail(function(){
                console.log('loaded error');
            })
            var chart3 = view.find('#chart3')[0];
            var _chart3 = echarts.init(chart3);
            _chart3.showLoading();
            $.get('/public/test-data/os3.json').done(function(data){
                _chart3.hideLoading();
                _chart3.setOption(data);
            }).fail(function(){
                console.log('loaded error');
            })
            var chart4 = view.find('#chart4')[0];
            var _chart4 = echarts.init(chart4);
            window.char = _chart4;
            _chart4.showLoading();
            $.get('/public/test-data/nest.json').done(function(data){
                _chart4.hideLoading();
                _chart4.setOption(data);
                _chart4.setOption({
                    toolbox:{
                        show: true,
                        feature: {
                            dataView: {show: true, readOnly: false},
                            magicType: {show: true, type: ['line', 'bar']},
                            restore: {show: true},
                            saveAsImage: {show: true}
                        }
                    }
                })
            }).fail(function(){
                console.log('loaded error');
            })
            var chart5 = view.find('#chart5')[0];
            var _chart5 = echarts.init(chart5);
            _chart5.showLoading();
            $.get('/public/test-data/os2.json').done(function(data){
                _chart5.hideLoading();
                _chart5.setOption(data);
            }).fail(function(){
                console.log('loaded error');
            })
            var chart6 = view.find('#chart6')[0];
            var _chart6 = echarts.init(chart6);
            _chart6.showLoading();
            $.get('/public/test-data/radar.json').done(function(data){
                _chart6.hideLoading();
                _chart6.setOption(data);
            }).fail(function(){
                console.log('loaded error');
            })
            var chart7 = view.find('#chart7')[0];
            var _chart7 = echarts.init(chart7);
            _chart7.showLoading();
            $.get('/public/test-data/os.json').done(function(data){
                _chart7.hideLoading();
                _chart7.setOption(data);
            }).fail(function(){
                console.log('loaded error');
            })
            var chart8 = view.find('#chart8')[0];
            var _chart8 = echarts.init(chart8);
            _chart8.showLoading();
            $.get('/public/test-data/os.json').done(function(data){
                _chart8.hideLoading();
                _chart8.setOption(data);
            }).fail(function(){
                console.log('loaded error');
            })
            this.echartObj = [_chart,_chart1,_chart2,_chart3,_chart4,_chart5,_chart6,_chart7,_chart8];
        }
    }
    return {
        container: undefined,
        render: function(container) {
            this.container = container;
            var view = $(container);
            view.append(tpl);
            var sort = $.cookie('layout')?$.cookie('layout').split('-'):[];
            opt.sort(view,sort);
            opt.init(view);

            view.on('click','.chart_opt',function(){
                var that = $(this);
                if(that.hasClass('active')){
                    that.removeClass('active');
                    that.find('> span i').removeClass('fa-chevron-up').addClass('fa-chevron-down');
                }else{
                    that.addClass('active');
                    that.find('> span i').removeClass('fa-chevron-down').addClass('fa-chevron-up');
                }
            })
            view.on('click','.js_downAsImg',function(){
                var fa = $(this).closest('[sortNum]');
                var chart = opt.echartObj[fa.attr('sortNum')];
                var a = document.createElement('a');
                a.download = 'download.png';
                a.target = '_blank';
                var url = chart.getConnectedDataURL();
                a.href = url;
                if (typeof MouseEvent === 'function') {
                    var evt = new MouseEvent('click', {
                        view: window,
                        bubbles: true,
                        cancelable: false
                    });
                    a.dispatchEvent(evt);
                }
                // IE
                else {
                    var html = ''
                        + '<body style="margin:0;">'
                        + '<img src="' + url + '" style="max-width:100%;" title="" />'
                        + '</body>';
                    var tab = window.open();
                    tab.document.write(html);
                }
            })

           
        },
        destroy: function() {            
            $(this.container).off().empty();
            opt.echartObj
            for(var i=opt.echartObj.length-1;i>0;i--){
                opt.echartObj[i].dispose();
            }
        }
    }
})