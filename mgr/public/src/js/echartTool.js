module.exports = {

    /* 新版 终端实时在线数 */
    echart_term_reaytime: function (data) {
        let wArr = [
            0,
            0,
            0,
            0,
            0,
            0,
            0
        ]
        let lArr = [
            0,
            0,
            0,
            0,
            0,
            0,
            0
        ]
        let aArr = [
            0,
            0,
            0,
            0,
            0,
            0,
            0
        ]
        let total = 0
        data.forEach(function (v, idx, arr) {
            if (idx > 6) {
                return
            }
            total = Math
                .max
                .call(null, Number(v['win']), Number(v['linux']), Number(v['android']), total)
            wArr[idx] = Number(v['win']) || 0
            lArr[idx] = Number(v['linux']) || 0
            aArr[idx] = Number(v['android']) || 0
        })

        var splitNumber = this.splitN(total, 5)

        let option = {
            title: {
                text: '终端实时在线',
                left: 15,
                top: 10,
                textStyle: {
                    color: '#57606f',
                    fontSize: 16,
                    fontWeight: 'normal',
                    fontFamily: ['微软雅黑']
                }
            },
            tooltip: {
                trigger: 'axis'
            },
            legend: {
                bottom: 5,
                itemWidth: 12,
                itemHeight: 12,
                data: [{
                    name: 'Windows',
                    icon: 'image://../mgr/public/views/static/img/img02.png'
                }, {
                    name: 'Linux',
                    icon: 'image://../mgr/public/views/static/img/img01.png'
                }, {
                    name: 'Android',
                    icon: 'image://../mgr/public/views/static/img/img03.png'
                }]
            },
            grid: {
                top: '60',
                left: '3%',
                right: '4%',
                bottom: '35',
                containLabel: true
            },
            xAxis: {
                type: 'category',
                boundaryGap: false,
                axisLine: {
                    lineStyle: {
                        color: '#ebebeb'
                    }
                },
                axisTick: {
                    show: false
                },
                axisLabel: {
                    textStyle: {
                        color: '#adb0b5'
                    }
                },
                data: [
                    '0分钟',
                    '1分钟',
                    '2分钟',
                    '3分钟',
                    '4分钟',
                    '5分钟',
                    '6分钟'
                ]
            },
            yAxis: {
                minInterval: 1,
                min: 0,
                max: total.toFixed(0),
                splitNumber: splitNumber,
                splitLine: {
                    show: false
                },
                axisLine: {
                    lineStyle: {
                        color: '#ebebeb'
                    }
                },
                axisTick: {
                    show: false
                },
                axisLabel: {
                    textStyle: {
                        color: '#adb0b5'
                    }
                },
                type: 'value'
            },
            series: [{
                name: 'Windows',
                type: 'line',
                lineStyle: {
                    normal: {
                        color: '#29bcef'
                    }
                },
                itemStyle: {
                    normal: {
                        color: '#29bcef'
                    }
                },
                data: wArr
            }, {
                name: 'Linux',
                type: 'line',
                lineStyle: {
                    normal: {
                        color: '#626ca8'
                    }
                },
                itemStyle: {
                    normal: {
                        color: '#626ca8'
                    }
                },
                data: lArr
            }, {
                name: 'Android',
                type: 'line',
                lineStyle: {
                    normal: {
                        color: '#7db53a'
                    }
                },
                itemStyle: {
                    normal: {
                        color: '#7db53a'
                    }
                },
                data: aArr
            }]
        }
        return option
    },
    /* 每日客户端新增 */
    echart_term_day_rasie: function (data) {
        let time = []
        let wins = []
        let androids = []
        let linuxs = []
        let total = 0
        data.forEach(function (v, idx, arr) {
            let tim = v['time']
                .split(' ')[0]
                .split('-')
            if (idx === 0) {
                time.push([tim[1], '月', tim[2], '日'].join(''))
            } else {
                time.push(tim[2])
            }
            // date.push(v['time'])
            total = Math
                .max
                .call(null, Number(v['win']), Number(v['linux']), Number(v['android']), total)
            // time.push(v['time'])
            wins.push(Number(v['win']) || 0)
            androids.push(Number(v['android']) || 0)
            linuxs.push(Number(v['linux']) || 0)
        })
        // data.forEach(function (val, idx, arr) {
        //     total = Math
        //         .max
        //         .call(null, Number(val['win']), Number(val['linux']), Number(val['android']), total)
        //     time.push(val['time'])
        //     wins.push(Number(val['win']))
        //     androids.push(Number(val['android']))
        //     linuxs.push(Number(val['linux']))
        // })

        var splitNumber = this.splitN(total, 5)

        let option = {
            title: {
                text: '每日新增终端',
                left: 15,
                top: 10,
                textStyle: {
                    color: '#57606f',
                    fontSize: 16,
                    fontWeight: 'normal',
                    fontFamily: ['微软雅黑']
                }
            },
            tooltip: {
                trigger: 'axis'
            },
            legend: {
                bottom: 5,
                itemWidth: 12,
                itemHeight: 12,
                data: [{
                    name: 'Windows',
                    icon: 'image://../mgr/public/views/static/img/img02.png'
                }, {
                    name: 'Linux',
                    icon: 'image://../mgr/public/views/static/img/img01.png'
                }, {
                    name: 'Android',
                    icon: 'image://../mgr/public/views/static/img/img03.png'
                }]
            },
            grid: {
                top: '60',
                left: '3%',
                right: '4%',
                bottom: '35',
                containLabel: true
            },
            xAxis: {
                type: 'category',
                boundaryGap: false,
                axisLine: {
                    lineStyle: {
                        color: '#ebebeb'
                    }
                },
                axisTick: {
                    show: false
                },
                axisLabel: {
                    textStyle: {
                        color: '#adb0b5'
                    }
                },
                data: time
            },
            yAxis: {
                minInterval: 1,
                min: 0,
                max: total.toFixed(0),
                splitNumber: splitNumber,
                splitLine: {
                    show: false
                },
                axisLine: {
                    lineStyle: {
                        color: '#ebebeb'
                    }
                },
                axisTick: {
                    show: false
                },
                axisLabel: {
                    textStyle: {
                        color: '#adb0b5'
                    }
                },
                type: 'value'
            },
            series: [{
                name: 'Windows',
                type: 'line',
                lineStyle: {
                    normal: {
                        color: '#29bcef'
                    }
                },
                itemStyle: {
                    normal: {
                        color: '#29bcef'
                    }
                },
                data: wins
            }, {
                name: 'Android',
                type: 'line',
                lineStyle: {
                    normal: {
                        color: '#7db53a'
                    }
                },
                itemStyle: {
                    normal: {
                        color: '#7db53a'
                    }
                },
                data: androids
            }, {
                name: 'Linux',
                type: 'line',
                lineStyle: {
                    normal: {
                        color: '#626ca8'
                    }
                },
                itemStyle: {
                    normal: {
                        color: '#626ca8'
                    }
                },
                data: linuxs
            }]
        }
        if (total < 5) {
            option.yAxis.interval = 1
        }
        return option
    },
    /* 每日新增用户 */
    echart_user_day_rasie: function (data) {
        let ucounts = []
        let ecounts = []
        let totals = []
        let date = []
        let max = 0
        data.forEach(function (v, idx, arr) {
            let tim = v['time']
                .split(' ')[0]
                .split('-')
            if (idx === 0) {
                date.push([tim[1], '月', tim[2], '日'].join(''))
            } else {
                date.push(tim[2])
            }
            // date.push(v['time'])
            max = Math
                .max
                .call(null, Number(v['ucount']) + Number(v['ecount']), max)
            totals.push((Number(v['ucount']) || 0) + (Number(v['ecount']) || 0))
            ucounts.push(Number(v['ucount']) || 0)
            ecounts.push(Number(v['ecount']) || 0)
        })

        var splitNumber = this.splitN(max, 5)
        console.log('echartTool echart_user_day_rasie', ucounts, ecounts, totals)
        let option = {
            title: {
                text: '每日新增用户',
                left: 15,
                top: 10,
                textStyle: {
                    color: '#57606f',
                    fontSize: 16,
                    fontWeight: 'normal',
                    fontFamily: ['微软雅黑']
                }
            },
            tooltip: {
                trigger: 'axis'
            },
            legend: {
                bottom: 5,
                itemWidth: 12,
                itemHeight: 12,
                data: [{
                    name: '全部',
                    icon: 'image://../mgr/public/views/static/img/img01.png'
                }, {
                    name: '企业',
                    icon: 'image://../mgr/public/views/static/img/img02.png'
                }, {
                    name: '家庭',
                    icon: 'image://../mgr/public/views/static/img/img03.png'
                }]
            },
            grid: {
                top: '60',
                left: '3%',
                right: '4%',
                bottom: '35',
                containLabel: true
            },
            xAxis: {
                type: 'category',
                boundaryGap: false,
                axisLine: {
                    lineStyle: {
                        color: '#ebebeb'
                    }
                },
                axisTick: {
                    show: false
                },
                axisLabel: {
                    textStyle: {
                        color: '#adb0b5'
                    }
                },
                data: date
            },
            yAxis: {
                minInterval: 1,
                min: 0,
                max: max.toFixed(0),
                splitNumber: splitNumber,
                splitLine: {
                    show: false
                },
                axisLine: {
                    lineStyle: {
                        color: '#ebebeb'
                    }
                },
                axisTick: {
                    show: false
                },
                axisLabel: {
                    textStyle: {
                        color: '#adb0b5'
                    }
                },
                type: 'value'
            },
            series: [{
                name: '全部',
                type: 'line',
                lineStyle: {
                    normal: {
                        color: '#626ca8'
                    }
                },
                itemStyle: {
                    normal: {
                        color: '#626ca8'
                    }
                },
                data: totals
            }, {
                name: '企业',
                type: 'line',
                lineStyle: {
                    normal: {
                        color: '#29bcef'
                    }
                },
                itemStyle: {
                    normal: {
                        color: '#29bcef'
                    }
                },
                data: ucounts
            }, {
                name: '家庭',
                type: 'line',
                lineStyle: {
                    normal: {
                        color: '#7db53a'
                    }
                },
                itemStyle: {
                    normal: {
                        color: '#7db53a'
                    }
                },
                data: ecounts
            }]
        }
        if (max < 5) {
            option.yAxis.interval = 1
        }
        return option
    },
    splitN: function (num, N) {
        if (num === 0) {
            return 1
        }
        if ((num / N) >= 1) {
            return N
        } else {
            return this.splitN(num, --N)
        }
    }
}
