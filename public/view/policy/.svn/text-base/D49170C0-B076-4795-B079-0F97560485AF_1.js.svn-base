define(function(require) {
    var tpl = require('text!policy/D49170C0-B076-4795-B079-0F97560485AF_1.html');
    require('selectric');
    require('css!selectric');
    require('datetimepicker');
    require('css!datetimepicker')
    //var mustache = require('mustache');
    //var xmlTpl = require('text!policy/D49170C0-B076-4795-B079-0F97560485AF_1.xml');

    var op = {
        view:null,
        init: function(container, policy) {
            // 绑定事件
            this.view = container;
            this.bindEvent(container);
            this.validationEvent(container);
            // 初始化策略内容
            if (policy) {
                // 策略初始化赋值
                //console.log(policy);
                this.toHtml(container, policy);
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
            // 验证提示
            container.find('#pub_txtCloudCount').tooltip();

            /* begin 公共设置 */
            // 白名单、排除列表
            container.on('click', '#pub_btnAddWhiteList', function() {
                var exist = false;
                var type = container.find('#pub_selFileType').val();
                var title = container.find('#pub_txtFile').val();
                if (title) {
                    container.find('#pub_tbWhiteList tr').find('>td:first').each(function() {
                        if ($(this).attr('title') == title) {
                            container.find('#pub_txtFile').tooltip({
                                title: '已存在',
                                trigger: 'manual'
                            }).tooltip('show');
                            exist = true;
                            return false;
                        }
                    });
                    !exist && container.find('#pub_tbWhiteList').append('<tr><td filetype="' + type + '" title="' + title + '">' + title + '<td width="50"><a href="#" class="pub_btnRemove">删除</a>');
                } else {
                    container.find('#pub_txtFile').tooltip({
                        title: '不允许为空',
                        trigger: 'manual'
                    }).tooltip('show');
                }
            });
            container.on('blur', '#pub_btnAddWhiteList', function() {
                container.find('#pub_txtFile').tooltip('destroy');
            });
            container.on('click', '.pub_btnRemove', function() {
                $(this).closest('tr').remove();
                return false;
            });
            // 启用公有云
            container.on('change', '#publicCloudEnable', function() {
                var inputs = $(this).closest('dt').siblings('dd').find('input');
                if ($(this).prop('checked')) {
                    inputs.prop('disabled', false);
                } else {
                    inputs.prop('disabled', true);
                }
            });

            container.on('click','.js_addcloud',function(){
                var obj = container.find('#privateCloudTmpl');
                var $html = $('<dd style="display:none">'+obj.html()+'</dd>');
                obj.before($html);
                $html.fadeIn();

            })

            container.on('click','.js_delcloud',function(){
                var target = $(this).closest('dl').parent();
                target.fadeOut(400,function(){
                    target.remove();
                });
            })
            /* end 公共设置 */

            /* begin 扫描设置 */
            container.on('change', '#scan_chkAllscanEnable,#scan_chkQuickscanEnable', function() {
                if ($(this).prop('checked')) {
                    $(this).closest('dt').next('dd').find('input').prop('disabled', false);
                } else {
                    $(this).closest('dt').next('dd').find('input').prop('disabled', true);
                }
            });
            /* end 扫描设置 */

            /* begin 文件监控设置 */
            /* end 文件监控设置 */

            /* begin 邮件监控设置 */
            var mailNum = 2;
            container.on('click', '.mail_btnAddPort', function() {
                var mailTpl = '<dl class="mailPortPanel"><dt><a href="#" class="mail_btnRemovePort">删除</a> <a href="#" class="mail_btnAddPort">增加</a><label><i name="mail_lockMailPortBox" class="lock"></i></label><dd>端口号： <input type="text" name="mail_txtMailPort" value="25" validation="intNum"> 端口协议：<label class="radio inline"><input name="mail_radMailPort' + mailNum + '" type="radio" value="0" checked>SMTP</label><label class="radio inline"><input name="mail_radMailPort' + mailNum + '" type="radio" value="1">POP3</label></dl>';
                //$(this).closest('dd').after(mailTpl);
                $(this).closest('dl.mailPortPanel').after(mailTpl);
                mailNum++;
                return false;
            });
            container.on('click', '.mail_btnRemovePort', function() {
                $(this).closest('dl.mailPortPanel').remove();
                if (container.find('dl.mailPortPanel').length == 0) {
                    container.find('#mail_lockPolicyWrap').closest('dt').next('dd').append('<dl class="mailNoRecordPanel"><span>当前没有任何规则</span> <a href="#" id="mail_btnAddRecord">增加</a></dl>');
                }
                return false;
            });
            container.on('click', '#mail_btnAddRecord', function() {
                var mailTpl = '<dl class="mailPortPanel"><dt><a href="#" class="mail_btnRemovePort">删除</a> <a href="#" class="mail_btnAddPort">增加</a><label><i name="mail_lockMailPortBox" class="lock"></i></label><dd>端口号： <input type="text" name="mail_txtMailPort" value="25" validation="intNum"> 端口协议：<label class="radio inline"><input name="mail_radMailPort' + mailNum + '" type="radio" value="0" checked>SMTP</label><label class="radio inline"><input name="mail_radMailPort' + mailNum + '" type="radio" value="1">POP3</label></dl>';
                $(this).closest('dl.mailNoRecordPanel').after(mailTpl).remove();
                mailNum++;
                return false;
            });
            /* end 邮件监控设置 */

            /* begin 应用加固设置 */
            container.on('change', '#app_chkAllaptEnable', function() {
                var inputs = $(this).closest('dt').siblings('dd').find('input');
                if ($(this).prop('checked')) {
                    inputs.prop('disabled', false);
                } else {
                    inputs.prop('disabled', true);
                }
            });
            /* end 应用加固设置 */

            /* begin 系统加固设置 */
            container.on('change', '#sys_chkAllsysdefEnable', function() {
                var inputs = $(this).closest('dt').siblings('dd').find('input');
                if ($(this).prop('checked')) {
                    inputs.prop('disabled', false);
                } else {
                    inputs.prop('disabled', true);
                }
            });
            /* end 系统加固设置 */

            /*时间控件*/
            container.find('.js_time').datetimepicker({
                datepicker: false,
                format: 'H:i',
                step: 1
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
                intNum:function(obj){
                    var val = obj.val();
                    return /^\d+$/.test(val);
                },
                ip:function(obj){
                    var val = obj.val();
                    return /^(25[0-5]|2[0-4]\d|[01]?\d\d?)\.(25[0-5]|2[0-4]\d|[01]?\d\d?)\.(25[0-5]|2[0-4]\d|[01]?\d\d?)\.(25[0-5]|2[0-4]\d|[01]?\d\d?)$/i.test(val);
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
            var root = {}
                /* begin 公共设置 */
            root.pub = {};
            root.pub.whitelist = {};
            root.pub.whitelist.admin = {};
            root.pub.whitelist.admin["@attributes"] = {
                admin: 1
            };
            root.pub.whitelist.admin.file = {};
            var arrWhiteList = container.find('#pub_tbWhiteList tr').find('>td:first');
            if (arrWhiteList.length > 0) {
                root.pub.whitelist.admin.file.item = [];
                arrWhiteList.each(function() {
                    var item = {};
                    item["@attributes"] = {
                        path: $(this).attr('filetype')
                    };
                    item["@value"] = $(this).attr('title');
                    root.pub.whitelist.admin.file.item.push(item);
                });
            } else {
                root.pub.whitelist.admin.file["@value"] = '';
            }
            root.pub.whitelist.admin.ext = {};
            root.pub.whitelist.admin.ext["@value"] = container.find('#pub_txtExt').val();
            
            root.pub.cloud = {};
            root.pub.cloud.cpuradio = {};
            root.pub.cloud.cpuradio["@attributes"] = {admin: 1};
            root.pub.cloud.cpuradio["@value"] = container.find('#cpuradio').val();
            root.pub.cloud.connrate = {};
            root.pub.cloud.connrate["@attributes"] = {admin: 1};
            root.pub.cloud.connrate["@value"] = container.find('#connrate').val();
            root.pub.cloud.servers = {};
            root.pub.cloud.servers.csrv = [];
            var cloud = {};
            cloud["@attributes"] = {private: 0,lock:Number(container.find('#publicCloudLock').hasClass('enableLock'))};
            cloud.enable = {};
            cloud.enable["@value"] = Number(container.find('#publicCloudEnable').prop('checked'));
            cloud.addr = {};
            cloud.addr["@value"] = 'rscloud.rising.net.cn';
            cloud.port = {};
            cloud.port["@value"] = 80;
            cloud.mode = {};
            cloud.mode["@value"] = container.find('.js_cloud:eq(0) [name=publicCloudMode]:checked').val();
            cloud.count = {};
            cloud.count["@value"] = container.find('#publicCloudCount').val() || 0;
            cloud.name = {};
            cloud.name["@value"] = container.find('#publicCloudName').val() || '';
            root.pub.cloud.servers.csrv.push(cloud);
            container.find('.js_cloud:gt(0)').each(function(i,item){
                var target = $(item);
                if(!(target.parent().attr('id')=='privateCloudTmpl')){
                    var cloud = {};
                    cloud["@attributes"] = {private: 1,lock:Number(target.find('.privateCloudLock').hasClass('enableLock'))};
                    cloud.enable = {};
                    cloud.enable["@value"] = Number(container.find('.privateCloudEnable').prop('checked'));
                    cloud.addr = {};
                    cloud.addr["@value"] = target.find('.privateCloudAddr').val();
                    cloud.port = {};
                    cloud.port["@value"] = target.find('.privateCloudPort').val();
                    cloud.mode = {};
                    cloud.mode["@value"] = target.find('[name=publicCloudMode]:checked').val();
                    cloud.count = {};
                    cloud.count["@value"] = target.find('.privateCloudCount').val() || 0;
                    cloud.name = {};
                    cloud.name["@value"] = target.find('.privateCloudName').val() || '';
                    root.pub.cloud.servers.csrv.push(cloud);   
                }                
            })
            
            
            root.pub.vstore = {};
            root.pub.vstore.nospace = {};
            root.pub.vstore.nospace["@attributes"] = {
                lock: Number(container.find('#pub_lockNospace').hasClass('enableLock'))
            };
            root.pub.vstore.nospace["@value"] = container.find(':radio[name="pub_radNospace"]:checked').val();
            root.pub.vstore.storefailed = {};
            root.pub.vstore.storefailed["@attributes"] = {
                lock: Number(container.find('#pub_lockStorefailed').hasClass('enableLock'))
            };
            root.pub.vstore.storefailed["@value"] = container.find(':radio[name=pub_radStorefailed]:checked').val();
            root.pub.vstore.bigfile = {};
            root.pub.vstore.bigfile["@attributes"] = {
                lock: Number(container.find('#pub_lockBigfile').hasClass('enableLock'))
            };
            root.pub.vstore.bigfile["@value"] = container.find(':radio[name=pub_radBigfile]:checked').val();
            root.pub.vstore.nobackup = {};
            root.pub.vstore.nobackup["@attributes"] = {
                lock: Number(container.find('#pub_lockNobackup').hasClass('enableLock'))
            };
            root.pub.vstore.nobackup["@value"] = Number(container.find('#pub_chkNobackup').prop('checked'));
            
            root.pub.vtrack = {};
            root.pub.vtrack["@attributes"] = {
                lock: Number(container.find('#pub_lockVtrack').hasClass('enableLock'))
            };
            root.pub.vtrack["@value"] = Number(container.find('#pub_chkVtrack').prop('checked'));
            //udiskmon 废弃
            // root.pub.udiskmon = {};
            // root.pub.udiskmon["@attributes"] = {
            //     lock: Number(container.find('#pub_lockUdiskmon').hasClass('enableLock'))
            // };
            // root.pub.udiskmon["@value"] = Number(container.find('#pub_chkUdiskmon').prop('checked'));
            root.pub.memlib = {};
            root.pub.memlib["@attributes"] = {
                lock: Number(container.find('#pub_lockMemlib').hasClass('enableLock'))
            };
            root.pub.memlib["@value"] = Number(container.find('#pub_chkMemlib').prop('checked'));

            // root.pub.trolib = {};
            // root.pub.trolib["@attributes"] = {
            //     lock: Number(container.find('#pub_lockTrolib').hasClass('enableLock'))
            // };
            // root.pub.trolib["@value"] = Number(container.find('#pub_chkTrolib').prop('checked'));
        
            root.pub.writelog = {};
            root.pub.writelog["@attributes"] = {
                lock: Number(container.find('#pub_lockWritelog').hasClass('enableLock'))
            };
            root.pub.writelog["@value"] = Number(container.find('#pub_chkWritelog').prop('checked'));
            root.pub.smartcpu = {};
            root.pub.smartcpu.type = {};
            root.pub.smartcpu.type["@attributes"] = {
                lock: 0
            };
            root.pub.smartcpu.type["@value"] = 1;
            root.pub.smartcpu.level = {};
            root.pub.smartcpu.level["@attributes"] = {
                lock: Number(container.find('#pub_lockSmartcpuLevel').hasClass('enableLock'))
            };
            root.pub.smartcpu.level["@value"] = container.find(':radio[name=pub_radSmartcpuLevel]:checked').val();
            root.pub.worm08067 = {};
            root.pub.worm08067['@attributes'] = { lock: Number(container.find('#pub_lockWorm08067').hasClass('enableLock'))};
            root.pub.worm08067['@value'] = Number(container.find('#pub_worm08067').prop('checked'));
            root.pub.yunyu = {};
            root.pub.yunyu['@attributes'] = { lock: Number(container.find('#pub_lockYunyu').hasClass('enableLock'))};
            root.pub.yunyu['@value'] = Number(container.find('#pub_yunyu').prop('checked'));
            root.pub.lpktool = {};
            root.pub.lpktool['@attributes'] = { lock: Number(container.find('#pub_lockLpktool').hasClass('enableLock'))};
            root.pub.lpktool['@value'] = Number(container.find('#pub_lpktool').prop('checked'));
            root.pub.virut = {};
            root.pub.virut['@attributes'] = { lock: Number(container.find('#pub_lockVirut').hasClass('enableLock'))};
            root.pub.virut['@value'] = Number(container.find('#pub_virut').prop('checked'));
            root.pub.kvengine = {};
            root.pub.kvengine['@value'] = 'rego';
            root.pub.closerfm = {};
            root.pub.closerfm['@attributes'] = { lock: Number(container.find('#pub_lockCloserfm').hasClass('enableLock'))};
            root.pub.closerfm['@value'] = Number(container.find('#pub_closerfm').prop('checked'));
            root.pub.InnerWhiteList = {};
            root.pub.InnerWhiteList['@attributes'] = { lock: Number(container.find('#pub_lockInnerWhiteList').hasClass('enableLock'))};
            root.pub.InnerWhiteList['@value'] = Number(container.find('#pub_InnerWhiteList').prop('checked'));
            /* end 公共设置 */

            /* begin 扫描设置 */
            root.scan = {};
            root.scan.efficient = {};
            root.scan.efficient["@attributes"] = {
                admin: 1
            };
            root.scan.efficient["@value"] = '';
            root.scan.timerscan = {};
            root.scan.timerscan["@attributes"] = {
                admin: 1
            };
            root.scan.timerscan.allscan = {};
            root.scan.timerscan.allscan.enable = {};
            root.scan.timerscan.allscan.enable["@value"] = Number(container.find('#scan_chkAllscanEnable').prop('checked'));
            root.scan.timerscan.allscan.RsTimer = {};
            root.scan.timerscan.allscan.RsTimer.Task = {};
            root.scan.timerscan.allscan.RsTimer.Task["@attributes"] = {
                id: '{51ECB824-C7CF-BD11-042C-2B06746A4A7F}',
                kind: 1,
                type: 5,
                msgid: '0x06B0000100000001'
            };
            root.scan.timerscan.allscan.RsTimer.Task.Time = {};
            root.scan.timerscan.allscan.RsTimer.Task.Time["@attributes"] = {
                startdate: '2010-1-1'
            };

            switch (container.find(':radio[name=scan_radAllscanTime]:checked').val()) {
                case "2": //开机
                    root.scan.timerscan.allscan.RsTimer.Task.Time.AfterBoot = {};
                    root.scan.timerscan.allscan.RsTimer.Task.Time.AfterBoot["@attributes"] = {
                        minutes: 5
                    };
                    root.scan.timerscan.allscan.RsTimer.Task.Time.AfterBoot["@value"] = '';
                    break;
                case "6": //每天
                    var arr = container.find('#scan_txtAllscanEveryDay').val().split(':');
                    root.scan.timerscan.allscan.RsTimer.Task.Time.EveryDay = {};
                    root.scan.timerscan.allscan.RsTimer.Task.Time.EveryDay["@attributes"] = {
                        hour: arr[0],
                        minute: arr[1],
                        number: 1
                    };
                    root.scan.timerscan.allscan.RsTimer.Task.Time.EveryDay["@value"] = '';
                    break;
                case "5": //每周
                    var arr = container.find('#scan_txtAllscanEveryWeek').val().split(':');
                    var bitOr = 0;
                    container.find(':checkbox[name=scan_chkAllscanEveryWeek]:checked').each(function(i, item) {
                        bitOr = bitOr | item.value;
                    });
                    root.scan.timerscan.allscan.RsTimer.Task.Time.EveryWeek = {};
                    root.scan.timerscan.allscan.RsTimer.Task.Time.EveryWeek["@attributes"] = {
                        number: 1,
                        hour: arr[0],
                        minute: arr[1],
                        weekmark: bitOr
                    };
                    //root.scan.timerscan.allscan.RsTimer.Task.Time.EveryWeek["@value"] = '';
                    break;
            };
            root.scan.timerscan.quickscan = {};
            root.scan.timerscan.quickscan.enable = {};
            root.scan.timerscan.quickscan.enable["@value"] = Number(container.find('#scan_chkQuickscanEnable').prop('checked'));;
            root.scan.timerscan.quickscan.RsTimer = {};
            root.scan.timerscan.quickscan.RsTimer.Task = {};
            root.scan.timerscan.quickscan.RsTimer.Task["@attributes"] = {
                id: '{DFBAC594-02FD-565B-1496-8F657E91FF95}',
                kind: 1,
                type: 5,
                msgid: '0x06B0000100000002'
            };
            root.scan.timerscan.quickscan.RsTimer.Task.Time = {};
            root.scan.timerscan.quickscan.RsTimer.Task.Time["@attributes"] = {
                startdate: '2010-1-1'
            };
            switch (container.find(':radio[name=scan_radQuickscanTime]:checked').val()) {
                case "2": //开机
                    root.scan.timerscan.quickscan.RsTimer.Task.Time.AfterBoot = {};
                    root.scan.timerscan.quickscan.RsTimer.Task.Time.AfterBoot["@attributes"] = {
                        minutes: 5
                    };
                    root.scan.timerscan.quickscan.RsTimer.Task.Time.AfterBoot["@value"] = '';
                    root.scan.quickscanAfterBoot = true;
                    break;
                case "6": //每天
                    var arr = container.find('#scan_txtQuickscanEveryDay').val().split(':');
                    root.scan.timerscan.quickscan.RsTimer.Task.Time.EveryDay = {};
                    root.scan.timerscan.quickscan.RsTimer.Task.Time.EveryDay["@attributes"] = {
                        hour: arr[0],
                        minute: arr[1],
                        number: 1
                    };
                    root.scan.timerscan.quickscan.RsTimer.Task.Time.EveryDay["@value"] = '';
                    break;
                case "5": //每周
                    var arr = container.find('#scan_txtQuickscanEveryWeek').val().split(':');
                    var bitOr = 0;
                    container.find(':checkbox[name=scan_chkQuickscanEveryWeek]:checked').each(function(i, item) {
                        bitOr = bitOr | item.value;
                    });
                    root.scan.timerscan.quickscan.RsTimer.Task.Time.EveryWeek = {};
                    root.scan.timerscan.quickscan.RsTimer.Task.Time.EveryWeek["@attributes"] = {
                        number: 1,
                        hour: arr[0],
                        minute: arr[1],
                        weekmark: bitOr
                    };
                    root.scan.timerscan.quickscan.RsTimer.Task.Time.EveryWeek["@value"] = '';
                    break;
            };
            root.scan.engine = {};
            root.scan.engine.pub = {};
            root.scan.engine.pub.filetype = {};
            root.scan.engine.pub.filetype["@attributes"] = {
                lock: Number(container.find('#scan_lockFiletype').hasClass('enableLock'))
            };
            root.scan.engine.pub.filetype["@value"] = container.find(':radio[name=scan_radFiletype]:checked').val();
            root.scan.engine.id = [];
            root.scan.engine.id[0] = {};
            root.scan.engine.id[0].engid = {};
            root.scan.engine.id[0].engid["@value"] = 1;
            root.scan.engine.id[0].enable = {};
            root.scan.engine.id[0].enable["@value"] = 1;
            root.scan.engine.id[0].cfg = {};
            root.scan.engine.id[0].cfg.heuristic = {};
            root.scan.engine.id[0].cfg.heuristic["@attributes"] = {
                lock: Number(container.find('#scan_lockHeuristic').hasClass('enableLock'))
            };
            root.scan.engine.id[0].cfg.heuristic["@value"] = Number($('#scan_chkHeuristic').prop('checked'));
            root.scan.engine.id[0].cfg.popvirus = {};
            root.scan.engine.id[0].cfg.popvirus["@attributes"] = {
                lock: Number(container.find('#scan_lockPopvirus').hasClass('enableLock'))
            };
            root.scan.engine.id[0].cfg.popvirus["@value"] = Number($('#scan_chkPopvirus').prop('checked'));
            root.scan.engine.id[0].cfg.zip = {};
            root.scan.engine.id[0].cfg.zip.scanzip = {};
            root.scan.engine.id[0].cfg.zip.scanzip["@attributes"] = {
                lock: Number(container.find('#scan_lockScanzip').hasClass('enableLock'))
            };
            root.scan.engine.id[0].cfg.zip.scanzip["@value"] = Number($('#scan_chkScanzip').prop('checked'));
            root.scan.engine.id[0].cfg.zip.filesize = {};
            root.scan.engine.id[0].cfg.zip.filesize["@attributes"] = {
                lock: Number(container.find('#scan_lockFilesize').hasClass('enableLock'))
            };
            root.scan.engine.id[0].cfg.zip.filesize["@value"] = container.find('#scan_txtFilesize').val();
            root.scan.engine.id[1] = {};
            root.scan.engine.id[1].engid = {};
            root.scan.engine.id[1].engid["@value"] = 2;
            root.scan.engine.id[1].enable = {};
            root.scan.engine.id[1].enable["@attributes"] = {
                lock: Number(container.find('#scan_lockCloudScan').hasClass('enableLock'))
            };
            root.scan.engine.id[1].enable["@value"] = Number($('#scan_chkCloudScan').prop('checked'));
            root.scan.findvirus = {};
            root.scan.findvirus["@attributes"] = {
                lock: Number(container.find('#scan_lockfindvirus').hasClass('enableLock'))
            };
            root.scan.findvirus["@value"] = container.find(':radio[name=scan_radFindvirus]:checked').val();
            root.scan.killfailed = {};
            root.scan.killfailed["@value"] = 0;
            root.scan.adminscanoper = {};
            root.scan.adminscanoper["@attributes"] = {
                admin: 1
            };
            root.scan.adminscanoper = container.find(':radio[name=scan_radAdminscanoper]:checked').val();
            /* end 扫描设置 */

            /* begin 文件监控设置 */
            root.filemon = {};
            root.filemon.rundisable = {};
            root.filemon.rundisable["@attributes"] = {
                admin: 1
            };
            root.filemon.rundisable["@value"] = Number($('#file_chkRundisable').prop('checked'));
            root.filemon.runmode = {};
            root.filemon.runmode["@attributes"] = {
                admin: 1
            };
            root.filemon.runmode["@value"] = '';
            root.filemon.lockclose = {};
            root.filemon.lockclose["@attributes"] = {
                admin: 1
            };
            root.filemon.lockclose["@value"] = Number($('#file_chkLockclose').prop('checked'));
            root.filemon.monmode = {};
            root.filemon.monmode["@attributes"] = {
                lock: Number(container.find('#file_lockMonmode').hasClass('enableLock'))
            };
            root.filemon.monmode["@value"] = Number($('#file_chkMonmode').prop('checked'));
            root.filemon.enablekernel = {};
            root.filemon.enablekernel["@attributes"] = {
                lock: Number(container.find('#file_lockCore').hasClass('enableLock'))
            };
            root.filemon.enablekernel["@value"] = Number($('#file_chkCoreMonitor').prop('checked'));
            root.filemon.reportresult = {};
            root.filemon.reportresult["@attributes"] = {
                lock: Number(container.find('#file_lockReportresult').hasClass('enableLock'))
            };
            root.filemon.reportresult["@value"] = Number($('#file_chkReportresult').prop('checked'));
            root.filemon.engine = {};
            root.filemon.engine.pub = {};
            root.filemon.engine.pub.filetype = {};
            root.filemon.engine.pub.filetype["@attributes"] = {
                lock: Number(container.find('#file_lockFiletype2').hasClass('enableLock'))
            };
            root.filemon.engine.pub.filetype["@value"] = container.find(':radio[name=file_radFiletype2]:checked').val();
            root.filemon.engine.id = [];
            root.filemon.engine.id[0] = {};
            root.filemon.engine.id[0].engid = {};
            root.filemon.engine.id[0].engid["@value"] = 1;
            root.filemon.engine.id[0].enable = {};
            root.filemon.engine.id[0].enable["@value"] = 1;
            root.filemon.engine.id[0].cfg = {};
            root.filemon.engine.id[0].cfg.heuristic = {};
            root.filemon.engine.id[0].cfg.heuristic["@attributes"] = {
                lock: Number(container.find('#file_lockHeuristic2').hasClass('enableLock'))
            };
            root.filemon.engine.id[0].cfg.heuristic["@value"] = Number($('#file_chkHeuristic2').prop('checked'));
            root.filemon.engine.id[0].cfg.popvirus = {};
            root.filemon.engine.id[0].cfg.popvirus["@attributes"] = {
                lock: Number(container.find('#file_lockPopvirus2').hasClass('enableLock'))
            };
            root.filemon.engine.id[0].cfg.popvirus["@value"] = Number($('#file_chkPopvirus2').prop('checked'));
            root.filemon.engine.id[0].cfg.zip = {};
            root.filemon.engine.id[0].cfg.zip.scanzip = {};
            root.filemon.engine.id[0].cfg.zip.scanzip["@attributes"] = {
                lock: Number(container.find('#file_lockScanzip2').hasClass('enableLock'))
            };
            root.filemon.engine.id[0].cfg.zip.scanzip["@value"] = Number($('#file_chkScanzip2').prop('checked'));
            root.filemon.engine.id[0].cfg.zip.filesize = {};
            root.filemon.engine.id[0].cfg.zip.filesize["@attributes"] = {
                lock: Number(container.find('#file_lockFilesize2').hasClass('enableLock'))
            };
            root.filemon.engine.id[0].cfg.zip.filesize["@value"] = container.find('#file_txtFilesize2').val();
            root.filemon.engine.id[1] = {};
            root.filemon.engine.id[1].engid = {};
            root.filemon.engine.id[1].engid["@value"] = 2;
            root.filemon.engine.id[1].enable = {};
            root.filemon.engine.id[1].enable["@attributes"] = {
                lock: Number(container.find('#file_lockCloudScan2').hasClass('enableLock'))
            };
            root.filemon.engine.id[1].enable["@value"] = Number($('#file_chkCloudScan2').prop('checked'));
            root.filemon.findvirus = {};
            root.filemon.findvirus["@attributes"] = {
                lock: Number(container.find('#file_lockFindvirus2').hasClass('enableLock'))
            };
            root.filemon.findvirus["@value"] = container.find(':radio[name=file_radFindvirus2]:checked').val();
            root.filemon.sigsource = {};
            root.filemon.sigsource["@attributes"] = {
                lock: Number(container.find('#lockSigsource').hasClass('enableLock'))
            };
            root.filemon.sigsource["@value"] = Number($('#sigsource').prop('checked'));
            root.filemon.killfailed = {};
            root.filemon.killfailed["@value"] = 0;
            root.filemon.ole = {};
            root.filemon.ole['#attributes'] = { lock : Number(container.find('#ole_Lock').hasClass('enableLock')) };
            root.filemon.ole['@value'] = Number($('#ole').prop('checked'));
            /* end 文件监控设置 */

            /* begin 邮件监控设置 */
            root.mailmon = {};
            root.mailmon.rundisable = {};
            root.mailmon.rundisable["@attributes"] = {
                admin: 1
            };
            root.mailmon.rundisable["@value"] = Number($('#mail_chkRundisable2').prop('checked'));
            root.mailmon.lockclose = {};
            root.mailmon.lockclose["@attributes"] = {
                admin: 1
            };
            root.mailmon.lockclose["@value"] = Number($('#mail_chkLockclose2').prop('checked'));
            root.mailmon.reportresult = {};
            root.mailmon.reportresult["@attributes"] = {
                lock: Number(container.find('#mail_lockReportresult2').hasClass('enableLock'))
            };
            root.mailmon.reportresult["@value"] = container.find(':radio[name=mail_radReportresult2]:checked').val();
            root.mailmon.engine = {};
            root.mailmon.engine.pub = {};
            root.mailmon.engine.pub.filetype = {};
            root.mailmon.engine.pub.filetype["@attributes"] = {
                lock: Number(container.find('#mail_lockFiletype3').hasClass('enableLock'))
            };
            root.mailmon.engine.pub.filetype["@value"] = container.find(':radio[name=mail_radFiletype3]:checked').val();
            root.mailmon.engine.id = [];
            root.mailmon.engine.id[0] = {};
            root.mailmon.engine.id[0].engid = {};
            root.mailmon.engine.id[0].engid["@value"] = 1;
            root.mailmon.engine.id[0].enable = {};
            root.mailmon.engine.id[0].enable["@value"] = 1;
            root.mailmon.engine.id[0].cfg = {};
            root.mailmon.engine.id[0].cfg.heuristic = {};
            root.mailmon.engine.id[0].cfg.heuristic["@attributes"] = {
                lock: Number(container.find('#mail_lockHeuristic3').hasClass('enableLock'))
            };
            root.mailmon.engine.id[0].cfg.heuristic["@value"] = Number($('#mail_chkHeuristic3').prop('checked'));
            root.mailmon.engine.id[0].cfg.popvirus = {};
            root.mailmon.engine.id[0].cfg.popvirus["@attributes"] = {
                lock: Number(container.find('#mail_lockPopvirus3Lock').hasClass('enableLock'))
            };
            root.mailmon.engine.id[0].cfg.popvirus["@value"] = Number($('#mail_chkPopvirus3').prop('checked'));
            root.mailmon.engine.id[0].cfg.zip = {};
            root.mailmon.engine.id[0].cfg.zip.scanzip = {};
            root.mailmon.engine.id[0].cfg.zip.scanzip["@attributes"] = {
                lock: Number(container.find('#mail_lockScanzip3').hasClass('enableLock'))
            };
            root.mailmon.engine.id[0].cfg.zip.scanzip["@value"] = Number($('#mail_chkScanzip3').prop('checked'));
            root.mailmon.engine.id[0].cfg.zip.filesize = {};
            root.mailmon.engine.id[0].cfg.zip.filesize["@attributes"] = {
                lock: Number(container.find('#mail_lockFilesize3').hasClass('enableLock'))
            };
            root.mailmon.engine.id[0].cfg.zip.filesize["@value"] = container.find('#mail_txtFilesize3').val();
            root.mailmon.engine.id[1] = {};
            root.mailmon.engine.id[1].engid = {};
            root.mailmon.engine.id[1].engid["@value"] = 2;
            root.mailmon.engine.id[1].enable = {};
            root.mailmon.engine.id[1].enable["@attributes"] = {
                lock: Number(container.find('#mail_lockCloudScan3').hasClass('enableLock'))
            };
            root.mailmon.engine.id[1].enable["@value"] = Number($('#mail_chkCloudScan3').prop('checked'));
            root.mailmon.engine.portstrategy = {};
            root.mailmon.engine.portstrategy["@attributes"] = {
                lock: Number(container.find('#mail_lockPolicyWrap').hasClass('enableLock'))
            };
            var arrMailPorts = container.find('dl.mailPortPanel');
            if (arrMailPorts.length > 0) {
                root.mailmon.engine.portstrategy.item = [];
                arrMailPorts.each(function(i, el) {
                    var item = {};
                    item.port = {};
                    item.port["@value"] = $(el).find(':text[name=mail_txtMailPort]').val();
                    item.protocol = {};
                    item.protocol["@value"] = $(el).find(':radio[name^=mail_radMailPort]:checked').val();
                    item["@attributes"] = {
                        lock: +$(el).find('i[name=mail_lockMailPortBox]').hasClass('enableLock'),
                        name: (item.protocol["@value"] == '0' ? 'smtp' : 'pop3') + '.' + item.port["@value"]
                    };
                    root.mailmon.engine.portstrategy.item.push(item);
                });
            } else {
                root.mailmon.engine.portstrategy["@value"] = '';
            }

            root.mailmon.findvirus = {};
            root.mailmon.findvirus["@attributes"] = {
                lock: Number(container.find('#mail_lockFindvirus3').hasClass('enableLock'))
            };
            root.mailmon.findvirus["@value"] = container.find(':radio[name=mail_radFindvirus3]:checked').val();
            /* end 邮件监控设置 */

            /* begin 系统加固设置 */
            root.defmon = {};
            root.defmon.sysdef = {};
            root.defmon.sysdef.enable = {};
            root.defmon.sysdef.enable["@value"] = Number($('#sys_chkAllsysdefEnable').prop('checked'));
            root.defmon.sysdef.notify = {};
            root.defmon.sysdef.notify["@attributes"] = {
                lock: Number(container.find('#sys_lockSysdefnotify').hasClass('enableLock'))
            };
            root.defmon.sysdef.notify["@value"] = container.find(':radio[name=sys_radSysdefnotify]:checked').val();
            root.defmon.sysdef.needlog = {};
            root.defmon.sysdef.needlog["@attributes"] = {
                lock: Number(container.find('#sys_lockSysdefneedlog').hasClass('enableLock'))
            };
            root.defmon.sysdef.needlog["@value"] = container.find(':radio[name=sys_radSysdefneedlog]:checked').val();
            root.defmon.sysdef.level = {};
            root.defmon.sysdef.level["@attributes"] = {
                lock: Number(container.find('#sys_lockSysdeflevel').hasClass('enableLock'))
            };
            root.defmon.sysdef.level["@value"] = container.find(':radio[name=sys_radSysdeflevel]:checked').val();
            root.defmon.sysdef.auditmode = {};
            root.defmon.sysdef.auditmode["@attributes"] = {
                lock: Number(container.find('#sys_lockSysdefauditmode').hasClass('enableLock'))
            };
            root.defmon.sysdef.auditmode["@value"] = Number($('#app_chkSysdefauditmode').prop('checked'));
            root.defmon.sysdef.digitalsignature = {};
            root.defmon.sysdef.digitalsignature["@attributes"] = {
                lock: Number(container.find('#sys_lockSysdefdigitalsignature').hasClass('enableLock'))
            };
            root.defmon.sysdef.digitalsignature["@value"] = container.find(':radio[name=sys_radSysdefdigitalsignature]:checked').val();
            /* end 系统加固设置 */

            /* begin 应用加固设置 */
            root.actanalyze = {};
            root.actanalyze.apt = {};
            root.actanalyze.apt.status = {};
            root.actanalyze.apt.status["@value"] = Number($('#app_chkAllaptEnable').prop('checked'));
            root.actanalyze.apt.deal = {};
            root.actanalyze.apt.deal["@attributes"] = {
                lock: Number(container.find('#app_lockAptdeal').hasClass('enableLock'))
            };
            root.actanalyze.apt.deal["@value"] = container.find(':radio[name=app_radAptdeal]:checked').val();
            root.actanalyze.apt.notify = {};
            root.actanalyze.apt.notify["@attributes"] = {
                lock: Number(container.find('#app_lockAptnotify').hasClass('enableLock'))
            };
            root.actanalyze.apt.notify["@value"] = container.find(':radio[name=app_radAptnotify]:checked').val();
            root.actanalyze.apt.log = {};
            root.actanalyze.apt.log["@attributes"] = {
                lock: Number(container.find('#app_lockAptlog').hasClass('enableLock'))
            };
            root.actanalyze.apt.log["@value"] = container.find(':radio[name=app_radAptlog]:checked').val();
            root.actanalyze.apt.starttip = {};
            root.actanalyze.apt.starttip["@attributes"] = {
                lock: Number(container.find('#app_lockAptstarttip').hasClass('enableLock'))
            };
            root.actanalyze.apt.starttip["@value"] = container.find(':radio[name=app_radAptstarttip]:checked').val();
            /* end 应用加固设置 */

            /* start 设备监控 U盘*/
            root.devmon = {};
            root.devmon.usbmon = {};
            root.devmon.usbmon.scanlevel = {};
            root.devmon.usbmon.scanlevel['@value'] = container.find('#pub_udiskmonLevel').val() || 2;
            root.devmon.usbmon.enable = {};
            root.devmon.usbmon.enable['@value'] = Number(container.find('#pub_chkUdiskmon').prop('checked'));
            /* end 设备监控 U盘*/

            var json = {
                root: root
            };
            return json;
        },
        /*toXml: function(json) {
      var xml = mustache.render(xmlTpl, json);
      return xml;
    },*/
        toHtml: function(container, json) {
            /* begin 通用函数 */
            var opLock = function(id, status) {
                if (status == 1) {
                    container.find(id).addClass('enableLock');
                } else {
                    container.find(id).removeClass('enableLock');
                }
            };
            var opSelect = function(id, value) {
                container.find(id + ' option[value=' + value + ']').prop('selected', true);
            };
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
            /* end 通用函数 */

            var root = json.root;
            if (!root) {
                return;
            }

            /* begin 公共设置 */
            var pub = root.pub;
            //console.log(pub.file);
            if (pub.whitelist.admin.file.item) {
                $(pub.whitelist.admin.file.item).each(function(i, item) {
                    container.find('#pub_tbWhiteList').append('<tr><td filetype="' + item["@attributes"].path + '" title="' + item["@value"] + '">' + item["@value"] + '<td width="50"><a href="#" class="pub_btnRemove">删除</a>');
                });
            };
            container.find('#pub_txtExt').val(pub.whitelist.admin.ext["@value"]);
            opLock('#pub_lockNobackup', pub.vstore.nobackup["@attributes"].lock);
            opCheck('#pub_chkNobackup', pub.vstore.nobackup["@value"]);
            opLock('#pub_lockNospace', pub.vstore.nospace["@attributes"].lock);
            opRadio('pub_radNospace', pub.vstore.nospace["@value"]);
            opLock('#pub_lockStorefailed', pub.vstore.storefailed["@attributes"].lock);
            opRadio('pub_radStorefailed', pub.vstore.storefailed["@value"]);
            opLock('#pub_lockBigfile', pub.vstore.bigfile["@attributes"].lock);
            opRadio('pub_radBigfile', pub.vstore.bigfile["@value"]);
            opLock('#pub_lockVtrack', pub.vtrack["@attributes"].lock);
            opCheck('#pub_chkVtrack', pub.vtrack["@value"]);           
            opLock('#pub_lockMemlib', pub.memlib["@attributes"].lock);
            opCheck('#pub_chkMemlib', pub.memlib["@value"]);
            // opLock('#pub_lockTrolib', pub.trolib["@attributes"].lock);
            // opCheck('#pub_chkTrolib', pub.trolib["@value"]);
            opLock('#pub_lockWritelog', pub.writelog["@attributes"].lock);
            opCheck('#pub_chkWritelog', pub.writelog["@value"]);
            opLock('#pub_lockSmartcpuLevel', pub.smartcpu.level["@attributes"].lock);
            opRadio('pub_radSmartcpuLevel', pub.smartcpu.level["@value"]);

            opLock('#pub_lockWorm08067', pub.worm08067["@attributes"].lock);
            opCheck('#pub_worm08067', pub.worm08067["@value"]);
            opLock('#pub_lockYunyu', pub.yunyu["@attributes"].lock);
            opCheck('#pub_yunyu', pub.yunyu["@value"]);
            opLock('#pub_lockVirut', pub.virut["@attributes"].lock);
            opCheck('#pub_virut', pub.virut["@value"]);
            opLock('#pub_lockLpktool', pub.lpktool["@attributes"].lock);
            opCheck('#pub_lpktool', pub.lpktool["@value"]);
            opLock('#pub_lockCloserfm', pub.closerfm["@attributes"].lock);
            opCheck('#pub_closerfm', pub.closerfm["@value"]);
            opLock('#pub_lockInnerWhiteList', pub.InnerWhiteList["@attributes"].lock);
            opCheck('#pub_InnerWhiteList', pub.InnerWhiteList["@value"]);


            var cloud = pub.cloud;
            opSelect('#cpuradio',cloud.cpuradio['@value']);
            opSelect('#connrate',cloud.connrate['@value']);
            var csrv = cloud.servers.csrv;
            $(csrv).each(function(i,item){                
                if(item['@attributes'].private==0){
                    //公有云
                    opLock('#publicCloudLock', item["@attributes"].lock);
                    opCheck('#publicCloudEnable', item.enable["@value"]);
                    container.find('.js_cloud:eq(0) [name=publicCloudMode] [value='+item.mode['@value']+']').prop('checked',true);
                    container.find('#publicCloudCount').val(item.count['@value']);
                    container.find('#publicCloudName').val(item.name['@value']);
                }else{
                    //私有云
                    var target = container.find('#privateCloudTmpl');
                    var $html = $('<dd>'+target.html()+'</dd>');
                    item['@attributes'].lock ==1 ? $html.find('.privateCloudLock').addClass('enableLock'):$html.find('.privateCloudLock').removeClass('enableLock');
                    $html.find('.privateCloudEnable').prop('checked',item.enable['@value'] == 1 );
                    $html.find('.privateCloudAddr').val(item.addr['@value']);
                    $html.find('.privateCloudPort').val(item.port['@value']);
                    $html.find('[name=privateCloudMode][value='+item.mode['@value']+']').prop('checked',true);
                    $html.find('.privateCloudCount').val(item.count['@value']);
                    $html.find('.privateCloudName').val(item.name['@value']);
                }
            })

            /* end 公共设置 */

            /* begin 扫描设置 */
            var scan = root.scan;
            opCheck('#scan_chkAllscanEnable', scan.timerscan.allscan.enable["@value"]);
            container.find('#scan_chkAllscanEnable').trigger('change');
            if (scan.timerscan.allscan.RsTimer.Task.Time.AfterBoot) {
                opRadio('scan_radAllscanTime', '2');
            } else if (scan.timerscan.allscan.RsTimer.Task.Time.EveryDay) {
                opRadio('scan_radAllscanTime', '6');
                container.find('#scan_txtAllscanEveryDay').val(scan.timerscan.allscan.RsTimer.Task.Time.EveryDay["@attributes"].hour + ':' + scan.timerscan.allscan.RsTimer.Task.Time.EveryDay["@attributes"].minute);
            } else if (scan.timerscan.allscan.RsTimer.Task.Time.EveryWeek) {
                opRadio('scan_radAllscanTime', '5');
                container.find(':checkbox[name=scan_chkAllscanEveryWeek]').each(function(i, item) {
                    (item.value | scan.timerscan.allscan.RsTimer.Task.Time.EveryWeek["@attributes"].weekmark) == scan.timerscan.allscan.RsTimer.Task.Time.EveryWeek["@attributes"].weekmark && (item.checked = true) || (item.checked = false);
                });
                container.find('#scan_txtAllscanEveryWeek').val(scan.timerscan.allscan.RsTimer.Task.Time.EveryWeek["@attributes"].hour + ':' + scan.timerscan.allscan.RsTimer.Task.Time.EveryWeek["@attributes"].minute);
            }
            opCheck('#scan_chkQuickscanEnable', scan.timerscan.quickscan.enable["@value"])
            $('#scan_chkQuickscanEnable').trigger('change');
            if (scan.timerscan.quickscan.RsTimer.Task.Time.AfterBoot) {
                opRadio('scan_radQuickscanTime', '2');
            } else if (scan.timerscan.quickscan.RsTimer.Task.Time.EveryDay) {
                opRadio('scan_radQuickscanTime', '6');
                container.find('#scan_txtQuickscanEveryDay').val(scan.timerscan.quickscan.RsTimer.Task.Time.EveryDay["@attributes"].hour + ':' + scan.timerscan.quickscan.RsTimer.Task.Time.EveryDay["@attributes"].minute);
            } else if (scan.timerscan.quickscan.RsTimer.Task.Time.EveryWeek) {
                opRadio('scan_radQuickscanTime', '5');
                container.find(':checkbox[name=scan_chkQuickscanEveryWeek]').each(function(i, item) {
                    (item.value | scan.timerscan.quickscan.RsTimer.Task.Time.EveryWeek["@attributes"].weekmark) == scan.timerscan.quickscan.RsTimer.Task.Time.EveryWeek["@attributes"].weekmark && (item.checked = true) || (item.checked = false);
                });
                container.find('#scan_txtQuickscanEveryWeek').val(scan.timerscan.quickscan.RsTimer.Task.Time.EveryWeek["@attributes"].hour + ':' + scan.timerscan.quickscan.RsTimer.Task.Time.EveryWeek["@attributes"].minute);
            }
            opLock('#scan_lockFiletype', scan.engine.pub.filetype["@attributes"].lock);
            opRadio('scan_radFiletype', scan.engine.pub.filetype["@value"]);
            opLock('#scan_lockHeuristic', scan.engine.id[0].cfg.heuristic["@attributes"].lock);
            opCheck('#scan_chkHeuristic', scan.engine.id[0].cfg.heuristic["@value"]);
            opLock('#scan_lockPopvirus', scan.engine.id[0].cfg.popvirus["@attributes"].lock);
            opCheck('#scan_chkPopvirus', scan.engine.id[0].cfg.popvirus["@value"]);
            opLock('#scan_lockScanzip', scan.engine.id[0].cfg.zip.scanzip["@attributes"].lock);
            opCheck('#scan_chkScanzip', scan.engine.id[0].cfg.zip.scanzip["@value"]);
            opLock('#scan_lockFilesize', scan.engine.id[0].cfg.zip.filesize["@attributes"].lock);
            container.find('#scan_txtFilesize').val(scan.engine.id[0].cfg.zip.filesize["@value"]);
            opLock('#scan_lockCloudScan', scan.engine.id[1].enable["@attributes"].lock);
            opCheck('#scan_chkCloudScan', scan.engine.id[1].enable["@value"]);
            opLock('#scan_lockfindvirus', scan.findvirus["@attributes"].lock);
            opRadio('scan_radFindvirus', scan.findvirus["@value"]);
            opRadio('scan_radAdminscanoper', scan.adminscanoper["@value"]);
            /* end 扫描设置 */

            /* begin 文件监控设置 */
            var file = root.filemon;
            opCheck('#file_chkRundisable', file.rundisable["@value"]);
            opCheck('#file_chkLockclose', file.lockclose["@value"]);
            opLock('#file_lockMonmode', file.monmode["@attributes"].lock);
            opCheck('#file_chkMonmode', file.monmode["@value"]);
            opLock('#file_lockCore', file.enablekernel["@attributes"].lock);
            opCheck('#file_chkCoreMonitor', file.enablekernel["@value"]);
            opLock('#file_lockReportresult', file.reportresult["@attributes"].lock);
            opCheck('#file_chkReportresult', file.reportresult["@value"]);
            opLock('#file_lockFiletype2', file.engine.pub.filetype["@attributes"].lock);
            opRadio('file_radFiletype2', file.engine.pub.filetype["@value"]);
            opLock('#file_lockHeuristic2', file.engine.id[0].cfg.heuristic["@attributes"].lock);
            opCheck('#file_chkHeuristic2', file.engine.id[0].cfg.heuristic["@value"]);
            opLock('#file_lockPopvirus2', file.engine.id[0].cfg.popvirus["@attributes"].lock);
            opCheck('#file_chkPopvirus2', file.engine.id[0].cfg.popvirus["@value"]);
            opLock('#file_lockScanzip2', file.engine.id[0].cfg.zip.scanzip["@attributes"].lock);
            opCheck('#file_chkScanzip2', file.engine.id[0].cfg.zip.scanzip["@value"]);
            opLock('#file_lockFilesize2', file.engine.id[0].cfg.zip.filesize["@attributes"].lock);
            container.find('#file_txtFilesize2').val(file.engine.id[0].cfg.zip.filesize["@value"]);
            opLock('#file_lockCloudScan2', file.engine.id[1].enable["@attributes"].lock);
            opCheck('#file_chkCloudScan2', file.engine.id[1].enable["@value"]);
            opLock('#file_lockFindvirus2', file.findvirus["@attributes"].lock);
            opRadio('file_radFindvirus2', file.findvirus["@value"]);
            opLock('#lockSigsource', file.sigsource["@attributes"].lock);
            opCheck('#sigsource', file.sigsource["@value"]);
            opLock('#ole_Lock', file.ole["@attributes"].lock);
            opCheck('#ole', file.ole["@value"]);
            /* end 文件监控设置 */

            /* begin 邮件监控设置 */
            var mail = root.mailmon;
            opCheck('#mail_chkRundisable2', mail.rundisable["@value"]);
            opCheck('#mail_chkLockclose2', mail.lockclose["@value"]);
            opLock('#mail_lockReportresult2', mail.reportresult["@attributes"].lock);
            opRadio('mail_radReportresult2', mail.reportresult["@value"]);
            opLock('#mail_lockFiletype3', mail.engine.pub.filetype["@attributes"].lock);
            opRadio('mail_radFiletype3', mail.engine.pub.filetype["@value"]);
            opLock('#mail_lockHeuristic3', mail.engine.id[0].cfg.heuristic["@attributes"].lock);
            opCheck('#mail_chkHeuristic3', mail.engine.id[0].cfg.heuristic["@value"]);
            opLock('#mail_lockPopvirus3Lock', mail.engine.id[0].cfg.popvirus["@attributes"].lock);
            opCheck('#mail_chkPopvirus3', mail.engine.id[0].cfg.popvirus["@value"]);
            opLock('#mail_lockScanzip3', mail.engine.id[0].cfg.zip.scanzip["@attributes"].lock);
            opCheck('#mail_chkScanzip3', mail.engine.id[0].cfg.zip.scanzip["@value"]);
            opLock('#mail_lockFilesize3', mail.engine.id[0].cfg.zip.filesize["@attributes"].lock);
            container.find('#mail_txtFilesize3').val(mail.engine.id[0].cfg.zip.filesize["@value"]);
            opLock('#mail_lockCloudScan3', mail.engine.id[1].enable["@attributes"].lock);
            opCheck('#mail_chkCloudScan3', mail.engine.id[1].enable["@value"]);
            opLock('#mail_lockFindvirus3', mail.findvirus["@attributes"].lock);
            opRadio('mail_radFindvirus3', mail.findvirus["@value"]);
            opLock('#mail_lockPolicyWrap', mail.engine.portstrategy["@attributes"].lock);
            var mailPolicy = container.find('#mail_lockPolicyWrap').closest('dt').next('dd').empty();
            if (mail.engine.portstrategy.item) {
                var arrHtml = [];
                $.each(mail.engine.portstrategy.item, function(i, item) {
                    arrHtml.push('<dl class="mailPortPanel"><dt><a href="#" class="mail_btnRemovePort">删除</a> <a href="#" class="mail_btnAddPort">增加</a><label>');
                    arrHtml.push('<i name="mail_lockMailPortBox" class="lock' + (item["@attributes"].lock ? ' enableLock' : '') + '"></i>');
                    arrHtml.push('</label><dd>端口号： <input type="text" name="mail_txtMailPort" value="' + item.port["@value"] + '" validation="intNum"> 端口协议：<label class="radio inline">');
                    arrHtml.push('<input name="mail_radMailPort' + i + '" type="radio" value="0"' + (item.protocol["@value"] == 0 ? ' checked' : '') + '>SMTP</label><label class="radio inline"><input name="mail_radMailPort' + i + '" type="radio" value="1"' + (item.protocol["@value"] == 1 ? ' checked' : '') + '>POP3</label></dl>');
                });
                mailPolicy.append(arrHtml.join(''));
            } else {
                mailPolicy.append('<dl class="mailNoRecordPanel"><span>当前没有任何规则</span> <a href="#" id="mail_btnAddRecord">增加</a></dl>');
            }
            /* end 邮件监控设置 */

            /* begin 应用加固设置 */
            var app = root.actanalyze.apt;
            opCheck('#app_chkAllaptEnable', app.status["@value"]);
            container.find('#app_chkAllaptEnable').trigger('change');
            opLock('#app_lockAptdeal', app.deal["@attributes"].lock);
            opRadio('app_radAptdeal', app.deal["@value"]);
            opLock('#app_lockAptnotify', app.notify["@attributes"].lock);
            opRadio('app_radAptnotify', app.notify["@value"]);
            opLock('#app_lockAptlog', app.log["@attributes"].lock);
            opRadio('app_radAptlog', app.log["@value"]);
            opLock('#app_lockAptstarttip', app.starttip["@attributes"].lock);
            opRadio('app_radAptstarttip', app.starttip["@value"]);
            /* end 应用加固设置 */

            /* begin 系统加固设置 */
            var sys = root.defmon.sysdef;
            opCheck('#sys_chkAllsysdefEnable', sys.enable["@value"]);
            container.find('#sys_chkAllsysdefEnable').trigger('change');
            opLock('#sys_lockSysdefnotify', sys.notify["@attributes"].lock);
            opRadio('sys_radSysdefnotify', sys.notify["@value"]);
            opLock('#sys_lockSysdefneedlog', sys.needlog["@attributes"].lock);
            opRadio('sys_radSysdefneedlog', sys.needlog["@value"]);
            opLock('#sys_lockSysdeflevel', sys.level["@attributes"].lock);
            opRadio('sys_radSysdeflevel', sys.level["@value"]);
            opLock('#sys_lockSysdefauditmode', sys.auditmode["@attributes"].lock);
            opCheck('#app_chkSysdefauditmode', sys.auditmode["@value"]);
            opLock('#sys_lockSysdefdigitalsignature', sys.digitalsignature["@attributes"].lock);
            opRadio('sys_radSysdefdigitalsignature', sys.digitalsignature["@value"]);
            /* end 系统加固设置 */

            /* start 设备监控*/
            var usbmon = root.devmon.usbmon;
            opCheck('#pub_chkUdiskmon', usbmon.enable["@value"]);
            container.find('#pub_udiskmonLevel').val(usbmon.scanlevel['@value']);
            /* end 设备监控 */
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