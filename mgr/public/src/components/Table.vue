<template>
    <div class="my-table">
        <table class="ui celled table" v-if="Manage">
            <thead>
                <tr>
                    <th>选择</th>
                    <th>序号</th>
                    <th v-on:click.self="sort" data-sort="CreateTime">注册时间<i class="sort icon"></i></th>
                    <th v-on:click.self="sort" data-sort="EID">eid<i class="sort icon"></i></th>
                    <th v-on:click.self="sort" data-sort="OName">企业名称<i class="sort icon"></i></th>
                    <th v-on:click.self="sort" data-sort="type">类型<i class="sort icon"></i></th>
                    <th>终端数</th>
                    <th>在线数</i></th>
                    <th v-on:click.self="sort" data-sort="UserName">用户名<i class="sort icon"></i></th>
                    <th v-on:click.self="sort" data-sort="LastLoginTime">最后登录时间<i class="sort icon"></i></th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <tr v-if="!(parentdata.rows&&parentdata.rows.length)">
                    <td colspan="11" class="no-data">
                        没有数据...
                    </td>
                </tr>
                <tr v-for="da in parentdata.rows">
                    <td><input type="checkbox" v-bind:eid="da.EID" v-on:click="checkEid"></td>
                    <td>{{da.num}}</td>
                    <td>{{da.CreateTime}}</td>
                    <td>{{da.EID}}</td>
                    <td>{{da.OName}}</td>
                    <td>{{da.type}}</td>
                    <td>{{da.ClientCount}}</td>
                    <td>{{da.OnlineCount}}</td>
                    <td>{{da.UserName}}</td>
                    <td>{{da.LastLoginTime}}</td>
                    <!--<td><a :href="da.url" v-on:click.prevent="showModal" v-bind:eid="da.EID" v-bind:idx="da.idx">详情</a></td>-->
                    <td><a :href="da.url" target="_blank" v-bind:eid="da.EID" v-bind:idx="da.idx">详情</a></td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="11">
                        <div is="pa-com" v-bind:exported="true" v-bind:total="total" v-bind:pageNumInfo="pageNumInfo" v-on:pageChange="refer"></div>
                    </th>
                </tr>
            </tfoot>
        </table>
        <table class="ui celled table" v-if="Term">
            <thead>
                <tr>
                    <th>序号</th>
                    <th v-on:click.self="sort" data-sort="groupname">组名称<i class="sort icon"></i></th>
                    <th v-on:click.self="sort" data-sort="computername">计算机名称<i class="sort icon"></i></th>
                    <!--<th>在线状态</th>-->
                    <th v-on:click.self="sort" data-sort="ip">IP<i class="sort icon"></i></th>
                    <th v-on:click.self="sort" data-sort="mac">MAC<i class="sort icon"></i></th>
                    <th v-on:click.self="sort" data-sort="version">版本号<i class="sort icon"></i></th>
                    <th>SGUID</th>
                    <th v-on:click.self="sort" data-sort="createtime">第一次登录时间<i class="sort icon"></i></th>
                    <th v-on:click.self="sort" data-sort="edate">最近活跃时间<i class="sort icon"></i></th>
                    <th>操作</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th colspan="11">
                        <div is="pa-com" v-bind:total="total" v-bind:pageNumInfo="pageNumInfo" v-on:pageChange="refer"></div>
                    </th>
                </tr>
            </tfoot>
            <tbody>
                <tr v-if="!(parentdata.rows&&parentdata.rows.length)">
                    <td colspan="11" class="no-data">
                        没有数据...
                    </td>
                </tr>
                <tr v-for="da in parentdata.rows">
                    <td>{{da.num}}</td>
                    <td>{{da.groupname}}</td>
                    <td>{{da.name}}</td>
                    <!--<td>{{da.online}}</td>-->
                    <td>{{da.ip}}</td>
                    <td>{{da.mac}}</td>
                    <td>{{da.version}}</td>
                    <td>{{da.sguid}}</td>
                    <td>{{da.createtime}}</td>
                    <td>{{da.edate}}</td>
                    <td>
                        <a href="javascript:void(0)" v-on:click="popup">详情</a>
                        <div class="ui popup top left transition hidden">
                            <div style="width:600px;">
                                <div>
                                    <button class="ui button mini basic">同步组</button>
                                    <button class="ui button mini basic">同步客户端</button>
                                </div>
                                <talbe class="ui celled striped table">
                                    <thead>
                                        <tr>
                                            <th colspan="4">终端信息</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="center aligned collapsing">终端名称：</td>
                                            <td class="left aligned">{{da.name}}</td>
                                            <td class="center aligned collapsing">在线情况：</td>
                                            <td class="left aligned">{{da.online}}</td>
                                        </tr>
                                        <tr>
                                            <td class="center aligned collapsing">IP地址：</td>
                                            <td class="left aligned">{{da.ip}}</td>
                                            <td class="center aligned collapsing">MAC地址：</td>
                                            <td class="left aligned">{{da.mac}}</td>
                                        </tr>
                                        <tr>
                                            <td class="center aligned collapsing">所属组：</td>
                                            <td class="left aligned">{{da.groupname}}</td>
                                            <td class="center aligned collapsing">加入时间：</td>
                                            <td class="left aligned">{{da.edate}}</td>
                                        </tr>
                                        <tr>
                                            <td class="center aligned collapsing">机器名称：</td>
                                            <td class="left aligned">{{da.computername}}</td>
                                            <td class="center aligned collapsing">最后上线：</td>
                                            <td class="left aligned">{{da.lastime}}</td>
                                        </tr>
                                        <tr>
                                            <td class="center aligned collapsing">操作系统：</td>
                                            <td class="left aligned" colspan="3">{{da.systype}}</td>
                                        </tr>
                                    </tbody>

                                </table>
                            </div>
                         </div>
                     </td>
                </tr>
            </tbody>
        </table>
        <table class="ui celled table" v-if="Log">
            <thead>
                <tr>
                    <th>Header</th>
                    <th>Header</th>
                    <th>Header</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>First</td>
                    <td>Cell</td>
                    <td>Cell</td>
                </tr>
                <tr>
                    <td>Cell</td>
                    <td>Cell</td>
                    <td>Cell</td>
                </tr>
                <tr>
                    <td>Cell</td>
                    <td>Cell</td>
                    <td>Cell</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3">
                        <div is="pa-com" v-bind:total="total" v-bind:pageNumInfo="pageNumInfo" v-on:pageChange="refer"></div>
                    </th>
                </tr>
            </tfoot>
        </table>
    </div>
