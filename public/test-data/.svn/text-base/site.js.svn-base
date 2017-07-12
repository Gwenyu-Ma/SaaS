define(function(require) {
    return {
        radar: {
            indicator: [{
                name: '木马管理',
                max: 6500
            }, {
                name: '钓鱼网址',
                max: 16000
            }, {
                name: '恶意下载',
                max: 30000
            }, {
                name: '跨站脚本攻击',
                max: 38000
            }, {
                name: '搜索结果',
                max: 52000
            }, {
                name: '其他',
                max: 25000
            }],
            center:['50%','50%'],
            radius:50
        },
        series: [{
            name: '数据',
            type: 'radar',
            itemStyle: {
                normal: {
                    color:'#6e77ae',
                    areaStyle: {
                        color: '#d1d9fa'
                    }
                }
            },
            // areaStyle: {normal: {}},
            data: [{
                value: [4300, 10000, 10000, 20000, 26000, 19000]
            }]
        }]
    };
})