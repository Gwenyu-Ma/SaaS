define(function(require) {
    return {        
        legend: {
            data: ["在线", "离线"],
            y: "bottom",
            itemWidth: 5,
            itemHeight: 12,
            formatter: "{name}"
        },        
        startAngle: 90,
        series: [{
            name: "pc",
            type: "pie",
            radius: ["40%", "50%"],
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
                "value": 25,
                "name": "在线",
                "itemStyle": {
                    "normal": {
                        "color": "#29bcef"
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
                "value": 75,
                "name": "离线",
                "itemStyle": {
                    "normal": {
                        "color": "#ccc"
                    }
                },
                hoverAnimation: false
            }],
            markPoint: {
                symbol:"image://../public/img/icon/client-center.png",
                label:{
                    normal:{
                        show:true,
                        formatter:"{b}",
                        textStyle:{
                            color:'#29bcef'
                        }
                    }
                },
                data: [
                    { name: "100",x:"50%",y:"55%"}
                ]
                
            }  

        }]
    }
})
