<template>
    <div class="home">
        <div class="home-search">
            <label>用户查询: </label>
            <select class="js_type">
                                                                <option value="eid">EID</option>
                                                                <option value="uName">邮箱</option>
                                                                <option value="oName">企业名称</option>
                                                            </select>
            <input type="text" value="" class="js_text">
            <input type="button" value="查询" v-on:click="searchQuery">
        </div>
        <div class="detail-info">
            <div class="term-total">
                <label>终端总数: <em>{{detailInfo.t_total}}</em></label>
                <span title="Windows"><i class="c-icon icon-win"></i>{{detailInfo.t_w}}</span>
                <span title="Linux"><i class="c-icon icon-linux"></i>{{detailInfo.t_l}}</span>
                <span title="Android"><i class="c-icon icon-andriod"></i>{{detailInfo.t_a}}</span>
            </div>
            <div class="user-total">
                <label>用户总数: <em>{{detailInfo.u_total}}</em></label>
                <span title="企业用户"><i class="c-icon icon-enterprise"></i>{{detailInfo.u_e}}</span>
                <span title="家庭用户"><i class="c-icon icon-family"></i>{{detailInfo.u_f}}</span>
            </div>
        </div>
        <div class="chart">
            <div class="chart-row">
                <div class="chart-box">
                    <div class="chart-head">
                        <div class="chart-info">当前在线<em>0</em>个<span>10:00</span></div>
                    </div>
                    <div class="chart-content" id="chart01"></div>
                </div>
                <div class="chart-box">
                    <div class="chart-head">
                        <h3>最新注册用户</h3>
                    </div>
                    <div class="chart-content" id="chart02">
                        <ul class="org-list" v-show="orgSize.length">
                            <li v-for="org in orgSize">
                                <a :href="org.url" target="_blank"><span>{{org.createtime}}</span><strong>{{org.name}}</strong><em>{{org.num}}</em></a>
                            </li>
                        </ul>
                        <div v-show="!orgSize.length" style="padding-top:40px;padding-left:20px;">暂时没有数据</div>
                    </div>
                </div>
            </div>
            <div class="chart-row">
                <div class="chart-box">
                    <div class="chart-head">
                        <div class="chart-info">今日新增<em>0</em>个<span>10:00</span></div>
                    </div>
                    <div class="chart-content" id="chart03"></div>
                </div>
                <div class="chart-box">
                    <div class="chart-head">
                        <div class="chart-info">
                            <div>今日新增<em>0</em>个<span>10:00</span></div>
                            <select>
                                                <option value="7">近7天</option>
                                                <option value="15">近15天</option>
                                                <option value="30">近30天</option>
                                            </select>
                        </div>
                    </div>
                    <div class="chart-content" id="chart04"></div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import echartTool from '../js/echartTool'
    import server from '../js/data'
    module.exports = {
        data: function() {
            obj['local'] = window.location.href.split('#')[0]
            return {
                greeting: 'Hello',
                orgSize: [],
                detailInfo: {
                    t_total: 0,
                    t_w: 0,
                    t_l: 0,
                    t_a: 0,
                    u_total: 0,
                    u_e: 0,
                    u_f: 0
                },
                info: '',
                tick: null
            }
        },
        mounted: function() {
            /* 客户端信息 */
            this.getAllTermInfo()

            this.echartTermReaytime()

            this.echartTermDayRaise()

            this.echartUserDayRaise()

            this.getorgSize()

            let self = this
            $('.chart-head select').on('change', function() {
                let val = $(this).val()
                self.echartUserDayRaise(val)
            })
        },
        beforeDestroyed: function() {
            clearInterval(this.tick)
            this.tick = null
        },
        watch: {
            'detailInfo': function(val, oldval) {
                console.log('Home - detailInfo  val:', val, ',oldval:', oldval)
            },
            'orgSize': function(val, oldval) {
                let oSize = [
                    '',
                    '20人以下',
                    '20-99人',
                    '100-499人',
                    '500-999人',
                    '1000-9999人',
                    '10000人以上'
                ]
                if (val) {
                    val.forEach(function(v, idx, arr) {
                        val[idx]['date'] = v['createtime']
                        val[idx]['name'] = v['oname'] || v['uname']
                        val[idx]['num'] = oSize[v['osize'] || 0]
                    })
                }
                console.log('Home - orgSize val:', val, ',oldval:', oldval)
            }
        },
        methods: {
            getAllTermInfo: function() {
                let self = this
                server.getAllTermInfo(function(data, status, xhr) {
                    let da = data.data
                    self.detailInfo = {
                        t_total: Number(da['win'] || 0) + Number(da['android'] || 0) +
                            Number(da['linux'] || 0),
                        t_w: da['win'] || 0,
                        t_l: da['linux'] || 0,
                        t_a: da['android'] || 0,
                        u_total: Number(da['ucount'] || 0) + Number(da['ecount'] || 0),
                        u_e: da['ecount'] || 0,
                        u_f: da['ucount'] || 0
                    }
                    console.info('getAllTermInfo', self.detailInfo)
                })
            },
            echartTermReaytime: function() {
                let self = this
                let ecEl = $('#chart01')
                let myChart = echarts.init(ecEl[0])
                myChart.showLoading()
                server.getTermReaytime(function(data, status, xhr) {
                    let total = Number(data.data[0]['win'] || 0) + Number(data.data[0]['android'] || 0) + Number(
                        data.data[0]['0'] || 0)
                    let option = echartTool.echart_term_reaytime(data.data)
                    console.log('echart_term_reaytime', option)
                    myChart.hideLoading()
                    myChart.setOption(option)
                    let time = new Date()
                    let Minute = ('' + time.getMinutes()).length === 1 ? ('0' + time.getMinutes()) : time.getMinutes()
                    let timeStr = [time.getHours(), Minute].join(':')
                    ecEl.parent().find('.chart-info').html('当前在线数<em>' +
                        total + '</em>个<span>' + timeStr + '</span>')
                })
                this.tick = setInterval(function() {
                    console.log('Home - getTermReaytime tick')
                    server.getTermReaytime(function(data, status, xhr) {
                        let total = Number(data.data[0]['win'] || 0) + Number(data.data[0]['android'] || 0) + Number(
                            data.data[0]['0'] || 0)
                        let option = echartTool.echart_term_reaytime(data.data)
                        console.log('echart_term_reaytime', option)
                        myChart.setOption(option)
                        let time = new Date()
                        let Minute = ('' + time.getMinutes()).length === 1 ? ('0' + time.getMinutes()) : time.getMinutes()
                        let timeStr = [time.getHours(), Minute].join(':')
                        ecEl.parent().find('.chart-info').html('当前在线数<em>' +
                            total + '</em>个<span>' + timeStr + '</span>')
                    })
                }, 60000)
            },
            echartTermDayRaise: function() {
                let self = this
                let ecEl = $('#chart03')
                let myChart = echarts.init(ecEl[0])
                myChart.showLoading()
                server.getTermDayRaise(function(data, status, xhr) {
                    let len = data.data.length - 1
                    let total = Number(data.data[len]['win'] || 0) + Number(data.data[len]['android'] || 0) + Number(
                        data.data[len]['0'] || 0)
                    let time = new Date()
                    let Minute = ('' + time.getMinutes()).length === 1 ? ('0' + time.getMinutes()) : time.getMinutes()
                    let timeStr = [time.getHours(), Minute].join(':')
                    ecEl.parent().find('.chart-info').html('今日新增<em>' +
                        total + '</em>个<span>' + timeStr + '</span>')
                    let option = echartTool.echart_term_day_rasie(data.data.slice(-7))
                    console.log('echart_term_day_rasie', option)
                    myChart.hideLoading()
                    myChart.setOption(option)
                })
            },
            echartUserDayRaise: function(val) {
                let self = this
                let ecEl = $('#chart04')
                let myChart = echarts.init(ecEl[0])
                myChart.showLoading()
                server.getUserDayRaise(val, function(data, status, xhr) {
                    let len = data.data.length - 1
                    let total = data.data[len]['total']
                    let time = new Date()
                    let Minute = ('' + time.getMinutes()).length === 1 ? ('0' + time.getMinutes()) : time.getMinutes()
                    let timeStr = [time.getHours(), Minute].join(':')
                    ecEl.parent().find('.chart-info>div').html('今日新增<em>' +
                        total + '</em>个<span>' + timeStr + '</span>')
                    let option = echartTool.echart_user_day_rasie(data.data.slice(-val))
                    console.log('echart_user_day_rasie', option)
                    myChart.hideLoading()
                    myChart.setOption(option)
                })
            },
            getorgSize: function() {
                let self = this
                server.getorgSize(function(data, status, xhr) {
                    self.orgSize = data.data
                })
            },
            searchQuery: function() {
                let type = $('.js_type').val()
                let text = $('.js_text').val()
                let query = {}
                query[type] = text
                this.$router.push({
                    path: 'Mange',
                    query
                })
            }
        }
    }
