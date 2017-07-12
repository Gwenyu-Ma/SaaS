<template>
    <div class="ui form search" v-bind:type="type">
        <div v-if="Manage">
            <div class="inline fields">
                <div class="three wide field">
                    <label>eid:</label>
                    <input type="text" name="eid" placeholder="eid">
                </div>
                <div class="three wide field">
                    <label>企业名称:</label>
                    <input type="text" name="orgName" placeholder="企业名称">
                </div>
                <div class="three wide field">
                    <label>用户名称:</label>
                    <input type="text" name="userName" placeholder="用户名称">
                </div>
                <div class="three wide field">
                    <button class="ui primary button" v-on:click="search4M">搜索</button>
                </div>
            </div>
        </div>
        <div v-if="Term">
            <div class="inline fields">
                <div class="three wide field">
                    <label>eid:</label>
                    <input type="text" name="eid" placeholder="eid" disabled v-bind:value="EID">
                </div>
                <!--<div class="three wide field">
                    <label>组名称:</label>
                    <input type="text" name="groupname" placeholder="组名称">
                </div>-->
                <div class="five wide field">
                    <select class="ui search dropdown js_searchType">
                        <option value="computername">计算机名称</option>
                        <option value="ip">IP</option>
                        <option value="mac">MAC</option>
                    </select>
                    <input type="text" name="searchTxt">
                </div>
                <div class="three wide field">
                    <button class="ui primary button" v-on:click="search4T">搜索</button>
                </div>
            </div>
        </div>
        <div v-if="Log">
            <div class="inline fields">
                <div class="three wide field">
                    <label>eid:</label>
                    <input type="text" name="eid" placeholder="eid">
                </div>
                <div class="three wide field">
                    <label>企业名称:</label>
                    <input type="text" name="orgName" placeholder="企业名称">
                </div>
                <div class="three wide field">
                    <label>用户名称:</label>
                    <input type="text" name="userName" placeholder="用户名称">
                </div>
                <div class="three wide field">
                    <button class="ui primary button" >搜索</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import eventHub from '../js/event'

    module.exports = {
        props: ['parentCom', 'EID'],
        data: function() {
            var obj = {
                Term: false,
                Log: false,
                Manage: false,
                SearchParam: {}
            }
            obj[this.parentCom] = true
            obj['type'] = this.parentCom
            return obj
        },
        mounted: function() {
            this.getParam()
            console.log('search', this)
        },
        methods: {
            search4M: function() {
                console.log('search-start-Manage')
                let self = this
                let eid = $.trim($('[name=eid]').val()) || ''
                let oName = $.trim($('[name=orgName]').val()) || ''
                let uName = $.trim($('[name=userName]').val()) || ''
                let data = {
                    eid: eid,
                    oName: oName,
                    uName: uName,
                    paging: {
                        offset: 0
                    }
                }
                self.$router.push({
                    query: {
                        eid: eid,
                        oName: oName,
                        uName: uName
                    }
                })
                self.$emit('search', data)
            },
            search4T: function() {
                console.log('search-start-term')
                var self = this
                let data = {
                    eid: $.trim($('[name=eid]').val()) || '',
                    sKey: $('.js_searchType').val(),
                    sValue: $.trim($('[name=searchTxt]').val()) || '',
                    paging: {
                        offset: 0
                    }
                }
                self.$emit('search', data)
            },
            search4L: function() {
                console.log('search-start-log')
                var self = this
                let data = {
                    searchType: '',
                    a: 1,
                    b: 2
                }
                self.$emit('search', 'Manage')
            },
            getParam: function() {
                console.log('getParam', this)
                let el = $(this.$el)
                let type = el.attr('type')
                console.log('__type__', type)
                switch (type) {
                    case 'Manage':
                        break
                    case 'Term':
                        break
                    case 'Log':
                        break
                    default:
                        console.error('getParam error not in [Manage,Term,Log]')
                        break
                }
            }
        }
    }
</script>
<style>
    .search label {
        white-space: nowrap;
    }
</style>
