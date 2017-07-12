<template>
    <div class="info">
        <div class="opt-btn" style="margin-bottom:15px;">
            <div class="ui basic buttons">
                <!--<button class="ui button mini">修正内存数据</button>-->
                <div class="ui button simple dropdown item">
                    修正首页数据<i class="dropdown icon"></i>
                    <div class="menu">
                        <div class="item" v-on:click="initClientW">威胁终端</div>
                        <div class="item" v-on:click="initXav">病毒数量</div>
                        <div class="item" v-on:click="initRfwBNS">违规联网</div>
                        <div class="item" v-on:click="initPhoneSpam">骚扰拦截</div>
                        <div class="item" v-on:click="initRfwTFA">当日流量排行</div>
                        <div class="item" v-on:click="initRfwUrlByResult">恶意网址拦截</div>
                        <div class="item" v-on:click="initClientOS">操作系统分布</div>
                    </div>
                </div>
                <!--<button class="ui button mini">修正策略</button>-->
            </div>
            <!--<div class="ui basic buttons">
                <button class="ui button mini">客户端同步</button>
                <button class="ui button mini">组数据同步</button>
                <button class="ui button mini">修改密码</button>
            </div>-->
        </div>
        <!--<div class="ui icon message mini">
            <i class="idea icon teal"></i>
            <div class="content">
                {{info}}
            </div>
        </div>-->
    </div>
</template>
<script>
    import server from '../js/data'
    import common from '../js/common'
    module.exports = {
        props: ['eids'],
        data: function () {
            return {
                info: '系统内当前共有100家企业，1000个终端（window 600，andirod400）'
            }
        },
        watch: {
            'eids': function (val, oldval) {
                console.log('components Info', 'eids', val)
            }
        },
        methods: {
            checkEid: function () {
                let eid = this.eids
                if (!eid || eid.length < 1 || eid.length > 1) {
                    common.msg.err('', '请选择一家企业')
                    return false
                }
                return true
            },
            initClientW: function (e) {
                if (!this.checkEid()) {
                    return false
                }
                var eid = this.eids[0]
                let self = this
                server.initClientW(eid, function (data, status, xhr) {
                    console.log(data)
                    self.handleAjax(data)
                })
            },
            initXav: function (e) {
                if (!this.checkEid()) {
                    return false
                }
                var eid = this.eids[0]
                let self = this
                server.initXav(eid, function (data, status, xhr) {
                    self.handleAjax(data)
                })
            },
            initRfwBNS: function (e) {
                if (!this.checkEid()) {
                    return false
                }
                var eid = this.eids[0]
                let self = this
                server.initRfwBNS(eid, function (data, status, xhr) {
                    self.handleAjax(data)
                })
            },
            initPhoneSpam: function (e) {
                if (!this.checkEid()) {
                    return false
                }
                var eid = this.eids[0]
                let self = this
                server.initPhoneSpam(eid, function (data, status, xhr) {
                    self.handleAjax(data)
                })
            },
            initRfwTFA: function (e) {
                if (!this.checkEid()) {
                    return false
                }
                var eid = this.eids[0]
                let self = this
                server.initRfwTFA(eid, function (data, status, xhr) {
                    self.handleAjax(data)
                })
            },
            initRfwUrlByResult: function (e) {
                if (!this.checkEid()) {
                    return false
                }
                var eid = this.eids[0]
                let self = this
                server.initRfwUrlByResult(eid, function (data, status, xhr) {
                    self.handleAjax(data)
                })
            },
            initClientOS: function (e) {
                if (!this.checkEid()) {
                    return false
                }
                var eid = this.eids[0]
                let self = this
                server.initClientOS(eid, function (data, status, xhr) {
                    self.handleAjax(data)
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
    .info .ui.icon.message>.icon:not(.close) {
        font-size: 1.8em;
    }

    .opt-btn .ui.button {
        font-size: 0.8rem;
    }

    .info .ui.table thead th,
    .info .ui.table tbody td {
        padding: 0.3em 0.4em;
    }

</style>
