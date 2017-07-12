define(function(require) {
    return {
        legend: {
            data: ["电话", "短信"],
            y: "bottom",
            itemWidth: 5,
            itemHeight: 12,
            formatter: "{name}"
        },        
        startAngle: 90,
        grid: {
            top: '3%',
            left: '3%',
            right: '4%',
            bottom: '50',
            containLabel: true
        },
        series: [{
            name: "手机",
            type: "pie",
            radius: ["50%", "65%"],
            xAxis: [0],
            yAxis: [0],
            label: {
                normal: {
                    show: false,
                    formatter:function(params){
                        return params.percent+'%';
                    }
                }
            },            
            data: [{
                value: 25,
                name: "电话",
                itemStyle: {
                    normal: {
                        color: "#e47470"
                    }
                },
                label:{
                    normal:{
                        show:true,
                        position:'outside'
                    },
                    emphasis:{
                        show:true,
                        position:'outside'
                    }
                },
                labelLine:{
                    normal: {
                        show:true,
                        length:10,
                        length2:8
                    },
                    emphasis:{
                        show:true
                    }
                },
                hoverAnimation: false
            },{
                value: 75,
                name: "短信",
                itemStyle: {
                    normal: {
                        color: "#616ba7"
                    }
                },
                label:{
                    normal:{
                        show:true,
                        position:'outside'
                    },
                    emphasis:{
                        show:true,
                        position:'outside'
                    }
                },
                labelLine:{
                    normal: {
                        show:true,
                        length:10,
                        length2:8
                    },
                    emphasis:{
                        show:true
                    }
                },
                hoverAnimation: false
            }]

        }]
    }
})
