<template>
    <div style="padding:0 20px;">
        <search-com parent-com="Log"></search-com>
        <table-com parent-com="Log" v-bind:query="query" v-bind:parentdata="parentData" v-on:search="tableRef"></table-com>
    </div>
</template>

<script>
    import SearchCom from '../components/Search.vue'
    import ListCom from '../components/List.vue'
    import TableCom from '../components/Table.vue'
    module.exports = {
        data: function() {
            return {
                parentData: [],
                query: {
                    paging: {}
                }
            }
        },
        components: {
            SearchCom,
            ListCom,
            TableCom
        }

    }
</script>