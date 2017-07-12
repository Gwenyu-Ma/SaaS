define(function(require) {
    return { 
        tooltip: {
            trigger: 'axis'
        },
        legend: {
            data:[
                {
                    name:'病毒',
                    icon:'image://../public/img/echarts/threat-ico01.png'
                },{
                    name:'网址',
                    icon:'image://../public/img/echarts/threat-ico02.png'
                },{
                    name:'骚扰',
                    icon:'image://../public/img/echarts/threat-ico03.png'
                },{
                    name:'联网',
                    icon:'image://../public/img/echarts/threat-ico04.png'
                }
            ],
            bottom:10,
            itemHeight:15,
            itemWidth:10
        },
        grid: {
            top: '3%',
            left: '3%',
            right: '4%',
            bottom: '50',
            containLabel: true
        },
        xAxis: {
            type: 'category',
            boundaryGap: false,
            data: ['4月25',26,27,28,29,30,1],
            axisLine: {
                lineStyle: {
                    color: '#ccc'
                }
            },
            axisLabel:{
                textStyle:{
                    color:'#ccc'
                }
            },
            axisTick:{
                show:false
            },
            splitLine:{
                show:false
            }
        },
        yAxis: {
            type: 'value',
            axisLine: {
                lineStyle: {
                    color: '#ccc'
                }
            },
            axisLabel:{
                textStyle:{
                    color:'#ccc'
                }
            },
            axisTick:{
                show:false
            },
            splitLine:{
                show:false
            }
        },
        series: [
            {
                name:'病毒',
                type:'line',
                stack: '总量',
                data:[5, 2, 0, 0, 7, 10, 8],
                lineStyle:{
                    normal:{
                        color:'#e47470'
                    }
                }
            },
            {
                name:'网址',
                type:'line',
                stack: '总量',
                data:[8, 10, 2, 5, 0, 0, 6],
                lineStyle:{
                    normal:{
                        color:'#29bcef'
                    }
                }
            },
            {
                name:'骚扰',
                type:'line',
                stack: '总量',
                data:[0, 7, 7, 0, 9, 6, 2],
                lineStyle:{
                    normal:{
                        color:'#616ba7'
                    }
                }
            },
            {
                name:'联网',
                type:'line',
                stack: '总量',
                data:[8, 0, 0, 7, 3, 3, 2],
                lineStyle:{
                    normal:{
                        color:'#7db53a'
                    }
                }
            }
        ]
    }
})