</template>

<script>
    import PaCom from './PageTurn.vue'
    import eventHub from '../js/event'
    module.exports = {
        props: ['parentCom', 'parentdata', 'eids', 'query'],
        data: function() {
            console.log('table-data', this.parentdata)
            let obj = {
                Term: false,
                Log: false,
                Manage: false,
                pageNumInfo: this.query.paging,
                total: 0
            }
            obj[this.parentCom] = true
            obj['local'] = window.location.href.split('#')[0]
            // obj['currentPage'] = 0
            // onj['totalPage'] = this.getNum(this.parentdata.total, this.parentdata.datas.length)
            return obj
        },
        components: {
            PaCom
        },
        created: function() {
            console.log('table-created', this.parentdata)
        },
        mounted: function() {},
        updated: function() {},
        beforeDestroy: function() {
            this.$off()
            console.log('table destroy')
        },
        computed: {
            isOdd: function(event) {
                return event.target.index % 2 === 0
            },
            pageNum: function(total, listNum) {
                return Math.floor(total / listNum)
            }
        },
        watch: {
            'parentdata': function(val, oldval) {
                var self = this
                if (!val) {
                    this.parentdata = {
                        rows: [],
                        total: 0
                    }
                    this.total = 0
                    return this.parentdata
                }
                if (this.Manage) {
                    val.rows.forEach(function(val, key, list) {
                        val['num'] = key + 1
                        val['idx'] = key
                        val['type'] = val['type'] === '1' ? '企业' : '家庭'
                        val['url'] = self.local + '#/Search?eid=' + val['EID']
                    })
                }
                if (this.Term) {
                    val.rows.forEach(function(val, key, list) {
                        val['num'] = key + 1
                        val['online'] = val['onlinestate'] === 1 ? '在线' : '离线'
                        val['name'] = val['memo'] || val['computername']
                    })
                }
                this.total = val.total
                console.info('components table  watch total', val.total, this.Manage, this.Term)
            },
            'query': function(val, oldval) {
                if (val) {
                    pageNumInfo = val.paging
                }
            }
        },
        methods: {
            showModal: function(event) {
                let target = event.target
                let eid = target.getAttribute('eid')
                let idx = target.getAttribute('idx')
                console.log('show-modal start eid', eid)
                console.log('show-modal start idx', idx)
                console.log('show-modal start parentdata', this.parentdata.rows[idx])
                this.$emit('showmodal', eid, this.parentdata.rows[idx])
            },
            checkEid: function(e) {
                let checked = e.target.checked
                let eid = e.target.getAttribute('EID')
                console.log('modifyEids start', checked, eid)
                if (checked) {
                    this.$emit('modifyeids', 'add', eid)
                } else {
                    this.$emit('modifyeids', 'del', eid)
                }
            },
            refer: function(paging) {
                console.log('paging', paging)
                this.query.paging = $.extend(true, this.query.paging, paging)
                this.$emit('search', this.query)
            },
            popup: function(e) {
                let dom = $(e.target)
                dom
                    .popup({
                        inline: true,
                        hoverable: true,
                        position: 'bottom right',
                        on: 'click',
                        delay: {
                            show: 300,
                            hide: 800
                        }
                    })
                    .popup('show')
            },
            sort: function(e) {
                let dom = $(e.target)
                let sortname = dom.data('sort')
                let orderOjb = dom.find('i')
                let order = 0
                if (orderOjb.hasClass('ascending')) {
                    order = 1
                    orderOjb.removeClass('ascending').addClass('descending')
                } else {
                    orderOjb.removeClass('descending').addClass('ascending')
                }
                dom.siblings().find('.sort').removeClass('ascending descending')

                this.query.paging = $.extend(true, this.query.paging, {
                    'sort': sortname,
                    'order': order
                })
                this.$emit('search', this.query)
            }
        }
    }
</script>

<style>
    .my-table .ui.table thead th,
    .my-table .ui.table tbody td {
        padding: 0.4em 0.6em;
    }

    .my-table .ui.menu {
        font-size: 0.5rem;
    }

    .my-table .ui.table tbody tr.gray {
        background: #999;
    }

    .my-table .ui.table thead th {
        cursor: pointer;
    }

    .popup .ui.table {
        display: table;
    }

    .popup .ui.table tbody td {
        padding: 0.5em 0.7em;
    }

    .no-data {
        height: 100px;
        line-height: 80px;
        text-align: center!important;
        color: #666;
    }
</style>
