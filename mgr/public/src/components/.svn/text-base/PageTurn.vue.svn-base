<template>
    <div style="width:100%;">
        <div class="ui right floated pagination menu js_pageturn">
            <a class="icon item" v-on:click="pageFirst">
                <i class="angle double left icon"></i>
            </a>
            <a class="icon item" v-on:click="pagePrev">
                <i class="angle left icon"></i>
            </a>
            <input type="text" v-bind:value="pageNum" v-on:change="pageGo">/<span>{{totalPage}}</span>
            <a class="icon item" v-on:click="pageNext">
                <i class="angle right icon"></i>
            </a>
            <a class="icon item" v-on:click="pageLast">
                <i class="angle double right icon"></i>
            </a>
        </div>
        <div class="ui right floated pagination menu">
            <span class="item">每页显示</span>
            <select class="item page-limit js_page_limit" v-on:change="pageLimit">
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="30">30</option>
                <option value="40">40</option>
                <option value="50">50</option>
            </select>
            <span class="item">条</span>
        </div>
        <div v-if="exported" class="ui small  button">导出</div>
    </div>
</template>
<script>
    module.exports = {
        props: ['pageNumInfo', 'total', 'exported'],
        data: function () {
            console.info('PageTurn')
            let limit = this.pageNumInfo.limit || 10
            let totalPage = Math.ceil(this.total / limit)
            totalPage = (totalPage === 0 ? 1 : totalPage)
            let offset = this.pageNumInfo.offset
            let pageNum = parseInt(offset / limit) + 1
            console.info('pageturn data', {
                offset: offset,
                limit: limit,
                totalPage: totalPage,
                pageNum: pageNum
            })
            return {
                offset: offset,
                limit: limit,
                totalPage: totalPage,
                pageNum: pageNum
            }
        },
        watch: {
            'pageNumInfo': function (val, oldval) {
                console.info('pageturn pageNumInfo change', val)
                this.handle()
            },
            'total': function (val, oldval) {
                console.info('pageturn total change', val)
                this.handle()
            },
            'limit': function (val, oldval) {
                console.info('pageturn limit change', val)
                this.handle()
                $('.js_page_limit').find('option[value=' + this.limit + ']').prop('selected', true)
            }
        },
        methods: {
            handle: function () {
                console.info('pageTurn data change')
                this.limit = this.pageNumInfo.limit || 10
                this.totalPage = Math.ceil(this.total / this.limit)
                this.totalPage = (this.totalPage === 0 ? 1 : this.totalPage)
                this.offset = this.pageNumInfo.offset
                this.pageNum = parseInt(this.offset / this.limit) + 1
            },
            pageFirst: function () {
                this.pageNum = 1
                this.$emit('pageChange', {
                    offset: 0
                })
            },
            pageLast: function () {
                this.pageNum = this.totalPage
                this.$emit('pageChange', {
                    offset: this.limit * (this.totalPage - 1)
                })
            },
            pagePrev: function () {
                if (this.pageNum > 1) {
                    this.pageNum -= 1
                    this.$emit('pageChange', {
                        offset: this.limit * (this.pageNum - 1)
                    })
                }
            },
            pageNext: function () {
                if (this.pageNum < this.totalPage) {
                    this.pageNum += 1
                    this.$emit('pageChange', {
                        offset: this.limit * (this.pageNum - 1)
                    })
                }
            },
            pageGo: function (event) {
                let num = event.target.value
                if (num > this.totalPage) {
                    num = this.totalPage
                }
                if (num < 0) {
                    num = 0
                }
                this.pageNum = num
                this.$emit('pageChange', {
                    offset: this.limit * (this.pageNum - 1)
                })
            },
            pageLimit: function (event) {
                let dom = $(event.target)
                this.limit = dom.find('option:selected').val()
                this.$emit('pageChange', {
                    limit: this.limit,
                    offset: 0
                })
            }
        }
    }

</script>

<style>
    .js_pageturn {
        line-height: 34px;
        font-size: 16px;
        outline: none;
    }

    .js_pageturn input {
        border: 0;
        text-align: center;
        width: 20px;
    }

    .js_pageturn span {
        padding: 0 3px;
    }

    select.page-limit {
        padding: 0.5em!important;
        border: 0;
        border-right: 1px solid rgba(34, 36, 38, .1);
        outline: none;
    }

    select option{
        font-size: 13px;
    }
</style>
