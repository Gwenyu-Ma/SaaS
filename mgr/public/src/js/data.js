module.exports = {
    AjaxUrl: '/',
    getEnterprise: function (query, callback) {
        /* test */
        // query = {     'eid': '' }
        /* test */
        $.ajax({
            url: this.AjaxUrl + 'org/getOrgs',
            type: 'POST',
            data: query,
            dataType: 'json',
            success: function (data, status, xhr) {
                callback && callback(data, status, xhr)
            }
        })
    },
    getClients: function (query, callback) {
        /* test */
        // query = {     'eid': 'D05D6DE488005623' }
        /* test */
        $.ajax({
            url: this.AjaxUrl + 'client/getClients',
            type: 'POST',
            data: query,
            dataType: 'json',
            success: function (data, status, xhr) {
                callback && callback(data, status, xhr)
            }

        })
    },
    getUsedSpace: function (eid, callback) {
        $.ajax({
            url: this.AjaxUrl + 'org/usedspace',
            type: 'POST',
            data: {
                'eid': eid
            },
            dataType: 'json',
            success: function (data, status, xhr) {
                callback && callback(data, status, xhr)
            }

        })
    },
    /* 组结构 */
    getGroups: function (eid, callback) {
        $.ajax({
            url: this.AjaxUrl + 'group/getgroups',
            type: 'POST',
            data: {
                'eid': eid
            },
            dataType: 'json',
            success: function (data, status, xhr) {
                callback && callback(data, status, xhr)
            }

        })
    },

    /* 命令 */
    initClientW: function (eids, callback) {
        $.ajax({
            url: this.AjaxUrl + 'client/initClientW',
            type: 'POST',
            data: {
                'eid': eids
            },
            dataType: 'json',
            success: function (data, status, xhr) {
                callback && callback(data, status, xhr)
            }

        })
    },
    initXav: function (eids, callback) {
        $.ajax({
            url: this.AjaxUrl + 'xav/initXav',
            type: 'POST',
            data: {
                'eid': eids
            },
            dataType: 'json',
            success: function (data, status, xhr) {
                callback && callback(data, status, xhr)
            }

        })
    },
    initRfwBNS: function (eids, callback) {
        $.ajax({
            url: this.AjaxUrl + 'xav/initRfwBNS',
            type: 'POST',
            data: {
                'eid': eids
            },
            dataType: 'json',
            success: function (data, status, xhr) {
                callback && callback(data, status, xhr)
            }

        })
    },
    initPhoneSpam: function (eids, callback) {
        $.ajax({
            url: this.AjaxUrl + 'xav/initPhoneSpam',
            type: 'POST',
            data: {
                'eid': eids
            },
            dataType: 'json',
            success: function (data, status, xhr) {
                callback && callback(data, status, xhr)
            }

        })
    },
    initRfwTFA: function (eids, callback) {
        $.ajax({
            url: this.AjaxUrl + 'xav/initRfwTFA',
            type: 'POST',
            data: {
                'eid': eids
            },
            dataType: 'json',
            success: function (data, status, xhr) {
                callback && callback(data, status, xhr)
            }

        })
    },
    initRfwUrlByResult: function (eids, callback) {
        $.ajax({
            url: this.AjaxUrl + 'xav/initRfwUrlByResult',
            type: 'POST',
            data: {
                'eid': eids
            },
            dataType: 'json',
            success: function (data, status, xhr) {
                callback && callback(data, status, xhr)
            }

        })
    },
    initClientOS: function (eids, callback) {
        $.ajax({
            url: this.AjaxUrl + 'client/initClientOS',
            type: 'POST',
            data: {
                'eid': eids
            },
            dataType: 'json',
            success: function (data, status, xhr) {
                callback && callback(data, status, xhr)
            }

        })
    },
    /* 获取所有客户端数 */
    getAllTermInfo: function (callback) {
        $.ajax({
            url: this.AjaxUrl + 'client/getClientOSTypeStat',
            type: 'POST',
            dataType: 'json',
            success: function (data, status, xhr) {
                callback && callback(data, status, xhr)
            }
        })
    },

    /* 报表 */
    /* 每日新增终端 */
    getTermDayRaise: function (callback) {
        $.ajax({
            url: this.AjaxUrl + 'client/getNewClientTrend',
            type: 'POST',
            dataType: 'json',
            success: function (data, status, xhr) {
                callback && callback(data, status, xhr)
            }
        })
    },
    /* 每日新增用户 */
    getUserDayRaise: function (val, callback) {
        $.ajax({
            url: this.AjaxUrl + 'org/getNewOrgTrend',
            type: 'POST',
            data: {
                'top': val || 7
            },
            dataType: 'json',
            success: function (data, status, xhr) {
                callback && callback(data, status, xhr)
            }
        })
    },
    /* 最新注册用户 */
    getorgSize: function (callback) {
        $.ajax({
            url: this.AjaxUrl + 'org/getneworgs',
            type: 'POST',
            dataType: 'json',
            success: function (data, status, xhr) {
                callback && callback(data, status, xhr)
            }
        })
    },
    getTermReaytime: function (callback) {
        $.ajax({
            url: this.AjaxUrl + 'client/getClientOnLineStatByTime',
            type: 'POST',
            dataType: 'json',
            success: function (data, status, xhr) {
                callback && callback(data, status, xhr)
            }
        })
    }

}
