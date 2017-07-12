<template>
    <div style="padding:10px 20px;">
        <search-com v-on:search="tableRef" parent-com="Manage"></search-com>
        <info-com v-bind:eids="eids"></info-com>
        <table-com parent-com="Manage" v-bind:query="query" v-bind:parentdata="parentData" v-on:showmodal="showModal" v-on:modifyeids="modifyEids"
            v-on:search="tableRef"></table-com>
        <!--<modal-com v-bind:enterEid="enterEid" v-bind:enterInfo="enterInfo"/>-->
    </div>
</template>
<script>
    import SearchCom from '../components/Search.vue'
    import InfoCom from '../components/Info.vue'
    import TableCom from '../components/Table.vue'
    import ModalCom from '../components/Modal.vue'

    import eventHub from '../js/event'
    import server from '../js/data'

    module.exports = {
        data: function () {
            let eid = this.$route.query.eid || ''
            let oName = this.$route.query.oName || ''
            let uName = this.$route.query.uName || ''
            return {
                greeting: 'Hello',
                parentData: [],
                enterEid: eid,
                enterInfo: {},
                eids: [],
                query: {
                    eid: eid,
                    oName: oName,
                    uName: uName,
                    paging: {
                        order: 0,
                        limit: 10,
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
            ModalCom
        },
        created: function () {
            var self = this

            eventHub.$on('search-toggle', function (data) {
                console.log('search-toggle-start')
                self.referTable('Manage')
            })

            self.tableUpdate(this.query)
            console.log('parentData-create', self.parentData)
        },
        mounted: function () {},
        watch: {
            eids: function (val, oldval) {
                console.log('views Manage', 'eids', val)
            }
        },
        methods: {
            tableRef: function (data) {
                console.info('view Manage tableRef', data)
                var self = this
                $.extend(true, self.query, data)
                server.getEnterprise(self.query, function (data, status, xhr) {
                    self.parentData = data.data
                    console.log('parentData2', self.parentData)
                })
            },
            tableUpdate: function (query) {
                var self = this
                server.getEnterprise(query, function (data, status, xhr) {
                    self.parentData = data.data
                    console.log('parentData2', self.parentData)
                })
            },
            showModal: function (val, data) {
                var self = this
                console.log('showModal-val', val, data)
                if (val) {
                    self.enterEid = val
                    self.enterInfo = data
                    $('.ui.modal.fullscreen').modal({
                        closable: false,
                        detachable: false
                    }).modal('show')
                }
            },
            modifyEids: function (method, val) {
                var self = this
                var idx = self.eids.indexOf(val)
                if (method === 'add') {
                    if (idx < 0) {
                        self.eids.push(val)
                    }
                }
                if (method === 'del') {
                    self.eids.splice(idx, 1)
                }
            }
        }

    }

</script>
