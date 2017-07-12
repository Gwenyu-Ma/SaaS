define(function(require) {
    return {
        legend: {
            data: ["病毒", "蠕虫", "广告", "木马", "可疑"],
            y: "bottom",
            itemWidth: 5,
            itemHeight: 12,
            formatter: "{name}"
        },
        tooltip: {
            trigger: "axis",
            axisPointer: {
                type: "shadow"
            }
        },
        xAxis: [{
            type: "category",
            nameLocation: "middle",
            data: ["4月5日", "6","7","8","9","10", "11"],
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
            axisTick: {
                show: false
            },
            splitLine: {
                show: false
            }
        }],
        yAxis: [{
            type: "value",
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
            axisTick: {
                show: false
            },
            splitLine: {
                show: false
            }
        }],
        grid: {
            containLabel: true,
            top:'3%',
            left: "3%",
            right: "3%",
            bottom: "30"
        },
        series: [{
                name: "病毒",
                type: "bar",
                stack: "a",
                data: [800, 100, 200, 500, 50, 500, 50],
                barGap: '60%',
                barCategoryGap: '50%',
                itemStyle: {
                    normal: {
                        color: '#e47470'
                    }
                }
            }, {
                name: "蠕虫",
                stack: "a",
                type: "bar",
                data: [200, 500, 400, 500, 700, 500, 50],
                barGap: '60%',
                barCategoryGap: '50%',
                itemStyle: {
                    normal: {
                        color: '#84cae3'
                    }
                }
            }, {
                name: "广告",
                stack: "a",
                type: "bar",
                data: [200, 100, 400, 500, 700, 500, 50],
                barGap: '60%',
                barCategoryGap: '50%',
                itemStyle: {
                    normal: {
                        color: '#ade488'
                    }
                }
            }, {
                name: "木马",
                stack: "a",
                type: "bar",
                data: [200, 100, 400, 500, 700, 500, 500],
                barGap: '60%',
                barCategoryGap: '50%',
                itemStyle: {
                    normal: {
                        color: '#616ba7'
                    }
                }
            }, {
                name: "可疑",
                stack: "a",
                type: "bar",
                data: [200, 100, 400, 500, 700, 500, 50],
                barGap: '60%',
                barCategoryGap: '50%',
                itemStyle: {
                    normal: {
                        color: '#daba63'
                    }
                }
            }

        ]


    }


})