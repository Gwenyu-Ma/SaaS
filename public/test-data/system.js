define(function(require) {
    return {
        tooltip:{
            trigger:"axis",
            axisPointer:{
                type:"shadow"
            }
        },
        xAxis:[
            {
                type:"category",
                nameLocation:"middle",
                splitArea:{
                    areaStyle:{
                        color:["#616ba7","#616ba7", "#616ba7", "#616ba7", "#616ba7","#616ba7"]
                    }
                },
                data:["Windows XP","Windows 7","Windows Server 2008","Android","Linux","其他"],
                axisLabel:{
                    interval:0,
                    textStyle:{
                        color:'#ccc'
                    },
                    formatter:function(value){
                        if(value.length>7){
                            return $.trim(value.substring(0,7))+'\n'+$.trim(value.substring(7));
                        }else{
                            return value;
                        }
                    }
                },
                axisLine: {
                    lineStyle: {
                        color: '#ccc'
                    }
                },
                axisTick:{
                    show:false
                },
                splitLine:{
                    show:false
                }
            }
        ],
        yAxis:[
            {
                type:"value",
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
            }
        ],
        grid:{
            containLabel:true,
            top: '3%',
            left:"3%",
            right:"3%",
            bottom:"5%"
        },
        series:[
            {
                name:"总数",
                type:"bar",
                data:[8,10,2,5,0,0],
                label:{
                    normal:{
                        show:true
                    }
                },
                itemStyle:{
                    normal:{
                        color:'#616ba7'
                    }
                }
            }
        ]

        
    }

})
