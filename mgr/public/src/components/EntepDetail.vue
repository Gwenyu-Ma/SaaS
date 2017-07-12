<template>
    <div style="padding:0 20px">
        <div class="ui items">
            <div class="item">
                <!--<div class="image">
                <img :src="src" alt="log">
            </div>-->
                <div class="content">
                    企业名称：<a class="">{{comInfo['OName']}}</a>
                    <div class="meta">
                        <span>企业概览</span>
                    </div>
                    <div class="description">
                        <div class="ui list">
                            <div class="item">
                                <i class="users icon"></i>
                                <div class="content">
                                    客户端数：{{comInfo['ClientCount']}}
                                </div>
                            </div>
                            <div class="item">
                                <i class="marker icon"></i>
                                <div class="content">
                                    剩余存储空间：{{disk}}
                                </div>
                            </div>
                            <div class="item">
                                <i class="mail icon"></i>
                                <div class="content">
                                    注册时间：{{comInfo['CreateTime']}}
                                </div>
                            </div>
                            <div class="item">
                                <i class="linkify icon"></i>
                                <div class="content">
                                    最后一次登录：{{comInfo['LastLoginTime']}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="extra">

                    </div>
                </div>
                <div>
                    <!--<button class="ui button mini basic" v-bind:Eid="comInfo.EID">重置首页</button>-->
                    <div class="ui button mini basic simple dropdown item">
                        修正首页数据<i class="dropdown icon"></i>
                        <div class="menu">
                            <div class="item" v-bind:Eid="comInfo.EID" v-on:click="initClientW">威胁终端</div>
                            <div class="item" v-bind:Eid="comInfo.EID" v-on:click="initXav">病毒数量</div>
                            <div class="item" v-bind:Eid="comInfo.EID" v-on:click="initRfwBNS">违规联网</div>
                            <div class="item" v-bind:Eid="comInfo.EID" v-on:click="initPhoneSpam">骚扰拦截</div>
                            <div class="item" v-bind:Eid="comInfo.EID" v-on:click="initRfwTFA">当日流量排行</div>
                            <div class="item" v-bind:Eid="comInfo.EID" v-on:click="initRfwUrlByResult">恶意网址拦截</div>
                            <div class="item" v-bind:Eid="comInfo.EID" v-on:click="initClientOS">操作系统分布</div>
                        </div>
                    </div>
                    <!--<button class="ui button mini basic" v-bind:Eid="comInfo.EID">重置策略</button>
                    <button class="ui button mini basic" v-bind:Eid="comInfo.EID">修正内存数据</button>
                    <button class="ui button mini basic" v-bind:Eid="comInfo.EID">企业数据同步</button>
                    <button class="ui button mini basic" v-bind:Eid="comInfo.EID">终端数据同步</button>-->
                </div>
            </div>
        </div>
</template>

<script>
    import server from '../js/data'
    import common from '../js/common'
    module.exports = {
        props: ['comInfo'],
        data: function () {
            return {
                greeting: 'Hello',
                src: '/org/getlogo?eid=' + this.comInfo.EID,
                disk: '0'
            }
        },
        watch: {
            'comInfo': function (val, oldval) {
                console.info('entepDetail comInfo change', val)
                if (val) {
                    this.getUsedSpace(val.EID)
                    this.src = '/org/getlogo?eid=' + val.EID
                    let _val = {
                        Eid: val.EI,
                        OName: val['OName'] || '',
                        ClientCount: val['ClientCount'] || '',
                        disk: val['disk'] || '',
                        CreateTime: val['CreateTime'] || '',
                        LastLoginTime: val['LastLoginTime'] || ''
                    }
                    val = $.extend(true, val, _val)
                } else {
                    val = {
                        EID: '',
                        OName: '',
                        ClientCount: '',
                        disk: '',
                        CreateTime: '',
                        LastLoginTime: ''
                    }
                }
            }
        },
        mounted: function () {
            console.log('mounted entepDetail', this)
        },
        methods: {
            initClientW: function (e) {
                let self = this
                let eid = e.target.getAttribute('Eid')
                server.initClientW(eid, function (data, status, xhr) {
                    self.handleAjax(data)
                })
            },
            initXav: function (e) {
                let self = this
                let eid = e.target.getAttribute('Eid')
                server.initXav(eid, function (data, status, xhr) {
                    self.handleAjax(data)
                })
            },
            initRfwBNS: function (e) {
                let self = this
                let eid = e.target.getAttribute('Eid')
                server.initRfwBNS(eid, function (data, status, xhr) {
                    self.handleAjax(data)
                })
            },
            initPhoneSpam: function (e) {
                let self = this
                let eid = e.target.getAttribute('Eid')
                server.initPhoneSpam(eid, function (data, status, xhr) {
                    self.handleAjax(data)
                })
            },
            initRfwTFA: function (e) {
                let self = this
                let eid = e.target.getAttribute('Eid')
                server.initRfwTFA(eid, function (data, status, xhr) {
                    self.handleAjax(data)
                })
            },
            initRfwUrlByResult: function (e) {
                let self = this
                let eid = e.target.getAttribute('Eid')
                server.initRfwUrlByResult(eid, function (data, status, xhr) {
                    self.handleAjax(data)
                })
            },
            initClientOS: function (e) {
                let self = this
                let eid = e.target.getAttribute('Eid')
                server.initClientOS(eid, function (data, status, xhr) {
                    self.handleAjax(data)
                })
            },
            getUsedSpace: function (eid) {
                let self = this
                server.getUsedSpace(eid, function (data, status, xhr) {
                    self.disk = data
                })
            },
            handleAjax: function (data) {
                if (data.r.code === 0) {
                    console.log('成功')
                    common.msg.success(null, '成功')
                } else {
                    this.handleError(data)
                }
            },
            handleError: function (data) {
                common.msg.err(null, data.r.msg)
            }
        }

    }

</script>
<style>


</style>
