define(function(require) {
    var tpl = require('text!policy/53246C2F-F2EA-4208-9C6C-8954ECF2FA27_2.html');
    require('table');
    require('css!table');
    require('dep/jquery.md5');
    require('selectric');
    require('css!selectric');
    require('datetimepicker');
    require('css!datetimepicker')
    //var mustache = require('mustache');
    //var xmlTpl = require('text!policy/D49170C0-B076-4795-B079-0F97560485AF_1.xml');

    var op = {
        view:null,
        init: function(container, policy) {
            this.view = container;
            // 绑定事件
            this.bindEvent(container);
            this.validationEvent(container);
            // 初始化策略内容
            if (policy) {
                // 策略初始化赋值
                //console.log(policy);
                this.toHtml(container, policy);
            }else{
                /*时间控件*/
                this.initTime(container);
                this.initNetPro(container);
            }
            // 下拉列表美化
            container.find('select').selectric({
                inheritOriginalWidth: true
            });
        },
        bindEvent: function(container) {
            // 锁定图标
            container.on('click', 'i.lock', function() {
                $(this).toggleClass('enableLock');
                return false;
            });

            container.on('change', '.js_time_mode', function() {
                var that = $(this);
                var val = that.val();
                that.closest('dl').find('.js_time' + val).show().siblings('div').hide();
            });

            var urls_html = '<li>' +
                '<textarea class="input-large" name="urls" validation="noequal require" targets=".js_urls"></textarea>' +
                '<label class="checkbox inline"><input type="checkbox" value="" name="alert">提示</label>' +
                '<label class="checkbox inline"><input type="checkbox" value="" name="control">拦截</label>' +
                '</li>';
            container.on('click', '.add_urls', function() {
                $(this).closest('dl').find('.js_urls').append(urls_html);
            });

            var rule_html = '<dl>' +
                '<dt><i name="TimeRule_lock" class="lock"></i></dt>' +
                '<dt>' +
                '<span>受限时间</span>' +
                '</dt>' +
                '<dd>' +
                '<label class="mb5">' +
                '<select class="input-small js_time_mode">' +
                '<option value="0">每天</option>' +
                '<option value="1">每周</option>' +
                '<option value="2">时间段</option>' +
                '</select>' +
                '</label>' +
                '<div class="js_time0">' +
                '<input type="text" value="" class="input-mini js_time" name="start_time" validation="require">' +
                '<span>~</span>' +
                '<input type="text" value="" class="input-mini js_time" name="end_time" validation="require">' +
                '<span class="help-inline">(例 00:00 ~ 23:59)</span>' +
                '</div>' +
                '<div class="hide js_time1">' +
                '<label class="checkbox inline"><input type="checkbox" name="week">周一</label>' +
                '<label class="checkbox inline"><input type="checkbox" name="week">周二</label>' +
                '<label class="checkbox inline"><input type="checkbox" name="week">周三</label>' +
                '<label class="checkbox inline"><input type="checkbox" name="week">周四</label>' +
                '<label class="checkbox inline"><input type="checkbox" name="week">周五</label>' +
                '<label class="checkbox inline"><input type="checkbox" name="week">周六</label>' +
                '<label class="checkbox inline"><input type="checkbox" name="week">周日</label>' +
                '<input type="text" value="" class="input-mini js_time ml10" name="start_time">' +
                '<span>~</span>' +
                '<input type="text" value="" class="input-mini js_time" name="end_time"  validation="require">' +
                '<span class="help-inline">(例 00:00 ~ 23:59)</span>' +
                '</div>' +
                '<div class="hide js_time2">' +
                '<span>日期</span>' +
                '<input type="text" value="" class="input-medium js_date" name="start_date" validation="require">' +
                '<span>到</span>' +
                '<input type="text" value="" class="input-medium js_date"  name="end_date" validation="require">' +
                '</div>' +
                '</dd>' +
                '<dt>' +
                '<span>受限网址</span>' +
                '<button class="btn btn-success btn-small ml10 add_urls" >添加</button>' +
                '</dt>' +
                '<dd>' +
                '<ul class="unstyled js_urls">' +
                '<li>' +
                '<textarea class="input-large" name="urls" validation="noequal require" targets=".js_urls"></textarea>' +
                '<label class="checkbox inline"><input type="checkbox" value="" name="alert">提示</label>' +
                '<label class="checkbox inline"><input type="checkbox" value="" name="control">拦截</label>' +
                '</li>' +
                '</ul>' +
                '</dd>' +
                '<dt>跳转至</dt>' +
                '<dd>' +
                '<label><input type="text" value="" class="input-large redirect" validation="require"></label>' +
                '</dd>' +
                '</dl>';
            container.on('click', '#add_rules', function() {
                container.find('.browserAuditList').append(rule_html);
                /*时间控件*/
                op.initTime(container.find('.browserAuditList dl:last'));               
            });


            /*联网管理*/
            //this.initNetPro(container);
            var ModalTarget = null;
            container.on('click', '.net_add_pro', function() {
                $('.js_dialog_net_pro').modal();
                ModalTarget = $(this).closest('dl');
            });
            container.on('click', '.js_dialog_net_pro .js_sure', function() {
                var fa = $(this).closest('.js_dialog_net_pro').find('.tab-pane.active');
                var val = '';
                var softType = '';
                if (fa.attr('id') == 'tab1') {
                    //自定义软件
                    val = container.find('#js_sys_macro').val() + container.find('#js_sys_path').val();
                    softType = 2;
                } else {
                    //服务名
                    val = container.find('#js_server_name').val();
                    softType = 3;
                }
                if (val) {  
                    var data = {
                        Name: val,
                        md5: 0,
                        listen: 0,
                        outside: 0,
                        type: softType
                    };
                    var _table = ModalTarget.find('.net_pros');
                    var arrVals = [];
                    var trs = _table.find('tbody tr');
                    for(var i=0;i<trs.length;i++){
                        var txt = $(trs[i]).find('td:eq(0) span').text();
                        arrVals.push(txt);
                        if(arrVals.indexOf(val)>-1){
                            container.find('#js_sys_path,#js_server_name').addClass('error');
                            return false;
                        }
                    }
                    container.find('#js_sys_path,#js_server_name').removeClass('error');
                    _table.bootstrapTable('append', data);
                    container.find('#js_server_name,#js_sys_path').val('');
                    container.find('#js_sys_macro option:eq(0)')[0].selected = true;
                    $('.js_dialog_net_pro').modal('hide');
                }

            });
            container.on('click', '.js_dialog_net_pro .js_cancel', function() {
                $('.js_dialog_net_pro').modal('hide');
                container.find('#js_server_name,#js_sys_path').val('');
                container.find('#js_sys_macro option:eq(0)')[0].selected = true;
            });
            var net_rules_html = '<dl>' +
                '<dt>' +
                '<i name="TimeRule_lock" class="lock"></i>' +
                '</dt>' +
                '<dt>' +
                '<span>受限时间</span>' +
                '</dt>' +
                '<dd>' +
                '<label class="mb5">' +
                '<select class="input-small js_time_mode">' +
                '<option value="0">每天</option>' +
                '<option value="1">每周</option>' +
                '<option value="2">时间段</option>' +
                '</select>' +
                '</label>' +
                '<div class="js_time0">' +
                '<input type="text" value="" class="input-mini js_time" name="start_time" validation="require">' +
                '<span>~</span>' +
                '<input type="text" value="" class="input-mini js_time" name="end_time" validation="require">' +
                '<span class="help-inline">(例 00:00 ~ 23:59)</span>' +
                '</div>' +
                '<div class="hide js_time1">' +
                '<label class="checkbox inline"><input type="checkbox" name="week">周日</label>' +
                '<label class="checkbox inline"><input type="checkbox" name="week">周一</label>' +
                '<label class="checkbox inline"><input type="checkbox" name="week">周二</label>' +
                '<label class="checkbox inline"><input type="checkbox" name="week">周三</label>' +
                '<label class="checkbox inline"><input type="checkbox" name="week">周四</label>' +
                '<label class="checkbox inline"><input type="checkbox" name="week">周五</label>' +
                '<label class="checkbox inline"><input type="checkbox" name="week">周六</label>' +
                '<input type="text" value="" class="input-mini ml10 js_time" name="start_time">' +
                '<span>~</span>' +
                '<input type="text" value="" class="input-mini js_time" name="end_time" validation="require">' +
                '<span class="help-inline">(例 00:00 ~ 23:59)</span>' +
                '</div>' +
                '<div class="hide js_time2">' +
                '<span>日期</span>' +
                '<input type="text" value="" class="input-medium js_date" name="start_date" validation="require">' +
                '<span>到</span>' +
                '<input type="text" value="" class="input-medium js_date"  name="end_date"> validation="require"' +
                '</div>' +
                '</dd>' +
                '<dt>' +
                '<span>受限程序</span>' +
                '<button  class="btn btn-success btn-small ml10 net_add_pro" >添加</button>' +
                '</dt>' +
                '<dd>' +
                '<table class="net_pros"></table>' +
                '</dd>' +
                '</dl>';
            container.on('click', '#net_add_rules', function() {
                container.find('.netlist').append(net_rules_html);
                op.initNetPro(container.find('.netlist dl:last'));
                /*时间控件*/
                op.initTime(container.find('.netlist dl:last'));  
            });

            //IP控制列表
            container.on('change', '[name=ip_select]', function() {
                var val = $(this).val();
                if (val == 1) {
                    container.find('.js_ip_select_target').removeClass('hide');
                } else {
                    container.find('.js_ip_select_target').addClass('hide');
                }
            });

            container.on('click', '#list_add', function() {
                var type = container.find('.start_ip').val();
                var startIp = container.find('.start_ip'),
                    endIp = container.find('.end_ip'),
                    flag = false;
                if(type == 0) {
                    if(!startIp.val()){
                        startIp.tooltip({
                            title: '内容不能为空',
                            trigger: 'manual'
                        }).tooltip('show');
                        flag = true;
                    }else{
                        startIp.tooltip('hide');
                    }
                }else{
                    if(!startIp.val()){
                        startIp.tooltip({
                            title: '内容不能为空',
                            trigger: 'manual'
                        }).tooltip('show');
                        flag = true;
                    }else{
                        startIp.tooltip('hide');
                    }
                    if(!endIp.val()){
                        endIp.tooltip({
                            title: '内容不能为空',
                            trigger: 'manual'
                        }).tooltip('show');
                        flag = true;
                    }else{
                         endIp.tooltip('hide');
                    }
                }
                if(flag){
                    return false;
                }
                var arr = [];
                arr.push(type);
                arr.push(startIp.val());
                if (type == 1) {
                    arr.push(endIp.val());
                }
                container.find('#ip_list').append('<tr><td val="' + arr.join('|') + '">' + arr.slice(1).join('--') + '</td><td style="width:80px;"><a href="javascript:;" class="js_remove">删除</td></tr>');
            });

            container.on('keyup','.start_ip,.end_ip',function(){
                if($(this).val()){
                    $(this).tooltip('hide');
                }
            })

            container.on('click', '#ip_list .js_remove', function() {
                $(this).closest('tr').remove();
            });

        },
        initTime:function(container){
            container.find('.js_time[name=start_time]').datetimepicker({
                datepicker: false,
                format: 'H:i',
                step: 1,
                onShow:function(ct,target){
                    this.setOptions({
                        maxTime:$(target).parent().find('[name=end_time]').val()?$(target).parent().find('[name=end_time]').val():false
                    })
                }
            });
            container.find('.js_time[name=end_time]').datetimepicker({
                datepicker: false,
                format: 'H:i',
                step: 1,
                onShow:function(ct,target){
                    this.setOptions({
                        minTime:$(target).parent().find('[name=start_time]').val()?$(target).parent().find('[name=start_time]').val():false
                    })
                }
            });
            container.find('.js_date[name=start_date]').datetimepicker({
                format: 'Y-m-d H:i',
                step: 1,
                onShow:function(ct,target){
                    this.setOptions({
                        maxDate:$(target).parent().find('[name=end_date]').val()?$(target).parent().find('[name=end_date]').val():false,
                        maxTime:$(target).parent().find('[name=end_date]').val()?$(target).parent().find('[name=end_date]').val():false
                    })
                }
            });
            container.find('.js_date[name=end_date]').datetimepicker({
                format: 'Y-m-d H:i',
                step: 1,
                onShow:function(ct,target){
                    this.setOptions({
                        minDate:$(target).parent().find('[name=start_date]').val()?$(target).parent().find('[name=start_date]').val():false,
                        minTime:$(target).parent().find('[name=start_date]').val()?$(target).parent().find('[name=start_date]').val():false
                    })
                }
            });
        },
        initNetPro: function(container, data) {
            data = data || [];
            container.find('.net_pros').bootstrapTable({
                columns: [{
                    field: 'Name',
                    title: '受限程序',
                    align: 'left',
                    sortable: false,
                    formatter: function(value, row, index) {
                        var html = '<span _type="' + row.type + '">' + row.Name + '</span>';
                        return html;
                    }
                }, {
                    field: 'md5',
                    title: '防篡改',
                    align: 'center',
                    width: '60px',
                    sortable: false,
                    formatter: function(value, row, index) {
                        var checked = row.md5 ? 'checked' : '';
                        var html = '<input type="checkbox" name="md5" ' + checked + '>';
                        return html;
                    }
                }, {
                    field: 'listen',
                    title: '监控',
                    align: 'center',
                    width: '60px',
                    sortable: false,
                    formatter: function(value, row, index) {
                        var checked = row.md5 ? 'checked' : '';
                        var html = '<input type="checkbox" name="listen" ' + checked + '>';
                        return html;
                    }
                }, {
                    field: 'outside',
                    title: '联网',
                    align: 'center',
                    width: '60px',
                    sortable: false,
                    formatter: function(value, row, index) {
                        var checked = row.md5 ? 'checked' : '';
                        var html = '<input type="checkbox" name="outside" ' + checked + '>';
                        return html;
                    }
                }, {
                    field: 'op',
                    title: '操作',
                    align: 'center',
                    width: '120px',
                    sortable: false,
                    formatter: function(value, row, index) {
                        var html = '<a class="remove" href="javascript:void(0)" title="Remove">删除</a>';
                        return html;
                    },
                    events: {
                        'click .remove': function(e, value, row, index) {
                            $(e.target).closest('tr').remove();
                            if ($('#net_pros tbody tr').length < 1) {
                                container.find('#net_pros').bootstrapTable('load', []);
                            }
                        }
                    }
                }],
                data: data,
                height: 200,
                formatNoMatches: function() {
                    return '当前没有受限程序';
                }
            });
        },
        valida:function(){
            op.view.find('[validation]').trigger('blur');
            if(op.view.find('.error').length){
                return false;
            }
            return true;
        },
        validationEvent:function(container){
            /*错误验证事件*/
            var rule = {
                require:function(obj){
                    return obj.val().length>0;
                },
                intNum:function(obj){
                    var val = obj.val();
                    return /^\d+$/.test(val);
                },
                ip:function(obj){
                    var val = obj.val();
                    return /^(25[0-5]|2[0-4]\d|[01]?\d\d?)\.(25[0-5]|2[0-4]\d|[01]?\d\d?)\.(25[0-5]|2[0-4]\d|[01]?\d\d?)\.(25[0-5]|2[0-4]\d|[01]?\d\d?)$/i.test(val);
                },
                noequal:function(obj){
                    var val = obj.val(),
                        targets = obj.attr('targets'),
                        tag = obj.closest(targets).find('[validation=noequal]'),
                        vals = [];
                    for(var i =0;i<tag.length;i++){
                        var tg = $(tag[i]),
                            v = tg.val();
                        if(tg.val()!=""){
                            var idx = vals.indexOf(v);
                            if(idx>-1){
                                $(tag).eq(idx).addClass('error');
                                obj.addClass('error');
                                return false;
                            }
                        }
                        vals.push(v);
                    }
                    tag.removeClass('error');
                    return true;
                }
            }

            container.on('blur','[validation]',function(){
                var that = $(this),
                    funcs = that.attr('validation').split(/\s+/),
                    len = funcs.length,
                    num = 0;
                if((funcs.indexOf('require')<0) && that.val() == ''){
                    that.removeClass('error');
                    return true;
                }
                for(var i=0;i<len;i++){
                    if(rule[funcs[i]].call(null,that)){
                        num++;
                    }else{
                        break;
                    }
                }
                if(num == len){
                    that.removeClass('error');
                }else{
                    that.addClass('error');
                }
                
            }) 
        },
        toJson: function(container) {
            function getRule(dl) {
                var TimeRule = {};
                var timeTxt = getTime(dl);
                TimeRule['@attributes'] = {
                    lock: Number(dl.find('[name=TimeRuleList_lock_TimeRule]').hasClass('enableLock')),
                    id: timeTxt
                };
                TimeRule.ValidTime = {};
                TimeRule.ValidTime['@value'] = timeTxt;
                TimeRule.RuleList = {};
                TimeRule.RuleList.Rule = [];
                var Rules = dl.find('.js_urls li');
                var _Redirect = dl.find('.redirect').val();
                for (var i = 0; i < Rules.length; i++) {
                    var R = $(Rules[i]);
                    var V = R.find('[name=urls]').val();
                    var Rule = {};
                    Rule['@attributes'] = {
                        id: $.md5(V)
                    };
                    Rule.Url = {};
                    Rule.Url['@value'] = V;
                    Rule.ControlMode = {};
                    Rule.ControlMode['@value'] = R.find('[name=control]').prop('checked') ? 1 : 2;
                    Rule.Alert = {};
                    Rule.Alert['@value'] = Number(R.find('[name=alert]').prop('checked'));
                    Rule.Redirect = {};
                    Rule.Redirect['@value'] = _Redirect;
                    TimeRule.RuleList.Rule.push(Rule);
                }

                return TimeRule;
            }

            function getRule2(dl) {
                var TimeRule = {};
                var timeTxt = getTime(dl);
                TimeRule['@attributes'] = {
                    lock: Number(dl.find('[name=TimeRule_lock]').hasClass('enableLock')),
                    id: timeTxt
                };
                TimeRule.ValidTime = {};
                TimeRule.ValidTime['@value'] = timeTxt;
                TimeRule.RuleList = {};
                TimeRule.RuleList.Rule = [];
                var Rules = dl.find('.net_pros tbody tr:not(.no-records-found)');
                for (var i = 0; i < Rules.length; i++) {
                    var R = $(Rules[i]);
                    var Name = R.find('td:eq(0) span').text()
                    var Rule = {};
                    Rule['@attributes'] = {
                        id: Name
                    };
                    Rule.CheckMd5 = {};
                    Rule.CheckMd5['@value'] = Number(R.find('[name=md5]').prop('checked'));
                    Rule.AllowListen = {};
                    Rule.AllowListen['@value'] = Number(R.find('[name=listen]').prop('checked'));
                    Rule.AllowOutside = {};
                    Rule.AllowOutside['@value'] = Number(R.find('[name=outside]').prop('checked'));
                    Rule.SoftType = {};
                    Rule.SoftType['@value'] = R.find('td:eq(0) span').attr('_type');
                    Rule.SoftId = {};
                    Rule.SoftId['@value'] = Name;
                    TimeRule.RuleList.Rule.push(Rule);
                }

                return TimeRule;
            };

            function getTime(dl) {
                var time_mode = dl.find('.js_time_mode');
                var time_mode_val = time_mode.val();
                var targetTime = dl.find('.js_time' + time_mode_val);
                var arr = [];
                arr.push(time_mode_val);
                if (time_mode_val == 0) {
                    arr.push(targetTime.find('[name=start_time]').val());
                    arr.push(targetTime.find('[name=end_time]').val());
                } else if (time_mode_val == 1) {
                    arr.push(setDetialWek(targetTime.find('[name=week]')));
                    arr.push(targetTime.find('[name=start_time]').val());
                    arr.push(targetTime.find('[name=end_time]').val());
                } else {
                    //日期格式2015-9-7 00:00
                    arr.push(targetTime.find('[name=start_date]').val());
                    arr.push(targetTime.find('[name=end_date]').val());
                }
                return arr.join('|');

            };

            function setDetialWek(obj) {
                var arr = [];
                for (var i = 0; i < obj.length; i++) {
                    if (obj[i].checked) {
                        arr.push('1');
                    } else {
                        arr.push('0');
                    }
                }
                var str = arr.reverse().join('');
                return parseInt(str, 2);
            };

            var product = {};

            /*网址访问 start*/
            product.BrowserAudit = {};
            product.BrowserAudit['@attributes'] = {
                lock: Number(container.find('#BrowserAudit_lock').hasClass('enableLock'))
            };
            product.BrowserAudit.MonStatus = {};
            product.BrowserAudit.MonStatus['@attributes'] = {
                lock: Number(container.find('#BrowserAudit_lock_MonStatus').hasClass('enableLock'))
            };
            product.BrowserAudit.MonStatus['@value'] = Number(container.find('#BrowserAudit_MonStatus').prop('checked'));
            /*
            product.BrowserAudit.LogStatus = {};
            product.BrowserAudit.LogStatus['@attributes'] = {
                lock: Number(container.find('#BrowserAudit_lock_LogStatus').hasClass('enableLock'))
            };
            product.BrowserAudit.LogStatus['@value'] = Number(container.find('#BrowserAudit_LogStatus').prop('checked'));
            */
            product.BrowserAudit.LogAllWeb = {};
            product.BrowserAudit.LogAllWeb['@attributes'] = {
                lock: Number(container.find('#BrowserAudit_lock_LogAllWeb').hasClass('enableLock'))
            };
            product.BrowserAudit.LogAllWeb['@value'] = Number(container.find('#BrowserAudit_LogAllWeb').prop('checked'));
            product.BrowserAudit.TimeRuleList = {};
            product.BrowserAudit.TimeRuleList['@attributes'] = {
                lock: Number(container.find('#TimeRule_lock').hasClass('enableLock'))
            };
            product.BrowserAudit.TimeRuleList.TimeRule = [];
            var dls = container.find('.browserAuditList dl');
            for (var i = 0; i < dls.length; i++) {
                var dl = $(dls[i]);
                var TimeRule = getRule(dl);
                product.BrowserAudit.TimeRuleList.TimeRule.push(TimeRule);
            }
            /*网址访问 end*/

            /*联网程序 start*/
            product.NetProcAudit = {};
            product.NetProcAudit['@attributes'] = {
                lock: Number(container.find('#NetProcAudit_lock').hasClass('enableLock'))
            };
            product.NetProcAudit.MonStatus = {};
            product.NetProcAudit.MonStatus['@attributes'] = {
                lock: Number(container.find('#NetProcAudit_lock_MonStatus').hasClass('enableLock'))
            };
            product.NetProcAudit.MonStatus['@value'] = Number(container.find('#NetProcAudit_MonStatus').prop('checked'));
            product.NetProcAudit.LogStatus = {};
            product.NetProcAudit.LogStatus['@attributes'] = {
                lock: Number(container.find('#NetProcAudit_lock_LogStatus').hasClass('enableLock'))
            };
            product.NetProcAudit.LogStatus['@value'] = Number(container.find('#NetProcAudit_LogStatus').prop('checked'));
            product.NetProcAudit.CheckRsSign = {};
            product.NetProcAudit.CheckRsSign['@attributes'] = {
                lock: Number(container.find('#NetProcAudit_lock_CheckRsSign').hasClass('enableLock'))
            };
            product.NetProcAudit.CheckRsSign['@value'] = Number(container.find('#NetProcAudit_CheckRsSign').prop('checked'));
            product.NetProcAudit.CheckModule = {};
            product.NetProcAudit.CheckModule['@attributes'] = {
                lock: Number(container.find('#NetProcAudit_lock_CheckModule').hasClass('enableLock'))
            };
            product.NetProcAudit.CheckModule['@value'] = Number(container.find('#NetProcAudit_CheckModule').prop('checked'));
            product.NetProcAudit.UnknowAction = {};
            product.NetProcAudit.UnknowAction['@attributes'] = {
                lock: Number(container.find('#NetProcAudit_lock_UnknowAction').hasClass('enableLock'))
            };
            product.NetProcAudit.UnknowAction['@value'] = container.find('[name=NetProcAudit_UnknownAction]:checked').val();
            product.NetProcAudit.TimeRuleList = {};
            product.NetProcAudit.TimeRuleList['@attributes'] = {
                lock: Number(container.find('#NetProcAudit_lock_TimeRuleList').hasClass('enableLock'))
            };
            product.NetProcAudit.TimeRuleList.TimeRule = [];
            var net_dls = container.find('.netlist dl');
            for (var i = 0; i < net_dls.length; i++) {
                var net_dl = $(net_dls[i]);
                var TimeRule = getRule2(net_dl);
                product.NetProcAudit.TimeRuleList.TimeRule.push(TimeRule);
            }
            /*联网程序 end*/

            /*流量管理 start*/
            product.FluxMgr = {};
            product.FluxMgr['@attributes'] = {
                lock: Number(container.find('#FluxMgr_lock').hasClass('enableLock'))
            };
            product.FluxMgr.MonStatus = {};
            product.FluxMgr.MonStatus['@attributes'] = {
                lock: Number(container.find('#FluxMgr_lock_MonStatus').hasClass('enableLock'))
            };
            product.FluxMgr.MonStatus['@value'] = Number(container.find('#FluxMgr_MonStatus').prop('checked'));
            product.FluxMgr.LogTimer = {};
            product.FluxMgr.LogTimer['@attributes'] = {
                lock: Number(container.find('#FluxMgr_lock_MonStatus').hasClass('enableLock'))
            };
            product.FluxMgr.LogTimer['@value'] = container.find('#FluxMgr_LogTimer').val();
            /*流量管理 end*/

            /*adsl start*/
            product.AdslShare = {};
            product.AdslShare['@attributes'] = {
                lock: Number(container.find('#AdslShare_lock').hasClass('enableLock'))
            };
            product.AdslShare.MonStatus = {};
            product.AdslShare.MonStatus['@attributes'] = {
                lock: Number(container.find('#AdslShare_lock_MonStatus').hasClass('enableLock'))
            };
            product.AdslShare.MonStatus['@value'] = Number(container.find('#AdslShare_MonStatus').prop('checked'));
            product.AdslShare.TotalWidth = {};
            product.AdslShare.TotalWidth['@attributes'] = {
                lock: Number(container.find('#AdslShare_lock_TotalWidth').hasClass('enableLock'))
            };
            product.AdslShare.TotalWidth['@value'] = Number(container.find('#AdslShare_TotalWidth').val());
            /*adsl end*/

            /*共享管理 start*/
            product.ShareMgr = {};
            product.ShareMgr['@attributes'] = {
                lock: Number(container.find('#ShareMgr_lock').hasClass('enableLock'))
            };
            product.ShareMgr.FileLogStatus = {};
            product.ShareMgr.FileLogStatus['@attributes'] = {
                lock: Number(container.find('#ShareMgr_lock_FileLogStatus').hasClass('enableLock'))
            };
            product.ShareMgr.FileLogStatus['@value'] = Number(container.find('#ShareMgr_FileLogStatus').prop('checked'));
            product.ShareMgr.AccessLogStatus = {};
            product.ShareMgr.AccessLogStatus['@attributes'] = {
                lock: Number(container.find('#ShareMgr_lock_AccessLogStatus').hasClass('enableLock'))
            };
            product.ShareMgr.AccessLogStatus['@value'] = Number(container.find('#ShareMgr_AccessLogStatus').prop('checked'));
            product.ShareMgr.DisableDefaultShare = {};
            product.ShareMgr.DisableDefaultShare['@attributes'] = {
                lock: Number(container.find('#DisableDefaultShare_lock').hasClass('enableLock'))
            };
            var disable1 = container.find('#DisableDefaultShare_status1').prop('checked') ? 1 : 0;
            var disable2 = container.find('#DisableDefaultShare_status2').prop('checked') ? 4 : 0;
            var disable_value = disable1 + disable2;
            product.ShareMgr.DisableDefaultShare['@value'] = disable_value;

            product.ShareMgr.AccessControl = {};
            product.ShareMgr.AccessControl['@attributes'] = {
                lock: Number(container.find('#AccessControl_lock').hasClass('enableLock'))
            };
            product.ShareMgr.AccessControl.MonStatus = {};
            product.ShareMgr.AccessControl.MonStatus['@attributes'] = {
                lock: Number(container.find('#AccessControl_lock_MonStatus').hasClass('enableLock'))
            };
            product.ShareMgr.AccessControl.MonStatus['@value'] = Number(container.find('#AccessControl_MonStatus').prop('checked'));
            product.ShareMgr.AccessControl.AlertStatus = {};
            product.ShareMgr.AccessControl.AlertStatus['@attributes'] = {
                lock: Number(container.find('#AccessControl_lock_AlertStatus').hasClass('enableLock'))
            };
            product.ShareMgr.AccessControl.AlertStatus['@value'] = Number(container.find('#AccessControl_AlertStatus').prop('checked'));
            product.ShareMgr.AccessControl.ControlCode = {};
            product.ShareMgr.AccessControl.ControlCode['@attributes'] = {
                lock: Number(container.find('#AccessControl_lock_ControlCode').hasClass('enableLock'))
            };
            product.ShareMgr.AccessControl.ControlCode['@value'] = Number(container.find('[name=AccessControl_ControlCode]:checked').val());
            product.ShareMgr.AccessControl.RuleList = {};
            product.ShareMgr.AccessControl.RuleList['@attributes'] = {
                lock: Number(container.find('#AccessControl_lock_RuleList').hasClass('enableLock'))
            };
            product.ShareMgr.AccessControl.RuleList.Rule = [];
            var ips = container.find('#ip_list tr');
            for (var i = 0; i < ips.length; i++) {
                var ip = $(ips[i]);
                var Rule = {};
                Rule['@attributes'] = {
                    id: ip.find('td:eq(0)').attr('val')
                };
                product.ShareMgr.AccessControl.RuleList.Rule.push(Rule);
            }
            /*共享管理 end*/

            var json = {
                product: product
            };
            return json;
        },
        /*toXml: function(json) {
          var xml = mustache.render(xmlTpl, json);
          return xml;
        },*/
        toHtml: function(container, json) {
            var opRadio = function(name, value) {
                container.find(':radio[name=' + name + '][value=' + value + ']').prop('checked', true)
            };
            var opCheck = function(id, status) {
                if (status == 1) {
                    return container.find(id).prop('checked', true);
                } else {
                    return container.find(id).prop('checked', false);
                }
            };
            var opLock = function(id, status) {
                if (status == 1) {
                    container.find(id).addClass('enableLock');
                } else {
                    container.find(id).removeClass('enableLock');
                }
            };

            function getDetialWek(str) {
                var num = parseInt(str).toString(2);
                var num_len = num.length;
                num = '0000000'.slice(num_len) + num;
                var arr = [0, 0, 0, 0, 0, 0, 0];
                var len = arr.length - 1;
                for (var i = 0; i < num.length; i++) {
                    arr[len - i] = num[i];
                }
                return arr;
            }
            /*BrowserAudit TimeRuleList*/
            function setTimeRule2HTML(TimeRule, target, temp_html) {
                TimeRule['@attributes'].lock == '1' && temp_html.find('[name=TimeRuleList_lock_TimeRule]').addClass('enableLock');
                var validTime = TimeRule.ValidTime['@value'].split('|');
                if (validTime[0] == 0) {
                    temp_html.find('.js_time_mode option:eq(0)').prop('selected', true);
                    temp_html.find('.js_time0').removeClass('hide').end().find('.js_time1,.js_time2').addClass('hide');
                    temp_html.find('.js_time0 [name=start_time]').val(validTime[1]);
                    temp_html.find('.js_time0 [name=end_time]').val(validTime[2]);
                } else if (validTime[0] == 1) {
                    temp_html.find('.js_time_mode option:eq(1)').prop('selected', true);
                    temp_html.find('.js_time1').removeClass('hide').end().find('.js_time0,.js_time2').addClass('hide');
                    var weekArr = getDetialWek(validTime);
                    var weekmark = temp_html.find('[name=week]');
                    for (var i = 0; i < weekArr.length; i++) {
                        if (weekArr[i] == '1') {
                            $(weekmark[i]).attr('checked', 'checked');
                        } else {
                            $(weekmark[i]).removeAttr('checked');
                        }
                    }
                    temp_html.find('.js_time1 [name=start_time]').val(validTime[2]);
                    temp_html.find('.js_time1 [name=end_time]').val(validTime[3]);
                } else if (validTime[0] == 2) {
                    temp_html.find('.js_time_mode option:eq(2)').prop('selected', true);
                    temp_html.find('.js_time2').removeClass('hide').end().find('.js_time1,.js_time0').addClass('hide');
                    temp_html.find('.js_time2 [name=start_date]').val(validTime[1]);
                    temp_html.find('.js_time2 [name=end_date]').val(validTime[2]);
                }
                var RuleList = TimeRule.RuleList;
                var RuleList_Rule = RuleList.Rule;
                var jsUrls = temp_html.find('.js_urls');
                var temp = jsUrls.find('li').clone();
                jsUrls.html('');
                for (var i = 0; i < RuleList_Rule.length; i++) {
                    var Rule = RuleList_Rule[i];
                    setRuleList2HTML(Rule, jsUrls, temp.clone());
                    temp_html.find('.redirect').val(Rule.Redirect['@value']);
                }                
                target.append(temp_html);

            }
            /*BrowserAudit RuleList*/
            function setRuleList2HTML(Rule, jsUrls, temp_html) {
                temp_html.find('[name=urls]').val(Rule.Url['@value']);
                temp_html.find('[name=alert]').prop('checked', Rule.Alert['@value'] == 1 ? true : false);
                temp_html.find('[name=control]').prop('checked', Rule.ControlMode['@value'] == 1 ? true : false);
                jsUrls.append(temp_html);
            }

            function setTimeRule2HTML4Net(TimeRule, target, temp_html) {
                TimeRule['@attributes'].lock == '1' && temp_html.find('[name=TimeRule_lock]').addClass('enableLock');
                var validTime = TimeRule.ValidTime['@value'].split('|');
                if (validTime[0] == 0) {
                    temp_html.find('.js_time_mode option:eq(0)').prop('selected', true);
                    temp_html.find('.js_time0').removeClass('hide').end().find('.js_time1,.js_time2').addClass('hide');
                    temp_html.find('.js_time0 [name=start_time]').val(validTime[1]);
                    temp_html.find('.js_time0 [name=end_time]').val(validTime[2]);
                } else if (validTime[0] == 1) {
                    temp_html.find('.js_time_mode option:eq(1)').prop('selected', true);
                    temp_html.find('.js_time1').removeClass('hide').end().find('.js_time0,.js_time2').addClass('hide');
                    var weekArr = getDetialWek(validTime);
                    var weekmark = temp_html.find('[name=week]');
                    for (var i = 0; i < weekArr.length; i++) {
                        if (weekArr[i] == '1') {
                            $(weekmark[i]).attr('checked', 'checked');
                        } else {
                            $(weekmark[i]).removeAttr('checked');
                        }
                    }
                    temp_html.find('.js_time1 [name=start_time]').val(validTime[2]);
                    temp_html.find('.js_time1 [name=end_time]').val(validTime[3]);
                } else if (validTime[0] == 2) {
                    temp_html.find('.js_time_mode option:eq(2)').prop('selected', true);
                    temp_html.find('.js_time2').removeClass('hide').end().find('.js_time1,.js_time0').addClass('hide');
                    temp_html.find('.js_time2 [name=start_date]').val(validTime[1]);
                    temp_html.find('.js_time2 [name=end_date]').val(validTime[2]);
                }
                var RuleList = TimeRule.RuleList;
                var RuleList_Rule = RuleList.Rule;
                var jsUrls = temp_html.find('#net_pros');
                var tableData = [];
                for (var i = 0; i < RuleList_Rule.length; i++) {
                    var Rule = RuleList_Rule[i];
                    var da = {
                        Name: Rule.SoftId['@value'],
                        md5: Rule.CheckMd5['@value'],
                        listen: Rule.AllowListen['@value'],
                        outside: Rule.AllowOutside['@value'],
                        type: Rule.SoftType['@value']
                    };
                    tableData.push(da);
                }
                op.initNetPro(temp_html, tableData);
                target.append(temp_html);
            }
            
            var product = json.product;
            if (!product) {
                /*时间控件*/
                this.initTime(container);
                this.initNetPro(container);
                return;
            }

            var BrowserAudit = product.BrowserAudit;
            opLock('#BrowserAudit_lock', BrowserAudit['@attributes'].lock);
            opLock('#BrowserAudit_lock_MonStatus', BrowserAudit.MonStatus['@attributes'].lock);
            opCheck('#BrowserAudit_MonStatus', BrowserAudit.MonStatus['@value']);
            // opLock('#BrowserAudit_lock_LogStatus', BrowserAudit.LogStatus['@attributes'].lock);
            // opCheck('#BrowserAudit_LogStatus', BrowserAudit.LogStatus['@value']);
            opLock('#BrowserAudit_lock_LogAllWeb', BrowserAudit.LogAllWeb['@attributes'].lock);
            opCheck('#BrowserAudit_LogAllWeb', BrowserAudit.LogAllWeb['@value']);

            var brow_TimeRuleList = BrowserAudit.TimeRuleList;
            opLock('#TimeRule_lock', brow_TimeRuleList['@attributes'].lock);
            var brow_TimeRuleList_target = container.find('.browserAuditList');
            var temp_html = brow_TimeRuleList_target.find('dl:eq(0)').clone();
            var brow_TimeRuleList_TimeRule = brow_TimeRuleList.TimeRule;
            if (brow_TimeRuleList_TimeRule.length) {
                brow_TimeRuleList_target.html('');
                for (var i = 0; i < brow_TimeRuleList_TimeRule.length; i++) {
                    var TimeRule = brow_TimeRuleList_TimeRule[i];
                    var target = $(brow_TimeRuleList_target[i]);
                    setTimeRule2HTML(TimeRule, target, temp_html.clone());
                }
            }

            var NetProcAudit = product.NetProcAudit;
            opLock('#NetProcAudit_lock', NetProcAudit['@attributes'].lock);
            opLock('#NetProcAudit_lock_MonStatus', NetProcAudit.MonStatus['@attributes'].lock);
            opCheck('#NetProcAudit_MonStatus', NetProcAudit.MonStatus['@value']);
            opLock('#NetProcAudit_lock_LogStatus', NetProcAudit.LogStatus['@attributes'].lock);
            opCheck('#NetProcAudit_LogStatus', NetProcAudit.LogStatus['@value']);
            opLock('#NetProcAudit_lock_CheckModule', NetProcAudit.CheckModule['@attributes'].lock);
            opCheck('#NetProcAudit_CheckModule', NetProcAudit.CheckModule['@value']);
            opLock('#NetProcAudit_lock_CheckRsSign', NetProcAudit.CheckRsSign['@attributes'].lock);
            opCheck('#NetProcAudit_CheckRsSign', NetProcAudit.CheckRsSign['@value']);
            opLock('#NetProcAudit_lock_UnknowAction', NetProcAudit.UnknowAction['@attributes'].lock);
            opRadio('NetProcAudit_UnknownAction', NetProcAudit.UnknowAction['@value']);
            var Net_TimeRuleList = NetProcAudit.TimeRuleList;
            opLock('#NetProcAudit_lock_TimeRuleList', Net_TimeRuleList['@attributes'].lock);
            var target = container.find('.netlist');
            var temp_html = target.find('dl:eq(0)').clone();
            var Net_TimeRuleList_TimeRule = NetProcAudit.TimeRuleList.TimeRule;
            if (Net_TimeRuleList_TimeRule.length) {
                target.html('');
                for (var i = 0; i < Net_TimeRuleList_TimeRule.length; i++) {
                    var TimeRule = Net_TimeRuleList_TimeRule[i];
                    setTimeRule2HTML4Net(TimeRule, target, temp_html.clone());
                }
            } else {
                op.initNetPro(target);
            }

            var FluxMgr = product.FluxMgr;
            opLock('#FluxMgr_lock', FluxMgr['@attributes'].lock);
            opLock('#FluxMgr_lock_MonStatus', FluxMgr.MonStatus['@attributes'].lock);
            opCheck('#FluxMgr_MonStatus', FluxMgr.MonStatus['@value']);
            opLock('#FluxMgr_lock_LogTimer', FluxMgr.LogTimer['@attributes'].lock);
            container.find('#FluxMgr_LogTimer').val(FluxMgr.LogTimer['@value']);

            var AdslShare = product.AdslShare;
            opLock('#AdslShare_lock', AdslShare['@attributes'].lock);
            opLock('#AdslShare_lock_MonStatus', AdslShare.MonStatus['@attributes'].lock);
            opCheck('#AdslShare_MonStatus', AdslShare.MonStatus['@value']);
            opLock('#AdslShare_lock_TotalWidth', AdslShare.TotalWidth['@attributes'].lock);
            container.find('#AdslShare_TotalWidth').val(AdslShare.TotalWidth['@value']);

            var ShareMgr = product.ShareMgr;
            opLock('#ShareMgr_lock', ShareMgr['@attributes'].lock);
            opLock('#ShareMgr_lock_MonStatus', ShareMgr.MonStatus['@attributes'].lock);
            opCheck('#ShareMgr_MonStatus', ShareMgr.MonStatus['@value']);
            opLock('#ShareMgr_lock_LogStatus', ShareMgr.LogStatus['@attributes'].lock);
            opCheck('#ShareMgr_LogStatus', ShareMgr.LogStatus['@value']);
            opLock('#DisableDefaultShare_lock', ShareMgr.DisableDefaultShare['@attributes'].lock);
            switch (ShareMgr.DisableDefaultShare['@value']) {
                case 0:
                    container.find('#DisableDefaultShare_status1').prop('checked', false);
                    container.find('#DisableDefaultShare_status2').prop('checked', false);
                    break;
                case 1:
                    container.find('#DisableDefaultShare_status1').prop('checked', true);
                    container.find('#DisableDefaultShare_status2').prop('checked', false);
                    break;
                case 4:
                    container.find('#DisableDefaultShare_status1').prop('checked', false);
                    container.find('#DisableDefaultShare_status2').prop('checked', true);
                    break;
                case 5:
                    container.find('#DisableDefaultShare_status1').prop('checked', true);
                    container.find('#DisableDefaultShare_status2').prop('checked', true);
                    break;
            }
            var AccessControl = ShareMgr.AccessControl;
            opLock('#AccessControl_lock', AccessControl['@attributes'].lock);
            opLock('#AccessControl_lock_MonStatus', AccessControl.MonStatus['@attributes'].lock);
            opCheck('#AccessControl_MonStatus', AccessControl.MonStatus['@value']);
            opLock('#AccessControl_lock_LogStatus', AccessControl.MonStatus['@attributes'].lock);
            opCheck('#AccessControl_LogStatus', AccessControl.MonStatus['@value']);
            opLock('#AccessControl_lock_AlertStatus', AccessControl.MonStatus['@attributes'].lock);
            opCheck('#AccessControl_AlertStatus', AccessControl.MonStatus['@value']);
            opLock('#AccessControl_lock_ControlCode', AccessControl.MonStatus['@attributes'].lock);
            opRadio('AccessControl_ControlCode', AccessControl.MonStatus['@value']);

            var RuleLists = AccessControl.RuleList;
            opLock('#AccessControl_lock_RuleList', AccessControl.MonStatus['@attributes'].lock);
            var RuleLists_Rule = RuleLists.Rule;

            for (var i = 0; i < RuleLists_Rule.length; i++) {
                var rule = RuleLists_Rule[i];
                var txt = rule['@attributes'].id;
                container.find('#ip_list').append('<tr><td val="' + txt + '">' + txt.split('|').slice(1).join('--') + '</td><td style="width:80px;"><a href="javascript:;" class="js_remove">删除</td></tr>');
            }
            /*时间控件*/
            this.initTime(container);


        }
    };

    return {
        render: function(container, policy) {
            container.append(tpl);
            op.init(container, policy);
            return {
                toJson: op.toJson,
                valida: op.valida
            };
        }
    };
});