</script>

<style>
    .home-search {
        float: right;
        margin-top: -30px;
        color: #000;
        font-size: 12px;
    }

    .home-search input[type=text] {
        border: 1px solid #c1c3c8;
        height: 22px;
        line-height: 22px;
        width: 220px;
    }

    .home-search input[type=button] {
        width: 66px;
        height: 22px;
        color: #fff;
        font-size: 14px;
        background: #666e9f;
        border: 0;
        border-radius: 3px;
    }

    .home-search select {
        height: 22px;
        outline: none;
    }

    .detail-info {
        height: 134px;
        padding: 0 50px;
    }

    .detail-info .term-total {
        width: 66%;
        float: left;
        padding-top: 45px;
    }

    .term-total label {
        color: #838892;
        font-size: 18px;
        font-family: '微软雅黑';
    }

    .term-total em {
        font-style: normal;
        color: #565e69;
        font-size: 32px;
        font-family: 'Century Gothic', Arial, Helvetica, sans-serif;
        vertical-align: -2px;
        padding-left: 10px;
    }

    .term-total span {
        padding-left: 20px;
        color: #82878e;
        font-size: 20px;
        display: inline-block;
    }

    .detail-info .user-total {
        width: 33%;
        float: right;
        text-align: right;
        padding-top: 45px;
    }

    .user-total label {
        color: #838892;
        font-size: 18px;
        font-family: '微软雅黑';
    }

    .user-total em {
        font-style: normal;
        color: #565e69;
        font-size: 32px;
        font-family: 'Century Gothic', Arial, Helvetica, sans-serif;
        vertical-align: -2px;
        padding-left: 10px;
    }

    .user-total span {
        padding-left: 20px;
        color: #82878e;
        font-size: 20px;
        display: inline-block;
    }

    .chart {
        display: table;
        border-collapse: collapse;
        width: 100%;
    }

    .chart-row {
        display: table-row;
    }

    .chart-box {
        display: table-cell;
        width: 50%;
        border: 1px solid #e0e0e0;
        height: 248px;
        vertical-align: top;
    }

    .chart-content {
        width: 100%;
        height: 100%;
    }

    .chart-head {
        position: relative;
    }

    .chart-head h3 {
        color: #565e69;
        font-weight: normal;
        font-size: 16px;
        height: 40px;
        line-height: 40px;
        position: absolute;
        left: 20px;
    }

    .chart-info {
        position: absolute;
        top: 30px;
        right: 25px;
        color: #82878e;
        font-size: 14px;
        z-index: 9;
    }

    .chart-info>div {
        display: inline-block;
    }

    .chart-info>select {
        margin-left: 15px;
        width: 94px;
        height: 22px;
        line-height: 22px;
        border: 1px solid #e0e0e0;
        background: #fafafa;
        color: #57606f;
    }

    .chart-info em {
        font-family: Arial;
        font-size: 20px;
        font-style: normal;
        color: #57606f;
        padding: 0 3px;
    }

    .chart-info span {
        color: #a6abb3;
        font-size: 14px;
        padding-left: 10px;
    }

    .org-list {
        margin: 0;
        padding: 0;
        padding-top: 40px;
        padding-left: 15px;
        padding-right: 15px;
        list-style-type: none;
    }

    .org-list li {
        height: 30px;
        line-height: 30px;
    }

    .org-list a {
        display: block;
        height: 30px;
        color: #82878e;
        text-decoration: none;
        padding: 0 5px;
    }

    .org-list a:hover {
        background: #f2f2f2;
    }

    .org-list span {
        display: inline-block;
        width: 170px;
    }

    .org-list em {
        font-style: normal;
        font-weight: normal;
    }

    .org-list strong {
        margin-right: 20px;
        font-weight: normal;
    }

    .org-list a:hover strong {
        color: #29bcef;
    }
</style>
