<template>
    <div class="ui list group-list">
        <div class="item" v-for="item in model">
            <i class="chevron down icon" v-if="item.show" v-on:click="toggle(item)"></i>
            <i class="chevron right icon" v-if="!item.show" v-on:click="toggle(item)"></i>
            <div class="content">
                <div class="header" v-on:click="changeGroup()">{{item.name}}</div>
                <div class="list" v-if="item.show">
                    <div class="item" v-for="child in item.children">
                        <i class=""></i>
                        <div class="content">
                            <div class="header" v-bind:groupid="child.id" v-on:click="changeGroup(child.id)">{{child.name}}</div>
                            <div class="list" v-if="child.children">
                                <div class="item" v-for="chi in child.children">
                                    <i class=""></i>
                                    <div class="content">
                                        <div class="header" v-bind:groupid="chi.id" v-on:click="changeGroup(chi.id)">{{chi.name}}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import server from '../js/data'
    import eventHub from '../js/event'
    module.exports = {
        props: ['EID'],
        data: function () {
            console.log('List --data eid', this.EID)

            /*  , {        name: '无效终端',
                           show: false,
                           children: []
            } */
            return {
                tmpModel: [],
                model: [{
                    name: '默认终端',
                    show: true,
                    children: []
                }]
            }
        },
        mounted: function () {
            console.log('List mounted eid', this.EID)
            this.getData(this.EID)
            let self = this
            // $('.group-list .header').on('click', function () {
            //     let groupid = $(this).attr('groupid')
            //     self.$emit('changeGroup', groupid)
            // })
        },
        watch: {
            tmpModel: function (val, oldval) {
                let self = this
                console.log('tmpModel', val)
                if (val) {
                    self.model[0]['children'] = []
                    let type2model = []
                    // self.model[1]['children'] = []
                    val.forEach(function (val, idx, arr) {
                        if (val['type'] === 1) {
                            self.model[0].children.push({
                                id: val['id'],
                                name: val['groupname']
                            })
                        } else {
                            type2model.push({
                                id: val['id'],
                                name: val['groupname']
                            })
                        }
                    })
                    self.model[0]['children'] = self.model[0]['children'].concat(type2model)
                }
            },
            EID: function (val, oldval) {
                console.log('List -- eid', val)
                if (val) {
                    this.getData(val)
                }
            }
        },
        methods: {
            toggle: function (item) {
                item.show = !item.show
            },
            getData: function (eid) {
                let self = this
                server.getGroups(eid, function (data, status, xhr) {
                    self.tmpModel = data.data.rows
                })
            },
            changeGroup: function (val) {
                this.$emit('changeGroup', val || '')
            }
        }
    }

</script>
<style scoped>
    .header {
        cursor: pointer
    }

    .content>.header {
        font-weight: normal!important;
        font-size: 0.8em;
        height: 20px;
    }

    .group-list .content .content {
        padding-left: 10px !important;
    }

    .group-list .content {
        padding: 0!important;
    }

    .chevron {
        position: relative;
        top: 10px;
        left: 20px;
        cursor: pointer;
    }

</style>
