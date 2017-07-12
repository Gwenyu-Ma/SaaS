define(function(require){
    require('http://api.map.baidu.com/getscript?v=1.4');
    var init;
    init = function(container){
        var map = new BMap.Map(container);
        var point = new BMap.Point(116.404, 39.915);
        map.addControl(new BMap.NavigationControl());    
        map.addControl(new BMap.ScaleControl());    
        //map.addControl(new BMap.OverviewMapControl());    
        //map.addControl(new BMap.MapTypeControl());    
        map.centerAndZoom(point, 16);

        var marker = new BMap.Marker(point);
        map.addOverlay(marker);
    }

    return {
        init:init
    }
})