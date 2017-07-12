<template>
    <div class="ui">
        <div class="header">
            {{header}}
        </div>
        <div class="content">
            <info-com v-bind:comInfo="enterInfo" />
            <div class="ui grid">
                <div class="three wide column">
                    <list-com v-bind:EID="enterEid" v-on:changeGroup="changeG"></list-com>
                </div>
                <div class="thirteen wide column">
                    <search-com parent-com="Term" v-on:search="getClients" v-bind:EID="enterEid"></search-com>
                    <table-com v-bind:query="query" parent-com="Term" v-bind:parentdata="parentModalData" v-on:search="getClients"></table-com>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import InfoCom from '../components/EntepDetail.vue'
    import SearchCom from '../components/Search.vue'
    import ListCom from '../components/List.vue'
    import TableCom from '../components/Table.vue'

    import eventHub from '../js/event'
    import server from '../js/data'

    module.exports = {
        data: function () {
            let eid = this.$route.query.eid
            console.log('detail eid', eid)
            return {
                parentModalData: [],
                header: '企业详情',
                enterInfo: {
                    EID: eid,
                    OName: '',
                    ClientCount: '',
                    disk: '',
                    CreateTime: '',
                    LastLoginTime: ''
                },
                enterEid: eid,
                query: {
                    eid: eid,
                    sKey: '',
                    sValue: '',
                    groupId: '',
                    paging: {
                        order: 0,
                        limit: 10,
                        offset: 0,
                        sort: ''
                    }
                }
            }
        },
        mounted: function () {
            console.log('route mounted', this.$route)
            $('#app').addClass('detail')
            this.getClients()
            this.getEnterInfo()
        },
        components: {
            SearchCom,
            InfoCom,
            TableCom,
            ListCom
        },
        beforeDestroy: function () {
            $('#app').removeClass('detail')
        },
        watch: {
            $route: function () {
                console.log(this.$route)
            },
            'enterEid': function () {
                console.log('modal enterEid watch', this.enterEid)
                this.query.eid = this.enterEid
                this.getClients()
            },
            'enterInfo': function (val) {
                console.info('component Modal enterInfo', val)
            }
        },
        methods: {
            getClients: function (query) {
                let self = this
                $.extend(true, self.query, query || {})
                server.getClients(self.query, function (data, status, xhr) {
                    self.parentModalData = data.data
                    console.log('parentModalData', self.parentModalData)
                })
            },
            getGroups: function (query) {
                let self = this
                server.getGroups(query, function (data, status, xhr) {
                    self.groups = data.data
                    console.log('groups', self.groups)
                })
            },
            getEnterInfo: function () {
                let self = this
                server.getEnterprise({
                    eid: self.enterEid
                }, function (data, status, xhr) {
                    if (data.data.rows.length) {
                        $.extend(true, self.enterInfo, data.data.rows[0])
                        console.log('enterInfo', self.enterInfo)
                    }
                })
            },
            changeG: function (groupId) {
                let query = {
                    groupId: groupId
                }
                this.getClients(query)
            }
        }
    }

</script>
<style>
    /*.detail .head {
        display: none!important;
    }*/

    .detail .warp-nav {
        display: none!important;
    }

    .detail .header {
        line-height: 20px;
        font-size: 16px;
        padding: 10px 20px;
    }

</style>
