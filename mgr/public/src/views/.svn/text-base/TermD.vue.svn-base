<template>
    <div style="padding:0 20px">
        <div>
            <button class="ui button mini basic">同步组</button>
            <button class="ui button mini basic">同步客户端</button>
        </div>
        <table class="ui celled striped table">
            <thead>
                <tr><th colspan="4">终端信息</th></tr>
            </thead>
            <tbody>
                <tr>
                    <td class="center aligned collapsing">终端名称：</td>
                    <td class="left aligned">{{name}}</td>
                    <td class="center aligned collapsing">在线情况：</td>
                    <td class="left aligned">{{online}}</td>
                </tr>
                <tr>
                    <td class="center aligned collapsing">IP地址：</td>
                    <td class="left aligned">{{ip}}</td>
                    <td class="center aligned collapsing">MAC地址：</td>
                    <td class="left aligned">{{mac}}</td>
                </tr>
                <tr>
                    <td class="center aligned collapsing">所属组：</td>
                    <td class="left aligned">{{group}}</td>
                    <td class="center aligned collapsing">加入时间：</td>
                    <td class="left aligned">{{joindate}}</td>
                </tr>
                <tr>
                    <td class="center aligned collapsing">机器名称：</td>
                    <td class="left aligned">{{sysname}}</td>
                    <td class="center aligned collapsing">最后上线：</td>
                    <td class="left aligned">{{lastdate}}</td>
                </tr>
                <tr>
                    <td class="center aligned collapsing" >操作系统：</td>
                    <td class="left aligned" colspan="3">{{os}}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    module.exports = {
        data: function() {
            return {
                name: 'Hello',
                online: '在线',
                ip: '127.0.0.1',
                mac: '00-0C-29-F8-9C-4F',
                group: '默认分组',
                joindate: '2016-7-29 16:41:11',
                sysname: 'RISING-764EC4EE',
                lastdate: '2016-11-29 10:39:14',
                os: 'Microsoft Windows XP Professional Service Pack 3 (build 2600)'
            }
        }

    }
</script>

<style>
    .ui.table thead th,
    .ui.table tbody td {
        padding: 0.5em 0.7em;
    }
</style>