define(function(require) {
    var tpl = require('text!policy/A40D11F7-63D2-469d-BC9C-E10EB5EF32DB_1.html');
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
            }
        },
        bindEvent: function(container) {
            /*初始化时间空间*/
            container.find('#scanT').datetimepicker({
                datepicker: false,
                format: 'H:i',
                step: 1
            });

            container.on('click', '#add_ignPath', function() {
                var _html = '<li><input type="text" value="" class="ignPaths" placeholder="请填写忽略路径"><a href="javascript:;" class="ignPathsDel"><i class="icon-remove"></i></a></li>';
                container.find('.li_ignPaths ul').append(_html);
                container.find('.ignPathsDel').show();
            })

            container.on('click', '.ignPathsDel', function() {
                $(this).closest('li').remove();
                if (container.find('.ignPathsDel').length < 2) {
                    container.find('.ignPathsDel').hide();
                }
            })

            container.on('change', '#setTimeViru', function() {
                if ($(this).prop('checked')) {
                    container.find('[name=scanTime],#scanT').prop('disabled', false);
                } else {
                    container.find('[name=scanTime],#scanT').prop('disabled', true);
                }
            })

            container.on('change', '#keepDayAble', function() {
                var checked = $(this).prop('checked');
                if (checked) {
                    container.find('#keepDay').prop('disabled', false);
                } else {
                    container.find('#keepDay').prop('disabled', true);
                }
            })

            container.on('change', '#compsAble', function() {
                var checked = $(this).prop('checked');
                if (checked) {
                    container.find('#compress').prop('disabled', false);
                } else {
                    container.find('#compress').prop('disabled', true);
                }
            })

            container.on('change', '[name=ignPath]', function() {
                var checked = $(this).prop('checked');
                if (checked) {
                    container.find('.li_ignPaths input').prop('disabled', false);
                    var dels = container.find('.li_ignPaths .ignPathsDel');
                    if (dels.length > 1) {
                        dels.show();
                    }
                    container.find('#add_ignPath').prop('disabled', false);
                } else {
                    container.find('.li_ignPaths input').prop('disabled', true);
                    container.find('.li_ignPaths .ignPathsDel').hide();
                    container.find('#add_ignPath').prop('disabled', true);
                }
            })

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
            }
            var viruscan = {};
            viruscan.scanpath = {};
            viruscan.scanpath['@value'] = $.trim(container.find('#scanPath').val());

            viruscan.excludepath = {};
            viruscan.excludepath.enable = {};
            viruscan.excludepath.enable['@value'] = Number(container.find('[name=ignPath]').prop('checked'));
            viruscan.excludepath.excludelist = [];
            var excludelists = container.find('.ignPaths');
            for (var i = 0, len = excludelists.length; i < len; i++) {
                var exclude = {};
                exclude['@value'] = $.trim($(excludelists[i]).val());
                viruscan.excludepath.excludelist.push(exclude);
            }

            viruscan.scanpolicy = {};
            viruscan.scanpolicy.keepday = {};
            viruscan.scanpolicy.keepday.enable = {};
            viruscan.scanpolicy.keepday.enable['@value'] = Number(container.find('#keepDayAble').prop('checked'));
            viruscan.scanpolicy.keepday.keep = {};
            viruscan.scanpolicy.keepday.keep['@value'] = container.find('#keepDay').val();
            viruscan.scanpolicy.compressfile = {};
            viruscan.scanpolicy.compressfile.enable = {};
            viruscan.scanpolicy.compressfile.enable['@value'] = Number(container.find('#compsAble').prop('checked'));
            viruscan.scanpolicy.compressfile.compressfilesize = {};
            viruscan.scanpolicy.compressfile.compressfilesize = container.find('#compress').val();

            viruscan.scanpolicy.procmod = {};
            viruscan.scanpolicy.procmod['@value'] = container.find('[name=findViru]:checked').val() || '';

            viruscan.scanpolicy.deletefailmode = {};
            viruscan.scanpolicy.deletefailmode['@value'] = container.find('[name=clearViru]:checked').val() || '';

            viruscan.scanpolicy.backup = {};
            viruscan.scanpolicy.backup.backupenable = {};
            viruscan.scanpolicy.backup.backupenable['@value'] = container.find('[name=viruFail]:checked').length;
            viruscan.scanpolicy.backup.backupfailmode = {};
            viruscan.scanpolicy.backup.backupfailmode['@value'] = container.find('[name=viruFail]:checked').val() || '';

            viruscan.scanpolicy.TimerScan = {};
            viruscan.scanpolicy.TimerScan.enable = {};
            viruscan.scanpolicy.TimerScan.enable['@value'] = Number(container.find('#setTimeViru').prop('checked'));
            viruscan.scanpolicy.TimerScan.everyweek = {};
            viruscan.scanpolicy.TimerScan.everyweek.weekmark = {};
            viruscan.scanpolicy.TimerScan.everyweek.weekmark['@value'] = setDetialWek($('[name=scanTime]'));
            viruscan.scanpolicy.TimerScan.everyweek.hour = {};
            viruscan.scanpolicy.TimerScan.everyweek.hour['@value'] = container.find('#scanT').val().split(':')[0] || '';
            viruscan.scanpolicy.TimerScan.everyweek.min = {};
            viruscan.scanpolicy.TimerScan.everyweek.min['@value'] = container.find('#scanT').val().split(':')[1] || '';

            var json = {
                viruscan: viruscan
            };
            return json

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

            var viruscan = json.viruscan;
            if (!viruscan) {
                return;
            }

            container.find('#scanPath').val(viruscan.scanpath['@value']);

            var excludepath = viruscan.excludepath;
            if (excludepath.enable['@value'] == 1) {
                container.find('[name=ignPath]').prop('checked', true).trigger('change');     
            } else {
                container.find('[name=ignPath]').prop('checked', false).trigger('change');
            }

            var excludelist = excludepath.excludelist;
            var ignPaths = container.find('.ignPaths');
            if (excludelist.length) {
                for (var i = 0; i < excludelist.length; i++) {
                    var _html = '<li><input type="text" class="ignPaths" value="' + excludelist[i]['@value'] + '" placeholder="请填写忽略路径"><a href="javascript:;" class="ignPathsDel"><i class="icon-remove"></i></a></li>';
                    container.find('.li_ignPaths ul').append(_html);
                }
            } else {
                var _html = '<li><input type="text" class="ignPaths" value="" placeholder="请填写忽略路径"><a href="javascript:;" class="ignPathsDel"><i class="icon-remove"></i></a></li>';
                container.find('.li_ignPaths ul').append(_html);
            }

            var scanpolicy = viruscan.scanpolicy;
            var keepday = scanpolicy.keepday;
            opCheck('#keepDayAble', keepday.enable['@value']);
            container.find('#keepDayAble').trigger('change');
            container.find('#keepDay').val(keepday.keep['@value']);

            var compressfile = scanpolicy.compressfile;
            opCheck('#compsAble', compressfile.enable['@value']);
            container.find('#compsAble').trigger('change');
            container.find('#compress').val(keepday.keep['@value']);

            var procmod = scanpolicy.procmod;
            opRadio('findViru', procmod['@value']);

            var deletefailmode = scanpolicy.deletefailmode;
            opRadio('clearViru', deletefailmode['@value']);

            var backupfailmode = scanpolicy.backup.backupfailmode;
            opRadio('viruFail', backupfailmode['@value']);

            var TimerScan = scanpolicy.TimerScan;
            opCheck('#setTimeViru', TimerScan.enable['@value']);
            container.find('#setTimeViru').trigger('change');
            

            var everyweek = TimerScan.everyweek;
            var everymark = everyweek.weekmark;
            var weekArr = getDetialWek(everymark['@value']);
            var weekmark = container.find('[name=scanTime]');
            for (var i = 0; i < weekArr.length; i++) {
                if (weekArr[i] == '1') {
                    $(weekmark[i]).prop('checked', true);
                } else {
                    $(weekmark[i]).prop('checked',false);
                }
            }
            var time = everyweek.hour['@value'] + ':' + everyweek.min['@value'];
            container.find('#scanT').val(time);
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