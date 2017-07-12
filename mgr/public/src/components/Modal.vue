<template>
    <div class="ui modal fullscreen">
        <i class="close icon"></i>
        <div class="header">
            {{header}}
        </div>
        <div class="content">
            <info-com v-bind:comInfo="enterInfo"/>
            <div class="ui grid">
                <div class="three wide column">
                    <list-com v-bind:comInfo="enterInfo"></list-com>
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
    import InfoCom from './EntepDetail.vue'
    import SearchCom from './Search.vue'
    import ListCom from './List.vue'
    import TableCom from './Table.vue'

    import eventHub from '../js/event'
    import server from '../js/data'

    module.exports = {
        props: ['enterEid', 'enterInfo'],
        data: function() {
            return {
                parentModalData: [],
                header: '企业详情',
                query: {
                    eid: this.enterEid,
                    sKey: '',
                    sValue: '',
                    paging: {
                        order: 0,
                        limit: 0,
                        offset: 0,
                        sort: ''
                    }
                }
            }
        },
        components: {
            SearchCom,
            InfoCom,
            TableCom,
            ListCom
        },
        mounted: function() {

        },
        watch: {
            'enterEid': function() {
                console.log('modal enterEid watch', this.enterEid)
                this.query.eid = this.enterEid
                this.getClients()
            },
            'enterInfo': function(val) {
                console.info('component Modal enterInfo', val)
            }
        },
        methods: {
            getClients: function(query) {
                var self = this
                $.extend(true, self.query, query || {})
                server.getClients(self.query, function(data, status, xhr) {
                    self.parentModalData = data.data
                    console.log('parentModalData', self.parentModalData)
                })
            }
        }
    }
</script>
<style>
    .ui.fullscreen.modal {
        height: 95%!important;
        width: 98%!important;
        margin: 0!important;
    }
    
    .scrolling.undetached.dimmable .ui.scrolling.modal {
        margin-top: 0!important;
    }
</style>