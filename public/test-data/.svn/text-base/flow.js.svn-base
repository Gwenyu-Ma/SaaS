define(function(require) {
    return {        
        legend: {
            data: ['上行流量', '下行流量'],
            bottom:10
        },
        grid: {
            top: '3%',
            left: '3%',
            right: '4%',
            bottom: '30',
            containLabel: true
        },
        xAxis:  {
            axisLine:{
                show:false
            },
            axisTick:{
                show:false
            },
            axisLabel:{
                show:false
            },
            splitLine:{
                show:false
            }
        },
        yAxis: {
            type: 'category',
            inverse:true,
            data: [
                {
                    value:'1.盈守宝'
                },{
                    value:'2.   守宝'
                },{
                    value:'3.       宝'
                },{
                    value:'4.盈守宝'
                },{
                    value:'5.盈守宝'
                }
            ],
            axisLine:{
                show:false
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
                name: '上行流量',
                type: 'bar',
                stack: '总量',
                label: {
                    normal: {
                        show: true,
                        position: 'inside',
                        formatter:function(params){
                            return params.value+'M';
                        }
                    }
                },
                data: [320, 200, 190, 180, 170]
            },
            {
                name: '下行流量',
                type: 'bar',
                stack: '总量',
                label: {
                    normal: {
                        show: true,
                        position: 'inside',
                        formatter:function(params){
                            return params.value+'M';
                        }
                    }
                },
                data: [200, 180, 160, 60, 30]
            }
        ]
    };
})
